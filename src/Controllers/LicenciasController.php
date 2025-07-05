<?php

// Declaramos el espacio de nombres para mantener una estructura organizada
namespace App\Controllers;

// Importamos clases necesarias
use PDO;
use PDOException;
use App\Config\Database;

// Declaramos la clase controladora para Licencias
class LicenciasController {
    private $db;   // Variable que almacenará el objeto de conexión a la base de datos
    private $conn; // Variable que guardará la conexión activa (PDO)

    // Constructor: se ejecuta automáticamente cuando se crea un objeto de esta clase
    public function __construct() {
        // Creamos una nueva instancia de Database
        $this->db = new Database();
        // Obtenemos la conexión a la base de datos
        $this->conn = $this->db->getConnection();
    }

    // ================================================
    // MÉTODO PRIVADO: Genera un código único para la licencia
    // ================================================
    private function generarCodigoLicencia() {
        // uniqid('LIC-') genera un identificador único que inicia con 'LIC-'
        // substr(md5(time()), 0, 6) agrega una parte aleatoria adicional
        return strtoupper(uniqid('LIC-') . substr(md5(time()), 0, 6));
    }

    // ================================================
    // CREA UNA NUEVA LICENCIA EN LA BASE DE DATOS
    // ================================================
    public function crearLicencia($datos) {
        try {
            // Generamos un código de licencia único
            $codigo = $this->generarCodigoLicencia();

            // Consulta SQL con marcadores para insertar los datos
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

            // Preparamos la consulta
            $stmt = $this->conn->prepare($sql);

            // Convertimos las características a formato JSON
            $caracteristicas = json_encode($datos['caracteristicas'] ?? []);

            // Ejecutamos la consulta pasando los valores en orden
            $stmt->execute([
                $codigo,
                $datos['nombre_residencial'],
                $datos['fecha_inicio'],
                $datos['fecha_fin'],
                $datos['max_usuarios'],
                $datos['max_residentes'],
                $caracteristicas
            ]);

            // Devolvemos mensaje de éxito
            return [
                'success' => true,
                'mensaje' => 'Licencia creada exitosamente',
                'codigo' => $codigo
            ];
        } catch (PDOException $e) {
            // Si ocurre error, se captura y se devuelve mensaje
            return [
                'success' => false,
                'mensaje' => 'Error al crear la licencia: ' . $e->getMessage()
            ];
        }
    }

    // ================================================
    // OBTIENE TODAS LAS LICENCIAS EXISTENTES
    // ================================================
    public function obtenerLicencias() {
        try {
            // Consulta para obtener todas las licencias ordenadas por fecha de creación (descendente)
            $sql = "SELECT * FROM licencias ORDER BY fecha_creacion DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            // Retornamos los resultados en un array asociativo
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

    // ================================================
    // OBTIENE UNA LICENCIA ESPECÍFICA POR ID
    // ================================================
    public function obtenerLicencia($id) {
        try {
            // Consulta para buscar una licencia por ID
            $sql = "SELECT * FROM licencias WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);

            // Obtenemos la licencia
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

    // ================================================
    // ACTUALIZA LOS DATOS DE UNA LICENCIA EXISTENTE
    // ================================================
    public function actualizarLicencia($id, $datos) {
        try {
            // Consulta para actualizar los datos de una licencia
            $sql = "UPDATE licencias SET 
                        nombre_residencial = ?,
                        fecha_fin = ?,
                        max_usuarios = ?,
                        max_residentes = ?,
                        caracteristicas = ?,
                        estado = ?
                    WHERE id = ?";

            $stmt = $this->conn->prepare($sql);

            // Convertimos las características a JSON
            $caracteristicas = json_encode($datos['caracteristicas'] ?? []);

            // Ejecutamos la actualización
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

    // ================================================
    // VERIFICA SI UNA LICENCIA ESTÁ ACTIVA Y VIGENTE
    // ================================================
    public function verificarLicencia($codigo) {
        try {
            // Buscamos una licencia activa y con fecha válida
            $sql = "SELECT * FROM licencias 
                    WHERE codigo_licencia = ? 
                    AND estado = 'activa' 
                    AND fecha_fin >= CURRENT_DATE";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$codigo]);

            $licencia = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($licencia) {
                // Obtenemos el uso actual de la licencia (usuarios/residentes)
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

    // ================================================
    // CALCULA CUÁNTOS USUARIOS Y RESIDENTES USAN LA LICENCIA
    // ================================================
    public function obtenerEstadisticasUso($codigo_licencia) {
        try {
            // Obtenemos los límites máximos permitidos en la licencia
            $sqlLicencia = "SELECT max_usuarios, max_residentes FROM licencias WHERE codigo_licencia = ?";
            $stmtLicencia = $this->conn->prepare($sqlLicencia);
            $stmtLicencia->execute([$codigo_licencia]);
            $limites = $stmtLicencia->fetch(PDO::FETCH_ASSOC);

            // Si no hay datos, se devuelven valores en cero
            if (!$limites) {
                return [
                    'total_usuarios' => 0,
                    'total_residentes' => 0,
                    'porcentaje_usuarios' => 0,
                    'porcentaje_residentes' => 0
                ];
            }

            // Contar usuarios registrados con esta licencia
            $sqlUsuarios = "SELECT COUNT(*) as total_usuarios FROM usuarios WHERE licencia_id = ?";
            $stmtUsuarios = $this->conn->prepare($sqlUsuarios);
            $stmtUsuarios->execute([$codigo_licencia]);
            $totalUsuarios = $stmtUsuarios->fetch(PDO::FETCH_ASSOC)['total_usuarios'];

            // Contar residentes (rol = 3)
            $sqlResidentes = "SELECT COUNT(*) as total_residentes FROM usuarios WHERE licencia_id = ? AND role = 3";
            $stmtResidentes = $this->conn->prepare($sqlResidentes);
            $stmtResidentes->execute([$codigo_licencia]);
            $totalResidentes = $stmtResidentes->fetch(PDO::FETCH_ASSOC)['total_residentes'];

            // Calculamos los porcentajes de uso
            $porcentajeUsuarios = ($limites['max_usuarios'] > 0) 
                ? round(($totalUsuarios / $limites['max_usuarios']) * 100, 2)
                : 0;

            $porcentajeResidentes = ($limites['max_residentes'] > 0)
                ? round(($totalResidentes / $limites['max_residentes']) * 100, 2)
                : 0;

            // Devolvemos todo lo calculado
            return [
                'total_usuarios' => (int)$totalUsuarios,
                'total_residentes' => (int)$totalResidentes,
                'porcentaje_usuarios' => $porcentajeUsuarios,
                'porcentaje_residentes' => $porcentajeResidentes,
                'max_usuarios' => (int)$limites['max_usuarios'],
                'max_residentes' => (int)$limites['max_residentes']
            ];
        } catch (PDOException $e) {
            // En caso de error, se devuelve todo en cero y se registra en el log
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
