<?php
 
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/PersonaController.php';
 
$controller = new PersonaController();
$mensaje = $controller->registrar();
 
$titulo = 'Registro de Persona';
$pagina_actual = 'registro';
 
ob_start();
require_once __DIR__ . '/../views/components/registro_persona.php';
$contenido = ob_get_clean();
 
require_once __DIR__ . '/../views/layout/main.php';