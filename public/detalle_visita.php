<?php
// Incluye el controlador de visitas
require_once __DIR__ . '/../src/Controllers/VisitasController.php';

// Carga automática de clases mediante Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Inicia la sesión para poder acceder a las variables de sesión del usuario
session_start();

// Obtiene el ID de la visita desde la URL, si no está definido, se asigna null
$id = $_GET['id'] ?? null;

// Determina si se activa el modo edición:
// - El parámetro 'editar' debe estar en la URL y ser igual a 1
// - El usuario debe tener la sesión iniciada
// - El rol del usuario debe ser 1 (SuperAdmin) o 2 (Admin)
$modo_edicion = (
    isset($_GET['editar']) && $_GET['editar'] == 1 &&
    isset($_SESSION['user']) && in_array($_SESSION['user']['role'], [1, 2])
);

// Instancia el controlador de visitas
$controller = new VisitasController();

// Inicializa variables para la visita y mensajes
$visita = null;
$mensaje = '';

// Si el formulario fue enviado por método POST y se cumplen condiciones de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id && $modo_edicion) {
    // Se recopilan los datos enviados por el formulario
    $datos = [
        'nombre' => $_POST['nombre'] ?? '',
        'apellido' => $_POST['apellido'] ?? '',
        'documento' => $_POST['documento'] ?? '',
        'id_mot_visi' => $_POST['id_mot_visi'] ?? '',
        'fecha_ingreso' => $_POST['fecha_ingreso'] ?? '',
        'hora_ingreso' => $_POST['hora_ingreso'] ?? ''
    ];
    // Se actualiza la visita en la base de datos usando el controlador
    $controller->actualizarVisita($id, $datos);

    // Redirige a la misma página sin modo edición y muestra mensaje de éxito
    header('Location:/detalle_visita.php?id=' . urlencode($id) . "&actualizado=1");
    exit;
}

// Si se proporciona un ID, se consulta el detalle de la visita
if ($id) {
    $visita = $controller->obtenerDetalleVisita($id);
}

// Si se pasó por GET el parámetro 'actualizado', se define el mensaje de éxito
if (isset($_GET['actualizado'])) {
    $mensaje = 'Visita actualizada correctamente.';
}

// Define el título y la página actual para el layout
$titulo = 'Ver Visita';
$pagina_actual = 'ver_visita';

// Inicia el output buffering para capturar el contenido generado por la vista
ob_start();

// Se incluye la vista con el componente que muestra los datos de la visita
include __DIR__ . '/../views/components/detalle_visita.php';

// Se guarda el contenido de la vista en la variable $contenido
$contenido = ob_get_clean();

// Carga el layout principal y muestra la página con el contenido insertado
require_once __DIR__ . '/../views/layout/main.php';
