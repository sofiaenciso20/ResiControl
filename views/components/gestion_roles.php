<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Roles</title>
</head>
<body class="container mt-5">
  <h3 class="mb-4">Gestión de Roles de Usuarios</h3>
<?php if (!empty($mensaje_exito)): ?>
    <div class="alert alert-success" role="alert">
      <?= htmlspecialchars($mensaje_exito) ?>
    </div>
  <?php endif; ?>
  <?php $rol_usuario_logueado = $_SESSION['user']['role']; ?>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Documento</th>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Rol actual</th>
        <th>Cambiar Rol</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($usuarios as $user): ?>
        <tr>
          <td><?= $user['documento'] ?></td>
          <td><?= htmlspecialchars($user['nombre'] . ' ' . $user['apellido']) ?></td>
          <td><?= htmlspecialchars($user['correo']) ?></td>
          <td><?= htmlspecialchars($user['rol']) ?></td>
          <td>
           <?php if ($rol_usuario_logueado == 1 || $rol_usuario_logueado == 2): ?>
              <form method="POST" action="/gestion_roles.php" class="d-flex">
                <input type="hidden" name="documento" value="<?= $user['documento'] ?>">
                <select name="id_rol" class="form-select me-2" required>
                  <option value="">Seleccione</option>
                  <?php foreach ($roles as $rol): ?>
                    <?php
                    // Si es Administrador, solo puede ver Residente (3) y Vigilante (4)
                    if ($rol_usuario_logueado == 2 && !in_array($rol['id_rol'], [3, 4])) continue;
                    // Si es Super Admin, puede ver todos los roles
                    ?>
                    <option value="<?= $rol['id_rol'] ?>"><?= htmlspecialchars($rol['rol']) ?></option>
                  <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Cambiar</button>
              </form>
            <?php else: ?>
              <span class="text-muted">Sin permisos</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
