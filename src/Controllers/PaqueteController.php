<?php
require_once __DIR__ . '/../config/Database.php';
use App\Config\Database;

$db = new Database();
$conn = $db->getConnection();

// Residentes (habitantes)
$sqlResidentes = "SELECT documento, CONCAT(nombre, ' ', apellido) AS nombre_completo 
                  FROM usuarios 
                  WHERE id_rol = 3 AND id_estado = 1";
$residentes = $conn->query($sqlResidentes)->fetchAll(PDO::FETCH_ASSOC);

// Vigilantes
$sqlVigilantes = "SELECT documento, CONCAT(nombre, ' ', apellido) AS nombre_completo 
                  FROM usuarios 
                  WHERE id_rol = 4 AND id_estado = 1";
$vigilantes = $conn->query($sqlVigilantes)->fetchAll(PDO::FETCH_ASSOC);

class PaqueteController {
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new Database();
            $conn = $db->getConnection();

            $id_usuarios = $_POST['id_usuarios'];
            $id_vigilante = $_POST['id_vigilante'];
            $descripcion = $_POST['descripcion'] ?? null;
            $fech_hor_recep = $_POST['fech_hor_recep'];
            $id_estado = 1; // Pendiente por defecto

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
                echo "¡Paquete registrado exitosamente!";
                // Puedes hacer un header("Location: paquetes.php"); si tienes una lista
            } else {
                echo "Error al registrar el paquete.";
            }
        }
    }
}

$controller = new PaqueteController();
$controller->registrar();
