<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/RegistroReservaController.php';

$controller = new RegistroReservaController();
$visitas = $controller->index(); // Cambiado aquí

$titulo = 'Registro de Reservas';
$pagina_actual = 'registro_reservas';

ob_start();
require_once __DIR__ . '/../views/components/registro_reserva.php';
$contenido = ob_get_clean();

require_once __DIR__ . '/../views/layout/main.php';