<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h4 class="mb-0">Registrar Visita</h4>
        </div>
        <div class="card-body">
          <form method="POST" action="controllers/crear_visita.php">
            <div class="mb-3">
              <label class="form-label">Nombre del Visitante</label>
              <input type="text" name="nombre_visitante" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Residente</label>
              <input type="text" name="residente" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Casa</label>
              <input type="text" name="casa" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Motivo de la Visita</label>
              <input type="text" name="motivo" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Fecha de la Visita</label>
              <input type="date" name="fecha" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Hora Estimada de Llegada</label>
              <input type="time" name="hora_llegada" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Visita</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
