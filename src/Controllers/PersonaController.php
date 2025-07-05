<?php
// Se incluye el archivo de conexión a la base de datos
require_once __DIR__ . '/../Config/Database.php';

// Se importa la clase Database dentro del espacio de nombres
use App\Config\Database;

// Se declara la clase controladora de personas
class PersonaController {

    // Método para registrar una persona
    public function registrar() {

        // Verifica si se recibió una solicitud POST (desde un formulario)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Se crea la conexión a la base de datos
            $db = new Database();
            $conn = $db->getConnection();

            // Se capturan los datos del formulario (comunes a todos los tipos de usuario)
            $tipo_usuario = $_POST['tipo_usuario'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $id_tipo_doc = $_POST['tipo_identificacion'] ?? '';
            $documento = $_POST['numero_identificacion'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $contrasena = password_hash($_POST['contrasena'] ?? '', PASSWORD_DEFAULT); // Se encripta la contraseña

            // Asigna el rol según el tipo de usuario enviado
            switch ($tipo_usuario) {
                case 'vigilante':
                    $id_rol = 4;
                    break;
                case 'habitante':
                case 'residente':
                    $id_rol = 3;
                    break;
                case 'administrador':
                    $id_rol = 2;
                    break;
                default:
                    $id_rol = null; // Si no se especifica un tipo válido
            }

            // Otros datos opcionales o por defecto
            $empresa = $_POST['empresa'] ?? null;
            $direccion_casa = $_POST['direccion_casa'] ?? null;
            $id_manzana = null; // No usado por ahora
            $cantidad_personas = $_POST['cantidad_personas'] ?? null;
            $tiene_animales = $_POST['tiene_animales'] ?? null;
            $cantidad_animales = $_POST['cantidad_animales'] ?? null;
            $direccion_residencia = $_POST['direccion_residencia'] ?? null;
            $id_estado = 1; // Estado activo por defecto
            $nit = null;

            // Inicia la transacción para asegurar que todo se guarde correctamente
            try {
                $conn->beginTransaction();

                // Verifica si el documento ya está registrado
                $sql_verificar = "SELECT documento FROM usuarios WHERE documento = :documento";
                $stmt_verificar = $conn->prepare($sql_verificar);
                $stmt_verificar->bindParam(':documento', $documento);
                $stmt_verificar->execute();

                if ($stmt_verificar->fetch()) {
                    return "El número de documento " . htmlspecialchars($documento) . " ya está registrado en el sistema.";
                }

                // Consulta para insertar un nuevo usuario en la tabla `usuarios`
                $sql = "INSERT INTO usuarios
                    (documento, id_tipo_doc, nombre, apellido, telefono, correo, contrasena, id_rol, id_estado, id_manzana, nit, empresa, direccion_casa, cantidad_personas, tiene_animales, cantidad_animales, direccion_residencia)
                    VALUES
                    (:documento, :id_tipo_doc, :nombre, :apellido, :telefono, :correo, :contrasena, :id_rol, :id_estado, :id_manzana, :nit, :empresa, :direccion_casa, :cantidad_personas, :tiene_animales, :cantidad_animales, :direccion_residencia)";

                // Prepara y enlaza parámetros
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
                $stmt->bindParam(':id_manzana', $id_manzana);
                $stmt->bindParam(':nit', $nit);
                $stmt->bindParam(':empresa', $empresa);
                $stmt->bindParam(':direccion_casa', $direccion_casa);
                $stmt->bindParam(':cantidad_personas', $cantidad_personas);
                $stmt->bindParam(':tiene_animales', $tiene_animales);
                $stmt->bindParam(':cantidad_animales', $cantidad_animales);
                $stmt->bindParam(':direccion_residencia', $direccion_residencia);

                // Si no se puede ejecutar la inserción, se cancela la transacción
                if (!$stmt->execute()) {
                    $conn->rollBack();
                    return "Error al registrar la persona.";
                }

                // Si es residente, se intenta registrar su vehículo
                if ($id_rol == 3) {
                    $id_tipo_vehi = $_POST['id_tipo_vehi'] ?? null;
                    $placa = $_POST['placa'] ?? null;
                    $id_marca = $_POST['id_marca'] ?? null;

                    // Solo si los datos del vehículo están completos
                    if ($id_tipo_vehi && $placa && $id_marca) {

                        // Verifica que el tipo de vehículo existe
                        $stmt = $conn->prepare("SELECT id_tipo_vehi FROM tipo_vehiculos WHERE id_tipo_vehi = ?");
                        $stmt->execute([$id_tipo_vehi]);
                        if (!$stmt->fetch()) {
                            $conn->rollBack();
                            return "Error: El tipo de vehículo seleccionado no existe.";
                        }

                        // Verifica que la marca existe
                        $stmt = $conn->prepare("SELECT id_marca FROM marca WHERE id_marca = ?");
                        $stmt->execute([$id_marca]);
                        if (!$stmt->fetch()) {
                            $conn->rollBack();
                            return "Error: La marca seleccionada no existe.";
                        }

                        // Inserta el vehículo en la tabla `vehiculos`
                        $sql_vehiculo = "INSERT INTO vehiculos (id_tipo_vehi, id_usuarios, placa, id_marca)
                                         VALUES (:id_tipo_vehi, :id_usuarios, :placa, :id_marca)";

                        $stmtVehiculo = $conn->prepare($sql_vehiculo);
                        $stmtVehiculo->bindParam(':id_tipo_vehi', $id_tipo_vehi);
                        $stmtVehiculo->bindParam(':id_usuarios', $documento); // se asocia al documento
                        $stmtVehiculo->bindParam(':placa', $placa);
                        $stmtVehiculo->bindParam(':id_marca', $id_marca);

                        // Si no se puede insertar el vehículo, se cancela todo
                        if (!$stmtVehiculo->execute()) {
                            $error = $stmtVehiculo->errorInfo();
                            $conn->rollBack();
                            return "Error al registrar el vehículo: " . $error[2];
                        }
                    }
                }

                // Confirma la transacción: todo se guardó bien
                $conn->commit();
                return "¡Persona registrada exitosamente!";
            } catch (Exception $e) {
                // Si ocurre cualquier error, revierte todo y muestra el mensaje
                $conn->rollBack();
                return "Error en el proceso: " . $e->getMessage();
            }
        }

        // Si no es un POST, no hace nada
        return "";
    }
}
