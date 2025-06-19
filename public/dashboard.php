<?php

session_start();
// Verificar si el usuario esta logueado
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];
// Consulta el nombre del rol
/*
---------------------------------------------------------------
FLUJO DE CONSULTA DEL NOMBRE DEL ROL EN EL DASHBOARD
---------------------------------------------------------------
 
1. Cuando el usuario inicia sesión, se guarda en la sesión el id del rol (id_rol) como parte de $_SESSION['user']['role'].
 
2. Al cargar el dashboard, se recupera el usuario autenticado desde la sesión.
 
3. Antes de mostrar la vista, se realiza una consulta a la tabla 'roles' usando el id_rol del usuario:
      SELECT rol FROM roles WHERE id_rol = ?
 
   Esto obtiene el nombre legible del rol (por ejemplo, "Administrador", "Residente", etc.).
 
4. El nombre del rol obtenido ($rol_nombre) se pasa a la vista del dashboard.
 
5. En la vista, se muestra el nombre del rol en vez del id, haciendo la interfaz más clara y amigable para el usuario.
*/
 
require_once __DIR__ . '/../src/Config/database.php';
$db = new \App\Config\Database();
$conn = $db->getConnection();
$stmt = $conn->prepare("SELECT rol FROM roles WHERE id_rol = ?");
$stmt->execute([$user['role']]);
$rol_nombre = $stmt->fetchColumn();
$titulo = 'Dashboard';
$pagina_actual = 'dashboard';

ob_start();
require_once __DIR__ . '/../views/components/dashboard.php';

$contenido = ob_get_clean();
require_once __DIR__ . '/../views/layout/main.php';