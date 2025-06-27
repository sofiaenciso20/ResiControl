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
        // Detecta el rol del usuario en sesión
        $rol = isset($_SESSION['user']['role']) ? $_SESSION['user']['role'] : null;
        if ($rol == 4) { // Vigilante
            // Solo residentes
            $query = "SELECT documento, CONCAT(nombre, ' ', apellido) AS nombre, telefono, direccion_casa, id_estado_usuario
                      FROM usuarios
                      WHERE id_rol = 3";
        } elseif ($rol == 2) { // Administrador
            // Todos menos superadmin
            $query = "SELECT documento, CONCAT(nombre, ' ', apellido) AS nombre, telefono, direccion_casa, id_estado_usuario
                      FROM usuarios
                      WHERE id_rol != 1";
        } else {
            // SuperAdmin: todos los usuarios
            $query = "SELECT documento, CONCAT(nombre, ' ', apellido) AS nombre, telefono, direccion_casa, id_estado_usuario
                      FROM usuarios";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function obtenerDetalleResidente($id) {
        $query = "SELECT documento, nombre, apellido, telefono, correo, direccion_casa, cantidad_personas, tiene_animales, cantidad_animales, direccion_residencia
                  FROM usuarios
                  WHERE documento = :id ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    /**
     * Actualiza los datos de un residente en la base de datos.
     *
     * @param string $id    Documento del residente a actualizar
     * @param array  $datos Array asociativo con los nuevos valores de los campos
     */
    public function actualizarResidente($id, $datos) {
        // Prepara la consulta SQL para actualizar los campos del residente
        $query = "UPDATE usuarios SET nombre = :nombre, apellido = :apellido, telefono = :telefono, correo = :correo, direccion_casa = :direccion_casa, cantidad_personas = :cantidad_personas, tiene_animales = :tiene_animales, cantidad_animales = :cantidad_animales, direccion_residencia = :direccion_residencia WHERE documento = :id AND id_rol = 3";
        $stmt = $this->conn->prepare($query);
        // Asocia cada parámetro de la consulta con el valor correspondiente del array $datos
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':apellido', $datos['apellido']);
        $stmt->bindParam(':telefono', $datos['telefono']);
        $stmt->bindParam(':correo', $datos['correo']);
        $stmt->bindParam(':direccion_casa', $datos['direccion_casa']);
        $stmt->bindParam(':cantidad_personas', $datos['cantidad_personas']);
        $stmt->bindParam(':tiene_animales', $datos['tiene_animales']);
        $stmt->bindParam(':cantidad_animales', $datos['cantidad_animales']);
        $stmt->bindParam(':direccion_residencia', $datos['direccion_residencia']);
        $stmt->bindParam(':id', $id);
        // Ejecuta la consulta para guardar los cambios en la base de datos
        $stmt->execute();
    }
}

