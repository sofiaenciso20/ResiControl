<?php
require_once __DIR__ . '/../bootstrap.php';
use App\Controllers\LicenciasController;

header('Content-Type: application/json');

try {
    // Verificar que el usuario sea superadmin
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
        throw new Exception('Acceso no autorizado');
    }

    // Verificar que se proporcionó un código
    if (!isset($_GET['codigo'])) {
        throw new Exception('Código de licencia no proporcionado');
    }

    $codigo = $_GET['codigo'];
    $licenciasController = new LicenciasController();
    $estadisticas = $licenciasController->obtenerEstadisticasUso($codigo);

    echo json_encode([
        'success' => true,
        'data' => $estadisticas
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'mensaje' => $e->getMessage()
    ]);
}