<div class="container mt-5">
  <div class="card shadow-lg">
    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
      <h3 class="mb-0">Gestión de Personas</h3>
      <a href="exportar_excel.php" title="Exportar a Excel">
        <img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Excel_2013_logo.svg" alt="Exportar a Excel" width="30">
      </a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-bordered text-center align-middle">
          <thead class="table-dark">
            <tr>
              <th>Documento</th>
              <th>Nombre</th>
              <th>Teléfono</th>
              <th>Dirección</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($visitas as $residente): ?>
              <tr>
                <td><?= $residente['documento'] ?></td>
                <td><?= htmlspecialchars($residente['nombre']) ?></td>
                <td><?= $residente['telefono'] ?></td>
                <td><?= htmlspecialchars($residente['direccion_casa']) ?></td>
                <td>
                  <?php if ($residente['id_estado_usuario'] == 4): ?>
                    <span class="badge bg-success">Activo</span>
                  <?php else: ?>
                    <span class="badge bg-secondary">Inactivo</span>
                  <?php endif; ?>
                </td>
                <td>
                  <div class="d-flex justify-content-center gap-2">
                    <a href="detalle_persona.php?id=<?= $residente['documento'] ?>" class="btn btn-sm btn-outline-secondary" title="Ver">
                      <i class="bi bi-eye"></i>
                    </a>
                    <a href="inhabilitar_usuario.php?id=<?= $residente['documento'] ?>"
                        class="btn btn-sm btn-outline-warning"
                        title="Inhabilitar"
                        onclick="return confirm('¿Estás seguro de que deseas inhabilitar este usuario?');">
                        <i class="bi bi-person-x"></i>
                    </a>
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
