<?php
 
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/TerrenoController.php';
 
$controller = new terrenoController();
$mensaje = $controller->registrar();
 
$titulo = 'Registro de Terreno';
$pagina_actual = 'registro_terreno';
 
ob_start();
require_once __DIR__ . '/../views/components/registro_terreno.php';
$contenido = ob_get_clean();
 
require_once __DIR__ . '/../views/layout/main.php';