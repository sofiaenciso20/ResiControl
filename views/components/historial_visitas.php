<div class="container min-vh-100 d-flex flex-column">
  <div class="card shadow-lg flex-grow-1">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h3 class="mb-0">Historial de Visitas</h3>
      <a href="exportar_excel.php" title="Exportar a Excel">
        <img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Excel_2013_logo.svg" alt="Exportar a Excel" width="30">
      </a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle text-center">
          <thead class="table-dark">
            <tr>
              <th>Fecha</th>
              <th>Visitante</th>
              <th>Residente</th>
              <th>Casa</th>
              <th>Motivo</th>
              <th>Hora</th>
              <th>Confirmada</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($visitas as $visita): ?>
              <tr>
                <td><?= htmlspecialchars(date("d/m/y", strtotime($visita['fecha_ingreso']))) ?></td>
                <td><?= htmlspecialchars($visita['visitante_nombre'] . ' ' . $visita['visitante_apellido']) ?></td>
                <td><?= htmlspecialchars($visita['residente_nombre'] . ' ' . $visita['residente_apellido']) ?></td>
                <td><?= htmlspecialchars($visita['direccion_casa']) ?></td>
                <td><?= htmlspecialchars($visita['motivo_visita']) ?></td>
                <td><?= date('g:i a', strtotime($visita['hora_ingreso'])) ?></td>
                <td>
                  <?php if (isset($visita['id_estado'])): ?>
                    <?php if ($visita['id_estado'] == 1): ?>
                      <span class="badge bg-warning text-dark">Pendiente</span>
                    <?php elseif ($visita['id_estado'] == 2): ?>
                      <span class="badge bg-success">Aprobada</span>
                    <?php else: ?>
                      <span class="badge bg-secondary">Desconocido</span>
                    <?php endif; ?>
                  <?php else: ?>
                    <span class="badge bg-secondary">Desconocido</span>
                  <?php endif; ?>
                </td>
                <td>
                  <div class="d-flex justify-content-center gap-2">
                    <a href="detalle_visita.php?id=<?= $visita['id_visita'] ?>" class="btn btn-sm btn-outline-secondary" title="Ver"><i class="bi bi-eye"></i></a>
                    <?php if (isset($visita['id_estado']) && $visita['id_estado'] == 1): ?>
                      <form method="POST" action="confirmar_visitas.php" class="d-inline">
                        <input type="hidden" name="id_visita" value="<?= $visita['id_visita'] ?>">
                        <button type="submit" class="btn btn-sm btn-success" title="Confirmar llegada">
                          <i class="bi bi-check-circle"></i> 
                        </button>
                      </form>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($visitas)): ?>
              <tr><td colspan="8" class="text-muted">No hay visitas registradas.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
 
 