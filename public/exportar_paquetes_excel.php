<?php
session_start();
require_once __DIR__ . '/../src/Config/database.php';
 
use App\Config\Database;
 
// Validar sesión y permisos (solo admin y super admin)
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], [1, 2])) {
    header('Location: login.php');
    exit;
}
 
// Validar fechas
$fecha_inicio = $_POST['fecha_inicio'] ?? '';
$fecha_fin = $_POST['fecha_fin'] ?? '';
$incluir_entregados = isset($_POST['incluir_entregados']) && $_POST['incluir_entregados'] === 'on';
$incluir_pendientes = isset($_POST['incluir_pendientes']) && $_POST['incluir_pendientes'] === 'on';
 
if (empty($fecha_inicio) || empty($fecha_fin)) {
    die('Las fechas son requeridas');
}
 
if (!$incluir_entregados && !$incluir_pendientes) {
    die('Debe seleccionar al menos un tipo de paquete');
}
 
try {
    // Conexión a la base de datos usando la clase Database
    $db = new Database();
    $conn = $db->getConnection();
 
    // Consulta SQL para obtener paquetes
    $sql = "SELECT
        p.id_paquete,
        p.descripcion,
        p.fech_hor_recep,
        p.fech_hor_entre,
        p.id_estado,
        CASE
            WHEN p.id_estado = 1 THEN 'Pendiente'
            WHEN p.id_estado = 2 THEN 'Entregado'
            ELSE 'Desconocido'
        END as estado_nombre,
        res.nombre as nombre_residente,
        res.apellido as apellido_residente,
        res.documento as documento_residente,
        vig.nombre as nombre_vigilante,
        vig.apellido as apellido_vigilante
    FROM paquetes p
    INNER JOIN usuarios res ON p.id_usuarios = res.documento
    INNER JOIN usuarios vig ON p.id_vigilante = vig.documento
    WHERE DATE(p.fech_hor_recep) BETWEEN :fecha_inicio AND :fecha_fin";
 
    // Construir condición para estados según checkboxes
    $estados = [];
    if ($incluir_entregados) $estados[] = 2; // ID para estado Entregado
    if ($incluir_pendientes) $estados[] = 1; // ID para estado Pendiente
   
    if (!empty($estados)) {
        $sql .= " AND p.id_estado IN (" . implode(",", $estados) . ")";
    }
 
    $sql .= " ORDER BY p.fech_hor_recep DESC";
 
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':fecha_inicio', $fecha_inicio);
    $stmt->bindParam(':fecha_fin', $fecha_fin);
    $stmt->execute();
    $paquetes = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
    // Si no hay resultados
    if (empty($paquetes)) {
        die('No hay paquetes registrados para el rango de fechas seleccionado');
    }
 
    // Generar CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="Reporte_Paquetes_' . date('Y-m-d') . '.csv"');
   
    $output = fopen('php://output', 'w');
   
    // Escribir el BOM para Excel
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
   
    // Encabezados
    fputcsv($output, [
        'ID',
        'Residente',
        'Documento Residente',
        'Vigilante',
        'Descripción',
        'Fecha Recepción',
        'Fecha Entrega',
        'Estado'
    ]);
   
    // Datos
    foreach ($paquetes as $paquete) {
        fputcsv($output, [
            $paquete['id_paquete'],
            $paquete['nombre_residente'] . ' ' . $paquete['apellido_residente'],
            $paquete['documento_residente'],
            $paquete['nombre_vigilante'] . ' ' . $paquete['apellido_vigilante'],
            $paquete['descripcion'],
            date('d/m/Y H:i', strtotime($paquete['fech_hor_recep'])),
            $paquete['fech_hor_entre'] ? date('d/m/Y H:i', strtotime($paquete['fech_hor_entre'])) : 'Pendiente',
            $paquete['estado_nombre']
        ]);
    }
   
    fclose($output);
    exit;
 
} catch (PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
}
?>