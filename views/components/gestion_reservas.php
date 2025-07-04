<div class="container mt-5">
  <div class="card shadow-lg">
    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
      <h3 class="mb-0">Gestión de Reservas</h3>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-bordered text-center align-middle">
          <thead class="table-dark">
            <tr>
              <th>Zona</th>
              <th>Fecha</th>
              <th>Horario</th>
              <th>Residente</th>
              <th>Estado</th>
              <?php if ($_SESSION['user']['role'] == 2): ?>
                <th>Fecha Aprobación</th>
                <th>Administrador</th>
              <?php endif; ?>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservas as $reserva): ?>
              <tr>
                <td><?= htmlspecialchars($reserva['nombre_zona']) ?></td>
                <td><?= date('d/m/Y', strtotime($reserva['fecha'])) ?></td>
                <td><?= htmlspecialchars($reserva['horario']) ?></td>
                <td><?= htmlspecialchars($reserva['nombre_residente']) ?></td>
                <td>
                  <?php
                    if ($reserva['estado'] === 'Pendiente') {
                        $badgeClass = 'bg-warning';
                    } elseif ($reserva['estado'] === 'Aprobada') {
                        $badgeClass = 'bg-success';
                    } elseif ($reserva['estado'] === 'Rechazada') {
                        $badgeClass = 'bg-danger';
                    } else {
                        $badgeClass = 'bg-secondary';
                    }
 
                  ?>
                  <span class="badge <?= $badgeClass ?>"><?= $reserva['estado'] ?></span>
                </td>
                <?php if ($_SESSION['user']['role'] == 2): ?>
                  <td><?= $reserva['fecha_apro'] ? date('d/m/Y', strtotime($reserva['fecha_apro'])) : '-' ?></td>
                  <td><?= $reserva['nombre_administrador'] ?? '-' ?></td>
                <?php endif; ?>
                <td>
                  <div class="d-flex justify-content-center gap-2">
                    <a href="detalle_reserva.php?id=<?= $reserva['id_reservas'] ?>"
                       class="btn btn-sm btn-outline-secondary"
                       title="Ver Detalles">
                      <i class="bi bi-eye"></i>
                    </a>
                   
                    <?php if ($_SESSION['user']['role'] == 2 && $reserva['estado'] === 'Pendiente'): ?>
                      <a href="aprobar_reserva.php?id=<?= $reserva['id_reservas'] ?>"
                         class="btn btn-sm btn-outline-success"
                         title="Aprobar"
                         onclick="return confirm('¿Estás seguro de que deseas aprobar esta reserva?');">
                        <i class="bi bi-check-lg"></i>
                      </a>
                     
                      <a href="rechazar_reserva.php?id=<?= $reserva['id_reservas'] ?>"
                         class="btn btn-sm btn-outline-danger"
                         title="Rechazar"
                         onclick="return prompt('Por favor, ingrese el motivo del rechazo:') !== null;">
                        <i class="bi bi-x-lg"></i>
                      </a>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
 
 
 