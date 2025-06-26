<?php
require_once __DIR__ . '/../Config/Database.php';

class VisitasController {
    private $conn;

    public function __construct() {
        $db = new \App\Config\Database();
        $this->conn = $db->getConnection();
    }

    public function index() {
        $query = "SELECT v.id_visita, v.nombre AS visitante_nombre, v.apellido AS visitante_apellido, 
                         v.fecha_ingreso, v.hora_ingreso,
                         u.nombre AS residente_nombre, u.apellido AS residente_apellido, u.direccion_casa,
                         m.motivo_visita
                  FROM visitas v
                  JOIN usuarios u ON v.id_usuarios = u.documento
                  JOIN motivo_visita m ON v.id_mot_visi = m.id_mot_visi
                  ORDER BY v.fecha_ingreso DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
