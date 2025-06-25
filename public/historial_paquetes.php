<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/config/Database.php';
require_once __DIR__ . '/../src/Config/permissions.php';
 
session_start();
if (!tienePermiso('historial_paquetes')) {
    header('Location: dashboard.php');
    exit;
}

use App\Config\Database;

// Conexión a la base de datos
$db = new Database();
$conn = $db->getConnection();

// Consulta directa del historial de paquetes
$query = "
    SELECT 
        p.id_paquete,
        u.nombre AS nombre_residente,
        u.apellido AS apellido_residente,
        v.nombre AS nombre_vigilante,
        v.apellido AS apellido_vigilante,
        p.descripcion,
        p.fech_hor_recep,
        p.fech_hor_entre,
        e.estado
    FROM paquetes p
    JOIN usuarios u ON p.id_usuarios = u.documento
    JOIN usuarios v ON p.id_vigilante = v.documento
    JOIN estado e ON p.id_estado = e.id_estado
    ORDER BY p.fech_hor_recep DESC
";

$result = $conn->query($query);
$paquetes = $result->fetchAll(PDO::FETCH_ASSOC);

// Variables para el layout
$titulo = 'Historial de Paquetes';
$pagina_actual = 'historial';

// Inicia buffer para capturar el contenido de la vista
ob_start();
require_once __DIR__ . '/../views/components/historial_paquetes.php';
$contenido = ob_get_clean();

// Carga el layout principal
require_once __DIR__ . '/../views/layout/main.php';
?>
