<?php
session_start();

require_once __DIR__ . '/../bootstrap.php';
 
use App\Controllers\LicenciasController;


header('Content-Type: application/json');

try {
    // Verificar que sea una petición POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    // Verificar que el usuario sea superadmin
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 1) {
        throw new Exception('Acceso no autorizado');
    }

    // Obtener y decodificar los datos JSON
    $jsonData = file_get_contents('php://input');
    $datos = json_decode($jsonData, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Error al decodificar JSON: ' . json_last_error_msg());
    }

    // Log para debugging
    error_log('Datos recibidos: ' . print_r($datos, true));

    // Validar datos requeridos
    $camposRequeridos = ['id_administrador', 'nombre_residencial', 'fecha_inicio', 'fecha_fin', 'max_usuarios', 'max_residentes'];
    foreach ($camposRequeridos as $campo) {
        if (!isset($datos[$campo]) || empty($datos[$campo])) {
            throw new Exception("El campo $campo es requerido");
        }
    }

    // Validar que el administrador existe y tiene rol 2
    require_once __DIR__ . '/../src/Config/database.php';
    $db = new \App\Config\Database();
    $conn = $db->getConnection();
    
    $stmtAdmin = $conn->prepare("SELECT documento FROM usuarios WHERE documento = ? AND id_rol = 2 AND id_estado = 1");
    $stmtAdmin->execute([$datos['id_administrador']]);
    
    if (!$stmtAdmin->fetch()) {
        throw new Exception('El administrador seleccionado no es válido');
    }
    
    // Verificar que el administrador no tenga ya una licencia asignada
    $stmtLicencia = $conn->prepare("SELECT id_licencia FROM usuarios WHERE documento = ? AND id_licencia IS NOT NULL");
    $stmtLicencia->execute([$datos['id_administrador']]);
    
    if ($stmtLicencia->fetch()) {
        throw new Exception('El administrador seleccionado ya tiene una licencia asignada');
    }

    // Validar fechas
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

    // Validar números máximos
    $maxUsuarios = intval($datos['max_usuarios']);
    $maxResidentes = intval($datos['max_residentes']);

    if ($maxUsuarios < 1 || $maxResidentes < 1) {
        throw new Exception('Los valores máximos deben ser mayores a 0');
    }

    if ($maxResidentes > $maxUsuarios) {
        throw new Exception('El número máximo de residentes no puede ser mayor al número máximo de usuarios');
    }

    // Crear la licencia
    $licenciasController = new LicenciasController();
    $resultado = $licenciasController->crearLicencia($datos);

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
    error_log('Error en crear_licencia.php: ' . $e->getMessage());
}