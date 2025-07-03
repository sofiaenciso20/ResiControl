<?php
// src/Controllers/VisitasController.php
 
require_once __DIR__ . '/../config/Database.php';
use App\Config\Database;
 
class VisitasController {
    private $conn;
 
    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
 
    public function index() {
        // Obtener el rol y documento del usuario en sesión
        $rol = $_SESSION['user']['role'] ?? null;
        $documento_usuario = $_SESSION['user']['documento'] ?? null;
 
        $query = "SELECT v.*,
                 v.nombre as visitante_nombre,
                 v.apellido as visitante_apellido,
                 u.nombre as residente_nombre,
                 u.apellido as residente_apellido,
                 u.direccion_casa,
                 mv.motivo_visita
                 FROM visitas v
                 INNER JOIN usuarios u ON CAST(v.id_usuarios AS CHAR) = u.documento
                 INNER JOIN motivo_visita mv ON v.id_mot_visi = mv.id_mot_visi
                 ORDER BY v.fecha_ingreso DESC, v.hora_ingreso DESC";
 
        // Filtrar según el rol
        if ($rol == 3) { // Residente
            $query = "SELECT v.*,
                    v.nombre as visitante_nombre,
                    v.apellido as visitante_apellido,
                    u.nombre as residente_nombre,
                    u.apellido as residente_apellido,
                    u.direccion_casa,
                    mv.motivo_visita
                    FROM visitas v
                    INNER JOIN usuarios u ON CAST(v.id_usuarios AS CHAR) = u.documento
                    INNER JOIN motivo_visita mv ON v.id_mot_visi = mv.id_mot_visi
                    WHERE CAST(v.id_usuarios AS CHAR) = :documento
                    ORDER BY v.fecha_ingreso DESC, v.hora_ingreso DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':documento', $documento_usuario);
        } else { // Admin, SuperAdmin o Vigilante ven todas las visitas
            $stmt = $this->conn->prepare($query);
        }
 
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 
    public function obtenerDetalleVisita($id) {
        $query = "SELECT v.id_visita, v.nombre, v.apellido, v.documento,
                         u.nombre AS residente_nombre, u.apellido AS residente_apellido, u.direccion_casa,
                         mv.motivo_visita, v.fecha_ingreso, v.hora_ingreso
                  FROM visitas v
                  INNER JOIN usuarios u ON CAST(v.id_usuarios AS CHAR) = u.documento
                  INNER JOIN motivo_visita mv ON v.id_mot_visi = mv.id_mot_visi
                  WHERE v.id_visita = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
 
    public function actualizarVisita($id, $datos) {
        $query = "UPDATE visitas
                  SET nombre = :nombre, apellido = :apellido, documento = :documento,
                      id_mot_visi = :id_mot_visi, fecha_ingreso = :fecha_ingreso, hora_ingreso = :hora_ingreso
                  WHERE id_visita = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':apellido', $datos['apellido']);
        $stmt->bindParam(':documento', $datos['documento']);
        $stmt->bindParam(':id_mot_visi', $datos['id_mot_visi']);
        $stmt->bindParam(':fecha_ingreso', $datos['fecha_ingreso']);
        $stmt->bindParam(':hora_ingreso', $datos['hora_ingreso']);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}