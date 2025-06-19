<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
use App\Config\Database;

// Elimina la validación de rol para permitir que cualquiera pueda cambiar roles

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documento = $_POST['documento'];
    $nuevo_rol = $_POST['id_rol'];

    // Validar rol válido (solo del 2 al 4, no permitir cambiar a Super Admin)
    if (in_array($nuevo_rol, ['2', '3', '4'])) {
        $db = new Database();
        $conn = $db->getConnection();

        $sql = "UPDATE usuarios SET id_rol = :id_rol WHERE documento = :documento";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_rol', $nuevo_rol);
        $stmt->bindParam(':documento', $documento);
        $stmt->execute();
    }
}

header("Location: ../gestion_roles.php");
exit();
?>
