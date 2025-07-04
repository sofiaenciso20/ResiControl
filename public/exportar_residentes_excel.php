<?php
session_start();
require_once __DIR__ . '/../src/Config/database.php';
 
use App\Config\Database;
 
// Validar sesión y permisos (solo admin y super admin)
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], [1, 2])) {
    header('Location: login.php');
    exit;
}
 
try {
    // Conexión a la base de datos usando la clase Database
    $db = new Database();
    $conn = $db->getConnection();
 
    // Verificar si se incluyen inactivos
    $incluir_inactivos = isset($_POST['incluir_inactivos']) && $_POST['incluir_inactivos'] === 'on';
   
    // Consulta SQL para obtener residentes
    $sql = "SELECT
        u.documento,
        u.nombre,
        u.apellido,
        u.telefono,
        u.correo,
        u.direccion_casa,
        u.empresa,
        u.cantidad_personas,
        u.tiene_animales,
        u.cantidad_animales,
        u.id_estado_usuario,
        u.fecha_registro,
        u.nombre as nombre_rol
    FROM usuarios u
    LEFT JOIN roles r ON u.id_rol = r.id_rol
    WHERE 1=1";
   
    // Si no se incluyen inactivos, filtrar solo activos
    if (!$incluir_inactivos) {
        $sql .= " AND u.id_estado_usuario = 4";
    }
   
    $sql .= " ORDER BY u.apellido, u.nombre";
 
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $residentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
    // Si no hay resultados
    if (empty($residentes)) {
        die('No hay residentes registrados');
    }
 
    // Generar CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="Reporte_Residentes_' . date('Y-m-d') . '.csv"');
   
    $output = fopen('php://output', 'w');
   
    // Escribir el BOM para Excel
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
   
    // Encabezados
    fputcsv($output, [
        'Documento',
        'Nombre Completo',
        'Teléfono',
        'Correo',
        'Dirección',
        'Empresa',
        'Cantidad Personas',
        'Tiene Animales',
        'Cantidad Animales',
        'Estado',
        'Rol',
        'Fecha Registro'
    ]);
   
    // Datos
    foreach ($residentes as $residente) {
        // Determinar estado
        $estado = $residente['id_estado_usuario'] == 4 ? 'Activo' : 'Inactivo';
       
        fputcsv($output, [
            $residente['documento'],
            $residente['nombre'] . ' ' . $residente['apellido'],
            $residente['telefono'],
            $residente['correo'],
            $residente['direccion_casa'],
            $residente['empresa'] ?: 'N/A',
            $residente['cantidad_personas'] ?: '0',
            $residente['tiene_animales'] ?: 'No',
            $residente['cantidad_animales'] ?: '0',
            $estado,
            $residente['nombre_rol'] ?: 'No asignado',
            date('d/m/Y', strtotime($residente['fecha_registro']))
        ]);
    }
   
    fclose($output);
    exit;
 
} catch (PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
}
?>