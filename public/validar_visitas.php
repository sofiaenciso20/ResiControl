<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/ValidarVisitasController.php';

$controller = new ValidarVisitasController();
$visitas = $controller->index(); // Cambiado aquí

$titulo = 'Validar Visitas';
$pagina_actual = 'validar_visitas';

ob_start();
require_once __DIR__ . '/../views/components/validar_visitas.php';
$contenido = ob_get_clean();

require_once __DIR__ . '/../views/layout/main.php';