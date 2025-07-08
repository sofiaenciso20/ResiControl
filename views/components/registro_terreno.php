<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white text-center">
          <h3 class="mb-0">Registro de Terreno</h3>
        </div>
        <div class="card-body">
          <?php if (!empty($mensaje)): ?>
            <div class="alert alert-info text-center"><?= htmlspecialchars($mensaje) ?></div>
          <?php endif; ?>

          <form method="POST" action="/registro_terreno.php" id="formRegistroTerreno" novalidate>
            <div class="mb-3">
              <label class="form-label">Tipo de Terreno</label>
              <select class="form-select" name="tipo_terreno" id="tipo_terreno" required>
                <option value="">Selecciona</option>
                <option value="bloque">Bloque</option>
                <option value="manzana">Manzana</option>
              </select>
              <div class="invalid-feedback">Por favor selecciona un tipo de terreno.</div>
            </div>

            <div id="cantidad_apartamentos" class="mb-3" style="display: none;">
              <label class="form-label">Cantidad de Apartamentos</label>
              <input type="number" class="form-control" name="apartamentos" id="apartamentos"
                     min="1" max="500">
              <div class="invalid-feedback">Ingresa una cantidad válida entre 1 y 500 apartamentos.</div>
            </div>

            <div id="cantidad_casas" class="mb-3" style="display: none;">
              <label class="form-label">Cantidad de Casas</label>
              <input type="number" class="form-control" name="casas" id="casas"
                     min="1" max="500">
              <div class="invalid-feedback">Ingresa una cantidad válida entre 1 y 500 casas.</div>
            </div>

            <div class="text-end">
              <button type="submit" class="btn btn-success me-2">Registrar Terreno</button>
              <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalGestionTerrenos">
                <i class="bi bi-gear"></i> Gestionar Terrenos
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ✅ Script de validación -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const tipoTerreno = document.getElementById('tipo_terreno');
    const apartamentosDiv = document.getElementById('cantidad_apartamentos');
    const casasDiv = document.getElementById('cantidad_casas');
    const form = document.getElementById('formRegistroTerreno');

    const apartamentosInput = document.getElementById('apartamentos');
    const casasInput = document.getElementById('casas');

    // Mostrar campos dinámicos según el tipo de terreno
    tipoTerreno.addEventListener('change', function () {
      const tipo = this.value;
      apartamentosDiv.style.display = tipo === 'bloque' ? 'block' : 'none';
      casasDiv.style.display = tipo === 'manzana' ? 'block' : 'none';

      // Limpiar valores y validaciones al cambiar
      apartamentosInput.value = '';
      casasInput.value = '';
      apartamentosInput.classList.remove('is-invalid', 'is-valid');
      casasInput.classList.remove('is-invalid', 'is-valid');
    });

    // Validación al enviar
    form.addEventListener('submit', function (e) {
      const tipo = tipoTerreno.value;
      let valid = true;

      if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
        valid = false;
      }

      // Validar campo dinámico según tipo de terreno
      if (tipo === 'bloque') {
        if (!apartamentosInput.value || apartamentosInput.value < 1 || apartamentosInput.value > 500) {
          apartamentosInput.classList.add('is-invalid');
          valid = false;
        } else {
          apartamentosInput.classList.remove('is-invalid');
        }
      }

      if (tipo === 'manzana') {
        if (!casasInput.value || casasInput.value < 1 || casasInput.value > 500) {
          casasInput.classList.add('is-invalid');
          valid = false;
        } else {
          casasInput.classList.remove('is-invalid');
        }
      }

      if (!valid) {
        e.preventDefault();
        e.stopPropagation();
      }

      form.classList.add('was-validated');
    });

    // Validación en tiempo real
    [apartamentosInput, casasInput].forEach(input => {
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

<!-- Modal para gestionar terrenos -->
<div class="modal fade" id="modalGestionTerrenos" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Gestión de Terrenos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Pestañas -->
        <ul class="nav nav-tabs" id="gestionTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="manzanas-tab" data-bs-toggle="tab" data-bs-target="#manzanas" type="button" role="tab">
              <i class="bi bi-house"></i> Manzanas
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="bloques-tab" data-bs-toggle="tab" data-bs-target="#bloques" type="button" role="tab">
              <i class="bi bi-building"></i> Bloques
            </button>
          </li>
        </ul>

        <!-- Contenido de pestañas -->
        <div class="tab-content" id="gestionTabContent">
          <!-- Pestaña Manzanas -->
          <div class="tab-pane fade show active" id="manzanas" role="tabpanel">
            <div class="mt-3">
              <h6>Manzanas Existentes</h6>
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Cantidad de Casas</th>
                      <th>Casas Disponibles</th>
                      <th>Casas Ocupadas</th>
                      <th>Acción</th>
                    </tr>
                  </thead>
                  <tbody id="tablaManzanas">
                    <tr>
                      <td colspan="5" class="text-center">Cargando...</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Pestaña Bloques -->
          <div class="tab-pane fade" id="bloques" role="tabpanel">
            <div class="mt-3">
              <h6>Bloques Existentes</h6>
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Cantidad de Apartamentos</th>
                      <th>Apartamentos Disponibles</th>
                      <th>Apartamentos Ocupados</th>
                      <th>Acción</th>
                    </tr>
                  </thead>
                  <tbody id="tablaBloques">
                    <tr>
                      <td colspan="5" class="text-center">Cargando...</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Script adicional para gestión de terrenos -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Cargar datos cuando se abre el modal
  document.getElementById('modalGestionTerrenos').addEventListener('shown.bs.modal', function() {
    cargarManzanas();
    cargarBloques();
  });

  // Cargar manzanas
  function cargarManzanas() {
    fetch('?action=obtener_manzanas_completo')
      .then(response => response.json())
      .then(data => {
        const tbody = document.getElementById('tablaManzanas');
        if (data.length === 0) {
          tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No hay manzanas registradas</td></tr>';
          return;
        }

        tbody.innerHTML = data.map(manzana => `
          <tr>
            <td>Manzana ${manzana.id_manzana}</td>
            <td>${manzana.cantidad_casas}</td>
            <td><span class="badge bg-success">${manzana.disponibles}</span></td>
            <td><span class="badge bg-warning">${manzana.ocupadas}</span></td>
            <td>
              ${manzana.disponibles == manzana.cantidad_casas ? 
                `<button class="btn btn-sm btn-outline-danger" onclick="eliminarManzana(${manzana.id_manzana})">
                  <i class="bi bi-trash"></i> Eliminar
                </button>` :
                `<span class="text-muted">No se puede eliminar (tiene casas ocupadas)</span>`
              }
            </td>
          </tr>
        `).join('');
      })
      .catch(error => {
        console.error('Error:', error);
        document.getElementById('tablaManzanas').innerHTML = 
          '<tr><td colspan="5" class="text-center text-danger">Error al cargar datos</td></tr>';
      });
  }

  // Cargar bloques
  function cargarBloques() {
    fetch('?action=obtener_bloques_completo')
      .then(response => response.json())
      .then(data => {
        const tbody = document.getElementById('tablaBloques');
        if (data.length === 0) {
          tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No hay bloques registrados</td></tr>';
          return;
        }

        tbody.innerHTML = data.map(bloque => `
          <tr>
            <td>Bloque ${bloque.id_bloque}</td>
            <td>${bloque.cantidad_apartamentos}</td>
            <td><span class="badge bg-success">${bloque.disponibles}</span></td>
            <td><span class="badge bg-warning">${bloque.ocupados}</span></td>
            <td>
              ${bloque.disponibles == bloque.cantidad_apartamentos ? 
                `<button class="btn btn-sm btn-outline-danger" onclick="eliminarBloque(${bloque.id_bloque})">
                  <i class="bi bi-trash"></i> Eliminar
                </button>` :
                `<span class="text-muted">No se puede eliminar (tiene apartamentos ocupados)</span>`
              }
            </td>
          </tr>
        `).join('');
      })
      .catch(error => {
        console.error('Error:', error);
        document.getElementById('tablaBloques').innerHTML = 
          '<tr><td colspan="5" class="text-center text-danger">Error al cargar datos</td></tr>';
      });
  }

  // Funciones globales para eliminar
  window.eliminarManzana = function(idManzana) {
    if (confirm('¿Está seguro que desea eliminar la Manzana ' + idManzana + ' y todas sus casas?')) {
      fetch('?action=eliminar_manzana', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_manzana: idManzana })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Manzana eliminada exitosamente');
          cargarManzanas();
        } else {
          alert('Error: ' + data.mensaje);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Error al eliminar la manzana');
      });
    }
  };

  window.eliminarBloque = function(idBloque) {
    if (confirm('¿Está seguro que desea eliminar el Bloque ' + idBloque + ' y todos sus apartamentos?')) {
      fetch('?action=eliminar_bloque', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_bloque: idBloque })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Bloque eliminado exitosamente');
          cargarBloques();
        } else {
          alert('Error: ' + data.mensaje);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Error al eliminar el bloque');
      });
    }
  };
});
</script>
