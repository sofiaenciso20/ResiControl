<?php
session_start(); // Inicia la sesión para acceder a variables de usuario

require_once __DIR__ . '/../bootstrap.php'; // Carga el entorno del proyecto (autoload, config, sesión, etc.)

use App\Controllers\LicenciasController; // Importa el controlador de licencias

header('Content-Type: application/json'); // Indica que la respuesta será en formato JSON

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

    // 5. Log para debugging (opcional, útil para desarrollo)
    error_log('Datos recibidos: ' . print_r($datos, true));

    // 6. Valida que todos los campos requeridos estén presentes y no vacíos
    $camposRequeridos = ['nombre_residencial', 'fecha_inicio', 'fecha_fin', 'max_usuarios', 'max_residentes'];
    foreach ($camposRequeridos as $campo) {
        if (!isset($datos[$campo]) || empty($datos[$campo])) {
            throw new Exception("El campo $campo es requerido");
        }
    }

    // 7. Valida las fechas (formato y lógica)
    $fechaInicio = strtotime($datos['fecha_inicio']);
    $fechaFin = strtotime($datos['fecha_fin']);
    $hoy = strtotime('today');

    if ($fechaInicio === false || $fechaFin === false) {
        throw new Exception('Formato de fecha inválido');
    }

    if ($fechaInicio < $hoy) {
        throw new Exception('La fecha de inicio no puede ser anterior a hoy');
    }

    if ($fechaFin <= $fechaInicio) {
        throw new Exception('La fecha de fin debe ser posterior a la fecha de inicio');
    }

    // 8. Valida los valores máximos de usuarios y residentes
    $maxUsuarios = intval($datos['max_usuarios']);
    $maxResidentes = intval($datos['max_residentes']);

    if ($maxUsuarios < 1 || $maxResidentes < 1) {
        throw new Exception('Los valores máximos deben ser mayores a 0');
    }

    if ($maxResidentes > $maxUsuarios) {
        throw new Exception('El número máximo de residentes no puede ser mayor al número máximo de usuarios');
    }

    // 9. Crea la licencia usando el controlador
    $licenciasController = new LicenciasController();
    $resultado = $licenciasController->crearLicencia($datos);

    // 10. Si la creación falla, lanza una excepción con el mensaje de error
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
    // Log del error para depuración
    error_log('Error en crear_licencia.php: ' . $e->getMessage());
}