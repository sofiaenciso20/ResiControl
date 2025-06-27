<div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow-lg border-0">
          <div class="card-header bg-primary text-white text-center">
            <h3 class="mb-0">Registro de Reserva</h3>
          </div>
          <div class="card-body">
            <form method="POST" action="/registro_reserva.php">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="zona" class="form-label">Zona</label>
                  <select name="zona" id="zona" class="form-select" required>
                    <option value="">Seleccione una zona</option>
                    <option value="Salón Comunal">Salón Comunal</option>
                    <option value="Piscina">Piscina</option>
                    <option value="Cancha Múltiple">Cancha Múltiple</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="fecha" class="form-label">Fecha</label>
                  <input type="date" name="fecha" id="fecha" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="horario" class="form-label">Horario</label>
                  <select name="horario" id="horario" class="form-select" required>
                    <option value="">Seleccione un horario</option>
                    <option value="8:00 am a 10:00 am">8:00 am a 10:00 am</option>
                    <option value="10:00 am a 12:00 pm">10:00 am a 12:00 pm</option>
                    <option value="2:00 pm a 4:00 pm">2:00 pm a 4:00 pm</option>
                    <option value="4:00 pm a 6:00 pm">4:00 pm a 6:00 pm</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="residente" class="form-label">Nombre del Residente</label>
                  <input type="text" name="residente" id="residente" class="form-control" required>
                </div>
              </div>

              <div class="text-end">
                <button type="submit" class="btn btn-success">Reservar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>