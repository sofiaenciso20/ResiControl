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
    $query = "SELECT 
                v.id_visita,
                v.nombre AS visitante_nombre,
                v.apellido AS visitante_apellido,
                u.nombre AS residente_nombre,
                u.apellido AS residente_apellido,
                u.direccion_casa,
                mv.motivo_visita,
                v.fecha_ingreso,
                v.hora_ingreso
              FROM visitas v
              INNER JOIN usuarios u ON v.id_usuarios = u.documento
              INNER JOIN motivo_visita mv ON v.id_mot_visi = mv.id_mot_visi
              ORDER BY v.fecha_ingreso DESC";
    
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function obtenerDetalleVisita($id) {
        $query = "SELECT v.id_visita, v.nombre, v.apellido, v.documento, 
                         u.nombre AS residente_nombre, u.apellido AS residente_apellido, u.direccion_casa,
                         mv.motivo_visita, v.fecha_ingreso, v.hora_ingreso
                  FROM visitas v
                  INNER JOIN usuarios u ON v.id_usuarios = u.documento
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
