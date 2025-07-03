<?php
require_once __DIR__ . '/../config/Database.php';
use App\Config\Database;
 
class HistorialPaquetesController {
    private $conn;
 
    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
 
    public function index() {
        // Obtener el rol y documento del usuario en sesión
        $rol = $_SESSION['user']['role'] ?? null;
        $documento_usuario = $_SESSION['user']['documento'] ?? null;
 
        $query = "SELECT
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
        JOIN usuarios u ON CAST(p.id_usuarios AS CHAR) = u.documento
        JOIN usuarios v ON CAST(p.id_vigilante AS CHAR) = v.documento
        JOIN estado e ON p.id_estado = e.id_estado";
 
        // Filtrar según el rol
        if ($rol == 3) { // Residente
            $query .= " WHERE CAST(p.id_usuarios AS CHAR) = :documento";
            $stmt = $this->conn->prepare($query . " ORDER BY p.fech_hor_recep DESC");
            $stmt->bindParam(':documento', $documento_usuario);
        } else { // Admin, SuperAdmin o Vigilante ven todos los paquetes
            $stmt = $this->conn->prepare($query . " ORDER BY p.fech_hor_recep DESC");
        }
 
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 
    public function obtenerDetallePaquete($id) {
        $query = "SELECT
            p.*,
            u.nombre AS nombre_residente,
            u.apellido AS apellido_residente,
            v.nombre AS nombre_vigilante,
            v.apellido AS apellido_vigilante,
            e.estado
        FROM paquetes p
        JOIN usuarios u ON CAST(p.id_usuarios AS CHAR) = u.documento
        JOIN usuarios v ON CAST(p.id_vigilante AS CHAR) = v.documento
        JOIN estado e ON p.id_estado = e.id_estado
        WHERE p.id_paquete = :id";
 
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>