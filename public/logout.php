<?php
// Iniciar la sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 
// Limpiar todas las variables de sesión
$_SESSION = array();
 
// Destruir la cookie de sesión si existe
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}
 
// Destruir la sesión
session_destroy();
 
// Redirigir al usuario a la página de login
header('Location: /login.php');
exit();