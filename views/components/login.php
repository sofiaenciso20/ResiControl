
<div class="body-login">
<div class="login-wrapper">
    <div class="login-card">
        <div class="login-image">
            <div class="logo-superior">
                <img src="assets/img/logo.png" alt="Logo ResiControl" style="height: 50px;">
            </div>
        </div>
        <div class="login-form">
            <div class="text-center mb-4">
                <h4 class="mt-2">Iniciar sesión</h4>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Tipo de documento</label>
                    <select class="form-select" name="tipo_documento" required>
                        <option value="">Selecciona</option>
                        <option value="1">C.C</option>
                        <option value="2">T.I</option>
                        <option value="3">C.E</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Documento de identidad</label>
                    <input type="text" name="documento" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="correo" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="contrasena" class="form-control" required>
                </div>
                <div class="mb-3 text-end">
                    <a href="recuperar_contra.php">¿Olvidaste la contraseña?</a>
                </div>
                <button type="submit" class="btn btn-login">Iniciar sesión</button>
            </form>
        </div>
    </div>
</div>
            </div>

