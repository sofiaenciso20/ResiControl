<?php
 
// Cargar el autoload de Composer para usar PHPMailer y otras dependencias
require_once __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
// Configuración de errores para desarrollo (muestra todos los errores)
error_reporting(E_ALL);
ini_set('display_errors', '1');
 
// Zona horaria por defecto
date_default_timezone_set('America/Bogota');
 
// Variable para mensajes de éxito o error que se mostrarán en la vista
$mensaje = '';
 
// Si el formulario fue enviado (método POST) y se recibió el campo 'correo'
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['correo'])) {
    // Sanitiza el correo recibido del formulario
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
 
    // Conexión a la base de datos
    require_once __DIR__ . '/../src/Config/database.php';
    $db = new \App\Config\Database();
    $conn = $db->getConnection();
 
    // Buscar usuario por correo en la base de datos
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ? LIMIT 1");
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // Si el usuario existe
    if ($usuario) {
        // Generar un código aleatorio de 6 dígitos y una fecha de expiración (10 minutos desde ahora)
        $codigo = random_int(100000, 999999);
        $expira = date('Y-m-d H:i:s', strtotime('+10 minutes'));
 
        // Guardar el código y la expiración en la base de datos para ese usuario
        $stmtUpdate = $conn->prepare("UPDATE usuarios SET codigo_recuperacion = ?, codigo_expira = ? WHERE correo = ?");
        $stmtUpdate->execute([$codigo, $expira, $correo]);
 
        // Crear una instancia de PHPMailer para enviar el correo
        //El argumento true le dice a PHPMailer que lance excepciones si ocurre un error (para capturarlas con try-catch)
        $mail = new PHPMailer(true);
        //Se abre un bloque try para intentar enviar el correo.

        //Si algo falla, se pasará al catch(Exception $e)
        try {
            // Configuración SMTP para Gmail
            //Indica que vas a enviar el correo usando SMTP, que es un protocolo para el envío seguro de correos
            $mail->isSMTP();

            //Este es el servidor SMTP de Gmail, el cual PHPMailer usará para enviar el correo
            $mail->Host = 'smtp.gmail.com';

            //Activa la autenticación SMTP, es decir, PHPMailer necesita un usuario y contraseña válidos para enviar
            $mail->SMTPAuth = true;

            $mail->Username = 'rresicontrol@gmail.com'; //Es el correo electrónico remitente, el que enviará el mensaje
            $mail->Password = 'oaiejctxxsymgzwz'; //Es la contraseña de aplicación generada en tu cuenta de Google

            //Activa el cifrado TLS (Transport Layer Security), que protege la información que viaja entre tu servidor y Gmail.
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            $mail->Port = 587;//El puerto 587 es el estándar para enviar correos usando SMTP con cifrado TLS.
           
            // Configuración del remitente y destinatario
            //Define quién envía el mensaje.'ResiControl' será el nombre que verá el usuario como remitente
            $mail->setFrom('rresicontrol@gmail.com', 'ResiControl');

            //Define a quién va dirigido el correo (el destinatario).Usa el correo proporcionado y el nombre completo del usuario
            $mail->addAddress($correo, $usuario['nombre'] . ' ' . $usuario['apellido']);

            $mail->isHTML(true);//Indica que el cuerpo del mensaje estará en formato HTML, no texto plano

            //Asunto del correo que el usuario verá en su bandeja de entrada
            $mail->Subject = 'Código de recuperación de contraseña';

            // Cuerpo del correo con el código de recuperación
            $mail->Body = '<p>Hola,</p><p>Tu código de recuperación es: <b>' . $codigo . '</b></p><p>Este código expirará en 10 minutos.</p>';
 
            //Envía el mensaje con todas las configuraciones anteriores.Si todo funciona, no hay errores.
            $mail->send();
 
            // Redirigir automáticamente al formulario de verificación de código, pasando el correo por la URL
            //urlencode asegura que el correo sea seguro al pasarlo por la URL.
            header('Location: verificar_codigo.php?correo=' . urlencode($correo));
            exit;

            //Captura cualquier error que ocurra durante el envío y guarda el mensaje de error en la variable $mensaje.
        } catch (Exception $e) {
            // Si ocurre un error al enviar el correo, mostrar el mensaje de error
            $mensaje = 'No se pudo enviar el correo. Error: ' . $mail->ErrorInfo;
        }
        
        //Este else pertenece a la parte en la que se consulta si el usuario con ese correo existe en la base de datos.
        //Si no se encuentra, se guarda un mensaje indicando que el correo no está registrado.
    } else {
        // Si no se encuentra el usuario, mostrar mensaje de error
        $mensaje = 'No se encontró una cuenta con ese correo.';
    }
}
 
// Variables para la página (título y página actual)
$titulo = 'Recuperar Contraseña';
$pagina_actual = 'recuperar_contra';
 
// Inicia el output buffering para capturar el contenido de la vista
ob_start();
 
// Importa el componente de la vista (formulario de recuperación)
require_once __DIR__ . '/../views/components/recuperar_contra.php';
 
// Captura el contenido generado por la vista
$contenido = ob_get_clean();
 
// Carga el layout principal y muestra la página completa
require_once __DIR__ . '/../views/layout/main.php';