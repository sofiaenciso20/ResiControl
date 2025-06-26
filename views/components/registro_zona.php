<div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow-lg border-0">
          <div class="card-header bg-primary text-white text-center">
            <h3 class="mb-0">Registro de Zona Pública</h3>
          </div>
          <div class="card-body">
            <form method="POST" action="/registro_zona.php">
              <div class="row">
                <div class="col-md-12 mb-3">
                  <label class="form-label">Nombre de la Zona</label>
                  <input type="text" class="form-control" name="nombre" required>
                </div>

                <div class="col-md-12 mb-3">
                  <label class="form-label">Tipo de Zona</label>
                  <select class="form-select" name="tipo_zona" required>
                    <option value="">Selecciona</option>
                    <option value="salon">Salón</option>
                    <option value="piscina">Piscina</option>
                    <option value="gimnasio">Gimnasio</option>
                    <option value="otro">Otro</option>
                  </select>
                </div>

                <div class="col-md-12 mb-3">
                  <label class="form-label">Motivo de la Zona</label>
                  <textarea class="form-control" name="motivo_zona" rows="3" required></textarea>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Hora de Inicio</label>
                  <input type="time" class="form-control" name="hora_inicio" required>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Hora de Fin</label>
                  <input type="time" class="form-control" name="hora_fin" required>
                </div>
              </div>

              <div class="text-end">
                <button type="submit" class="btn btn-success">Registrar Zona</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>