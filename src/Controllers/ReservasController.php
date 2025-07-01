<?php
// Controlador para manejar todas las operaciones relacionadas con reservas
// src/Controllers/ReservasController.php
 
// Importamos la clase de conexión a la base de datos
require_once __DIR__ . '/../config/Database.php';
use App\Config\Database;
 
class ReservasController {
    // Propiedad para almacenar la conexión a la base de datos
    private $conn;
 
    /**
     * Constructor de la clase
     * Inicializa la conexión a la base de datos
     */
    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }
 
    /**
     * Obtiene todas las reservas con información relacionada
     * Incluye zona, horario, datos del residente y estado
     * @return array Lista de reservas ordenadas por fecha descendente
     */
    public function index() {
        $sql = "SELECT r.id_reservas, z.nombre_zona AS zona, r.fecha, h.horario,
                       CONCAT(u.nombre, ' ', u.apellido) AS residente,
                       r.id_estado
                FROM reservas r
                INNER JOIN zonas_comunes z ON r.id_zonas_comu = z.id_zonas_comu
                INNER JOIN horario h ON r.id_horario = h.id_horario
                INNER JOIN usuarios u ON r.id_usuarios = u.documento
                ORDER BY r.fecha DESC";
 
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 
    /**
     * Obtiene los detalles completos de una reserva específica
     * @param int $id ID de la reserva a consultar
     * @return array|false Datos de la reserva o false si no existe
     */
    public function obtenerDetalleReserva($id) {
        $query = "SELECT r.id_reservas, r.fecha, r.observaciones,
                        z.nombre_zona AS zona,
                        h.horario,
                        u.nombre AS nombre_residente,
                        u.apellido AS apellido_residente,
                        mz.motivo_zonas
                  FROM reservas r
                  LEFT JOIN zonas_comunes z ON r.id_zonas_comu = z.id_zonas_comu
                  LEFT JOIN horario h ON r.id_horario = h.id_horario
                  LEFT JOIN usuarios u ON r.id_usuarios = u.documento
                  LEFT JOIN motivo_zonas mz ON r.id_mot_zonas = mz.id_mot_zonas
                  WHERE r.id_reservas = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
 
    /**
     * Actualiza los datos de una reserva existente
     * @param int $id ID de la reserva a actualizar
     * @param array $datos Nuevos datos de la reserva (fecha, horario, observaciones, motivo)
     */
    public function actualizarReserva($id, $datos) {
        $query = "UPDATE reservas
                  SET fecha = :fecha,
                      id_horario = :id_horario,
                      observaciones = :observaciones,
                      id_mot_zonas = :id_mot_zonas
                  WHERE id_reservas = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fecha', $datos['fecha']);
        $stmt->bindParam(':id_horario', $datos['id_horario']);
        $stmt->bindParam(':observaciones', $datos['observaciones']);
        $stmt->bindParam(':id_mot_zonas', $datos['id_mot_zonas']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
 
    /**
     * Aprueba una reserva cambiando su estado a 2 (aprobada)
     * @param int $id ID de la reserva a aprobar
     * @return bool True si la actualización fue exitosa, False en caso contrario
     */
    public function aprobarReserva($id) {
        $query = "UPDATE reservas SET id_estado = 2 WHERE id_reservas = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
 
    /**
     * Rechaza una reserva cambiando su estado a 3 (rechazada)
     * @param int $id ID de la reserva a rechazar
     * @return bool True si la actualización fue exitosa, False en caso contrario
     */
    public function rechazarReserva($id) {
        $query = "UPDATE reservas SET id_estado = 3 WHERE id_reservas = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
 
 
 
 
 