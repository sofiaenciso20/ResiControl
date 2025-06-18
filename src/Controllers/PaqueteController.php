<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
use App\Config\Database;

class PaqueteController {
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new Database();
            $conn = $db->getConnection();

            $id_usuarios = $_POST['id_usuarios'];
            $id_vigilante = $_POST['id_vigilante'];
            $descripcion = $_POST['descripcion'] ?? null;
            $fech_hor_recep = $_POST['fech_hor_recep'];
            $id_estado = 1;

            $sql = "INSERT INTO paquetes 
                    (id_usuarios, id_vigilante, descripcion, fech_hor_recep, fech_hor_entre, id_estado)
                    VALUES 
                    (:id_usuarios, :id_vigilante, :descripcion, :fech_hor_recep, NULL, :id_estado)";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_usuarios', $id_usuarios);
            $stmt->bindParam(':id_vigilante', $id_vigilante);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':fech_hor_recep', $fech_hor_recep);
            $stmt->bindParam(':id_estado', $id_estado);

            if ($stmt->execute()) {
                $_SESSION['mensaje_paquete'] = "✅ ¡Paquete registrado exitosamente!";
            } else {
                $_SESSION['mensaje_paquete'] = "❌ Error al registrar el paquete.";
            }

            header("Location: /registro_paquete.php");
            exit();
        }
    }
}

// Ejecutar
$controller = new PaqueteController();
$controller->registrar();
