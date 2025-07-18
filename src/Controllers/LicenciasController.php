<?php

namespace App\Controllers;

use PDO;
use PDOException;
use App\Config\Database;
use Exception;

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
            
            // Iniciar transacción para asegurar la integridad de los datos
            $this->conn->beginTransaction();
            
            // 1. Crear la licencia
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
            
            $licencia_id = $this->conn->lastInsertId();
            
            // 2. Asignar la licencia al administrador
            if (isset($datos['id_administrador']) && !empty($datos['id_administrador'])) {
                $sqlUpdateAdmin = "UPDATE usuarios SET id_licencia = ? WHERE documento = ? AND id_rol = 2";
                $stmtUpdateAdmin = $this->conn->prepare($sqlUpdateAdmin);
                $stmtUpdateAdmin->execute([$licencia_id, $datos['id_administrador']]);
                
                // Verificar que se actualizó correctamente
                if ($stmtUpdateAdmin->rowCount() === 0) {
                    throw new Exception('No se pudo asignar la licencia al administrador seleccionado');
                }
            }
            
            // 3. Registrar en historial
            $this->registrarHistorial($licencia_id, 'creacion', [
                'administrador_asignado' => $datos['id_administrador'] ?? null,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ]);
            
            $this->conn->commit();

            return [
                'success' => true,
                'mensaje' => 'Licencia creada y asignada exitosamente',
                'codigo' => $codigo
            ];
        } catch (PDOException $e) {
            $this->conn->rollback();
            return [
                'success' => false,
                'mensaje' => 'Error al crear la licencia: ' . $e->getMessage()
            ];
        } catch (Exception $e) {
            $this->conn->rollback();
            return [
                'success' => false,
                'mensaje' => $e->getMessage()
            ];
        }
    }

    // Obtener todas las licencias
    public function obtenerLicencias() {
        try {
            $sql = "SELECT l.*, 
                           CONCAT(u.nombre, ' ', u.apellido) as administrador_nombre,
                           u.correo as administrador_correo
                    FROM licencias l
                    LEFT JOIN usuarios u ON l.id = u.id_licencia AND u.id_rol = 2
                    ORDER BY l.fecha_creacion DESC";
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

    private function registrarCambioEstado($idLicencia, $estadoAnterior, $estadoNuevo) {
        try {
            $detalles = json_encode([
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $estadoNuevo,
                'fecha_cambio' => date('Y-m-d H:i:s')
            ]);

            $accion = $estadoNuevo === 'activa' ? 'activacion' : 
                     ($estadoNuevo === 'inactiva' ? 'desactivacion' : 'actualizacion');

            $sql = "INSERT INTO historial_licencias (id_licencia, accion, detalles) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$idLicencia, $accion, $detalles]);
        } catch (PDOException $e) {
            error_log("Error al registrar cambio de estado: " . $e->getMessage());
            return false;
        }
    }

    private function registrarHistorial($idLicencia, $accion, $detalles) {
        try {
            $detallesJson = json_encode($detalles);
            $sql = "INSERT INTO historial_licencias (id_licencia, accion, detalles) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$idLicencia, $accion, $detallesJson]);
        } catch (PDOException $e) {
            error_log("Error al registrar historial: " . $e->getMessage());
            return false;
        }
    }

    private function verificarExpiracionLicencia($id, $fechaFin) {
        try {
            if ($fechaFin < date('Y-m-d')) {
                // Actualizar estado a expirada
                $sql = "UPDATE licencias SET estado = 'expirada' WHERE id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$id]);

                // Registrar en historial
                $detalles = json_encode([
                    'fecha_expiracion' => $fechaFin,
                    'motivo' => 'Expiración automática'
                ]);

                $sql = "INSERT INTO historial_licencias (id_licencia, accion, detalles) VALUES (?, 'expiracion', ?)";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$id, $detalles]);

                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error en verificarExpiracionLicencia: " . $e->getMessage());
            return false;
        }
    }

    public function validarLimitesLicencia($idLicencia, $idRol) {
        try {
            // Obtener límites de la licencia
            $sql = "SELECT id, max_usuarios, max_residentes, estado FROM licencias WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$idLicencia]);
            $licencia = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$licencia || $licencia['estado'] !== 'activa') {
                throw new Exception('La licencia no está activa o no existe');
            }

            // Contar usuarios actuales
            $sql = "SELECT COUNT(*) as total FROM usuarios WHERE id_licencia = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$idLicencia]);
            $totalUsuarios = $stmt->fetchColumn();

            if ($totalUsuarios >= $licencia['max_usuarios']) {
                throw new Exception('Se ha alcanzado el límite de usuarios permitidos por la licencia');
            }

            // Si es un residente, verificar límite de residentes
            if ($idRol == 3) {
                $sql = "SELECT COUNT(*) as total FROM usuarios WHERE id_licencia = ? AND id_rol = 3";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$idLicencia]);
                $totalResidentes = $stmt->fetchColumn();

                if ($totalResidentes >= $licencia['max_residentes']) {
                    throw new Exception('Se ha alcanzado el límite de residentes permitidos por la licencia');
                }
            }

            return ['success' => true];
        } catch (Exception $e) {
            return [
                'success' => false,
                'mensaje' => $e->getMessage()
            ];
        }
    }

    public function actualizarLicencia($id, $datos) {
        try {
            // Obtener el estado actual antes de la actualización
            $sqlEstadoActual = "SELECT estado, fecha_fin FROM licencias WHERE id = ?";
            $stmtEstadoActual = $this->conn->prepare($sqlEstadoActual);
            $stmtEstadoActual->execute([$id]);
            $licenciaActual = $stmtEstadoActual->fetch(PDO::FETCH_ASSOC);
            $estadoAnterior = $licenciaActual['estado'];

            // Verificar expiración si se está actualizando la fecha_fin o el estado a activa
            if (isset($datos['fecha_fin']) || (isset($datos['estado']) && $datos['estado'] === 'activa')) {
                $fechaFin = isset($datos['fecha_fin']) ? $datos['fecha_fin'] : $licenciaActual['fecha_fin'];
                if ($this->verificarExpiracionLicencia($id, $fechaFin)) {
                    $datos['estado'] = 'expirada';
                }
            }

            // Construir la consulta de actualización
            $campos = [];
            $valores = [];
            foreach ($datos as $campo => $valor) {
                if ($campo !== 'licencia_id') {
                    $campos[] = "$campo = ?";
                    $valores[] = $valor;
                }
            }
            $valores[] = $id;

            $sql = "UPDATE licencias SET " . implode(", ", $campos) . " WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $resultado = $stmt->execute($valores);

            // Si se actualizó el estado, registrar el cambio
            if (isset($datos['estado']) && $datos['estado'] !== $estadoAnterior) {
                $this->registrarCambioEstado($id, $estadoAnterior, $datos['estado']);
            }

            return [
                'success' => $resultado,
                'mensaje' => $resultado ? 'Licencia actualizada correctamente' : 'Error al actualizar la licencia'
            ];
        } catch (PDOException $e) {
            error_log("Error en actualizarLicencia: " . $e->getMessage());
            return [
                'success' => false,
                'mensaje' => 'Error al actualizar la licencia'
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
            // Primero obtener los límites y el ID de la licencia
            $sqlLicencia = "SELECT id, max_usuarios, max_residentes FROM licencias WHERE codigo_licencia = ?";
            $stmtLicencia = $this->conn->prepare($sqlLicencia);
            $stmtLicencia->execute([$codigo_licencia]);
            $licencia = $stmtLicencia->fetch(PDO::FETCH_ASSOC);

            if (!$licencia) {
                return [
                    'total_usuarios' => 0,
                    'total_residentes' => 0,
                    'porcentaje_usuarios' => 0,
                    'porcentaje_residentes' => 0,
                    'max_usuarios' => 0,
                    'max_residentes' => 0
                ];
            }

            // Obtener total de usuarios asignados a esta licencia
            $sqlUsuarios = "SELECT COUNT(*) as total_usuarios FROM usuarios WHERE id_licencia = ?";
            $stmtUsuarios = $this->conn->prepare($sqlUsuarios);
            $stmtUsuarios->execute([$licencia['id']]);
            $totalUsuarios = $stmtUsuarios->fetch(PDO::FETCH_ASSOC)['total_usuarios'];

            // Obtener total de residentes asignados a esta licencia
            $sqlResidentes = "SELECT COUNT(*) as total_residentes FROM usuarios WHERE id_licencia = ? AND id_rol = 3";
            $stmtResidentes = $this->conn->prepare($sqlResidentes);
            $stmtResidentes->execute([$licencia['id']]);
            $totalResidentes = $stmtResidentes->fetch(PDO::FETCH_ASSOC)['total_residentes'];

            // Calcular porcentajes
            $porcentajeUsuarios = ($licencia['max_usuarios'] > 0) 
                ? round(($totalUsuarios / $licencia['max_usuarios']) * 100, 2)
                : 0;

            $porcentajeResidentes = ($licencia['max_residentes'] > 0)
                ? round(($totalResidentes / $licencia['max_residentes']) * 100, 2)
                : 0;

            return [
                'total_usuarios' => (int)$totalUsuarios,
                'total_residentes' => (int)$totalResidentes,
                'porcentaje_usuarios' => $porcentajeUsuarios,
                'porcentaje_residentes' => $porcentajeResidentes,
                'max_usuarios' => (int)$licencia['max_usuarios'],
                'max_residentes' => (int)$licencia['max_residentes']
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