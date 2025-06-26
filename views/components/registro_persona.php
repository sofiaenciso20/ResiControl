<div class="container min-vh-100 d-flex flex-column">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card shadow-lg border-0">
          <div class="card-header bg-primary text-white text-center">
            <h3 class="mb-0">Registro de Persona</h3>
          </div>
          <div class="card-body">
            <?php if (!empty($mensaje)): ?>
              <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>
                <?= htmlspecialchars($mensaje); ?>
              </div>
            <?php endif; ?>

            <form method="POST" action="/registro_persona.php">
              <!-- Tipo de Usuario -->
              <div class="mb-3">
                <label class="form-label">Tipo de Usuario</label>
                <select class="form-select" name="tipo_usuario" id="tipo_usuario" required>
                  <option value="">Selecciona</option>
                  <option value="vigilante">Vigilante</option>
                  <option value="habitante">Habitante</option>
                  <option value="administrador">Administrador</option>
                </select>
              </div>

              <!-- Comunes -->
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Nombre</label>
                  <input type="text" class="form-control" name="nombre" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Apellido</label>
                  <input type="text" class="form-control" name="apellido" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Teléfono</label>
                  <input type="tel" class="form-control" name="telefono" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Tipo de Identificación</label>
                  <select class="form-select" name="tipo_identificacion" required>
                    <option value="">Selecciona</option>
                    <option value="1">C.C</option>
                    <option value="2">T.I</option>
                    <option value="3">C.E</option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Número de Identificación</label>
                  <input type="text" class="form-control" name="numero_identificacion" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Correo Electrónico</label>
                  <input type="email" class="form-control" name="correo" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Contraseña</label>
                  <input type="password" class="form-control" name="contrasena" required>
                </div>
              </div>

              <!-- Campos Vigilante -->
              <div id="campos_vigilante" style="display: none;">
                <hr>
                <h5>Datos Vigilante</h5>
                <div class="mb-3">
                  <label class="form-label">Nombre de la Empresa</label>
                  <input type="text" class="form-control" name="empresa">
                </div>
              </div>

              <!-- Campos Habitante -->
              <div id="campos_habitante" style="display: none;">
                <hr>
                <h5>Datos Habitante</h5>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Dirección de la Casa</label>
                    <input type="text" class="form-control" name="direccion_casa">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Cantidad de Personas</label>
                    <input type="number" class="form-control" name="cantidad_personas">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label">¿Tiene Animales?</label>
                    <select class="form-select" name="tiene_animales">
                      <option value="no">No</option>
                      <option value="si">Sí</option>
                    </select>
                  </div>
                  <div class="col-md-6 mb-3" id="cantidad_animales_div" style="display: none;">
                    <label class="form-label">Cantidad de Animales</label>
                    <input type="number" class="form-control" name="cantidad_animales">
                  </div>
                </div>

                <h6 class="mt-3">Vehículo</h6>
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label class="form-label">Tipo de Vehículo</label>
                    <select class="form-select" name="id_tipo_vehi">
                      <option value="">Seleccione</option>
                      <?php foreach ($tipos as $tipo): ?>
                        <option value="<?= $tipo['id_tipo_vehi'] ?>"><?= htmlspecialchars($tipo['tipo_vehiculos']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label">Placa</label>
                    <input type="text" class="form-control" name="placa">
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label">Marca</label>
                    <select class="form-select" name="id_marca">
                      <option value="">Seleccione</option>
                      <?php foreach ($marcas as $marca): ?>
                        <option value="<?= $marca['id_marca'] ?>"><?= htmlspecialchars($marca['marca']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label">Color</label>
                    <input type="text" class="form-control" name="color">
                  </div>
                </div>
              </div>

              <!-- Campos Administrador -->
              <div id="campos_administrador" style="display: none;">
                <hr>
                <h5>Datos Administrador</h5>
                <div class="mb-3">
                  <label class="form-label">Dirección de Residencia</label>
                  <input type="text" class="form-control" name="direccion_residencia">
                </div>
              </div>

              <div class="text-end">
                <button type="submit" class="btn btn-success">Registrar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap Icons y JS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- JS para mostrar/ocultar campos dinámicos -->
  <script>
    document.getElementById('tipo_usuario').addEventListener('change', function() {
      const tipo = this.value;
      document.getElementById('campos_vigilante').style.display = tipo === 'vigilante' ? 'block' : 'none';
      document.getElementById('campos_habitante').style.display = tipo === 'habitante' ? 'block' : 'none';
      document.getElementById('campos_administrador').style.display = tipo === 'administrador' ? 'block' : 'none';
    });

    document.querySelector('select[name="tiene_animales"]').addEventListener('change', function() {
      document.getElementById('cantidad_animales_div').style.display = this.value === 'si' ? 'block' : 'none';
    });
  </script>
