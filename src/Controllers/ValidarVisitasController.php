<?php
require_once __DIR__ . '/../config/Database.php';
use App\Config\Database;
 
class ValidarVisitasController {
    private $conn;
 
    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
 
    public function validar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $codigo = trim($_POST['codigo']);
            $now = date('Y-m-d H:i:s');
 
            // Buscar visita con el código y que no haya expirado
            $stmt = $this->conn->prepare("
                SELECT id_visita
                FROM visitas
                WHERE codigo = :codigo
                AND codigo_expira > :now
                AND id_estado = 1
            ");
            $stmt->bindParam(':codigo', $codigo);
            $stmt->bindParam(':now', $now);
            $stmt->execute();
            $visita = $stmt->fetch(PDO::FETCH_ASSOC);
 
            if ($visita) {
                // Actualizar estado de la visita y limpiar código
                $stmtUpdate = $this->conn->prepare("
                    UPDATE visitas
                    SET id_estado = 2,
                        codigo = NULL,
                        codigo_expira = NULL
                    WHERE id_visita = :id_visita
                ");
                $stmtUpdate->bindParam(':id_visita', $visita['id_visita']);
               
                if ($stmtUpdate->execute()) {
                    $_SESSION['mensaje_visita'] = "✅ ¡Visita verificada exitosamente!";
                } else {
                    $_SESSION['mensaje_visita'] = "❌ Error al verificar la visita.";
                }
            } else {
                $_SESSION['mensaje_visita'] = "❌ Código inválido o expirado. Por favor, verifica e intenta nuevamente.";
            }
           
            header("Location: /validar_visitas.php");
            exit();
        } else {
            $_SESSION['mensaje_visita'] = "Acceso no permitido.";
            header("Location: /validar_visitas.php");
            exit();
        }
    }
 
    public function index() {
        $sql = "SELECT * FROM visitas WHERE id_estado = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
 
 