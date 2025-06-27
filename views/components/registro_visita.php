<div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow-lg border-0">
          <div class="card-header bg-primary text-white text-center">
            <h3 class="mb-0">Registro de Visita</h3>
          </div>
          <div class="card-body">
            <?php if (!empty($mensaje)): ?>
              <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>
                <?= htmlspecialchars($mensaje) ?>
              </div>
            <?php endif; ?>

            <!-- Ruta corregida a relativa -->
            <form method="POST" action="../registro_visita.php">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Nombre del Visitante</label>
                  <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Apellido del Visitante</label>
                  <input type="text" name="apellido" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Documento del Visitante</label>
                  <input type="number" name="documento" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Residente</label>
                  <select name="id_usuarios" class="form-select" required>
                    <option value="">Seleccione un residente</option>
                    <?php foreach ($residentes as $r): ?>
                      <option value="<?= $r['documento'] ?>"><?= htmlspecialchars($r['nombre_completo']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Motivo de la Visita</label>
                  <select name="id_mot_visi" class="form-select" required>
                    <option value="">Seleccione un motivo</option>
                    <?php foreach ($motivos as $m): ?>
                      <option value="<?= $m['id_mot_visi'] ?>"><?= htmlspecialchars($m['motivo_visita']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Fecha de la Visita</label>
                  <input type="date" name="fecha_ingreso" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Hora Estimada de Llegada</label>
                  <input type="time" name="hora_ingreso" class="form-control" required>
                </div>
              </div>

              <div class="text-end">
                <button type="submit" class="btn btn-success">Registrar Visita</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

