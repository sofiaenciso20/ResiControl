<?php

require_once __DIR__ . '/../vendor/autoload.php';

$titulo = 'Gestion de Roles';
$pagina_actual = 'gestion_roles';

ob_start();
require_once __DIR__ . '/../views/components/gestion_roles.php';
$contenido = ob_get_clean();

require_once __DIR__ . '/../views/layout/main.php';