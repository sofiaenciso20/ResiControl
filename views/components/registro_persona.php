<div class="container min-vh-100 d-flex flex-column">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white text-center">
          <h3 class="mb-0">Registro de Persona</h3>
        </div>
        <div class="card-body">
          <?php if (!empty($mensaje)): ?>
              <div class="alert <?= strpos($mensaje, 'exitosamente') !== false ? 'alert-success' : 'alert-danger' ?> d-flex align-items-center" role="alert">
                <i class="bi <?= strpos($mensaje, 'exitosamente') !== false ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' ?> me-2"></i>
                <?= htmlspecialchars($mensaje); ?>
              </div>
            <?php endif; ?>

          <?php
          $rol_usuario = $_SESSION['user']['role'] ?? null;
          $es_admin = in_array($rol_usuario, [1, 2]);
          ?>

          <form method="POST" action="/registro_persona.php" id="formRegistroPersona" novalidate>
            <?php if ($es_admin): ?>
              <div class="mb-3">
                <label class="form-label">Tipo de Usuario</label>
                <div class="input-group">
                <select class="form-select" name="tipo_usuario" id="tipo_usuario" required>
                  <option value="">Selecciona</option>
                    <?php foreach ($roles as $rol): ?>
                      <option value="<?= htmlspecialchars($rol['rol']) ?>" data-id-rol="<?= $rol['id_rol'] ?>"><?= htmlspecialchars($rol['rol']) ?></option>
                    <?php endforeach; ?>
                </select>
                  <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalEditarRoles">
                      <i class="bi bi-pencil"></i>
                </button>
                </div>
                <div class="invalid-feedback">Por favor selecciona un tipo de usuario.</div>
              </div>
            <?php else: ?>
              <input type="hidden" name="tipo_usuario" value="habitante">
            <?php endif; ?>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" required pattern="[A-Za-záéíóúÁÉÍÓÚñÑ ]+" minlength="2" maxlength="50">
                <div class="invalid-feedback">Nombre inválido (solo letras, 2-50 caracteres).</div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Apellido</label>
                <input type="text" class="form-control" name="apellido" required pattern="[A-Za-záéíóúÁÉÍÓÚñÑ ]+" minlength="2" maxlength="50">
                <div class="invalid-feedback">Apellido inválido (solo letras, 2-50 caracteres).</div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Teléfono</label>
                <input type="tel" class="form-control" name="telefono" required pattern="[0-9]{7,15}">
                <div class="invalid-feedback">Teléfono inválido (7-15 dígitos).</div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Tipo de Identificación</label>
                <div class="input-group">
                  <select class="form-select" name="tipo_identificacion" required>
                    <option value="">Selecciona</option>
                    <?php foreach ($tipos_documento as $tipo_doc): ?>
                      <option value="<?= $tipo_doc['id_tipo_doc'] ?>"><?= htmlspecialchars($tipo_doc['tipo_documento']) ?></option>
                    <?php endforeach; ?>
                  </select>
                  <?php if ($es_admin): ?>
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalEditarTipoIdentificacion">
                      <i class="bi bi-pencil"></i>
                    </button>
                  <?php endif; ?>
                </div>
                <div class="invalid-feedback">Selecciona un tipo de identificación.</div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Número de Identificación</label>
                <input type="text" class="form-control" name="numero_identificacion" required pattern="[0-9]{6,20}">
                <div class="invalid-feedback">Número inválido (6-20 dígitos).</div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" name="correo" required>
                <div class="invalid-feedback">Correo electrónico inválido.</div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="contrasena" required 
                       pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$" 
                       id="contrasena">
                <div class="invalid-feedback">Mínimo 8 caracteres, con letras, números y un símbolo.</div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Confirmar Contraseña</label>
                <input type="password" class="form-control" name="confirmar_contrasena" required id="confirmar_contrasena">
                <div class="invalid-feedback">Las contraseñas no coinciden.</div>
              </div>
            </div>

            <?php if ($es_admin): ?>
              <div id="campos_vigilante" style="display: none;">
                <hr><h5>Datos Vigilante</h5>
                <div class="mb-3">
                  <label class="form-label">Nombre de la Empresa</label>
                  <input type="text" class="form-control" name="empresa" minlength="2" maxlength="100" id="empresa_vigilante">
                  <div class="invalid-feedback">Nombre inválido (2-100 caracteres).</div>
                </div>
              </div>

              <div id="campos_habitante" style="display: none;">
                <hr><h5>Datos Habitante</h5>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Dirección de la Casa</label>
                    <input type="text" class="form-control" name="direccion_casa" minlength="5" maxlength="200" id="direccion_casa">
                    <div class="invalid-feedback">Dirección inválida (5-200 caracteres).</div>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Cantidad de Personas</label>
                    <input type="number" class="form-control" name="cantidad_personas" min="1" max="20" id="cantidad_personas">
                    <div class="invalid-feedback">Número entre 1 y 20 requerido.</div>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">¿Tiene Animales?</label>
                    <select class="form-select" name="tiene_animales" id="tiene_animales">
                      <option value="no">No</option>
                      <option value="si">Sí</option>
                    </select>
                  </div>
                  <div class="col-md-6 mb-3" id="cantidad_animales_div" style="display: none;">
                    <label class="form-label">Cantidad de Animales</label>
                    <input type="number" class="form-control" name="cantidad_animales" min="0" max="10" id="cantidad_animales">
                    <div class="invalid-feedback">Número entre 0 y 10 requerido.</div>
                  </div>
                </div>

                <h6 class="mt-3">Residencia</h6>
                <div class="row">
                  <div class="col-md-12 mb-3">
                    <label class="form-label">Tipo de Residencia</label>
                    <select class="form-select" name="tipo_residencia" id="tipo_residencia" required>
                      <option value="">Selecciona</option>
                      <option value="casa">Casa (Manzana)</option>
                      <option value="apartamento">Apartamento (Bloque)</option>
                    </select>
                    <div class="invalid-feedback">Selecciona un tipo de residencia.</div>
                  </div>
                </div>

                <!-- Campos para Casa -->
                <div id="campos_casa" class="row" style="display: none;">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Manzana</label>
                    <select class="form-select" name="id_manzana" id="select_manzana">
                      <option value="">Selecciona una manzana</option>
                    </select>
                    <div class="invalid-feedback">Selecciona una manzana.</div>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Casa</label>
                    <select class="form-select" name="id_casa" id="select_casa">
                      <option value="">Primero selecciona una manzana</option>
                    </select>
                    <div class="invalid-feedback">Selecciona una casa.</div>
                  </div>
                </div>

                <!-- Campos para Apartamento -->
                <div id="campos_apartamento" class="row" style="display: none;">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Bloque</label>
                    <select class="form-select" name="id_bloque" id="select_bloque">
                      <option value="">Selecciona un bloque</option>
                    </select>
                    <div class="invalid-feedback">Selecciona un bloque.</div>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Apartamento</label>
                    <select class="form-select" name="id_apartamento" id="select_apartamento">
                      <option value="">Primero selecciona un bloque</option>
                    </select>
                    <div class="invalid-feedback">Selecciona un apartamento.</div>
                  </div>
                </div>

                <h6 class="mt-3">Vehículo</h6>
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label class="form-label">Tipo de Vehículo</label>
                    <div class="input-group">
                      <select class="form-select" name="id_tipo_vehi" id="tipo_vehiculo">
                        <option value="">Seleccione</option>
                        <?php foreach ($tipos as $tipo): ?>
                          <option value="<?= $tipo['id_tipo_vehi'] ?>"><?= htmlspecialchars($tipo['tipo_vehiculos']) ?></option>
                        <?php endforeach; ?>
                      </select>
                      <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalEditarTipoVehiculo">
                        <i class="bi bi-pencil"></i>
                      </button>
                    </div>
                    <div class="invalid-feedback">Selecciona un tipo de vehículo.</div>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label">Placa</label>
                    <input type="text" class="form-control" name="placa" pattern="[A-Za-z0-9]{3,10}" id="placa">
                    <div class="invalid-feedback">Placa inválida (3-10 caracteres alfanuméricos).</div>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label">Marca</label>
                    <div class="input-group">
                      <select class="form-select" name="id_marca" id="marca_vehiculo">
                        <option value="">Seleccione</option>
                        <?php foreach ($marcas as $marca): ?>
                          <option value="<?= $marca['id_marca'] ?>"><?= htmlspecialchars($marca['marca']) ?></option>
                        <?php endforeach; ?>
                      </select>
                      <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalEditarMarca">
                        <i class="bi bi-pencil"></i>
                      </button>
                    </div>
                    <div class="invalid-feedback">Selecciona una marca.</div>
                  </div>
                </div>
              </div>

              <div id="campos_administrador" style="display: none;">
                <hr><h5>Datos Administrador</h5>
                <div class="mb-3">
                  <label class="form-label">Dirección de Residencia</label>
                  <input type="text" class="form-control" name="direccion_residencia" minlength="5" maxlength="200" id="direccion_residencia">
                  <div class="invalid-feedback">Dirección inválida (5-200 caracteres).</div>
                </div>
              </div>
            <?php endif; ?>

            <div class="text-end">
              <button type="submit" class="btn btn-success">Registrar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modales para editar opciones -->
