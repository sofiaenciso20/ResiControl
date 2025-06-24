<?php
// Cargar el autoload de Composer (por si necesitas dependencias)
require_once __DIR__ . '/../vendor/autoload.php';
 
// Configuración de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('America/Bogota');
 
// Variable para mostrar mensajes de éxito o error en la vista
$mensaje = '';
 
// Si el formulario fue enviado (POST) y se recibieron los campos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['correo'], $_POST['codigo'], $_POST['nueva_contra'])) {
    // Sanitiza el correo recibido
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    // Obtiene el código y la nueva contraseña del formulario
    $codigo = $_POST['codigo'];
    $nueva_contra = $_POST['nueva_contra'];
 
    // Conexión a la base de datos
    require_once __DIR__ . '/../src/Config/database.php';
    $db = new \App\Config\Database();
    $conn = $db->getConnection();
 
    // Buscar usuario por correo y código de recuperación
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ? AND codigo_recuperacion = ? LIMIT 1");
    $stmt->execute([$correo, $codigo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // Si se encontró el usuario y el código es correcto
    if ($usuario) {
        // Verificar que el código no haya expirado
        if (strtotime($usuario['codigo_expira']) >= time()) {
            // Hashear la nueva contraseña
            $hash = password_hash($nueva_contra, PASSWORD_DEFAULT);
            // Actualizar la contraseña y limpiar el código de recuperación y expiración
            $stmtUpdate = $conn->prepare("UPDATE usuarios SET contrasena = ?, codigo_recuperacion = NULL, codigo_expira = NULL WHERE correo = ?");
            $stmtUpdate->execute([$hash, $correo]);
            // (Opcional) Iniciar sesión automáticamente con los datos del usuario
            session_start();
            $_SESSION['is_logged_in'] = true;
            $_SESSION['user'] = [
                'documento' => $usuario['documento'],
                'name' => $usuario['nombre'] . ' ' . $usuario['apellido'],
                'email' => $usuario['correo'],
                'role' => $usuario['id_rol']
            ];
            // Redirigir al dashboard tras el cambio de contraseña exitoso
            header('Location: dashboard.php');
            exit;
        } else {
            // Si el código ha expirado, mostrar mensaje de error
            $mensaje = 'El código ha expirado. Solicita uno nuevo.';
        }
    } else {
        // Si el código o el correo no son correctos, mostrar mensaje de error
        $mensaje = 'El código o el correo no son correctos.';
    }
}
 
// Variables para la página (título y página actual)
$titulo = 'Verificar Código';
$pagina_actual = 'verificar_codigo';
 
// Inicia el output buffering para capturar el contenido de la vista
ob_start();
require_once __DIR__ . '/../views/components/verificar_codigo.php';
$contenido = ob_get_clean();
 
// Carga el layout principal y muestra la página completa
require_once __DIR__ . '/../views/layout/main.php';