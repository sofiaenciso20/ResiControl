<?php
require_once __DIR__ . '/../src/Config/Database.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Controllers/PersonaController.php';
// Consulta las marcas
$db = new \App\Config\Database();
$conn = $db->getConnection();
$stmt = $conn->query("SELECT id_marca, marca FROM marca");
$marcas = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
// Consulta los tipos de vehículo
$stmtTipos = $conn->query("SELECT id_tipo_vehi, tipo_vehiculos FROM tipo_vehiculos");
$tipos = $stmtTipos->fetchAll(PDO::FETCH_ASSOC);
$controller = new PersonaController();
$mensaje = $controller->registrar();
 
$titulo = 'Registro de Persona';
$pagina_actual = 'registro';
 
ob_start();
require_once __DIR__ . '/../views/components/registro_persona.php';
$contenido = ob_get_clean();
 
require_once __DIR__ . '/../views/layout/main.php';
