<?php
// src/Controllers/ResidentesController.php

require_once __DIR__ . '/../config/Database.php';
use App\Config\Database;

class ResidentesController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function index() {
        $query = "SELECT documento, CONCAT(nombre, ' ', apellido) AS nombre, telefono, direccion_casa
                  FROM usuarios 
                  WHERE id_rol = 3"; // solo residentes

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
