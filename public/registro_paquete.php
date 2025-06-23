<?php
 
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/PaqueteController.php';
require_once __DIR__ . '/../src/Config/permissions.php';
 
session_start();
if (!tienePermiso('registro_paquete')) {
    header('Location: dashboard.php');
    exit;
}
 
$controller = new PaqueteController();
$mensaje = $controller->registrar();
 
$titulo = 'Registro de Paquete';
$pagina_actual = 'registro';
 
ob_start();
require_once __DIR__ . '/../views/components/registro_paquete.php';
$contenido = ob_get_clean();
 
require_once __DIR__ . '/../views/layout/main.php';