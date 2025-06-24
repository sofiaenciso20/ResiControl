<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Config/permissions.php';

session_start();

// Validar permiso antes de mostrar la vista
if (!tienePermiso('gestion_roles')) {
    header('Location: dashboard.php');
    exit;
}

// Procesar cambio de rol (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['documento'], $_POST['id_rol'])) {
    $documento = $_POST['documento'];
    $id_rol = $_POST['id_rol'];
 
    require_once __DIR__ . '/../src/Config/database.php';
    $db = new \App\Config\Database();
    $conn = $db->getConnection();
 
    $stmt = $conn->prepare("UPDATE usuarios SET id_rol = ? WHERE documento = ?");
    $stmt->execute([$id_rol, $documento]);
    // Guardar mensaje de éxito en la sesión
    $_SESSION['mensaje_exito'] = 'Rol actualizado correctamente.';
    // Redirigir para evitar reenvío del formulario y mostrar la tabla actualizada
    header('Location: gestion_roles.php');
    exit;
}

// Consulta de usuarios y roles para mostrar en la vista
require_once __DIR__ . '/../src/Config/database.php';
$db = new \App\Config\Database();
$conn = $db->getConnection();
 
$query = "SELECT u.documento, u.nombre, u.apellido, u.correo, r.rol, u.id_rol
          FROM usuarios u
          JOIN roles r ON u.id_rol = r.id_rol";
$stmt = $conn->prepare($query);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta todos los roles para el select
$stmtRoles = $conn->query("SELECT id_rol, rol FROM roles");
$roles = $stmtRoles->fetchAll(PDO::FETCH_ASSOC);

// Obtener y limpiar mensaje de éxito
$mensaje_exito = '';
if (isset($_SESSION['mensaje_exito'])) {
    $mensaje_exito = $_SESSION['mensaje_exito'];
    unset($_SESSION['mensaje_exito']);
}

// Variables de control para el layout
$titulo = 'Gestión de Roles';
$pagina_actual = 'gestion_roles';

// Cargar contenido dentro del layout
ob_start();
require_once __DIR__ . '/../views/components/gestion_roles.php';
$contenido = ob_get_clean();

require_once __DIR__ . '/../views/layout/main.php';
