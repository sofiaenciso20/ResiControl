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
    if (!isset($datos['rol']) || empty(trim($datos['rol']))) {
        throw new Exception('El rol es requerido');
    }

    $rol = trim($datos['rol']);

    // Validar longitud del rol
    if (strlen($rol) < 1 || strlen($rol) > 100) {
        throw new Exception('El rol debe tener entre 1 y 100 caracteres');
    }

    // Crear el rol
    $controller = new PersonaController();
    $resultado = $controller->agregarRol($rol);

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