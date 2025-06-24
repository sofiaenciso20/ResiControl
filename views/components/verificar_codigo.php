<div class="body-verificar">
  <div class="login-wrapper">
    <div class="login-card">
      <div class="login-form">
        <div class="text-center">
          <h4>Verificar código y cambiar contraseña</h4>
        </div>
        <form method="POST">
          <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input type="email" name="correo" class="form-control" required value="<?php echo isset($_GET['correo']) ? htmlspecialchars($_GET['correo']) : ''; ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Código de recuperación</label>
            <input type="text" name="codigo" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nueva contraseña</label>
            <input type="password" name="nueva_contra" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-login">Cambiar contraseña</button>
        </form>
        <?php if (isset($mensaje) && $mensaje): ?>
          <div class="alert alert-info mt-3">
            <?= htmlspecialchars($mensaje) ?>
          </div>
        <?php endif; ?>
        <div class="text-center mt-3">
          <a href="login.php">← Volver al login</a>
        </div>
      </div>
    </div>
  </div>
</div>