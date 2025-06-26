<?php
require_once __DIR__ . '/../config/Database.php';
use App\Config\Database;

class ReservasController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function index() {
        $sql = "SELECT r.id_reservas, z.nombre_zona AS zona, r.fecha, h.horario, 
                       CONCAT(u.nombre, ' ', u.apellido) AS residente
                FROM reservas r
                INNER JOIN zonas_comunes z ON r.id_zonas_comu = z.id_zonas_comu
                INNER JOIN horario h ON r.id_horario = h.id_horario
                INNER JOIN usuarios u ON r.id_usuarios = u.documento
                ORDER BY r.fecha DESC";

        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
