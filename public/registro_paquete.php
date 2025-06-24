<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/PaqueteController.php';
require_once __DIR__ . '/../src/Config/permissions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!tienePermiso('registro_paquete')) {
    header('Location: dashboard.php');
    exit;
}

// Conexión a la base de datos
require_once __DIR__ . '/../src/config/Database.php';
use App\Config\Database;

$db = new Database();
$conn = $db->getConnection();

// Cargar residentes
$sqlResidentes = "SELECT documento, CONCAT(nombre, ' ', apellido) AS nombre_completo 
                  FROM usuarios 
                  WHERE id_rol = 3 AND id_estado = 1";
$residentes = $conn->query($sqlResidentes)->fetchAll(PDO::FETCH_ASSOC);

// Cargar vigilantes
$sqlVigilantes = "SELECT documento, CONCAT(nombre, ' ', apellido) AS nombre_completo 
                  FROM usuarios 
                  WHERE id_rol = 4 AND id_estado = 1";
$vigilantes = $conn->query($sqlVigilantes)->fetchAll(PDO::FETCH_ASSOC);

// Mostrar mensaje si hay
$mensaje = $_SESSION['mensaje_paquete'] ?? null;
unset($_SESSION['mensaje_paquete']);

$controller = new PaqueteController();
$controller->registrar();

$titulo = 'Registro de Paquete';
$pagina_actual = 'registro';

ob_start();
require_once __DIR__ . '/../views/components/registro_paquete.php';
$contenido = ob_get_clean();

require_once __DIR__ . '/../views/layout/main.php';