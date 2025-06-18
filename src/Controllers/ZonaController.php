<?php
require_once __DIR__ . '/../config/Database.php';
use App\Config\Database;

class ZonaController {
    public $zonas;
    public $horarios;
    public $motivos;
    public $residentes;

    public function __construct() {
        $db = new Database();
        $conn = $db->getConnection();

        // Cargar zonas
        $this->zonas = $conn->query("SELECT id_zonas_comu, nombre_zona FROM zonas_comunes")->fetchAll(PDO::FETCH_ASSOC);

        // Cargar horarios
        $this->horarios = $conn->query("SELECT id_horario, horario FROM horario")->fetchAll(PDO::FETCH_ASSOC);

        // Cargar motivos de zonas
        $this->motivos = $conn->query("SELECT id_mot_zonas, motivo_zonas FROM motivo_zonas")->fetchAll(PDO::FETCH_ASSOC);

        // Cargar residentes (usuarios con rol 3 y estado activo)
        $this->residentes = $conn->query("
            SELECT documento, CONCAT(nombre, ' ', apellido) AS nombre_completo
            FROM usuarios
            WHERE id_rol = 3 AND id_estado = 1
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new Database();
            $conn = $db->getConnection();

            $id_zonas_comu = $_POST['id_zonas_comu'];
            $id_usuarios = $_POST['id_usuarios'];
            $id_mot_zonas = $_POST['id_mot_zonas'];
            $fecha = $_POST['fecha'];
            $id_horario = $_POST['id_horario'];
            $observaciones = $_POST['observaciones'] ?? null;

            $id_estado = 1; // Pendiente
            $id_administrador = null;
            $fecha_apro = null;

            $sql = "INSERT INTO reservas (
                        id_zonas_comu, id_usuarios, fecha, id_horario,
                        id_estado, id_administrador, fecha_apro,
                        observaciones, id_mot_zonas
                    ) VALUES (
                        :id_zonas_comu, :id_usuarios, :fecha, :id_horario,
                        :id_estado, :id_administrador, :fecha_apro,
                        :observaciones, :id_mot_zonas
                    )";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_zonas_comu', $id_zonas_comu);
            $stmt->bindParam(':id_usuarios', $id_usuarios);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':id_horario', $id_horario);
            $stmt->bindParam(':id_estado', $id_estado);
            $stmt->bindParam(':id_administrador', $id_administrador);
            $stmt->bindParam(':fecha_apro', $fecha_apro);
            $stmt->bindParam(':observaciones', $observaciones);
            $stmt->bindParam(':id_mot_zonas', $id_mot_zonas);

            if ($stmt->execute()) {
                echo "<div class='alert alert-success text-center'>✅ ¡Zona reservada exitosamente!</div>";
            } else {
                echo "<div class='alert alert-danger text-center'>❌ Error al registrar la zona.</div>";
            }
        }
    }
}

// Ejecutar controlador y cargar datos
$controller = new ZonaController();
$controller->registrar();
$zonas = $controller->zonas;
$horarios = $controller->horarios;
$motivos = $controller->motivos;
$residentes = $controller->residentes;
?>

