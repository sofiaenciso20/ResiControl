<?php
session_start();
require_once __DIR__ . '/../src/Controllers/LicenciasController.php';
require_once __DIR__ . '/../vendor/autoload.php';
 
use App\Controllers\LicenciasController;

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'mensaje' => 'Método no permitido']);
    exit;
}

// Verificar que el usuario sea superadmin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
    http_response_code(403);
    echo json_encode(['success' => false, 'mensaje' => 'Acceso no autorizado']);
    exit;
}

// Obtener y decodificar los datos JSON
$datos = json_decode(file_get_contents('php://input'), true);

// Validar datos requeridos
if (!isset($datos['id']) || !isset($datos['estado'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'mensaje' => 'ID y estado son requeridos']);
    exit;
}

// Validar estado válido
if (!in_array($datos['estado'], ['activa', 'inactiva'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'mensaje' => 'Estado no válido']);
    exit;
}

// Actualizar estado de la licencia
$licenciasController = new LicenciasController();
$resultado = $licenciasController->actualizarLicencia($datos['id'], [
    'estado' => $datos['estado']
]);

// Devolver respuesta
header('Content-Type: application/json');
echo json_encode($resultado);