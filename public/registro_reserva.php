<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/RegistroReservaController.php';
require_once __DIR__ . '/../src/Config/permissions.php';
require_once __DIR__ . '/../src/Config/Database.php';
 
session_start();
if (!tienePermiso('registro_reserva')) {
    header('Location: dashboard.php');
    exit;
}

$controller = new RegistroReservaController();
$visitas = $controller->index(); // Cambiado aquí

$titulo = 'Registro de Reservas';
$pagina_actual = 'registro_reservas';

// Conexión a la base de datos
$db = new \App\Config\Database();
$conn = $db->getConnection();
 
// Obtener zonas
$zonas = $conn->query("SELECT id_zonas_comu, nombre_zona FROM zonas_comunes")->fetchAll(PDO::FETCH_ASSOC);
 
// Obtener todos los horarios posibles
$horariosPosibles = $conn->query("SELECT id_horario, horario FROM horario")->fetchAll(PDO::FETCH_ASSOC);
 
// Inicializar horarios ocupados vacío
$horariosOcupados = [];
if (isset($_POST['zona'], $_POST['fecha'])) {
    $id_zona = $_POST['zona'];
    $fecha = $_POST['fecha'];
    $stmt = $conn->prepare("SELECT id_horario FROM reservas WHERE id_zonas_comu = ? AND fecha = ?");
    $stmt->execute([$id_zona, $fecha]);
    $horariosOcupados = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_zona = $_POST['zona'];
    $fecha = $_POST['fecha'];
    $id_horario = $_POST['horario'];
    $residente = trim($_POST['residente']);
    // Aquí deberías obtener el id_usuarios real según el residente (ajustar si tienes login)
    
    $id_usuarios = isset($_SESSION['user']['documento']) ? $_SESSION['user']['documento'] : null;
    if (!$id_usuarios) {
        die('Error: No se encontró el usuario en sesión.');
    }
    $id_estado = 1; // Pendiente
    $observaciones = '';
    $id_mot_zonas = 1; // Puedes ajustar esto según tu formulario
 
    $stmt = $conn->prepare("INSERT INTO reservas (id_zonas_comu, id_usuarios, fecha, id_horario, id_estado, observaciones, id_mot_zonas) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id_zona, $id_usuarios, $fecha, $id_horario, $id_estado, $observaciones, $id_mot_zonas]);
    // Redirigir o mostrar mensaje de éxito
    header('Location: gestion_reservas.php?reserva=ok');
    exit;
}
 
 

ob_start();
require_once __DIR__ . '/../views/components/registro_reserva.php';
$contenido = ob_get_clean();

require_once __DIR__ . '/../views/layout/main.php';