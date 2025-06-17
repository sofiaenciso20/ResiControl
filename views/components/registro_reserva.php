<div class="container mt-4">
  <div class="card">
    <div class="card-header">
      <h4>Registro Reserva</h4>
    </div>
    <div class="card-body">
      <form method="POST" action="controllers/crear_reserva.php">
        <div class="mb-3">
          <label for="zona" class="form-label">Zona</label>
          <select name="zona" id="zona" class="form-select" required>
            <option value="">Seleccione una zona</option>
            <option value="Salón Comunal">Salón Comunal</option>
            <option value="Piscina">Piscina</option>
            <option value="Cancha Múltiple">Cancha Múltiple</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="fecha" class="form-label">Fecha</label>
          <input type="date" name="fecha" id="fecha" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="horario" class="form-label">Horario</label>
          <select name="horario" id="horario" class="form-select" required>
            <option value="">Seleccione un horario</option>
            <option value="8:00 am a 10:00 am">8:00 am a 10:00 am</option>
            <option value="10:00 am a 12:00 pm">10:00 am a 12:00 pm</option>
            <option value="2:00 pm a 4:00 pm">2:00 pm a 4:00 pm</option>
            <option value="4:00 pm a 6:00 pm">4:00 pm a 6:00 pm</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="residente" class="form-label">Nombre del Residente</label>
          <input type="text" name="residente" id="residente" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Reservar</button>
      </form>
    </div>
  </div>
</div>
