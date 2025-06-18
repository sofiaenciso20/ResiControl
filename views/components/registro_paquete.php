<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../src/config/Database.php';
use App\Config\Database;

$db = new Database();
$conn = $db->getConnection();

// Cargar residentes
$sqlResidentes = "SELECT documento, CONCAT(nombre, ' ', apellido) AS nombre_completo 
                  FROM usuarios 
                  WHERE id_rol = 3 AND id_estado = 1";
$residentes = $conn->query($sqlResidentes)->fetchAll(PDO::FETCH_ASSOC);

// Cargar vigilantes
$sqlVigilantes = "SELECT documento, CONCAT(nombre, ' ', apellido) AS nombre_completo 
                  FROM usuarios 
                  WHERE id_rol = 4 AND id_estado = 1";
$vigilantes = $conn->query($sqlVigilantes)->fetchAll(PDO::FETCH_ASSOC);

// Mostrar mensaje si hay
$mensaje = $_SESSION['mensaje_paquete'] ?? null;
unset($_SESSION['mensaje_paquete']);
?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h4 class="mb-0">Registro de Paquete</h4>
        </div>
        <div class="card-body">
          <?php if (!empty($mensaje)): ?>
            <div class="alert alert-info">
              <?= htmlspecialchars($mensaje); ?>
            </div>
          <?php endif; ?>

          <form method="POST" action="/registro_paquete.php">
            <div class="mb-3">
              <label class="form-label">Residente</label>
              <select name="id_usuarios" class="form-select" required>
                <option value="">Seleccione un residente</option>
                <?php foreach ($residentes as $residente): ?>
                  <option value="<?= $residente['documento'] ?>"><?= htmlspecialchars($residente['nombre_completo']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Vigilante</label>
              <select name="id_vigilante" class="form-select" required>
                <option value="">Seleccione un vigilante</option>
                <?php foreach ($vigilantes as $vigilante): ?>
                  <option value="<?= $vigilante['documento'] ?>"><?= htmlspecialchars($vigilante['nombre_completo']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Descripción del Paquete</label>
              <input type="text" name="descripcion" class="form-control">
            </div>

            <div class="mb-3">
              <label class="form-label">Fecha y Hora de Recepción</label>
              <input type="datetime-local" name="fech_hor_recep" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Registrar Encomienda</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
