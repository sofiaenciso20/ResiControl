<?php
require_once __DIR__ . '/../vendor/autoload.php';
 
use App\Config\Database;;
 
$db = new Database();
$conn = $db->getConnection();
 
if ($conn) {
    echo "¡Conexión exitosa a la base de datos!";
} else {
    echo "Error de conexión.";
}