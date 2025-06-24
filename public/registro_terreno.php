<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/TerrenoController.php';
require_once __DIR__ . '/../src/Config/permissions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!tienePermiso('registro_terreno')) {
    header('Location: dashboard.php');
    exit;
}

// Obtener mensaje de sesión si existe
$mensaje = $_SESSION['mensaje'] ?? null;
unset($_SESSION['mensaje']);

$controller = new terrenoController();
$controller->registrar();

$titulo = 'Registro de Terreno';
$pagina_actual = 'registro_terreno';

ob_start();
require_once __DIR__ . '/../views/components/registro_terreno.php';
$contenido = ob_get_clean();

require_once __DIR__ . '/../views/layout/main.php';