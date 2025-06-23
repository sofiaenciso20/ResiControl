<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Config/permissions.php';
 
session_start();
if (!tienePermiso('gestion_roles')) {
    header('Location: dashboard.php');
    exit;
}


$titulo = 'Gestion de Roles';
$pagina_actual = 'gestion_roles';

ob_start();
require_once __DIR__ . '/../views/components/gestion_roles.php';
$contenido = ob_get_clean();

require_once __DIR__ . '/../views/layout/main.php';