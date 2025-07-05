<?php

namespace App\Controllers;

use PDO;
use PDOException;
use App\Config\Database;

class LicenciasController {
    private $db;
    private $conn;
    
    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Generar código único de licencia
    private function generarCodigoLicencia() {
        return strtoupper(uniqid('LIC-') . substr(md5(time()), 0, 6));
    }

    // Crear nueva licencia
    public function crearLicencia($datos) {
        try {
            $codigo = $this->generarCodigoLicencia();
            
            $sql = "INSERT INTO licencias (
                codigo_licencia, 
                nombre_residencial, 
                fecha_inicio, 
                fecha_fin, 
                max_usuarios, 
                max_residentes, 
                caracteristicas,
                estado
            ) VALUES (?, ?, ?, ?, ?, ?, ?, 'activa')";

            $stmt = $this->conn->prepare($sql);
            
            $caracteristicas = json_encode($datos['caracteristicas'] ?? []);
            
            $stmt->execute([
                $codigo,
                $datos['nombre_residencial'],
                $datos['fecha_inicio'],
                $datos['fecha_fin'],
                $datos['max_usuarios'],
                $datos['max_residentes'],
                $caracteristicas
            ]);

            return [
                'success' => true,
                'mensaje' => 'Licencia creada exitosamente',
                'codigo' => $codigo
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'mensaje' => 'Error al crear la licencia: ' . $e->getMessage()
            ];
        }
    }

    // Obtener todas las licencias
    public function obtenerLicencias() {
        try {
            $sql = "SELECT * FROM licencias ORDER BY fecha_creacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            return [
                'success' => true,
                'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'mensaje' => 'Error al obtener las licencias: ' . $e->getMessage()
            ];
        }
    }

    // Obtener una licencia específica
    public function obtenerLicencia($id) {
        try {
            $sql = "SELECT * FROM licencias WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            
            $licencia = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($licencia) {
                return [
                    'success' => true,
                    'data' => $licencia
                ];
            } else {
                return [
                    'success' => false,
                    'mensaje' => 'Licencia no encontrada'
                ];
            }
        } catch (PDOException $e) {
            return [
                'success' => false,
                'mensaje' => 'Error al obtener la licencia: ' . $e->getMessage()
            ];
        }
    }

    // Actualizar licencia
    public function actualizarLicencia($id, $datos) {
        try {
            $sql = "UPDATE licencias SET 
                    nombre_residencial = ?,
                    fecha_fin = ?,
                    max_usuarios = ?,
                    max_residentes = ?,
                    caracteristicas = ?,
                    estado = ?
                    WHERE id = ?";

            $stmt = $this->conn->prepare($sql);
            
            $caracteristicas = json_encode($datos['caracteristicas'] ?? []);
            
            $stmt->execute([
                $datos['nombre_residencial'],
                $datos['fecha_fin'],
                $datos['max_usuarios'],
                $datos['max_residentes'],
                $caracteristicas,
                $datos['estado'],
                $id
            ]);

            return [
                'success' => true,
                'mensaje' => 'Licencia actualizada exitosamente'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'mensaje' => 'Error al actualizar la licencia: ' . $e->getMessage()
            ];
        }
    }

    // Verificar estado de licencia
    public function verificarLicencia($codigo) {
        try {
            $sql = "SELECT * FROM licencias WHERE codigo_licencia = ? AND estado = 'activa' AND fecha_fin >= CURRENT_DATE";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$codigo]);
            
            $licencia = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($licencia) {
                // Verificar límites de uso
                $usoActual = $this->obtenerEstadisticasUso($codigo);
                
                return [
                    'success' => true,
                    'valida' => true,
                    'data' => [
                        'licencia' => $licencia,
                        'uso' => $usoActual
                    ]
                ];
            } else {
                return [
                    'success' => true,
                    'valida' => false,
                    'mensaje' => 'Licencia inválida o expirada'
                ];
            }
        } catch (PDOException $e) {
            return [
                'success' => false,
                'mensaje' => 'Error al verificar la licencia: ' . $e->getMessage()
            ];
        }
    }

    // Obtener estadísticas de uso
    public function obtenerEstadisticasUso($codigo_licencia) {
        try {
            // Primero obtener los límites de la licencia
            $sqlLicencia = "SELECT max_usuarios, max_residentes FROM licencias WHERE codigo_licencia = ?";
            $stmtLicencia = $this->conn->prepare($sqlLicencia);
            $stmtLicencia->execute([$codigo_licencia]);
            $limites = $stmtLicencia->fetch(PDO::FETCH_ASSOC);

            if (!$limites) {
                return [
                    'total_usuarios' => 0,
                    'total_residentes' => 0,
                    'porcentaje_usuarios' => 0,
                    'porcentaje_residentes' => 0
                ];
            }

            // Obtener total de usuarios
            $sqlUsuarios = "SELECT COUNT(*) as total_usuarios FROM usuarios WHERE licencia_id = ?";
            $stmtUsuarios = $this->conn->prepare($sqlUsuarios);
            $stmtUsuarios->execute([$codigo_licencia]);
            $totalUsuarios = $stmtUsuarios->fetch(PDO::FETCH_ASSOC)['total_usuarios'];

            // Obtener total de residentes
            $sqlResidentes = "SELECT COUNT(*) as total_residentes FROM usuarios WHERE licencia_id = ? AND role = 3";
            $stmtResidentes = $this->conn->prepare($sqlResidentes);
            $stmtResidentes->execute([$codigo_licencia]);
            $totalResidentes = $stmtResidentes->fetch(PDO::FETCH_ASSOC)['total_residentes'];

            // Calcular porcentajes
            $porcentajeUsuarios = ($limites['max_usuarios'] > 0) 
                ? round(($totalUsuarios / $limites['max_usuarios']) * 100, 2)
                : 0;

            $porcentajeResidentes = ($limites['max_residentes'] > 0)
                ? round(($totalResidentes / $limites['max_residentes']) * 100, 2)
                : 0;

            return [
                'total_usuarios' => (int)$totalUsuarios,
                'total_residentes' => (int)$totalResidentes,
                'porcentaje_usuarios' => $porcentajeUsuarios,
                'porcentaje_residentes' => $porcentajeResidentes,
                'max_usuarios' => (int)$limites['max_usuarios'],
                'max_residentes' => (int)$limites['max_residentes']
            ];

        } catch (PDOException $e) {
            error_log("Error en obtenerEstadisticasUso: " . $e->getMessage());
            return [
                'total_usuarios' => 0,
                'total_residentes' => 0,
                'porcentaje_usuarios' => 0,
                'porcentaje_residentes' => 0,
                'max_usuarios' => 0,
                'max_residentes' => 0
            ];
        }
    }
}