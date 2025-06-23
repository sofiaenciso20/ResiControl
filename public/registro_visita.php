<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/RegistroVisitasController.php';
require_once __DIR__ . '/../src/Config/permissions.php';
 
session_start();
if (!tienePermiso('registro_visita')) {
    header('Location: dashboard.php');
    exit;
}


$controller = new RegistroVisitasController();
$visitas = $controller->index(); // Cambiado aquí

$titulo = 'Registro de Visitas';
$pagina_actual = 'registro_visitas';

ob_start();
require_once __DIR__ . '/../views/components/registro_visita.php';
$contenido = ob_get_clean();

require_once __DIR__ . '/../views/layout/main.php';