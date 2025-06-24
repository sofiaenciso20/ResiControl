<?php
require_once __DIR__ . '/../config/Database.php';
use App\Config\Database;

class HistorialPaquetesController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function obtenerHistorial() {
        $query = "
            SELECT 
                p.id_paquete,
                u.nombre AS nombre_residente,
                u.apellido AS apellido_residente,
                v.nombre AS nombre_vigilante,
                v.apellido AS apellido_vigilante,
                p.descripcion,
                p.fech_hor_recep,
                p.fech_hor_entre,
                e.estado
            FROM paquetes p
            JOIN usuarios u ON p.id_usuarios = u.documento
            JOIN usuarios v ON p.id_vigilante = v.documento
            JOIN estado e ON p.id_estado = e.id_estado
            ORDER BY p.fech_hor_recep DESC
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>
