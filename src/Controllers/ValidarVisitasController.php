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

            $stmt = $this->conn->prepare("SELECT * FROM visitas WHERE codigo = :codigo");
            $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result && count($result) > 0) {
                $_SESSION['mensaje_visita'] = "✅ Código válido. Acceso autorizado.";
            } else {
                $_SESSION['mensaje_visita'] = "❌ Código inválido. Verifica e intenta nuevamente.";
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
