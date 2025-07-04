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

if (empty($fecha_inicio) || empty($fecha_fin)) {
    die('Las fechas son requeridas');
}

// Validar que la fecha fin no sea menor que la fecha inicio
if ($fecha_fin < $fecha_inicio) {
    die('El rango de fechas no es válido');
}

try {
    // Conexión a la base de datos usando la clase Database
    $db = new Database();
    $conn = $db->getConnection();

    // Consulta SQL corregida
    $sql = "SELECT
        v.fecha_ingreso,
        CONCAT(v.nombre, ' ', v.apellido) as visitante_nombre,
        CONCAT(u.nombre, ' ', u.apellido) as residente_nombre,
        u.direccion_casa,
        mv.motivo_visita as motivo_visita,
        v.hora_ingreso,
        v.id_estado
    FROM visitas v
    INNER JOIN usuarios u ON v.id_usuarios = u.documento
    INNER JOIN motivo_visita mv ON v.id_mot_visi = mv.id_mot_visi
    WHERE DATE(v.fecha_ingreso) BETWEEN :fecha_inicio AND :fecha_fin
    ORDER BY v.fecha_ingreso DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':fecha_inicio', $fecha_inicio);
    $stmt->bindParam(':fecha_fin', $fecha_fin);
    $stmt->execute();
    $visitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Si no hay resultados
    if (empty($visitas)) {
        die('No hay visitas registradas en el rango de fechas seleccionado');
    }

    // Generar CSV temporalmente mientras instalamos PhpSpreadsheet
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="Reporte_Visitas_' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');

    // Escribir el BOM para Excel
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

    // Encabezados
    fputcsv($output, [
        'Fecha',
        'Visitante',
        'Residente',
        'Casa',
        'Motivo',
        'Hora',
        'Estado'
    ]);

    // Datos
    foreach ($visitas as $visita) {
        $estado = 'Desconocido';
        if ($visita['id_estado'] == 1) {
            $estado = 'Pendiente';
        } elseif ($visita['id_estado'] == 2) {
            $estado = 'Aprobada';
        }

        fputcsv($output, [
            date("d/m/Y", strtotime($visita['fecha_ingreso'])),
            $visita['visitante_nombre'],
            $visita['residente_nombre'],
            $visita['direccion_casa'],
            $visita['motivo_visita'],
            date('g:i a', strtotime($visita['hora_ingreso'])),
            $estado
        ]);
    }

    fclose($output);
    exit;

} catch (Exception $e) {
    die("Error en la base de datos: " . $e->getMessage());
}
?>
