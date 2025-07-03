<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de Paquetes</title>
 
</head>
<body class="bg-light py-5">
  <div class="container min-vh-100 d-flex flex-column">
    <div class="card shadow-lg flex-grow-1">
      <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Historial de Paquetes</h3>
        <?php if (in_array($_SESSION['user']['role'], [1, 2, 4])): ?>
        <a href="exportar_excel_paquetes.php" title="Exportar a Excel">
          <img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Excel_2013_logo.svg" alt="Exportar a Excel" width="30">
        </a>
        <?php endif; ?>
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
              <?php if (!empty($paquetes)): ?>
                <?php foreach ($paquetes as $p): ?>
                  <tr>
                    <td><?= $p['id_paquete'] ?></td>
                    <td><?= htmlspecialchars($p['nombre_residente'] . ' ' . $p['apellido_residente']) ?></td>
                    <td><?= htmlspecialchars($p['nombre_vigilante'] . ' ' . $p['apellido_vigilante']) ?></td>
                    <td><?= htmlspecialchars($p['descripcion']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($p['fech_hor_recep'])) ?></td>
                    <td><?= $p['fech_hor_entre'] ? date('d/m/Y H:i', strtotime($p['fech_hor_entre'])) : '<span class="badge bg-warning text-dark">Pendiente</span>' ?></td>
                    <td>
                      <?php if ($p['estado'] == 'Entregado'): ?>
                        <span class="badge bg-success"><?= htmlspecialchars($p['estado']) ?></span>
                      <?php elseif ($p['estado'] == 'Pendiente'): ?>
                        <span class="badge bg-warning text-dark"><?= htmlspecialchars($p['estado']) ?></span>
                      <?php else: ?>
                        <span class="badge bg-secondary"><?= htmlspecialchars($p['estado']) ?></span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <div class="d-flex justify-content-center gap-2">
                        <a href="detalle_paquete.php?id=<?= $p['id_paquete'] ?>" class="btn btn-sm btn-outline-info">
                          <i class="bi bi-eye"></i> Ver
                        </a>
                        <?php if (in_array($_SESSION['user']['role'], [1, 2, 4]) && $p['estado'] == 'Pendiente'): ?>
                          <form method="POST" action="entregar_paquete.php" class="d-inline">
                            <input type="hidden" name="id_paquete" value="<?= $p['id_paquete'] ?>">
                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('¿Confirmar la entrega del paquete?')">
                              <i class="bi bi-check-circle"></i> Entregar
                            </button>
                          </form>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="8" class="text-muted">
                    <?php if ($_SESSION['user']['role'] == 3): ?>
                      No tienes paquetes registrados.
                    <?php else: ?>
                      No hay paquetes registrados en el sistema.
                    <?php endif; ?>
                  </td>
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
 
 