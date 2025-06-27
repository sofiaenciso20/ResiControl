<?php
// src/Controllers/ReservasController.php

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
    public function obtenerDetalleReserva($id) {
        $query = "SELECT r.id_reservas, r.fecha, r.observaciones,
                        z.nombre_zona AS zona,
                        h.horario,
                        u.nombre AS nombre_residente,
                        u.apellido AS apellido_residente,
                        mz.motivo_zonas
                  FROM reservas r
                  LEFT JOIN zonas_comunes z ON r.id_zonas_comu = z.id_zonas_comu
                  LEFT JOIN horario h ON r.id_horario = h.id_horario
                  LEFT JOIN usuarios u ON r.id_usuarios = u.documento
                  LEFT JOIN motivo_zonas mz ON r.id_mot_zonas = mz.id_mot_zonas
                  WHERE r.id_reservas = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarReserva($id, $datos) {
        $query = "UPDATE reservas
                  SET fecha = :fecha,
                      id_horario = :id_horario,
                      observaciones = :observaciones,
                      id_mot_zonas = :id_mot_zonas
                  WHERE id_reservas = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fecha', $datos['fecha']);
        $stmt->bindParam(':id_horario', $datos['id_horario']);
        $stmt->bindParam(':observaciones', $datos['observaciones']);
        $stmt->bindParam(':id_mot_zonas', $datos['id_mot_zonas']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}



