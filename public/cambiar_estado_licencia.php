<?php
// Incluye el archivo de arranque del proyecto (carga clases, inicia sesión, etc.)
require_once __DIR__ . '/../bootstrap.php';

// Importa el controlador de licencias desde el namespace correspondiente
use App\Controllers\LicenciasController;

// Indica que la respuesta será en formato JSON
header('Content-Type: application/json');

try {
    // 1. Verifica que la petición sea de tipo POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    // 2. Verifica que el usuario esté autenticado y sea superadmin (rol 1)
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
        throw new Exception('Acceso no autorizado');
    }

    // 3. Obtiene y decodifica los datos JSON enviados en el cuerpo de la petición
    $jsonData = file_get_contents('php://input');
    $datos = json_decode($jsonData, true);

    // 4. Verifica que la decodificación JSON fue exitosa
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Error al decodificar JSON: ' . json_last_error_msg());
    }

    // 5. Valida que se hayan enviado los campos requeridos: id y estado
    if (!isset($datos['id']) || !isset($datos['estado'])) {
        throw new Exception('ID de licencia y estado son requeridos');
    }

    // 6. Valida que el estado enviado sea uno de los permitidos
    if (!in_array($datos['estado'], ['activa', 'inactiva'])) {
        throw new Exception('Estado no válido');
    }

    // 7. Instancia el controlador de licencias y actualiza el estado de la licencia
    $licenciasController = new LicenciasController();
    $resultado = $licenciasController->actualizarLicencia($datos['id'], [
        'estado' => $datos['estado']
    ]);

    // 8. Si la actualización falla, lanza una excepción con el mensaje de error
    if (!$resultado['success']) {
        throw new Exception($resultado['mensaje']);
    }

    // 9. Si todo sale bien, responde con el resultado en formato JSON
    echo json_encode($resultado);

} catch (Exception $e) {
    // Si ocurre cualquier error, responde con código 400 y el mensaje de error en JSON
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'mensaje' => $e->getMessage()
    ]);
}