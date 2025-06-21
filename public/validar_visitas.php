<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/ValidarVisitasController.php';

$controller = new ValidarVisitasController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->validar();
}


$titulo = 'Validar Visitas';
$pagina_actual = 'validar_visitas';

ob_start();
require_once __DIR__ . '/../views/components/validar_visitas.php';
$contenido = ob_get_clean();

require_once __DIR__ . '/../views/layout/main.php';