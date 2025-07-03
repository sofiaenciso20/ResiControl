<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/HistorialPaquetesController.php';
require_once __DIR__ . '/../src/Config/permissions.php';
 
session_start();
if (!tienePermiso('historial_paquetes')) {
    header('Location: dashboard.php');
    exit;
}
 
// Instanciar el controlador
$controller = new HistorialPaquetesController();
$paquetes = $controller->index();
 
// Variables para el layout
$titulo = 'Historial de Paquetes';
$pagina_actual = 'historial_paquetes';
 
// Inicia buffer para capturar el contenido de la vista
ob_start();
require_once __DIR__ . '/../views/components/historial_paquetes.php';
$contenido = ob_get_clean();
 
// Carga el layout principal
require_once __DIR__ . '/../views/layout/main.php';
?>