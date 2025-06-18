<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../src/config/Database.php';
use App\Config\Database;

$db = new Database();
$conn = $db->getConnection();

// Cargar residentes
$sql = "SELECT documento, CONCAT(nombre, ' ', apellido) AS nombre_completo 
        FROM usuarios 
        WHERE id_rol = 3 AND id_estado = 1";
$residentes = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Cargar motivos
$sqlMotivos = "SELECT id_mot_visi, motivo_visita FROM motivo_visita";
$motivos = $conn->query($sqlMotivos)->fetchAll(PDO::FETCH_ASSOC);

$mensaje = $_SESSION['mensaje_visita'] ?? null;
unset($_SESSION['mensaje_visita']);
?>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h4 class="mb-0">Registro de Visita</h4>
        </div>
        <div class="card-body">
          <?php if ($mensaje): ?>
            <div class="alert alert-info text-center"><?= htmlspecialchars($mensaje) ?></div>
          <?php endif; ?>

          <form method="POST" action="/registro_visita.php">
            <div class="mb-3">
              <label class="form-label">Nombre del Visitante</label>
              <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Apellido del Visitante</label>
              <input type="text" name="apellido" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Documento del Visitante</label>
              <input type="number" name="documento" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Residente</label>
              <select name="id_usuarios" class="form-select" required>
                <option value="">Seleccione un residente</option>
                <?php foreach ($residentes as $r): ?>
                  <option value="<?= $r['documento'] ?>"><?= htmlspecialchars($r['nombre_completo']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Motivo de la Visita</label>
              <select name="id_mot_visi" class="form-select" required>
                <option value="">Seleccione un motivo</option>
                <?php foreach ($motivos as $m): ?>
                  <option value="<?= $m['id_mot_visi'] ?>"><?= htmlspecialchars($m['motivo_visita']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Fecha de la Visita</label>
              <input type="date" name="fecha_ingreso" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Hora Estimada de Llegada</label>
              <input type="time" name="hora_ingreso" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Registrar Visita</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
