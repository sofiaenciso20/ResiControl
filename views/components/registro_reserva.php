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
                  <?php foreach ($zonas as $z): ?>
                    <option value="<?= $z['id_zonas_comu'] ?>" <?= (isset($_POST['zona']) && $_POST['zona'] == $z['id_zonas_comu']) ? 'selected' : '' ?>><?= htmlspecialchars($z['nombre_zona']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-6 mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" name="fecha" id="fecha" class="form-control" min="<?= date('Y-m-d') ?>" value="<?= isset($_POST['fecha']) ? htmlspecialchars($_POST['fecha']) : '' ?>" required>
              </div>

              <div class="col-md-6 mb-3">
                <label for="horario" class="form-label">Horario</label>
                <select name="horario" id="horario" class="form-select" required>
                  <option value="">Seleccione un horario</option>
                  <?php foreach ($horariosPosibles as $h):
                    $disabled = in_array($h['id_horario'], $horariosOcupados) ? 'disabled style=\"background:#f8d7da;\"' : ''; ?>
                    <option value="<?= $h['id_horario'] ?>" <?= $disabled ?>>
                      <?= htmlspecialchars($h['horario']) ?>  <?= $disabled ? ' (Ocupado)' : '' ?>
                    </option>
                  <?php endforeach; ?>
                </select>
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

<script>
// Espera a que el DOM esté completamente cargado
// para asegurar que los elementos existen antes de manipularlos
 
document.addEventListener('DOMContentLoaded', function() {
    // Referencias a los elementos del formulario
    const zona = document.getElementById('zona');
    const fecha = document.getElementById('fecha');
    const horario = document.getElementById('horario');
 
    // Función que consulta los horarios ocupados y actualiza el select de horarios
    function actualizarHorarios() {
        // Solo ejecuta si ambos campos tienen valor
        if (!zona.value || !fecha.value) return;
        const formData = new FormData();
        formData.append('zona', zona.value);
        formData.append('fecha', fecha.value);
 
        // Realiza una petición AJAX al endpoint PHP para obtener los horarios ocupados
        fetch('horarios_disponibles.php', {
            method: 'POST',
            body: formData
        })
        .then(resp => resp.json()) // Convierte la respuesta a JSON (array de id_horario ocupados)
        .then(ocupados => {
          ocupados = ocupados.map(String); // Asegura que todos los ids sean string para la comparación
            // Recorre todas las opciones del select de horarios
            for (let opt of horario.options) {
                if (!opt.value) continue; // Salta la opción vacía
                // Si el id_horario está ocupado, deshabilita y marca en rojo
                if (ocupados.includes(opt.value)) {
                    opt.disabled = true;
                    opt.style.background = '#f8d7da';
                    if (!opt.textContent.includes(' (Ocupado)'))
                        opt.textContent += ' (Ocupado)';
                } else {
                    // Si está disponible, habilita y limpia el color
                    opt.disabled = false;
                    opt.style.background = '';
                    opt.textContent = opt.textContent.replace(' (Ocupado)', '');
                }
            }
            // Reinicia la selección del select de horarios
            horario.selectedIndex = 0;
        });
    }
 
    // Cuando el usuario cambia la zona o la fecha, actualiza los horarios disponibles
    zona.addEventListener('change', actualizarHorarios);
    fecha.addEventListener('change', actualizarHorarios);
});
</script>
 
 
