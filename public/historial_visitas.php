<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/VisitasController.php';
require_once __DIR__ . '/../src/Config/permissions.php';

session_start();
if (!tienePermiso('historial_visitas')) {
    header('Location: dashboard.php');
    exit;
}

$controller = new VisitasController();
$visitas = $controller->index();

$titulo = 'Historial de Visitas';
$pagina_actual = 'historial_visitas';

// Eliminar visitas pendientes con hora_ingreso +1h < hora actual y fecha de hoy
$db = new \App\Config\Database();
$conn = $db->getConnection();
$stmt = $conn->prepare("DELETE FROM visitas WHERE id_estado = 1 AND TIMESTAMPADD(HOUR, 1, hora_ingreso) < CURTIME() AND fecha_ingreso = CURDATE()");
$stmt->execute();

ob_start();
require_once __DIR__ . '/../views/components/historial_visitas.php';
$contenido = ob_get_clean();

require_once __DIR__ . '/../views/layout/main.php';
