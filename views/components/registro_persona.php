<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Registro de Persona</h4>
                </div>
                <div class="card-body">
                <?php if (!empty($mensaje)): ?>
                        <div class="alert alert-info">
                            <?php echo htmlspecialchars($mensaje); ?>
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
 
                        <!-- Campos comunes -->
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Apellido</label>
                            <input type="text" class="form-control" name="apellido" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" name="telefono" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo de Identificación</label>
                            <select class="form-select" name="tipo_identificacion" required>
                                <option value="">Selecciona</option>
                                <option value="1">C.C</option>
                                <option value="2">T.I</option>
                                <option value="3">C.E</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Número de Identificación</label>
                            <input type="text" class="form-control" name="numero_identificacion" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" name="correo" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" class="form-control" name="contrasena" required>
                        </div>
 
                        <!-- Campos dinámicos según tipo de usuario -->
                        <div id="campos_vigilante" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Nombre de la Empresa</label>
                                <input type="text" class="form-control" name="empresa">
                            </div>
                        </div>
 
                        <div id="campos_habitante" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Dirección de la Casa</label>
                                <input type="text" class="form-control" name="direccion_casa">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cantidad de Personas</label>
                                <input type="number" class="form-control" name="cantidad_personas">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">¿Tiene Animales?</label>
                                <select class="form-select" name="tiene_animales">
                                    <option value="no">No</option>
                                    <option value="si">Sí</option>
                                </select>
                            </div>
                            <div class="mb-3" id="cantidad_animales_div" style="display: none;">
                                <label class="form-label">Cantidad de Animales</label>
                                <input type="number" class="form-control" name="cantidad_animales">
                            </div>
                        </div>
 
                        <div id="campos_administrador" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Dirección de Residencia</label>
                                <input type="text" class="form-control" name="direccion_residencia">
                            </div>
                        </div>
 
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
 
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