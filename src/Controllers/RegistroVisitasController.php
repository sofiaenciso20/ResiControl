<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
use App\Config\Database;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
class RegistroVisitasController {
    private $conn;
 
    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
 
    private function enviarCorreoVerificacion($codigo) {
        if (!isset($_SESSION['user']['email'])) {
            throw new Exception("No se encontró el correo del usuario en la sesión");
        }
 
        $correo_destino = $_SESSION['user']['email'];
        $nombre_usuario = $_SESSION['user']['name'];
 
        // Configuración de PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'rresicontrol@gmail.com';
            $mail->Password = 'oaiejctxxsymgzwz';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';
 
            $mail->setFrom('rresicontrol@gmail.com', 'ResiControl');
            $mail->addAddress($correo_destino, $nombre_usuario);
 
            $mail->isHTML(true);
            $mail->Subject = 'Código de Verificación - Registro de Visita';
            $mail->Body = "
                <h2>Hola {$nombre_usuario}</h2>
                <p>Has solicitado registrar una nueva visita en ResiControl.</p>
                <p>Tu código de verificación es: <strong style='font-size: 24px; color: #007bff;'>{$codigo}</strong></p>
                <p>Este código expirará en 30 minutos.</p>
                <p>Si no solicitaste este código, por favor ignora este correo.</p>
                <br>
                <p>Saludos,<br>Equipo de ResiControl</p>
            ";
 
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error al enviar correo: " . $mail->ErrorInfo);
            throw new Exception("Error al enviar el correo de verificación: " . $e->getMessage());
        }
    }
 
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->conn->beginTransaction();
 
                $nombre = $_POST['nombre'] ?? '';
                $apellido = $_POST['apellido'] ?? '';
                $documento = $_POST['documento'] ?? '';
                $id_usuarios = $_POST['id_usuarios'] ?? null;
                $id_mot_visi = $_POST['id_mot_visi'] ?? null;
                $fecha_ingreso = $_POST['fecha_ingreso'] ?? null;
                $hora_ingreso = $_POST['hora_ingreso'] ?? null;
                $fecha_soli = date('Y-m-d');
                $id_estado = 1; // Estado inicial: Pendiente
                $codigo = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6)); // Genera código de 6 caracteres
                $codigo_expira = date('Y-m-d H:i:s', strtotime('+30 minutes')); // Expira en 30 minutos
 
                $sql = "INSERT INTO visitas (
                            nombre, apellido, documento, id_usuarios, id_mot_visi,
                            fecha_ingreso, hora_ingreso, fecha_soli, codigo, codigo_expira, id_estado
                        ) VALUES (
                            :nombre, :apellido, :documento, :id_usuarios, :id_mot_visi,
                            :fecha_ingreso, :hora_ingreso, :fecha_soli, :codigo, :codigo_expira, :id_estado
                        )";
 
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':apellido', $apellido);
                $stmt->bindParam(':documento', $documento);
                $stmt->bindParam(':id_usuarios', $id_usuarios);
                $stmt->bindParam(':id_mot_visi', $id_mot_visi);
                $stmt->bindParam(':fecha_ingreso', $fecha_ingreso);
                $stmt->bindParam(':hora_ingreso', $hora_ingreso);
                $stmt->bindParam(':fecha_soli', $fecha_soli);
                $stmt->bindParam(':codigo', $codigo);
                $stmt->bindParam(':codigo_expira', $codigo_expira);
                $stmt->bindParam(':id_estado', $id_estado);
 
                if ($stmt->execute()) {
                    if ($this->enviarCorreoVerificacion($codigo)) {
                        $this->conn->commit();
                        $_SESSION['codigo_visita_temp'] = $codigo; // Guardamos temporalmente para mostrar en la vista
                        $_SESSION['mensaje_visita'] = "Se ha enviado un código de verificación a tu correo ({$_SESSION['user']['email']}).";
                        header("Location: /validar_visitas.php");
                        exit();
                    } else {
                        throw new Exception("Error al enviar el correo de verificación");
                    }
                } else {
                    throw new Exception("Error al registrar la visita");
                }
            } catch (Exception $e) {
                $this->conn->rollBack();
                $_SESSION['mensaje_visita'] = "❌ Error: " . $e->getMessage();
                header("Location: /registro_visita.php");
                exit();
            }
        }
    }
 
    public function index() {
        return [];
    }
}
 
$controller = new RegistroVisitasController();
$controller->registrar();
 
 