<?php
session_start();
require_once __DIR__ . '/../../src/config/Database.php';
use App\Config\Database;

$db = new Database();
$conn = $db->getConnection();

// Traer todos los usuarios y sus roles
$query = "SELECT u.documento, u.nombre, u.apellido, u.correo, r.rol, u.id_rol 
          FROM usuarios u
          JOIN roles r ON u.id_rol = r.id_rol";
$stmt = $conn->prepare($query);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Roles</title>
  
</head>
<body class="container mt-5">
  <h3 class="mb-4">Gestión de Roles de Usuarios</h3>

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
            <form method="POST" action="/gestion_roles.php" class="d-flex">
              <input type="hidden" name="documento" value="<?= $user['documento'] ?>">
              <select name="id_rol" class="form-select me-2" required>
                <option value="">Seleccione</option>
                <option value="2">Administrador</option>
                <option value="3">Residente</option>
                <option value="4">Vigilante</option>
              </select>
              <button type="submit" class="btn btn-primary btn-sm">Cambiar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
