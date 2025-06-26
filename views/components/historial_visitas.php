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
                  <div class="d-flex justify-content-center gap-2">
                    <a href="ver_visita.php?id=<?= $visita['id_visita'] ?>" class="btn btn-sm btn-outline-secondary" title="Ver"><i class="bi bi-eye"></i></a>
                    <a href="editar_visita.php?id=<?= $visita['id_visita'] ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                    <a href="eliminar_visita.php?id=<?= $visita['id_visita'] ?>" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar esta visita?');"><i class="bi bi-x-lg"></i></a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($visitas)): ?>
              <tr><td colspan="7" class="text-muted">No hay visitas registradas.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
