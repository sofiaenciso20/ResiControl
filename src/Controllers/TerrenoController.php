<?php
// Inicia una sesión para poder utilizar variables de sesión como mensajes
session_start();

// Incluye la clase que maneja la conexión a la base de datos
require_once __DIR__ . '/../config/Database.php';

// Usa el espacio de nombres correcto para acceder a la clase Database
use App\Config\Database;

// Se define la clase del controlador llamada TerrenoController
class TerrenoController {
    
    // Método público para registrar un terreno (bloque o manzana)
    public function registrar() {

        // Verifica que el formulario haya sido enviado por el método POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Obtiene el tipo de terreno enviado desde el formulario (bloque o manzana)
            $tipo_terreno = $_POST['tipo_terreno'];

            // Crea una instancia de la base de datos y obtiene la conexión
            $db = new Database();
            $conn = $db->getConnection();

            // Si el tipo de terreno es "bloque", procede a registrar un bloque
            if ($tipo_terreno === 'bloque') {

                // Obtiene el número de apartamentos desde el formulario
                $cantidad_apartamentos = $_POST['apartamentos'];

                try {
                    // Iniciar transacción
                    $conn->beginTransaction();

                    // Consulta SQL para insertar un nuevo bloque
                    $sql = "INSERT INTO bloque (cantidad_apartamentos) VALUES (:cantidad_apartamentos)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':cantidad_apartamentos', $cantidad_apartamentos);

                    if ($stmt->execute()) {
                        // Obtener el ID del bloque recién creado
                        $id_bloque = $conn->lastInsertId();

                        // Auto-generar apartamentos para este bloque
                        $this->generarApartamentos($conn, $id_bloque, $cantidad_apartamentos);

                        // Confirmar transacción
                        $conn->commit();
                        $_SESSION['mensaje'] = "✅ ¡Bloque registrado exitosamente con {$cantidad_apartamentos} apartamentos!";
                    } else {
                        $conn->rollBack();
                        $_SESSION['mensaje'] = "❌ Error al registrar el bloque.";
                    }
                } catch (Exception $e) {
                    $conn->rollBack();
                    $_SESSION['mensaje'] = "❌ Error al registrar el bloque: " . $e->getMessage();
                }

            } 
            // Si el tipo es "manzana", procede a registrar una manzana
            elseif ($tipo_terreno === 'manzana') {

                // Obtiene el número de casas desde el formulario
                $cantidad_casas = $_POST['casas'];

                try {
                    // Iniciar transacción
                    $conn->beginTransaction();

                    // Consulta SQL para insertar una nueva manzana
                    $sql = "INSERT INTO manzana (cantidad_casas) VALUES (:cantidad_casas)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':cantidad_casas', $cantidad_casas);

                    if ($stmt->execute()) {
                        // Obtener el ID de la manzana recién creada
                        $id_manzana = $conn->lastInsertId();

                        // Auto-generar casas para esta manzana
                        $this->generarCasas($conn, $id_manzana, $cantidad_casas);

                        // Confirmar transacción
                        $conn->commit();
                        $_SESSION['mensaje'] = "✅ ¡Manzana registrada exitosamente con {$cantidad_casas} casas!";
                    } else {
                        $conn->rollBack();
                        $_SESSION['mensaje'] = "❌ Error al registrar la manzana.";
                    }
                } catch (Exception $e) {
                    $conn->rollBack();
                    $_SESSION['mensaje'] = "❌ Error al registrar la manzana: " . $e->getMessage();
                }

            } 
            // Si el tipo de terreno no es válido
            else {
                $_SESSION['mensaje'] = "⚠️ Tipo de terreno no válido.";
            }

            // Redirige al formulario de registro de terreno, mostrando el mensaje de sesión
            header("Location: /registro_terreno.php");
            exit();
        }
    }

    // Método para generar apartamentos automáticamente al crear un bloque
    private function generarApartamentos($conn, $id_bloque, $cantidad_apartamentos) {
        for ($i = 1; $i <= $cantidad_apartamentos; $i++) {
            // Crear número de apartamento con formato: primer dígito = número de bloque, siguientes = número secuencial
            $numero_apartamento = $id_bloque . str_pad($i, 2, '0', STR_PAD_LEFT);
            
            $sql = "INSERT INTO apartamentos (id_bloque, numero_apartamento, estado) VALUES (?, ?, 'disponible')";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id_bloque, $numero_apartamento]);
        }
    }

    // Método para generar casas automáticamente al crear una manzana
    private function generarCasas($conn, $id_manzana, $cantidad_casas) {
        for ($i = 1; $i <= $cantidad_casas; $i++) {
            // Crear número de casa con formato: Manzana + número secuencial
            $numero_casa = "M" . $id_manzana . "-" . str_pad($i, 2, '0', STR_PAD_LEFT);
            
            $sql = "INSERT INTO casas (id_manzana, numero_casa, estado) VALUES (?, ?, 'disponible')";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id_manzana, $numero_casa]);
        }
    }
}

// Crea una instancia del controlador y ejecuta el método registrar (si es un POST)
$controller = new TerrenoController();
$controller->registrar();
