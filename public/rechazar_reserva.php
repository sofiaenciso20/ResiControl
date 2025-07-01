<?php
// Incluimos el controlador necesario para manejar las reservas
require_once __DIR__ . '/../src/Controllers/ReservasController.php';
 
// Iniciamos la sesión para acceder a las variables de sesión
session_start();
 
// Verificamos la autenticación y los permisos del usuario
// Solo los administradores pueden rechazar reservas
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 2 && $_SESSION['user']['role'] !== 1) {
    // Si no es administrador, redirigimos al login
    header('Location: login.php');
    exit;
}
 
 
// Validamos que se haya enviado el ID de la reserva en el formulario
if (!isset($_POST['id_reserva'])) {
    // Si no se recibió el ID, guardamos mensaje de error y redirigimos
    $_SESSION['error'] = 'ID de reserva no proporcionado';
    header('Location: dashboard.php');
    exit;
}
 
// Obtenemos el ID de la reserva del formulario
$id_reserva = $_POST['id_reserva'];
 
// Creamos una instancia del controlador de reservas
$controller = new ReservasController();
 
try {
    // Intentamos rechazar la reserva
    // El método rechazarReserva cambia el id_estado a 3 (rechazada)
    if ($controller->rechazarReserva($id_reserva)) {
        // Si la actualización fue exitosa, guardamos mensaje de éxito
        $_SESSION['success'] = 'Reserva rechazada exitosamente';
    } else {
        // Si hubo un error en la actualización, guardamos mensaje de error
        $_SESSION['error'] = 'Error al rechazar la reserva';
    }
} catch (Exception $e) {
    // Si ocurre una excepción (error de BD u otro), guardamos mensaje de error
    $_SESSION['error'] = 'Error al procesar la solicitud';
}
 
// Redirigimos al usuario a la página anterior
// $_SERVER['HTTP_REFERER'] contiene la URL de la página que hizo la petición
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
 