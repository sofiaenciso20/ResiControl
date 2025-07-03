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
        // Obtener el rol y documento del usuario en sesión
        $rol = $_SESSION['user']['role'] ?? null;
        $documento_usuario = $_SESSION['user']['documento'] ?? null;
 
        $query = "SELECT
            r.id_reservas,
            r.fecha,
            r.fecha_apro,
            r.observaciones,
            zc.nombre_zona,
            h.horario,
            CONCAT(u.nombre, ' ', u.apellido) as nombre_residente,
            u.documento as documento_residente,
            e.estado,
            CONCAT(a.nombre, ' ', a.apellido) as nombre_administrador,
            mz.motivo_zonas as motivo
            FROM reservas r
            INNER JOIN zonas_comunes zc ON r.id_zonas_comu = zc.id_zonas_comu
            INNER JOIN horario h ON r.id_horario = h.id_horario
            INNER JOIN usuarios u ON CAST(r.id_usuarios AS CHAR) = u.documento
            INNER JOIN estado e ON r.id_estado = e.id_estado
            LEFT JOIN usuarios a ON CAST(r.id_administrador AS CHAR) = a.documento
            LEFT JOIN motivo_zonas mz ON r.id_mot_zonas = mz.id_mot_zonas";
 
        // Filtrar según el rol
        if ($rol == 3) { // Residente
            $query .= " WHERE CAST(r.id_usuarios AS CHAR) = :documento";
            $stmt = $this->conn->prepare($query . " ORDER BY r.fecha DESC, h.horario ASC");
            $stmt->bindParam(':documento', $documento_usuario);
        } else { // Admin ve todas las reservas
            $stmt = $this->conn->prepare($query . " ORDER BY r.fecha DESC, h.horario ASC");
        }
 
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 
    /**
     * Obtiene los detalles completos de una reserva específica
     * @param int $id ID de la reserva a consultar
     * @return array|false Datos de la reserva o false si no existe
     */
    public function obtenerDetalleReserva($id) {
        $query = "SELECT
            r.*,
            zc.nombre_zona,
            h.horario,
            CONCAT(u.nombre, ' ', u.apellido) as nombre_residente,
            u.documento as documento_residente,
            u.telefono as telefono_residente,
            e.estado,
            CONCAT(a.nombre, ' ', a.apellido) as nombre_administrador,
            mz.motivo_zonas as motivo
            FROM reservas r
            INNER JOIN zonas_comunes zc ON r.id_zonas_comu = zc.id_zonas_comu
            INNER JOIN horario h ON r.id_horario = h.id_horario
            INNER JOIN usuarios u ON CAST(r.id_usuarios AS CHAR) = u.documento
            INNER JOIN estado e ON r.id_estado = e.id_estado
            LEFT JOIN usuarios a ON CAST(r.id_administrador AS CHAR) = a.documento
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
    public function aprobarReserva($id_reserva) {
        try {
            $this->conn->beginTransaction();
           
            // Verificar que la reserva existe y está pendiente
            $stmt = $this->conn->prepare("
                SELECT id_estado
                FROM reservas
                WHERE id_reservas = ?
                AND id_estado = 1"); // 1 = Pendiente
            $stmt->execute([$id_reserva]);
           
            if (!$stmt->fetch()) {
                throw new Exception("La reserva no existe o ya fue procesada");
            }
 
            // Actualizar la reserva
            $stmt = $this->conn->prepare("
                UPDATE reservas
                SET id_estado = 2,
                    id_administrador = ?,
                    fecha_apro = CURDATE()
                WHERE id_reservas = ?");
           
            $admin_documento = $_SESSION['user']['documento'];
            $stmt->execute([$admin_documento, $id_reserva]);
           
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
 
    /**
     * Rechaza una reserva cambiando su estado a 3 (rechazada)
     * @param int $id ID de la reserva a rechazar
     * @return bool True si la actualización fue exitosa, False en caso contrario
     */
    public function rechazarReserva($id_reserva, $observaciones) {
        try {
            $this->conn->beginTransaction();
           
            // Verificar que la reserva existe y está pendiente
            $stmt = $this->conn->prepare("
                SELECT id_estado
                FROM reservas
                WHERE id_reservas = ?
                AND id_estado = 1"); // 1 = Pendiente
            $stmt->execute([$id_reserva]);
           
            if (!$stmt->fetch()) {
                throw new Exception("La reserva no existe o ya fue procesada");
            }
 
            // Actualizar la reserva
            $stmt = $this->conn->prepare("
                UPDATE reservas
                SET id_estado = 3,
                    id_administrador = ?,
                    fecha_apro = CURDATE(),
                    observaciones = ?
                WHERE id_reservas = ?");
           
            $admin_documento = $_SESSION['user']['documento'];
            $stmt->execute([$admin_documento, $observaciones, $id_reserva]);
           
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
}
 