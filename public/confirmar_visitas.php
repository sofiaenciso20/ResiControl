<?php
require_once __DIR__ . '/../src/Config/Database.php';
session_start();
 
$db = new \App\Config\Database();
$conn = $db->getConnection();
 
// Solo lógica para confirmar la visita
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_visita'])) {
    $stmt = $conn->prepare("UPDATE visitas SET id_estado = 2 WHERE id_visita = ?");
    $stmt->execute([$_POST['id_visita']]);
}
 
header('Location: historial_visitas.php');
exit;