<?php if ($es_admin): ?>
  <!-- Modal para editar tipo de identificación -->
  <div class="modal fade" id="modalEditarTipoIdentificacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar Tipos de Identificación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formTipoIdentificacion">
            <div class="mb-3">
              <label class="form-label">Nuevo Tipo</label>
              <input type="text" class="form-control" id="nuevoTipoIdentificacion" placeholder="Ej: Pasaporte">
            </div>
            <div class="mb-3">
              <label class="form-label">Tipos Existentes</label>
              <ul class="list-group" id="listaTiposIdentificacion">
                <?php 
                // Debug: Verificar que $tipos_documento existe y tiene datos
                if (isset($tipos_documento) && !empty($tipos_documento)) {
                  foreach ($tipos_documento as $tipo_doc): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= $tipo_doc['id_tipo_doc'] ?>">
                      <?= htmlspecialchars($tipo_doc['tipo_documento']) ?>
                      <button type="button" class="btn btn-sm btn-outline-danger btnEliminarTipoIdentificacion">Eliminar</button>
                </li>
                  <?php endforeach;
                } else { ?>
                  <li class="list-group-item text-muted">No hay tipos de documento disponibles o hay un error en la consulta</li>
                <?php } ?>
              </ul>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="btnGuardarTipoIdentificacion">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para editar tipos de vehículo -->
  <div class="modal fade" id="modalEditarTipoVehiculo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar Tipos de Vehículo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formTipoVehiculo">
            <div class="mb-3">
              <label class="form-label">Nuevo Tipo</label>
              <input type="text" class="form-control" id="nuevoTipoVehiculo" placeholder="Ej: Motocicleta">
            </div>
            <div class="mb-3">
              <label class="form-label">Tipos Existentes</label>
              <ul class="list-group" id="listaTiposVehiculo">
                <?php foreach ($tipos as $tipo): ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= $tipo['id_tipo_vehi'] ?>">
                    <?= htmlspecialchars($tipo['tipo_vehiculos']) ?>
                    <button type="button" class="btn btn-sm btn-outline-danger btnEliminarTipoVehiculo">Eliminar</button>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="btnGuardarTipoVehiculo">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para editar marcas de vehículo -->
  <div class="modal fade" id="modalEditarMarca" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar Marcas de Vehículo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formMarcaVehiculo">
            <div class="mb-3">
              <label class="form-label">Nueva Marca</label>
              <input type="text" class="form-control" id="nuevaMarcaVehiculo" placeholder="Ej: Tesla">
            </div>
            <div class="mb-3">
              <label class="form-label">Marcas Existentes</label>
              <ul class="list-group" id="listaMarcasVehiculo">
                <?php foreach ($marcas as $marca): ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= $marca['id_marca'] ?>">
                    <?= htmlspecialchars($marca['marca']) ?>
                    <button type="button" class="btn btn-sm btn-outline-danger btnEliminarMarca">Eliminar</button>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="btnGuardarMarca">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para editar roles de usuario -->
  <div class="modal fade" id="modalEditarRoles" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar Roles de Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formRol">
            <div class="mb-3">
              <label class="form-label">Nuevo Rol</label>
              <input type="text" class="form-control" id="nuevoRol" placeholder="Ej: Supervisor">
            </div>
            <div class="mb-3">
              <label class="form-label">Roles Existentes</label>
              <ul class="list-group" id="listaRoles">
                <?php 
                if (isset($roles) && !empty($roles)) {
                  foreach ($roles as $rol): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= $rol['id_rol'] ?>">
                      <?= htmlspecialchars($rol['rol']) ?>
                      <button type="button" class="btn btn-sm btn-outline-danger btnEliminarRol">Eliminar</button>
                    </li>
                  <?php endforeach;
                } else { ?>
                  <li class="list-group-item text-muted">No hay roles disponibles o hay un error en la consulta</li>
                <?php } ?>
              </ul>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="btnGuardarRol">Guardar</button>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- JS -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const tipoUsuarioSelect = document.getElementById('tipo_usuario');
    const form = document.getElementById('formRegistroPersona');
    const contrasena = document.getElementById('contrasena');
    const confirmar = document.getElementById('confirmar_contrasena');

    if (tipoUsuarioSelect) {
      tipoUsuarioSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const idRol = selectedOption ? selectedOption.getAttribute('data-id-rol') : null;
        
        // Mostrar campos basados en ID de rol
        // ID 4 = Vigilante, ID 3 = Residente/Habitante, ID 2 = Administrador
        document.getElementById('campos_vigilante').style.display = idRol === '4' ? 'block' : 'none';
        document.getElementById('campos_habitante').style.display = idRol === '3' ? 'block' : 'none';
        document.getElementById('campos_administrador').style.display = idRol === '2' ? 'block' : 'none';
      });
    }

    document.getElementById('tiene_animales')?.addEventListener('change', function () {
      const mostrar = this.value === 'si';
      document.getElementById('cantidad_animales_div').style.display = mostrar ? 'block' : 'none';
      if (!mostrar) document.getElementById('cantidad_animales').value = '';
    });

    // Funcionalidad para campos de residencia
    const tipoResidenciaSelect = document.getElementById('tipo_residencia');
    const camposCasa = document.getElementById('campos_casa');
    const camposApartamento = document.getElementById('campos_apartamento');
    const selectManzana = document.getElementById('select_manzana');
    const selectCasa = document.getElementById('select_casa');
    const selectBloque = document.getElementById('select_bloque');
    const selectApartamento = document.getElementById('select_apartamento');

    // Manejar cambio de tipo de residencia
    tipoResidenciaSelect?.addEventListener('change', function() {
      const tipo = this.value;
      
      if (tipo === 'casa') {
        camposCasa.style.display = 'block';
        camposApartamento.style.display = 'none';
        cargarManzanas();
        // Limpiar apartamento
        selectBloque.value = '';
        selectApartamento.value = '';
      } else if (tipo === 'apartamento') {
        camposCasa.style.display = 'none';
        camposApartamento.style.display = 'block';
        cargarBloques();
        // Limpiar casa
        selectManzana.value = '';
        selectCasa.value = '';
      } else {
        camposCasa.style.display = 'none';
        camposApartamento.style.display = 'none';
      }
    });

    // Manejar cambio de manzana
    selectManzana?.addEventListener('change', function() {
      const idManzana = this.value;
      if (idManzana) {
        cargarCasas(idManzana);
      } else {
        selectCasa.innerHTML = '<option value="">Primero selecciona una manzana</option>';
      }
    });

    // Manejar cambio de bloque
    selectBloque?.addEventListener('change', function() {
      const idBloque = this.value;
      if (idBloque) {
        cargarApartamentos(idBloque);
      } else {
        selectApartamento.innerHTML = '<option value="">Primero selecciona un bloque</option>';
      }
    });

    // Funciones para cargar datos dinámicamente
    function cargarManzanas() {
      fetch('?action=obtener_manzanas')
        .then(response => response.json())
        .then(data => {
          selectManzana.innerHTML = '<option value="">Selecciona una manzana</option>';
          data.forEach(manzana => {
            selectManzana.innerHTML += `<option value="${manzana.id_manzana}">Manzana ${manzana.id_manzana}</option>`;
          });
        })
        .catch(error => console.error('Error cargando manzanas:', error));
    }

    function cargarBloques() {
      fetch('?action=obtener_bloques')
        .then(response => response.json())
        .then(data => {
          selectBloque.innerHTML = '<option value="">Selecciona un bloque</option>';
          data.forEach(bloque => {
            selectBloque.innerHTML += `<option value="${bloque.id_bloque}">Bloque ${bloque.id_bloque}</option>`;
          });
        })
        .catch(error => console.error('Error cargando bloques:', error));
    }

    function cargarCasas(idManzana) {
      fetch(`?action=obtener_casas&id_manzana=${idManzana}`)
        .then(response => response.json())
        .then(data => {
          selectCasa.innerHTML = '<option value="">Selecciona una casa</option>';
          data.forEach(casa => {
            selectCasa.innerHTML += `<option value="${casa.id_casa}">Casa ${casa.numero_casa}</option>`;
          });
        })
        .catch(error => console.error('Error cargando casas:', error));
    }

    function cargarApartamentos(idBloque) {
      fetch(`?action=obtener_apartamentos&id_bloque=${idBloque}`)
        .then(response => response.json())
        .then(data => {
          selectApartamento.innerHTML = '<option value="">Selecciona un apartamento</option>';
          data.forEach(apartamento => {
            selectApartamento.innerHTML += `<option value="${apartamento.id_apartamento}">Apartamento ${apartamento.numero_apartamento}</option>`;
          });
        })
        .catch(error => console.error('Error cargando apartamentos:', error));
    }

    if (contrasena && confirmar) {
      contrasena.addEventListener('input', () => {
        const value = contrasena.value;
        const valid = /[A-Za-z]/.test(value) && /\d/.test(value) && /[@$!%*#?&]/.test(value) && value.length >= 8;
        contrasena.classList.toggle('is-valid', valid);
        contrasena.classList.toggle('is-invalid', !valid);
      });

      confirmar.addEventListener('input', () => {
        const match = contrasena.value === confirmar.value;
        confirmar.setCustomValidity(match ? '' : 'Las contraseñas no coinciden');
        confirmar.classList.toggle('is-invalid', !match);
      });
    }

    form.addEventListener('submit', function (e) {
      if (!form.checkValidity()) {
        e.preventDefault();
        form.classList.add('was-validated');
      }
    });

    const inputs = form.querySelectorAll('input[required], select[required]');
    inputs.forEach(input => {
      input.addEventListener('input', () => {
        input.classList.toggle('is-valid', input.checkValidity());
        input.classList.toggle('is-invalid', !input.checkValidity());
      });
    });

    // Funcionalidad para los modales de edición
    <?php if ($es_admin): ?>
      // Manejar el modal de tipos de vehículo
      document.getElementById('btnGuardarTipoVehiculo')?.addEventListener('click', function() {
        const nuevoTipo = document.getElementById('nuevoTipoVehiculo').value.trim();
        if (nuevoTipo) {
          // Aquí iría la llamada AJAX para guardar en la base de datos
          fetch('guardar_tipo_vehiculo.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ tipo: nuevoTipo })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Agregar el nuevo tipo a la lista
              const lista = document.getElementById('listaTiposVehiculo');
              const nuevoItem = document.createElement('li');
              nuevoItem.className = 'list-group-item d-flex justify-content-between align-items-center';
              nuevoItem.dataset.id = data.id;
              nuevoItem.innerHTML = `
                ${nuevoTipo}
                <button type="button" class="btn btn-sm btn-outline-danger btnEliminarTipoVehiculo">Eliminar</button>
              `;
              lista.appendChild(nuevoItem);
              
              // Agregar al select en el formulario principal
              const select = document.querySelector('select[name="id_tipo_vehi"]');
              const nuevaOpcion = document.createElement('option');
              nuevaOpcion.value = data.id;
              nuevaOpcion.textContent = nuevoTipo;
              select.appendChild(nuevaOpcion);
              
              // Limpiar el input
              document.getElementById('nuevoTipoVehiculo').value = '';
              
              // Mostrar mensaje de éxito
              alert('Tipo de vehículo guardado exitosamente');
            } else {
              alert('Error al guardar: ' + data.mensaje);
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Error al guardar el tipo de vehículo');
          });
        }
      });

      // Manejar eliminación de tipos de vehículo
      document.getElementById('listaTiposVehiculo')?.addEventListener('click', function(e) {
        if (e.target.classList.contains('btnEliminarTipoVehiculo')) {
          const item = e.target.closest('li');
          const id = item.dataset.id;
          const tipo = item.textContent.trim().replace('Eliminar', '').trim();
          
          if (confirm(`¿Está seguro que desea eliminar el tipo "${tipo}"?`)) {
            // Aquí iría la llamada AJAX para eliminar de la base de datos
            fetch('eliminar_tipo_vehiculo.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                // Eliminar de la lista
                item.remove();
                
                // Eliminar del select en el formulario principal
                const select = document.querySelector('select[name="id_tipo_vehi"]');
                const opcion = select.querySelector(`option[value="${id}"]`);
                if (opcion) opcion.remove();
                
                alert('Tipo de vehículo eliminado exitosamente');
              } else {
                alert('Error al eliminar: ' + data.mensaje);
              }
            })
            .catch(error => {
              console.error('Error:', error);
              alert('Error al eliminar el tipo de vehículo');
            });
          }
        }
      });

      // Manejar el modal de marcas de vehículo (similar al de tipos)
      document.getElementById('btnGuardarMarca')?.addEventListener('click', function() {
        const nuevaMarca = document.getElementById('nuevaMarcaVehiculo').value.trim();
        if (nuevaMarca) {
          // Llamada AJAX similar a la de tipos de vehículo
          // ...
        }
      });

      // Manejar eliminación de marcas (similar a tipos)
      document.getElementById('listaMarcasVehiculo')?.addEventListener('click', function(e) {
        if (e.target.classList.contains('btnEliminarMarca')) {
          // Lógica similar a la de tipos de vehículo
          // ...
        }
      });

      // Manejar el modal de tipos de identificación (similar)
      document.getElementById('btnGuardarTipoIdentificacion')?.addEventListener('click', function() {
        const nuevoTipo = document.getElementById('nuevoTipoIdentificacion').value.trim();
        if (nuevoTipo) {
          // Llamada AJAX para guardar en la base de datos
          fetch('guardar_tipo_documento.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ tipo: nuevoTipo })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Agregar el nuevo tipo a la lista
              const lista = document.getElementById('listaTiposIdentificacion');
              const nuevoItem = document.createElement('li');
              nuevoItem.className = 'list-group-item d-flex justify-content-between align-items-center';
              nuevoItem.dataset.id = data.id;
              nuevoItem.innerHTML = `
                ${nuevoTipo}
                <button type="button" class="btn btn-sm btn-outline-danger btnEliminarTipoIdentificacion">Eliminar</button>
              `;
              lista.appendChild(nuevoItem);
              
              // Agregar al select en el formulario principal
              const select = document.querySelector('select[name="tipo_identificacion"]');
              const nuevaOpcion = document.createElement('option');
              nuevaOpcion.value = data.id;
              nuevaOpcion.textContent = nuevoTipo;
              select.appendChild(nuevaOpcion);
              
              // Limpiar el input
              document.getElementById('nuevoTipoIdentificacion').value = '';
              
              // Mostrar mensaje de éxito
              alert('Tipo de documento guardado exitosamente');
            } else {
              alert('Error al guardar: ' + data.mensaje);
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Error al guardar el tipo de documento');
          });
        }
      });

      // Manejar eliminación de tipos de identificación
      document.getElementById('listaTiposIdentificacion')?.addEventListener('click', function(e) {
        if (e.target.classList.contains('btnEliminarTipoIdentificacion')) {
          const item = e.target.closest('li');
          const id = item.dataset.id;
          const tipo = item.textContent.trim().replace('Eliminar', '').trim();
          
          if (id === undefined || id === null || id === '') {
            alert('Error: No se pudo obtener el ID del tipo de documento. Verifique que el elemento tenga el atributo data-id.');
            return;
          }
          
          if (confirm(`¿Está seguro que desea eliminar el tipo "${tipo}"?`)) {
            // Llamada AJAX para eliminar de la base de datos
            fetch('eliminar_tipo_documento.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                // Eliminar de la lista
                item.remove();
                
                // Eliminar del select en el formulario principal
                const select = document.querySelector('select[name="tipo_identificacion"]');
                const opcion = select.querySelector(`option[value="${id}"]`);
                if (opcion) opcion.remove();
                
                alert('Tipo de documento eliminado exitosamente');
              } else {
                alert('Error al eliminar: ' + data.mensaje);
              }
            })
            .catch(error => {
              console.error('Error:', error);
              alert('Error al eliminar el tipo de documento');
            });
          }
        }
      });

      // Manejar el modal de roles de usuario
      document.getElementById('btnGuardarRol')?.addEventListener('click', function() {
        const nuevoRol = document.getElementById('nuevoRol').value.trim();
        if (nuevoRol) {
          // Llamada AJAX para guardar en la base de datos
          fetch('guardar_rol.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ rol: nuevoRol })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Agregar el nuevo rol a la lista
              const lista = document.getElementById('listaRoles');
              const nuevoItem = document.createElement('li');
              nuevoItem.className = 'list-group-item d-flex justify-content-between align-items-center';
              nuevoItem.dataset.id = data.id;
              nuevoItem.innerHTML = `
                ${nuevoRol}
                <button type="button" class="btn btn-sm btn-outline-danger btnEliminarRol">Eliminar</button>
              `;
              lista.appendChild(nuevoItem);
              
              // Agregar al select en el formulario principal
              const select = document.querySelector('select[name="tipo_usuario"]');
              const nuevaOpcion = document.createElement('option');
              nuevaOpcion.value = nuevoRol;
              nuevaOpcion.textContent = nuevoRol;
              select.appendChild(nuevaOpcion);
              
              // Limpiar el input
              document.getElementById('nuevoRol').value = '';
              
              // Mostrar mensaje de éxito
              alert('Rol guardado exitosamente');
            } else {
              alert('Error al guardar: ' + data.mensaje);
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Error al guardar el rol');
          });
        }
      });

      // Manejar eliminación de roles
      document.getElementById('listaRoles')?.addEventListener('click', function(e) {
        if (e.target.classList.contains('btnEliminarRol')) {
          const item = e.target.closest('li');
          const id = item.dataset.id;
          const rol = item.textContent.trim().replace('Eliminar', '').trim();
          
          if (id === undefined || id === null || id === '') {
            alert('Error: No se pudo obtener el ID del rol. Verifique que el elemento tenga el atributo data-id.');
            return;
          }
          
          if (confirm(`¿Está seguro que desea eliminar el rol "${rol}"?`)) {
            // Llamada AJAX para eliminar de la base de datos
            fetch('eliminar_rol.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                // Eliminar de la lista
                item.remove();
                
                // Eliminar del select en el formulario principal
                const select = document.querySelector('select[name="tipo_usuario"]');
                const opcion = select.querySelector(`option[value="${rol}"]`);
                if (opcion) opcion.remove();
                
                alert('Rol eliminado exitosamente');
              } else {
                alert('Error al eliminar: ' + data.mensaje);
              }
            })
            .catch(error => {
              console.error('Error:', error);
              alert('Error al eliminar el rol');
            });
          }
        }
      });
    <?php endif; ?>
  });
</script>