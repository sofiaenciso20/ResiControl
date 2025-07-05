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

    // 5. Valida que se haya enviado el ID de la licencia
    if (!isset($datos['licencia_id'])) {
        throw new Exception('ID de licencia no proporcionado');
    }

    // 6. Valida que los campos requeridos estén presentes y no vacíos
    $camposRequeridos = ['nombre_residencial', 'fecha_fin', 'max_usuarios', 'max_residentes'];
    foreach ($camposRequeridos as $campo) {
        if (!isset($datos[$campo]) || empty($datos[$campo])) {
            throw new Exception("El campo $campo es requerido");
        }
    }

    // 7. Valida que los valores máximos sean mayores a 0
    $maxUsuarios = intval($datos['max_usuarios']);
    $maxResidentes = intval($datos['max_residentes']);

    if ($maxUsuarios < 1 || $maxResidentes < 1) {
        throw new Exception('Los valores máximos deben ser mayores a 0');
    }

    // 8. Valida que el número máximo de residentes no sea mayor al de usuarios
    if ($maxResidentes > $maxUsuarios) {
        throw new Exception('El número máximo de residentes no puede ser mayor al número máximo de usuarios');
    }

    // 9. Instancia el controlador de licencias y llama al método para actualizar la licencia
    $licenciasController = new LicenciasController();
    $resultado = $licenciasController->actualizarLicencia($datos['licencia_id'], $datos);

    // 10. Si la actualización falla, lanza una excepción con el mensaje de error
    if (!$resultado['success']) {
        throw new Exception($resultado['mensaje']);
    }

    // 11. Si todo sale bien, responde con el resultado en formato JSON
    echo json_encode($resultado);

} catch (Exception $e) {
    // Si ocurre cualquier error, responde con código 400 y el mensaje de error en JSON
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'mensaje' => $e->getMessage()
    ]);
}