<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/ReservasController.php';
require_once __DIR__ . '/../src/Config/permissions.php';
 
session_start();
if (!tienePermiso('gestion_reservas')) {
    header('Location: dashboard.php');
    exit;
}

$controller = new ReservasController();
$visitas = $controller->index(); // Cambiado aquí

$titulo = 'Gestion de reservas';
$pagina_actual = 'gestion_reservas';

ob_start();
require_once __DIR__ . '/../views/components/gestion_reservas.php';
$contenido = ob_get_clean();

require_once __DIR__ . '/../views/layout/main.php';