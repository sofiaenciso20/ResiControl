<?php
require_once __DIR__ . '/../src/Config/Database.php'; // Incluye la clase de conexión a la base de datos
require_once __DIR__ . '/../vendor/autoload.php';      // Carga el autoloader de Composer para dependencias externas
require_once __DIR__ . '/../src/Controllers/PersonaController.php'; // Incluye el controlador de personas
require_once __DIR__ . '/../src/Config/permissions.php'; // Incluye las funciones de gestión de permisos

use App\Controllers\PersonaController;
 
session_start(); // Inicia la sesión para acceder a variables de usuario

// Verifica si el usuario tiene permiso para registrar personas
if (!tienePermiso('registro_persona')) {
    header('Location: dashboard.php'); // Si no tiene permiso, redirige al dashboard
    exit;
}

// Manejar peticiones AJAX para los datos de residencia
if (isset($_GET['action'])) {
    $db = new \App\Config\Database();
    $conn = $db->getConnection();
    
    header('Content-Type: application/json');
    
    switch ($_GET['action']) {
        case 'obtener_manzanas':
            $stmt = $conn->query("SELECT id_manzana FROM manzana ORDER BY id_manzana");
            $manzanas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($manzanas);
            exit;
            
        case 'obtener_bloques':
            $stmt = $conn->query("SELECT id_bloque FROM bloque ORDER BY id_bloque");
            $bloques = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($bloques);
            exit;
            
        case 'obtener_casas':
            if (isset($_GET['id_manzana'])) {
                $idManzana = (int)$_GET['id_manzana'];
                $stmt = $conn->prepare("SELECT id_casa, numero_casa FROM casas WHERE id_manzana = ? AND estado = 'disponible' ORDER BY numero_casa");
                $stmt->execute([$idManzana]);
                $casas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($casas);
            } else {
                echo json_encode([]);
            }
            exit;
            
        case 'obtener_apartamentos':
            if (isset($_GET['id_bloque'])) {
                $idBloque = (int)$_GET['id_bloque'];
                $stmt = $conn->prepare("SELECT id_apartamento, numero_apartamento FROM apartamentos WHERE id_bloque = ? AND estado = 'disponible' ORDER BY numero_apartamento");
                $stmt->execute([$idBloque]);
                $apartamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($apartamentos);
            } else {
                echo json_encode([]);
            }
            exit;
            
        default:
            echo json_encode(['error' => 'Acción no válida']);
            exit;
    }
}

// Consulta las marcas de vehículos
$db = new \App\Config\Database(); // Crea una instancia de la clase Database
$conn = $db->getConnection();      // Obtiene la conexión PDO a la base de datos
$stmt = $conn->query("SELECT id_marca, marca FROM marca"); // Consulta todas las marcas
$marcas = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtiene los resultados como array asociativo
 
// Consulta los tipos de vehículo
$stmtTipos = $conn->query("SELECT id_tipo_vehi, tipo_vehiculos FROM tipo_vehiculos"); // Consulta todos los tipos de vehículo
$tipos = $stmtTipos->fetchAll(PDO::FETCH_ASSOC); // Obtiene los resultados como array asociativo

// Consulta los tipos de documento
$stmtTiposDocs = $conn->query("SELECT id_tipo_doc, tipo_documento FROM tipo_documento"); // Consulta todos los tipos de documento
$tipos_documento = $stmtTiposDocs->fetchAll(PDO::FETCH_ASSOC); // Obtiene los resultados como array asociativo

// Consulta los roles de usuario (excluyendo Super Administrador por seguridad)
$stmtRoles = $conn->query("SELECT id_rol, rol FROM roles WHERE id_rol != 1"); // Excluye el Super Administrador (id_rol = 1)
$roles = $stmtRoles->fetchAll(PDO::FETCH_ASSOC); // Obtiene los resultados como array asociativo

// Instancia el controlador de personas y procesa el registro si el formulario fue enviado
$controller = new PersonaController();
$mensaje = $controller->registrar(); // Procesa el registro y devuelve un mensaje de éxito o error
 
$titulo = 'Registro de Persona'; // Título de la página
$pagina_actual = 'registro';     // Identificador de la página actual para el menú
 
ob_start(); // Inicia el buffer de salida para capturar el contenido de la vista
require_once __DIR__ . '/../views/components/registro_persona.php'; // Incluye el componente de la vista de registro de persona
$contenido = ob_get_clean(); // Guarda el contenido generado en la variable $contenido
 
require_once __DIR__ . '/../views/layout/main.php'; // Carga el layout principal de la aplicación, que usará $contenido para mostrar la página completa