<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
use App\Config\Database;

class VisitaController {
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new Database();
            $conn = $db->getConnection();

            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $documento = $_POST['documento'] ?? '';
            $id_usuarios = $_POST['id_usuarios'] ?? null;
            $id_mot_visi = $_POST['id_mot_visi'] ?? null;
            $fecha_ingreso = $_POST['fecha_ingreso'] ?? null;
            $hora_ingreso = $_POST['hora_ingreso'] ?? null;
            $fecha_soli = date('Y-m-d');
            $id_estado = 1; // Pendiente
            $codigo = 'VIS' . strtoupper(substr(uniqid(), -4)); // Ej: VISA1B2

            $sql = "INSERT INTO visitas (
                        nombre, apellido, documento, id_usuarios, id_mot_visi,
                        fecha_ingreso, hora_ingreso, fecha_soli, codigo, id_estado
                    ) VALUES (
                        :nombre, :apellido, :documento, :id_usuarios, :id_mot_visi,
                        :fecha_ingreso, :hora_ingreso, :fecha_soli, :codigo, :id_estado
                    )";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':documento', $documento);
            $stmt->bindParam(':id_usuarios', $id_usuarios);
            $stmt->bindParam(':id_mot_visi', $id_mot_visi);
            $stmt->bindParam(':fecha_ingreso', $fecha_ingreso);
            $stmt->bindParam(':hora_ingreso', $hora_ingreso);
            $stmt->bindParam(':fecha_soli', $fecha_soli);
            $stmt->bindParam(':codigo', $codigo);
            $stmt->bindParam(':id_estado', $id_estado);

            if ($stmt->execute()) {
                $_SESSION['mensaje_visita'] = "✅ ¡Visita registrada exitosamente!";
            } else {
                $_SESSION['mensaje_visita'] = "❌ Error al registrar la visita.";
            }

            header("Location: /registro_visita.php");
            exit();
        }
    }
}

$controller = new VisitaController();
$controller->registrar();
