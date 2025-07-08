<?php
require_once __DIR__ . '/../bootstrap.php';
use App\Controllers\PersonaController;

header('Content-Type: application/json');

try {
    // Verificar que sea una petición POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    // Verificar que el usuario esté autenticado y sea admin
    if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], [1, 2])) {
        throw new Exception('Acceso no autorizado');
    }

    // Obtener y decodificar los datos JSON
    $jsonData = file_get_contents('php://input');
    $datos = json_decode($jsonData, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Error al decodificar JSON: ' . json_last_error_msg());
    }

    // Validar datos requeridos
    if (!isset($datos['tipo']) || empty(trim($datos['tipo']))) {
        throw new Exception('El tipo de documento es requerido');
    }

    $tipo = trim($datos['tipo']);

    // Validar longitud del tipo
    if (strlen($tipo) < 1 || strlen($tipo) > 50) {
        throw new Exception('El tipo de documento debe tener entre 1 y 50 caracteres');
    }

    // Crear el tipo de documento
    $controller = new PersonaController();
    $resultado = $controller->agregarTipoDocumento($tipo);

    if (!$resultado['success']) {
        throw new Exception($resultado['mensaje']);
    }

    echo json_encode($resultado);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'mensaje' => $e->getMessage()
    ]);
} 