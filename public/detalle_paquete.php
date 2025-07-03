<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/HistorialPaquetesController.php';
require_once __DIR__ . '/../src/Config/permissions.php';
 
session_start();
if (!tienePermiso('historial_paquetes')) {
    header('Location: dashboard.php');
    exit;
}
 
$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['error'] = 'ID de paquete no proporcionado';
    header('Location: historial_paquetes.php');
    exit;
}
 
$controller = new HistorialPaquetesController();
$paquete = $controller->obtenerDetallePaquete($id);
 
if (!$paquete) {
    $_SESSION['error'] = 'No se encontró información del paquete';
    header('Location: historial_paquetes.php');
    exit;
}
 
// Si es residente, verificar que el paquete le pertenece
if ($_SESSION['user']['role'] == 3 && $paquete['id_usuarios'] != $_SESSION['user']['documento']) {
    $_SESSION['error'] = 'No tienes permiso para ver este paquete';
    header('Location: historial_paquetes.php');
    exit;
}
 
$titulo = 'Detalle de Paquete';
$pagina_actual = 'detalle_paquete';
 
ob_start();
require_once __DIR__ . '/../views/components/detalle_paquete.php';
$contenido = ob_get_clean();
 
require_once __DIR__ . '/../views/layout/main.php';
 