<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/RegistroVisitasController.php';
require_once __DIR__ . '/../src/Config/permissions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!tienePermiso('registro_visita')) {
    header('Location: dashboard.php');
    exit;
}

// Conexión a la base de datos
require_once __DIR__ . '/../src/config/Database.php';
use App\Config\Database;

$db = new Database();
$conn = $db->getConnection();

// Cargar residentes
$sql = "SELECT documento, CONCAT(nombre, ' ', apellido) AS nombre_completo 
        FROM usuarios 
        WHERE id_rol = 3 AND id_estado = 1";
$residentes = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Cargar motivos
$sqlMotivos = "SELECT id_mot_visi, motivo_visita FROM motivo_visita";
$motivos = $conn->query($sqlMotivos)->fetchAll(PDO::FETCH_ASSOC);

// Mensaje de sesión
$mensaje = $_SESSION['mensaje_visita'] ?? null;
unset($_SESSION['mensaje_visita']);

$controller = new RegistroVisitasController();
$visitas = $controller->index();

$titulo = 'Registro de Visita';
$pagina_actual = 'registro_visita';

ob_start();
require_once __DIR__ . '/../views/components/registro_visita.php';
$contenido = ob_get_clean();

require_once __DIR__ . '/../views/layout/main.php';