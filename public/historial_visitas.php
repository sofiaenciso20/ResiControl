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
$visitas = $controller->index(); // Cambiado aquí

$titulo = 'Historial de Visitas';
$pagina_actual = 'historial_visitas';

ob_start();
require_once __DIR__ . '/../views/components/historial_visitas.php';
$contenido = ob_get_clean();

require_once __DIR__ . '/../views/layout/main.php';