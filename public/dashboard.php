<?php

session_start();
// Verificar si el usuario esta logueado
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];
$titulo = 'Dashboard';
$pagina_actual = 'dashboard';

ob_start();
require_once __DIR__ . '/../views/components/dashboard.php';

$contenido = ob_get_clean();
require_once __DIR__ . '/../views/layout/main.php';