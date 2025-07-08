<?php

// Se cargan las dependencias instaladas con Composer, como autoloaders o librerías externas
require_once __DIR__ . '/../vendor/autoload.php';

// Se importa el controlador que maneja la lógica relacionada con terrenos
require_once __DIR__ . '/../src/Controllers/TerrenoController.php';

// Se cargan las funciones o configuraciones relacionadas con los permisos del sistema
require_once __DIR__ . '/../src/Config/permissions.php';

// Verifica si aún no se ha iniciado una sesión, y si no, la inicia.
// Esto es necesario para trabajar con variables de sesión como $_SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica si el usuario tiene el permiso 'registro_terreno'
// Si no tiene el permiso, lo redirige al dashboard y termina la ejecución
if (!tienePermiso('registro_terreno')) {
    header('Location: dashboard.php');
    exit;
}

// Manejar peticiones AJAX para gestión de terrenos
if (isset($_GET['action'])) {
    require_once __DIR__ . '/../src/config/Database.php';
    
    $db = new \App\Config\Database();
    $conn = $db->getConnection();
    
    header('Content-Type: application/json');
    
    switch ($_GET['action']) {
        case 'obtener_manzanas_completo':
            $sql = "SELECT 
                        m.id_manzana,
                        m.cantidad_casas,
                        COUNT(c.id_casa) as total_casas,
                        SUM(CASE WHEN c.estado = 'disponible' THEN 1 ELSE 0 END) as disponibles,
                        SUM(CASE WHEN c.estado = 'ocupada' THEN 1 ELSE 0 END) as ocupadas
                    FROM manzana m
                    LEFT JOIN casas c ON m.id_manzana = c.id_manzana
                    GROUP BY m.id_manzana
                    ORDER BY m.id_manzana";
            $stmt = $conn->query($sql);
            $manzanas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($manzanas);
            exit;
            
        case 'obtener_bloques_completo':
            $sql = "SELECT 
                        b.id_bloque,
                        b.cantidad_apartamentos,
                        COUNT(a.id_apartamento) as total_apartamentos,
                        SUM(CASE WHEN a.estado = 'disponible' THEN 1 ELSE 0 END) as disponibles,
                        SUM(CASE WHEN a.estado = 'ocupado' THEN 1 ELSE 0 END) as ocupados
                    FROM bloque b
                    LEFT JOIN apartamentos a ON b.id_bloque = a.id_bloque
                    GROUP BY b.id_bloque
                    ORDER BY b.id_bloque";
            $stmt = $conn->query($sql);
            $bloques = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($bloques);
            exit;
            
        case 'eliminar_manzana':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                $idManzana = (int)$input['id_manzana'];
                
                try {
                    $conn->beginTransaction();
                    
                    // Verificar que no hay casas ocupadas
                    $stmt = $conn->prepare("SELECT COUNT(*) as ocupadas FROM casas WHERE id_manzana = ? AND estado = 'ocupada'");
                    $stmt->execute([$idManzana]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($result['ocupadas'] > 0) {
                        echo json_encode(['success' => false, 'mensaje' => 'No se puede eliminar: hay casas ocupadas']);
                        exit;
                    }
                    
                    // Eliminar casas primero
                    $stmt = $conn->prepare("DELETE FROM casas WHERE id_manzana = ?");
                    $stmt->execute([$idManzana]);
                    
                    // Eliminar manzana
                    $stmt = $conn->prepare("DELETE FROM manzana WHERE id_manzana = ?");
                    $stmt->execute([$idManzana]);
                    
                    $conn->commit();
                    echo json_encode(['success' => true, 'mensaje' => 'Manzana eliminada correctamente']);
                } catch (Exception $e) {
                    $conn->rollBack();
                    echo json_encode(['success' => false, 'mensaje' => 'Error al eliminar: ' . $e->getMessage()]);
                }
            }
            exit;
            
        case 'eliminar_bloque':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                $idBloque = (int)$input['id_bloque'];
                
                try {
                    $conn->beginTransaction();
                    
                    // Verificar que no hay apartamentos ocupados
                    $stmt = $conn->prepare("SELECT COUNT(*) as ocupados FROM apartamentos WHERE id_bloque = ? AND estado = 'ocupado'");
                    $stmt->execute([$idBloque]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($result['ocupados'] > 0) {
                        echo json_encode(['success' => false, 'mensaje' => 'No se puede eliminar: hay apartamentos ocupados']);
                        exit;
                    }
                    
                    // Eliminar apartamentos primero
                    $stmt = $conn->prepare("DELETE FROM apartamentos WHERE id_bloque = ?");
                    $stmt->execute([$idBloque]);
                    
                    // Eliminar bloque
                    $stmt = $conn->prepare("DELETE FROM bloque WHERE id_bloque = ?");
                    $stmt->execute([$idBloque]);
                    
                    $conn->commit();
                    echo json_encode(['success' => true, 'mensaje' => 'Bloque eliminado correctamente']);
                } catch (Exception $e) {
                    $conn->rollBack();
                    echo json_encode(['success' => false, 'mensaje' => 'Error al eliminar: ' . $e->getMessage()]);
                }
            }
            exit;
            
        default:
            echo json_encode(['error' => 'Acción no válida']);
            exit;
    }
}

// Recupera un mensaje de sesión, si existe, y luego lo elimina para evitar que se muestre varias veces
$mensaje = $_SESSION['mensaje'] ?? null;
unset($_SESSION['mensaje']);

// Se crea una instancia del controlador de terrenos
$controller = new terrenoController();

// Se llama al método registrar(), que probablemente maneja la lógica de guardado del terreno si es un POST
$controller->registrar();

// Se definen variables para el título de la página y el nombre de la página actual (puede usarse para resaltar un ítem del menú, por ejemplo)
$titulo = 'Registro de Terreno';
$pagina_actual = 'registro_terreno';

// Se inicia el almacenamiento en búfer de salida. Esto permite capturar el contenido generado por el archivo PHP en lugar de enviarlo directamente al navegador
ob_start();

// Se incluye la vista que contiene el formulario u otro contenido relacionado con el registro de terrenos
require_once __DIR__ . '/../views/components/registro_terreno.php';

// Se guarda el contenido capturado en la variable $contenido
$contenido = ob_get_clean();

// Finalmente, se carga el layout principal del sitio, al que se le inserta el contenido capturado anteriormente
require_once __DIR__ . '/../views/layout/main.php';
