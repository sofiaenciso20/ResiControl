<?php
 
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/ZonaController.php';
 
$controller = new zonaController();
$mensaje = $controller->registrar();
 
$titulo = 'Registro de zona';
$pagina_actual = 'registro';
 
ob_start();
require_once __DIR__ . '/../views/components/registro_zona.php';
$contenido = ob_get_clean();
 
require_once __DIR__ . '/../views/layout/main.php';