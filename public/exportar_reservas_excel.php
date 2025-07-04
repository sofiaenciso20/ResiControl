exportar_reservas_excel
 
<?php
session_start();
require_once __DIR__ . '/../src/Config/database.php';
 
use App\Config\Database;
 
// Validar sesión y permisos (solo admin y super admin)
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], [1, 2])) {
    header('Location: login.php');
    exit;
}
 
// Validar mes y año
$mes = $_POST['mes'] ?? date('n');
$anio = $_POST['anio'] ?? date('Y');
$incluir_rechazadas = isset($_POST['incluir_rechazadas']) && $_POST['incluir_rechazadas'] === 'on';
 
try {
    // Conexión a la base de datos usando la clase Database
    $db = new Database();
    $conn = $db->getConnection();
 
    // Consulta SQL para obtener reservas del mes
    $sql = "SELECT
        r.id_reservas,
        r.fecha,
        r.fecha_apro,
        r.observaciones,
        zc.nombre_zona as nombre_zona,
        h.horario,
        u.nombre as nombre_residente,
        u.apellido as apellido_residente,
        u.documento as documento_residente,
        mz.motivo_zonas as motivo_zonas,
        CASE r.id_estado
            WHEN 1 THEN 'Pendiente'
            WHEN 2 THEN 'Aprobada'
            WHEN 3 THEN 'Rechazada'
            ELSE 'Desconocido'
        END as estado,
        admin.nombre as nombre_administrador,
        admin.apellido as apellido_administrador
    FROM reservas r
    INNER JOIN zonas_comunes zc ON r.id_zonas_comu = zc.id_zonas_comu
    INNER JOIN usuarios u ON r.id_usuarios = u.documento
    INNER JOIN horario h ON r.id_horario = h.id_horario
    LEFT JOIN motivo_zonas mz ON r.id_mot_zonas = mz.id_mot_zonas
    LEFT JOIN usuarios admin ON r.id_administrador = admin.documento
    WHERE MONTH(r.fecha) = :mes
    AND YEAR(r.fecha) = :anio";
 
    if (!$incluir_rechazadas) {
        $sql .= " AND r.id_estado != 3";
    }
 
    $sql .= " ORDER BY r.fecha, h.horario";
 
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':mes', $mes, PDO::PARAM_INT);
    $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
    $stmt->execute();
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
    // Si no hay resultados
    if (empty($reservas)) {
        die('No hay reservas registradas para el mes seleccionado');
    }
 
    // Generar CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="Reporte_Reservas_' . $mes . '_' . $anio . '.csv"');
   
    $output = fopen('php://output', 'w');
   
    // Escribir el BOM para Excel
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
   
    // Encabezados
    fputcsv($output, [
        'Fecha',
        'Zona',
        'Horario',
        'Residente',
        'Documento',
        'Motivo',
        'Estado',
        'Fecha Aprobación',
        'Administrador',
        'Observaciones'
    ]);
   
    // Datos
    foreach ($reservas as $reserva) {
        fputcsv($output, [
            date('d/m/Y', strtotime($reserva['fecha'])),
            $reserva['nombre_zona'],
            $reserva['horario'],
            $reserva['nombre_residente'] . ' ' . $reserva['apellido_residente'],
            $reserva['documento_residente'],
            $reserva['motivo_zonas'],
            $reserva['estado'],
            $reserva['fecha_apro'] ? date('d/m/Y', strtotime($reserva['fecha_apro'])) : 'N/A',
            $reserva['nombre_administrador'] ? $reserva['nombre_administrador'] . ' ' . $reserva['apellido_administrador'] : 'N/A',
            $reserva['observaciones'] ?: 'N/A'
        ]);
    }
   
    fclose($output);
    exit;
 
} catch (PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
}
?>