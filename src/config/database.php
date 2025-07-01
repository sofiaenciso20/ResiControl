<?php
namespace App\Config;
 
use PDO;
use PDOException;
 
class Database {
    private $host = 'localhost';
    private $db_name = 'resicontrol1';
    private $username = 'root'; // Cambia si tu usuario es diferente
    private $password = '';     // Pon aquí tu contraseña de MySQL
    public $conn;
 
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            die("Error de conexión: " . $exception->getMessage());
        }
        return $this->conn;
    }
}