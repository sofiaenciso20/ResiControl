<?php
require_once __DIR__ . '/../bootstrap.php';
use App\Controllers\LicenciasController;

header('Content-Type: application/json');

try {
    // Verificar que el usuario sea superadmin
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
        throw new Exception('Acceso no autorizado');
    }

    // Verificar que se proporcionó un ID
    if (!isset($_GET['id'])) {
        throw new Exception('ID de licencia no proporcionado');
    }

    $id = (int)$_GET['id'];
    $licenciasController = new LicenciasController();
    $resultado = $licenciasController->obtenerLicencia($id);

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