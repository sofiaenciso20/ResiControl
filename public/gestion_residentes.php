<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/ResidentesController.php';
require_once __DIR__ . '/../src/Config/permissions.php';
 
session_start();
if (!tienePermiso('gestion_residentes')) {
    header('Location: dashboard.php');
    exit;
}

$controller = new ResidentesController();
$visitas = $controller->index(); // Cambiado aquí

$titulo = 'Gestion de residentes';
$pagina_actual = 'gestion_residentes';

ob_start();
require_once __DIR__ . '/../views/components/gestion_residentes.php';
$contenido = ob_get_clean();

require_once __DIR__ . '/../views/layout/main.php';