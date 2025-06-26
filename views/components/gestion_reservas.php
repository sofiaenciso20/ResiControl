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
                      <div class="d-flex justify-content-center gap-2">
                        <a href="ver_reserva.php?id=<?= $reserva['id_reservas'] ?>" class="btn btn-sm btn-outline-secondary" title="Ver">
                          <i class="bi bi-eye"></i>
                        </a>
                        <a href="editar_reserva.php?id=<?= $reserva['id_reservas'] ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                          <i class="bi bi-pencil"></i>
                        </a>
                        <a href="eliminar_reserva.php?id=<?= $reserva['id_reservas'] ?>" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar esta reserva?');">
                          <i class="bi bi-x-lg"></i>
                        </a>
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
