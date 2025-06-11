<?php

//cargar el autoload de composer
require_once __DIR__ . '/../vendor/autoload.php';

//configuracion de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', '1');

//zona horaria por defecto 
date_default_timezone_set('America/Bogota');

//variables para la pagina
$titulo = 'sobre nosotros';
$pagina_actual = 'sobre_nosotros';
//contenido de la pagina
ob_start();

//importar el componente home
require_once __DIR__ . '/../views/components/sobre_nosotros.php';

//capturar el contenido de la pagina
$contenido = ob_get_clean();

//cargar layout principal
require_once __DIR__ . '/../views/layout/main.php';



