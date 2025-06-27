<?php
require_once __DIR__ . '/../src/Config/Database.php';
session_start();
 
// Solo admin o superadmin pueden inhabilitar
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], [1,2])) {
    header('Location: gestion_residentes.php');
    exit;
}
 
$id = $_GET['id'] ?? null;
if ($id) {
    $db = new \App\Config\Database();
    $conn = $db->getConnection();
    // Cambia el estado a Inactivo (id_estado = 5)
    $stmt = $conn->prepare("UPDATE usuarios SET id_estado_usuario = 5 WHERE documento = ?");
    $stmt->execute([$id]);
}
 
header('Location: gestion_residentes.php');
exit;
 