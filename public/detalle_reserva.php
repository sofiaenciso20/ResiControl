<?php
require_once __DIR__ . '/../src/Controllers/ReservasController.php';
require_once __DIR__ . '/../vendor/autoload.php';

session_start();

$id = $_GET['id'] ?? null;
$modo_edicion = isset($_GET['editar']) && $_GET['editar'] == 1 && isset($_SESSION['user']) && in_array($_SESSION['user']['role'], [1, 2]);

$controller = new ReservasController();
$reserva = null;
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $modo_edicion && $id) {
    $datos = [
        'fecha' => $_POST['fecha'],
        'id_horario' => $_POST['id_horario'],
        'observaciones' => $_POST['observaciones'],
        'id_mot_zonas' => $_POST['id_mot_zonas']
    ];
    $controller->actualizarReserva($id, $datos);
    header("Location: detalle_reserva.php?id=" . urlencode($id) . "&actualizado=1");
    exit;
}

if ($id) {
    $reserva = $controller->obtenerDetalleReserva($id);
}
if (isset($_GET['actualizado'])) {
    $mensaje = 'Reserva actualizada correctamente.';
}

$titulo = 'ver reservas';   
$pagina_actual = 'ver_reserva';
 
// Inicia el output buffering para capturar el contenido de la vista
ob_start();
include __DIR__ . '/../views/components/detalle_reserva.php';
$contenido = ob_get_clean();
 
// Carga el layout principal y muestra la página completa
require_once __DIR__ . '/../views/layout/main.php';