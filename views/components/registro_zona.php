<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white text-center">
          <h3 class="mb-0">Registro de Zona Pública</h3>
        </div>
        <div class="card-body">
          <form method="POST" action="/registro_zona.php" id="formRegistroZona" novalidate>
            <div class="row">
              <div class="col-md-12 mb-3">
                <label class="form-label">Nombre de la Zona</label>
                <input type="text" class="form-control" name="nombre" id="nombre_zona"
                  required pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ ]{3,50}">
                <div class="invalid-feedback">
                  Por favor ingresa un nombre válido (solo letras y espacios, entre 3 y 50 caracteres).
                </div>
              </div>

              <div class="col-md-12 mb-3">
                <label class="form-label">Tipo de Zona</label>
                <select class="form-select" name="tipo_zona" id="tipo_zona" required>
                  <option value="">Selecciona</option>
                  <option value="salon">Salón</option>
                  <option value="piscina">Piscina</option>
                  <option value="gimnasio">Gimnasio</option>
                  <option value="otro">Otro</option>
                </select>
                <div class="invalid-feedback">Por favor selecciona un tipo de zona.</div>
              </div>

              <div class="col-md-12 mb-3">
                <label class="form-label">Motivo de la Zona</label>
                <textarea class="form-control" name="motivo_zona" id="motivo_zona"
                  rows="3" minlength="10" maxlength="500" required></textarea>
                <div class="invalid-feedback">
                  Por favor describe el motivo (mínimo 10 caracteres).
                </div>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Hora de Inicio</label>
                <input type="time" class="form-control" name="hora_inicio" id="hora_inicio" required>
                <div class="invalid-feedback">Por favor selecciona la hora de inicio.</div>
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">Hora de Fin</label>
                <input type="time" class="form-control" name="hora_fin" id="hora_fin" required>
                <div class="invalid-feedback" id="error_hora_fin">Por favor selecciona una hora de fin válida (mayor a la de inicio).</div>
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

<!-- ✅ Script de validación Bootstrap y lógica de hora -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formRegistroZona');
    const horaInicio = document.getElementById('hora_inicio');
    const horaFin = document.getElementById('hora_fin');
    const errorHoraFin = document.getElementById('error_hora_fin');

    // Validación de tiempo: la hora de fin debe ser mayor a la hora de inicio
    function validarHoras() {
      const inicio = horaInicio.value;
      const fin = horaFin.value;
      if (inicio && fin && fin <= inicio) {
        horaFin.setCustomValidity("La hora de fin debe ser mayor a la de inicio");
        errorHoraFin.style.display = 'block';
        return false;
      } else {
        horaFin.setCustomValidity('');
        errorHoraFin.style.display = '';
        return true;
      }
    }

    horaInicio.addEventListener('change', validarHoras);
    horaFin.addEventListener('change', validarHoras);

    form.addEventListener('submit', function (event) {
      validarHoras(); // Ejecutar validación de horas antes de enviar
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    });

    // Validación en tiempo real para todos los campos requeridos
    const campos = form.querySelectorAll('input[required], select[required], textarea[required]');
    campos.forEach(input => {
      input.addEventListener('input', function () {
        if (this.checkValidity()) {
          this.classList.remove('is-invalid');
          this.classList.add('is-valid');
        } else {
          this.classList.remove('is-valid');
          this.classList.add('is-invalid');
        }
      });
    });
  });
</script>
