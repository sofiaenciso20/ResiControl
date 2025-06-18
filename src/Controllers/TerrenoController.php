<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
use App\Config\Database;

class TerrenoController {
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo_terreno = $_POST['tipo_terreno'];

            $db = new Database();
            $conn = $db->getConnection();

            if ($tipo_terreno === 'bloque') {
                $cantidad_apartamentos = $_POST['apartamentos'];

                $sql = "INSERT INTO bloque (cantidad_apartamentos) VALUES (:cantidad_apartamentos)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':cantidad_apartamentos', $cantidad_apartamentos);

                if ($stmt->execute()) {
                    $_SESSION['mensaje'] = "✅ ¡Bloque registrado exitosamente!";
                } else {
                    $_SESSION['mensaje'] = "❌ Error al registrar el bloque.";
                }

            } elseif ($tipo_terreno === 'manzana') {
                $cantidad_casas = $_POST['casas'];

                $sql = "INSERT INTO manzana (cantidad_casas) VALUES (:cantidad_casas)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':cantidad_casas', $cantidad_casas);

                if ($stmt->execute()) {
                    $_SESSION['mensaje'] = "✅ ¡Manzana registrada exitosamente!";
                } else {
                    $_SESSION['mensaje'] = "❌ Error al registrar la manzana.";
                }

            } else {
                $_SESSION['mensaje'] = "⚠️ Tipo de terreno no válido.";
            }

            header("Location: /registro_terreno.php");
            exit();
        }
    }
}

$controller = new TerrenoController();
$controller->registrar();
