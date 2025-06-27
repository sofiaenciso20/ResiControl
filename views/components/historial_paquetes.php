<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de Paquetes</title>
  
</head>
<body class="bg-light py-5">
  <div class="container">
    <div class="card shadow-lg">
      <div class="card-header bg-primary text-white text-center">
        <h3 class="mb-0">Historial de Paquetes Registrados</h3>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover table-bordered align-middle text-center">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Residente</th>
                <th>Vigilante</th>
                <th>Descripción</th>
                <th>Recepción</th>
                <th>Entrega</th>
                <th>Estado</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($paquetes as $p): ?>
                <tr>
                  <td><?= $p['id_paquete'] ?></td>
                  <td><?= htmlspecialchars($p['nombre_residente'] . ' ' . $p['apellido_residente']) ?></td>
                  <td><?= htmlspecialchars($p['nombre_vigilante'] . ' ' . $p['apellido_vigilante']) ?></td>
                  <td><?= htmlspecialchars($p['descripcion']) ?></td>
                  <td><?= date('d/m/Y H:i', strtotime($p['fech_hor_recep'])) ?></td>
                  <td><?= $p['fech_hor_entre'] ? date('d/m/Y H:i', strtotime($p['fech_hor_entre'])) : 'Pendiente' ?></td>
                  <td><?= htmlspecialchars($p['estado']) ?></td>
                  <td>
                    <form method="GET" action="/detalle_paquete.php" class="d-inline">
                      <input type="hidden" name="id_paquete" value="<?= $p['id_paquete'] ?>">
                      <button type="submit" class="btn btn-sm btn-outline-info">Ver Detalle</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
              <?php if (empty($paquetes)): ?>
                <tr>
                  <td colspan="8" class="text-muted">No hay paquetes registrados.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>


</body>
</html>
