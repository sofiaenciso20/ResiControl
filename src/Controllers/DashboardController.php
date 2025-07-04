<?php
 
namespace App\Controllers;
 
use App\Config\Database;
use PDO;
 
class DashboardController {
    private $db;
    private $conn;
 
    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }
 
    public function getMetrics($userId, $userRole) {
        $metrics = [];
 
        // Métricas para Administrador y Super Admin
        if (in_array($userRole, [1, 2])) {
            $metrics['total_residentes'] = $this->getTotalResidentes();
            $metrics['visitas_dia'] = $this->getVisitasHoy();
            $metrics['reservas_pendientes'] = $this->getReservasPendientes();
            $metrics['paquetes_pendientes'] = $this->getPaquetesPendientes();
        }
        // Métricas para Vigilante
        else if ($userRole == 4) {
            $metrics['visitas_dia'] = $this->getVisitasHoy();
            $metrics['paquetes_pendientes'] = $this->getPaquetesPendientes();
            $metrics['reservas_dia'] = $this->getReservasHoy();
        }
        // Métricas para Residente
        else if ($userRole == 3) {
            $metrics['mis_visitas'] = $this->getMisVisitas($userId);
            $metrics['mis_paquetes'] = $this->getMisPaquetes($userId);
            $metrics['mis_reservas'] = $this->getMisReservas($userId);
        }
 
        return $metrics;
    }
 
    private function getTotalResidentes() {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as total
            FROM usuarios
            WHERE id_rol = 3
            AND id_estado_usuario = 4
        ");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
 
    private function getVisitasHoy() {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as total
            FROM visitas
            WHERE DATE(fecha_ingreso) = CURRENT_DATE
            AND id_estado = 1
        ");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
 
    private function getReservasPendientes() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM reservas WHERE id_estado = 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
 
    private function getPaquetesPendientes() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM paquetes WHERE id_estado = 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
 
    private function getReservasHoy() {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as total
            FROM reservas
            WHERE DATE(fecha) = CURRENT_DATE
        ");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
 
    private function getMisVisitas($userId) {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as total
            FROM visitas
            WHERE documento = :userId
            AND id_estado = 1
        ");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
 
    private function getMisPaquetes($userId) {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as total
            FROM paquetes
            WHERE id_usuarios = :userId
            AND id_estado = 1
        ");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
 
    private function getMisReservas($userId) {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as total
            FROM reservas
            WHERE id_usuarios = :userId
            AND id_estado = 1
        ");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>
 