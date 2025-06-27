<?php
// Incluye el controlador de Residentes y el autoload de Composer
require_once __DIR__ . '/../src/Controllers/ResidentesController.php';
require_once __DIR__ . '/../vendor/autoload.php';
 
// Inicia la sesión para acceder a los datos del usuario logueado
session_start();
 
// Obtiene el ID del residente desde la URL (?id=...)
$id = $_GET['id'] ?? null;
 
// Determina si el usuario está en modo edición (solo admins/superadmins pueden editar)
$modo_edicion = (
    isset($_GET['editar']) && $_GET['editar'] == 1 &&
    isset($_SESSION['user']) && in_array($_SESSION['user']['role'], [1,2])
);
 
// Inicializa variables
$residente = null;
$mensaje = '';
 
// Crea una instancia del controlador de residentes
$controller = new ResidentesController();
 
// Si se envió el formulario por POST y el usuario tiene permisos de admin/superadmin
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    $id &&
    isset($_SESSION['user']) &&
    in_array($_SESSION['user']['role'], [1,2])
) {
    // Recoge los datos enviados por el formulario
    $datos = [
        'nombre' => $_POST['nombre'] ?? '',
        'apellido' => $_POST['apellido'] ?? '',
        'telefono' => $_POST['telefono'] ?? '',
        'correo' => $_POST['correo'] ?? '',
        'direccion_casa' => $_POST['direccion_casa'] ?? '',
        'cantidad_personas' => $_POST['cantidad_personas'] ?? '',
        'tiene_animales' => isset($_POST['tiene_animales']) ? 1 : 0,
        'cantidad_animales' => $_POST['cantidad_animales'] ?? '',
        'direccion_residencia' => $_POST['direccion_residencia'] ?? ''
    ];
    // Actualiza los datos del residente en la base de datos
    $controller->actualizarResidente($id, $datos);
    // Redirige a la misma página en modo vista y muestra mensaje de éxito
    header('Location: /ResiControl/public/detalle_persona.php?id=' . urlencode($id) . '&actualizado=1');
    exit;
}
 
// Si hay un ID, obtiene los datos del residente para mostrar o editar
if ($id) {
    $residente = $controller->obtenerDetalleResidente($id);
}
// Si la URL tiene ?actualizado=1, muestra mensaje de éxito
if (isset($_GET['actualizado'])) {
    $mensaje = 'Datos actualizados correctamente.';
}
 
// Variables para el layout principal
$titulo = 'Ver Residente';
$pagina_actual = 'ver_residente';
 
// Inicia el output buffering para capturar el contenido de la vista
ob_start();
include __DIR__ . '/../views/components/detalle_persona.php';
$contenido = ob_get_clean();
 
// Carga el layout principal y muestra la página completa
require_once __DIR__ . '/../views/layout/main.php';