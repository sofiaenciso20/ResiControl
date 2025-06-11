<?php

//cargar el autoload de composer
require_once __DIR__ . '/../vendor/autoload.php';

//configuracion de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', '1');

//zona horaria por defecto 
date_default_timezone_set('America/Bogota');

//inciar la sesion
session_start();

//usuarios de prueba
$usuarios = [
    ['email' => 'encisogarciaelisabetsofia@gmail.com',
    'password' => 'admin12',
    'role'=> 'admin'],
    ['email' => 'cliente@gmail.com',
    'password' => 'cliente123', 
    'role'=> 'usuario']
];

// Usuarios de prueba
$demo_users = [
    ['email' => 'admin@example.com', 'password' => 'admin123', 'role' => 'admin'],
    ['email' => 'usuario@example.com', 'password' => 'user123', 'role' => 'user']
];
 
// Manejar el envío del formulario
$error_message = '';
$success_message = '';
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
 
    // Simular validación
    $user = null;
    foreach ($demo_users as $demo_user) {
        if ($demo_user['email'] === $email && $demo_user['password'] === $password) {
            $user = $demo_user;
            break;
        }
    }
 
    if ($user) {
        // Simular inicio de sesión exitoso
        $_SESSION['user'] = [
            'email' => $user['email'],
            'role' => $user['role']
        ];
 
        // Si marcó "recordarme", establecer una cookie
        if ($remember) {
            setcookie('user_email', $email, time() + (86400 * 30), "/"); // 30 días
        }
 
        // Redirigir al dashboard
        header('Location: /dashboard.php');
        exit;
    } else {
        $error_message = 'Credenciales incorrectas. Por favor, intenta de nuevo.';
    }
}

//variables para la pagina
$titulo = 'login';
$pagina_actual = 'login';

//contenido de la pagina
ob_start();
?>
<?php if ($error_message): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($error_message); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
 
<?php if ($success_message): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($success_message); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php
//importar el componente home
require_once __DIR__ . '/../views/components/login.php';

//capturar el contenido de la pagina
$contenido = ob_get_clean();

//cargar layout principal
require_once __DIR__ . '/../views/layout/main.php';
