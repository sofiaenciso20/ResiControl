<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Config/database.php';

 
use App\Config\Database;
 
$paquete = null;
$db = new Database();
$conn = $db->getConnection();
 
if (isset($_GET['id_paquete'])) {
    $id_paquete = intval($_GET['id_paquete']);
    $sql = "SELECT p.id_paquete, p.descripcion, p.fech_hor_recep, p.fech_hor_entre, e.estado,
                   r.nombre AS nombre_residente, r.apellido AS apellido_residente,
                   v.nombre AS nombre_vigilante, v.apellido AS apellido_vigilante
            FROM paquetes p
            JOIN usuarios r ON p.id_usuarios = r.documento
            JOIN usuarios v ON p.id_vigilante = v.documento
            JOIN estado e ON p.id_estado = e.id_estado
            WHERE p.id_paquete = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_paquete]);
    $paquete = $stmt->fetch(PDO::FETCH_ASSOC);
}
if (isset($_POST['marcar_entregado']) && isset($_POST['id_paquete'])) {
    $id_paquete = intval($_POST['id_paquete']);
    $fecha_entrega = date('Y-m-d H:i:s');
    $id_estado_aprobado = 2; // "Aprobado" se usa como entregado
    $sql_update = "UPDATE paquetes SET id_estado = ?, fech_hor_entre = ? WHERE id_paquete = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->execute([$id_estado_aprobado, $fecha_entrega, $id_paquete]);
    // Redirigir para evitar reenvío del formulario y mostrar el cambio
    header("Location: detalle_paquete.php?id_paquete=" . $id_paquete);
    exit;
}
if (isset($_POST['marcar_rechazado']) && isset($_POST['id_paquete'])) {
    $id_paquete = intval($_POST['id_paquete']);
    $fecha_rechazo = date('Y-m-d H:i:s');
    $id_estado_rechazado = 3; // Rechazado
    $sql_update = "UPDATE paquetes SET id_estado = ?, fech_hor_entre = ? WHERE id_paquete = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->execute([$id_estado_rechazado, $fecha_rechazo, $id_paquete]);
    header("Location: detalle_paquete.php?id_paquete=" . $id_paquete);
    exit;
}
ob_start();
include __DIR__ . '/../views/components/detalle_paquete.php';
$contenido = ob_get_clean();

require_once __DIR__ . '/../views/layout/main.php'; 