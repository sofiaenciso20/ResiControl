<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h4 class="mb-0">Registro de Zona Pública</h4>
        </div>
        <div class="card-body">
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Zona</label>
              <select class="form-select" name="id_zonas_comu" required>
                <option value="">Seleccione una zona</option>
                <?php foreach ($zonas as $z): ?>
                  <option value="<?= $z['id_zonas_comu'] ?>"><?= htmlspecialchars($z['nombre_zona']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Residente</label>
              <select class="form-select" name="id_usuarios" required>
                <option value="">Seleccione un residente</option>
                <?php foreach ($residentes as $r): ?>
                  <option value="<?= $r['documento'] ?>"><?= htmlspecialchars($r['nombre_completo']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Motivo</label>
              <select class="form-select" name="id_mot_zonas" required>
                <option value="">Seleccione un motivo</option>
                <?php foreach ($motivos as $m): ?>
                  <option value="<?= $m['id_mot_zonas'] ?>"><?= htmlspecialchars($m['motivo_zonas']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Fecha de Reserva</label>
              <input type="date" name="fecha" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Horario</label>
              <select class="form-select" name="id_horario" required>
                <option value="">Seleccione un horario</option>
                <?php foreach ($horarios as $h): ?>
                  <option value="<?= $h['id_horario'] ?>"><?= htmlspecialchars($h['horario']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Observaciones</label>
              <textarea name="observaciones" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Registrar Reserva</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>