<?php
namespace App\Controllers;

use PDO;
use PDOException;
use Exception;
use App\Config\Database;
use App\Controllers\LicenciasController;

class PersonaController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new Database();
            $conn = $db->getConnection();

            // Recoge los datos comunes
            $tipo_usuario = $_POST['tipo_usuario'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $id_tipo_doc = $_POST['tipo_identificacion'] ?? '';
            $documento = $_POST['numero_identificacion'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $contrasena = password_hash($_POST['contrasena'] ?? '', PASSWORD_DEFAULT);

            // Mapeo dinámico del rol desde la base de datos
            $id_rol = null;
            if (!empty($tipo_usuario)) {
                $stmt_rol = $conn->prepare("SELECT id_rol FROM roles WHERE rol = ?");
                $stmt_rol->execute([$tipo_usuario]);
                $rol_data = $stmt_rol->fetch(PDO::FETCH_ASSOC);
                if ($rol_data) {
                    $id_rol = $rol_data['id_rol'];
                }
            }

            // Comunes y opcionales
            $empresa = $_POST['empresa'] ?? null;
            $direccion_casa = $_POST['direccion_casa'] ?? null;
            $cantidad_personas = $_POST['cantidad_personas'] ?? null;
            $tiene_animales = $_POST['tiene_animales'] ?? null;
            $cantidad_animales = $_POST['cantidad_animales'] ?? null;
            $direccion_residencia = $_POST['direccion_residencia'] ?? null;
            $id_estado = 1;
            $nit = null;
            
            // Campos de residencia para residentes
            $id_casa = null;
            $id_apartamento = null;
            if ($id_rol == 3) { // Solo para residentes
                $tipo_residencia = $_POST['tipo_residencia'] ?? null;
                if ($tipo_residencia === 'casa') {
                    $id_casa = $_POST['id_casa'] ?? null;
                } elseif ($tipo_residencia === 'apartamento') {
                    $id_apartamento = $_POST['id_apartamento'] ?? null;
                }
            }

            // Inicia transacción
            try {
                $conn->beginTransaction();

                 // Verificar si el documento ya existe
                $sql_verificar = "SELECT documento FROM usuarios WHERE documento = :documento";
                $stmt_verificar = $conn->prepare($sql_verificar);
                $stmt_verificar->bindParam(':documento', $documento);
                $stmt_verificar->execute();
 
                if ($stmt_verificar->fetch()) {
                    return "El número de documento " . htmlspecialchars($documento) . " ya está registrado en el sistema.";
                }

                // Insertar usuario
                $sql = "INSERT INTO usuarios
                    (documento, id_tipo_doc, nombre, apellido, telefono, correo, contrasena, id_rol, id_estado, nit, empresa, direccion_casa, cantidad_personas, tiene_animales, cantidad_animales, direccion_residencia, id_casa, id_apartamento)
                    VALUES
                    (:documento, :id_tipo_doc, :nombre, :apellido, :telefono, :correo, :contrasena, :id_rol, :id_estado, :nit, :empresa, :direccion_casa, :cantidad_personas, :tiene_animales, :cantidad_animales, :direccion_residencia, :id_casa, :id_apartamento)";
                
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':documento', $documento);
                $stmt->bindParam(':id_tipo_doc', $id_tipo_doc);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':apellido', $apellido);
                $stmt->bindParam(':telefono', $telefono);
                $stmt->bindParam(':correo', $correo);
                $stmt->bindParam(':contrasena', $contrasena);
                $stmt->bindParam(':id_rol', $id_rol);
                $stmt->bindParam(':id_estado', $id_estado);
                $stmt->bindParam(':nit', $nit);
                $stmt->bindParam(':empresa', $empresa);
                $stmt->bindParam(':direccion_casa', $direccion_casa);
                $stmt->bindParam(':cantidad_personas', $cantidad_personas);
                $stmt->bindParam(':tiene_animales', $tiene_animales);
                $stmt->bindParam(':cantidad_animales', $cantidad_animales);
                $stmt->bindParam(':direccion_residencia', $direccion_residencia);
                $stmt->bindParam(':id_casa', $id_casa);
                $stmt->bindParam(':id_apartamento', $id_apartamento);
                
                if (!$stmt->execute()) {
                    $conn->rollBack();
                    return "Error al registrar la persona.";
                }

                // Marcar residencia como ocupada si es residente
                if ($id_rol == 3) {
                    if ($id_casa) {
                        $stmt_casa = $conn->prepare("UPDATE casas SET estado = 'ocupada' WHERE id_casa = ?");
                        if (!$stmt_casa->execute([$id_casa])) {
                            $conn->rollBack();
                            return "Error al actualizar el estado de la casa.";
                        }
                    } elseif ($id_apartamento) {
                        $stmt_apto = $conn->prepare("UPDATE apartamentos SET estado = 'ocupado' WHERE id_apartamento = ?");
                        if (!$stmt_apto->execute([$id_apartamento])) {
                            $conn->rollBack();
                            return "Error al actualizar el estado del apartamento.";
                        }
                    }
                }

                // Solo si es habitante, registra vehículo
                if ($id_rol == 3) {
                    $id_tipo_vehi = $_POST['id_tipo_vehi'] ?? null;
                    $placa = $_POST['placa'] ?? null;
                    $id_marca = $_POST['id_marca'] ?? null;
 
                    if ($id_tipo_vehi && $placa && $id_marca) {
                        // Verificar que el tipo de vehículo existe
                        $stmt = $conn->prepare("SELECT id_tipo_vehi FROM tipo_vehiculos WHERE id_tipo_vehi = ?");
                        $stmt->execute([$id_tipo_vehi]);
                        if (!$stmt->fetch()) {
                            $conn->rollBack();
                            return "Error: El tipo de vehículo seleccionado no existe.";
                        }
 
                        // Verificar que la marca existe
                        $stmt = $conn->prepare("SELECT id_marca FROM marca WHERE id_marca = ?");
                        $stmt->execute([$id_marca]);
                        if (!$stmt->fetch()) {
                            $conn->rollBack();
                            return "Error: La marca seleccionada no existe.";
                        }
 
                        $sql_vehiculo = "INSERT INTO vehiculos (id_tipo_vehi, id_usuarios, placa, id_marca)
                                         VALUES (:id_tipo_vehi, :id_usuarios, :placa, :id_marca)";
                       
                        $stmtVehiculo = $conn->prepare($sql_vehiculo);
                        $stmtVehiculo->bindParam(':id_tipo_vehi', $id_tipo_vehi);
                        $stmtVehiculo->bindParam(':id_usuarios', $documento);
                        $stmtVehiculo->bindParam(':placa', $placa);
                        $stmtVehiculo->bindParam(':id_marca', $id_marca);
 
                        if (!$stmtVehiculo->execute()) {
                            $error = $stmtVehiculo->errorInfo();
                            $conn->rollBack();
                            return "Error al registrar el vehículo: " . $error[2];
                        }
                    }
                }
 

                $conn->commit();
                return "¡Persona registrada exitosamente!";
            } catch (Exception $e) {
                $conn->rollBack();
                return "Error en el proceso: " . $e->getMessage();
            }
        }
        return "";
    }

    public function crearPersona($datos) {
        try {
            // Validar límites de licencia antes de crear el usuario
            $licenciasController = new LicenciasController();
            $validacionLimites = $licenciasController->validarLimitesLicencia($datos['id_licencia'], $datos['id_rol']);
            
            if (!$validacionLimites['success']) {
                return [
                    'success' => false,
                    'mensaje' => $validacionLimites['mensaje']
                ];
            }

            // Continuar con la creación del usuario si pasa la validación
            $sql = "INSERT INTO usuarios (nombre, apellido, email, password, id_rol, id_licencia) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            
            // Hashear la contraseña
            $hashedPassword = password_hash($datos['password'], PASSWORD_DEFAULT);
            
            $resultado = $stmt->execute([
                $datos['nombre'],
                $datos['apellido'],
                $datos['email'],
                $hashedPassword,
                $datos['id_rol'],
                $datos['id_licencia']
            ]);

            if ($resultado) {
                return [
                    'success' => true,
                    'mensaje' => 'Usuario creado exitosamente',
                    'id' => $this->conn->lastInsertId()
                ];
            } else {
                return [
                    'success' => false,
                    'mensaje' => 'Error al crear el usuario'
                ];
            }
        } catch (PDOException $e) {
            error_log("Error en crearPersona: " . $e->getMessage());
            return [
                'success' => false,
                'mensaje' => 'Error al crear el usuario'
            ];
        }
    }

    // Métodos para manejar los tipos y marcas
    public function agregarTipoVehiculo($tipo) {
        try {
            $sql = "INSERT INTO tipo_vehiculos (tipo_vehiculos) VALUES (?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$tipo]);
            
            return [
                'success' => true,
                'id' => $this->conn->lastInsertId(),
                'mensaje' => 'Tipo de vehículo agregado exitosamente'
            ];
        } catch (PDOException $e) {
            error_log("Error en agregarTipoVehiculo: " . $e->getMessage());
            return [
                'success' => false,
                'mensaje' => 'Error al agregar tipo de vehículo'
            ];
        }
    }

    public function eliminarTipoVehiculo($id) {
        try {
            $sql = "DELETE FROM tipo_vehiculos WHERE id_tipo_vehi = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            
            return [
                'success' => $stmt->rowCount() > 0,
                'mensaje' => $stmt->rowCount() > 0 ? 'Tipo de vehículo eliminado' : 'No se encontró el tipo de vehículo'
            ];
        } catch (PDOException $e) {
            error_log("Error en eliminarTipoVehiculo: " . $e->getMessage());
            return [
                'success' => false,
                'mensaje' => 'Error al eliminar tipo de vehículo'
            ];
        }
    }

    public function agregarMarca($marca) {
        try {
            $sql = "INSERT INTO marca (marca) VALUES (?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$marca]);
            
            return [
                'success' => true,
                'id' => $this->conn->lastInsertId(),
                'mensaje' => 'Marca agregada exitosamente'
            ];
        } catch (PDOException $e) {
            error_log("Error en agregarMarca: " . $e->getMessage());
            return [
                'success' => false,
                'mensaje' => 'Error al agregar marca'
            ];
        }
    }

    public function eliminarMarca($id) {
        try {
            $sql = "DELETE FROM marca WHERE id_marca = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            
            return [
                'success' => $stmt->rowCount() > 0,
                'mensaje' => $stmt->rowCount() > 0 ? 'Marca eliminada' : 'No se encontró la marca'
            ];
        } catch (PDOException $e) {
            error_log("Error en eliminarMarca: " . $e->getMessage());
            return [
                'success' => false,
                'mensaje' => 'Error al eliminar marca'
            ];
        }
    }

    // Métodos para manejar tipos de documento
    public function agregarTipoDocumento($tipo) {
        try {
            $sql = "INSERT INTO tipo_documento (tipo_documento) VALUES (?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$tipo]);
            
            return [
                'success' => true,
                'id' => $this->conn->lastInsertId(),
                'mensaje' => 'Tipo de documento agregado exitosamente'
            ];
        } catch (PDOException $e) {
            error_log("Error en agregarTipoDocumento: " . $e->getMessage());
            return [
                'success' => false,
                'mensaje' => 'Error al agregar tipo de documento'
            ];
        }
    }

    public function eliminarTipoDocumento($id) {
        try {
            $sql = "DELETE FROM tipo_documento WHERE id_tipo_doc = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            
            return [
                'success' => $stmt->rowCount() > 0,
                'mensaje' => $stmt->rowCount() > 0 ? 'Tipo de documento eliminado' : 'No se encontró el tipo de documento'
            ];
        } catch (PDOException $e) {
            error_log("Error en eliminarTipoDocumento: " . $e->getMessage());
            return [
                'success' => false,
                'mensaje' => 'Error al eliminar tipo de documento'
            ];
        }
    }

    // Métodos para manejar roles de usuario
    public function agregarRol($rol) {
        try {
            $sql = "INSERT INTO roles (rol) VALUES (?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$rol]);
            
            return [
                'success' => true,
                'id' => $this->conn->lastInsertId(),
                'mensaje' => 'Rol agregado exitosamente'
            ];
        } catch (PDOException $e) {
            error_log("Error en agregarRol: " . $e->getMessage());
            return [
                'success' => false,
                'mensaje' => 'Error al agregar rol'
            ];
        }
    }

    public function eliminarRol($id) {
        try {
            $sql = "DELETE FROM roles WHERE id_rol = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            
            return [
                'success' => $stmt->rowCount() > 0,
                'mensaje' => $stmt->rowCount() > 0 ? 'Rol eliminado' : 'No se encontró el rol'
            ];
        } catch (PDOException $e) {
            error_log("Error en eliminarRol: " . $e->getMessage());
            return [
                'success' => false,
                'mensaje' => 'Error al eliminar rol'
            ];
        }
    }
}