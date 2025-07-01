<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card shadow-lg">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
          <h3 class="mb-0">Gestión de Reservas</h3>
          <a href="exportar_reservas.php" title="Exportar a Excel">
            <img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Excel_2013_logo.svg" alt="Exportar a Excel" width="30">
          </a>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
              <thead class="table-dark">
                <tr>
                  <th>Zona</th>
                  <th>Fecha</th>
                  <th>Horario</th>
                  <th>Residente</th>
                  <th>Estado</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($reservas as $reserva): ?>
                  <tr>
                    <td><?= htmlspecialchars($reserva['zona']) ?></td>
                    <td><?= date('d/m/Y', strtotime($reserva['fecha'])) ?></td>
                    <td><?= htmlspecialchars($reserva['horario']) ?></td>
                    <td><?= htmlspecialchars($reserva['residente']) ?></td>
                    <td>
                      <?php
                        $estadoTexto = '';
                        $badgeClass = '';
                        switch($reserva['id_estado']) {
                          case 1:
                            $estadoTexto = 'Pendiente';
                            $badgeClass = 'bg-warning';
                            break;
                          case 2:
                            $estadoTexto = 'Aprobada';
                            $badgeClass = 'bg-success';
                            break;
                          case 3:
                            $estadoTexto = 'Rechazada';
                            $badgeClass = 'bg-danger';
                            break;
                          default:
                            $estadoTexto = 'Desconocido';
                            $badgeClass = 'bg-secondary';
                        }
                      ?>
                      <span class="badge <?= $badgeClass ?>">
                        <?= $estadoTexto ?>
                      </span>
                    </td>
                    <td>
                      <div class="d-flex justify-content-center gap-2">
                        <a href="detalle_reserva.php?id=<?= $reserva['id_reservas'] ?>" class="btn btn-sm btn-outline-secondary" title="Ver">
                          <i class="bi bi-eye"></i>
                        </a>
                        <?php if ($reserva['id_estado'] == 1): ?>
                          <form method="POST" action="aprobar_reserva.php" class="d-inline" style="margin:0">
                            <input type="hidden" name="id_reserva" value="<?= $reserva['id_reservas'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-success" title="Aprobar">
                              <i class="bi bi-check-lg"></i>
                            </button>
                          </form>
                          <form method="POST" action="rechazar_reserva.php" class="d-inline" style="margin:0">
                            <input type="hidden" name="id_reserva" value="<?= $reserva['id_reservas'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Rechazar">
                              <i class="bi bi-x-lg"></i>
                            </button>
                          </form>
                        <?php endif; ?>
                        <?php if ($reserva['id_estado'] != 1): ?>
                          <a href="eliminar_reserva.php?id=<?= $reserva['id_reservas'] ?>" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar esta reserva?');">
                            <i class="bi bi-trash"></i>
                          </a>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div> <!-- /.table-responsive -->
        </div> <!-- /.card-body -->
      </div> <!-- /.card -->
    </div> <!-- /.col -->
  </div> <!-- /.row -->
</div>
 
 