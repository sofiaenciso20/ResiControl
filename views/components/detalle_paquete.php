<div class="card shadow-lg  w-100 " style="background-color: #fff;">
  <div class="card-header  text-white d-flex align-items-center">
    <i class="bi bi-box-seam me-2" style="font-size: 1.5rem;"></i>
    <h4 class="mb-0">Detalle del Paquete</h4>
  </div>
  <div class="card-body">
    <?php if (empty($paquete)): ?>
      <div class="alert alert-danger">No se encontró información del paquete.</div>
    <?php else: ?>
      <ul class="list-group list-group-flush mb-4">
        <li class="list-group-item"><strong>ID:</strong> <?= htmlspecialchars($paquete['id_paquete']) ?></li>
        <li class="list-group-item"><strong>Residente:</strong> <span class="badge bg-info text-dark"><?= htmlspecialchars($paquete['nombre_residente'] . ' ' . $paquete['apellido_residente']) ?></span></li>
        <li class="list-group-item"><strong>Vigilante:</strong> <span class="badge bg-secondary"><?= htmlspecialchars($paquete['nombre_vigilante'] . ' ' . $paquete['apellido_vigilante']) ?></span></li>
        <li class="list-group-item"><strong>Descripción:</strong> <?= htmlspecialchars($paquete['descripcion']) ?></li>
        <li class="list-group-item"><strong>Fecha y Hora de Recepción:</strong> <span class="text-success"><?= htmlspecialchars($paquete['fech_hor_recep']) ?></span></li>
        <li class="list-group-item"><strong>Fecha y Hora de Entrega:</strong>
          <?= $paquete['fech_hor_entre'] ? '<span class="text-success">' . htmlspecialchars($paquete['fech_hor_entre']) . '</span>' : '<span class="badge bg-warning text-dark">Pendiente</span>' ?>
        </li>
        <li class="list-group-item">
          <strong>Estado:</strong>
          <?php
          $estado = htmlspecialchars($paquete['estado']);
          $badge = 'bg-secondary';
          if (stripos($estado, 'pendiente') !== false) $badge = 'bg-warning text-dark';
          if (stripos($estado, 'entregado') !== false) $badge = 'bg-success';
          if (stripos($estado, 'recibido') !== false) $badge = 'bg-primary';
          ?>
          <span class="badge <?= $badge ?>"><?= $estado ?></span>
          <?php if (stripos($estado, 'pendiente') !== false): ?>
            <!-- Botón Entregado (ya existe) -->
            <form method="POST" class="d-inline ms-3">
              <input type="hidden" name="id_paquete" value="<?= htmlspecialchars($paquete['id_paquete']) ?>">
              <button type="submit" name="marcar_entregado" class="btn btn-success btn-sm">Marcar como Entregado</button>
            </form>
            <!-- Botón Rechazado -->
            <form method="POST" class="d-inline ms-2">
              <input type="hidden" name="id_paquete" value="<?= htmlspecialchars($paquete['id_paquete']) ?>">
              <button type="submit" name="marcar_rechazado" class="btn btn-danger btn-sm">Marcar como Rechazado</button>
            </form>
          <?php endif; ?>
        </li>
      </ul>
      <a href="/historial_paquetes.php" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left"></i> Volver al Historial
      </a>
    <?php endif; ?>
  </div>
</div>