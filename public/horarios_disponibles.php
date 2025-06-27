<?php
require_once __DIR__ . '/../src/Config/Database.php';
$db = new \App\Config\Database();
$conn = $db->getConnection();
 
$id_zona = $_POST['zona'] ?? null;
$fecha = $_POST['fecha'] ?? null;
 
$ocupados = [];
if ($id_zona && $fecha) {
    $stmt = $conn->prepare("SELECT id_horario FROM reservas WHERE id_zonas_comu = ? AND fecha = ?");
    $stmt->execute([$id_zona, $fecha]);
    $ocupados = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
echo json_encode($ocupados);