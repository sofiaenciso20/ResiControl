<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Config/Database.php';
require_once __DIR__ . '/../src/Config/permissions.php';
 
session_start();
 
// Verificar permisos
if (!in_array($_SESSION['user']['role'], [1, 2, 4])) {
    $_SESSION['error'] = 'No tienes permiso para realizar esta acción';
    header('Location: historial_paquetes.php');
    exit;
}
 
// Verificar que se recibió el ID del paquete
if (!isset($_POST['id_paquete'])) {
    $_SESSION['error'] = 'ID de paquete no proporcionado';
    header('Location: historial_paquetes.php');
    exit;
}
 
$id_paquete = (int)$_POST['id_paquete'];
 
// Conectar a la base de datos
$db = new \App\Config\Database();
$conn = $db->getConnection();
 
try {
    // Actualizar el estado del paquete
    $stmt = $conn->prepare("
        UPDATE paquetes
        SET id_estado = 2,
            fech_hor_entre = NOW()
        WHERE id_paquete = ?
        AND id_estado = 1
    ");
   
    if ($stmt->execute([$id_paquete])) {
        if ($stmt->rowCount() > 0) {
            $_SESSION['success'] = 'Paquete marcado como entregado exitosamente';
        } else {
            $_SESSION['error'] = 'El paquete ya fue entregado o no existe';
        }
    } else {
        $_SESSION['error'] = 'Error al actualizar el estado del paquete';
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'Error en la base de datos';
}
 
// Redirigir de vuelta a la página anterior
$referer = $_SERVER['HTTP_REFERER'] ?? 'historial_paquetes.php';
header("Location: $referer");
exit;
 