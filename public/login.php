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


// Usuarios de prueba
$usuarios = [
    [
        'email' => 'encisogarciaelisabetsofia@gmail.com',
        'password' => 'admin1234',
        'role' => 'admin'
    ],
    [
        'email' => 'cliente@gmail.com',
        'password' => 'cliente123',
        'role' => 'usuario'
    ]
];
 
// Manejar el envío del formulario
$error_message = '';
$success_message = '';
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
 
    // Validación de usuario
    $user = null;
    foreach ($usuarios as $usuario) {
        if ($usuario['email'] === $email && $usuario['password'] === $password) {
            $user = $usuario;
            break;
        }
    }
 
    if ($user) {
        // Inicio de sesión exitoso
        $_SESSION['user'] = [
            'email' => $user['email'],
            'role' => $user['role']
        ];
 
        // Si marcó "recordarme", establecer una cookie
        if ($remember) {
            setcookie('user_email', $email, time() + (86400 * 30), "/"); // 30 días
        }
 
        // Redirigir al dashboard
        header('Location: dashboard.php');
        exit;
    } else {
        $error_message = 'Credenciales incorrectas. Por favor, intenta de nuevo.';
    }
}
 
//variables para la pagina
$titulo = 'Login - ResiControl';
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
//importar el componente login
require_once __DIR__ . '/../views/components/login.php';
 
//capturar el contenido de la pagina
$contenido = ob_get_clean();
 
//cargar layout principal
require_once __DIR__ . '/../views/layout/main.php';