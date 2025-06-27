<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de la Reserva</title>
</head>
<body class="container mt-5">
<div class="card">
    <div class="card-header bg-primary text-white">
        <h3>Detalle de Reserva</h3>
    </div>
    <div class="card-body">
        <?php if ($mensaje): ?>
            <div class="alert alert-success"><?= $mensaje ?></div>
        <?php endif; ?>

        <?php if (!$reserva): ?>
            <div class="alert alert-danger">No se encontró la reserva.</div>
        <?php elseif ($modo_edicion): ?>
            <form method="POST">
                <div class="mb-2">
                    <label>Fecha:</label>
                    <input type="date" name="fecha" class="form-control" value="<?= htmlspecialchars($reserva['fecha']) ?>" required>
                </div>
                <div class="mb-2">
                    <label>Horario:</label>
                    <select name="id_horario" class="form-select">
                        <option value="1" <?= $reserva['horario'] == '08:00 - 10:00' ? 'selected' : '' ?>>08:00 - 10:00</option>
                        <option value="2" <?= $reserva['horario'] == '10:00 - 12:00' ? 'selected' : '' ?>>10:00 - 12:00</option>
                        <option value="3" <?= $reserva['horario'] == '14:00 - 16:00' ? 'selected' : '' ?>>14:00 - 16:00</option>
                        <option value="4" <?= $reserva['horario'] == '16:00 - 18:00' ? 'selected' : '' ?>>16:00 - 18:00</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Motivo:</label>
                    <select name="id_mot_zonas" class="form-select">
                        <option value="1" <?= $reserva['motivo_zonas'] == 'Cumpleaños' ? 'selected' : '' ?>>Cumpleaños</option>
                        <option value="2" <?= $reserva['motivo_zonas'] == 'Reunión Familiar' ? 'selected' : '' ?>>Reunión Familiar</option>
                        <option value="3" <?= $reserva['motivo_zonas'] == 'Evento Comunitario' ? 'selected' : '' ?>>Evento Comunitario</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label>Observaciones:</label>
                    <textarea name="observaciones" class="form-control"><?= htmlspecialchars($reserva['observaciones']) ?></textarea>
                </div>
                <button type="submit" class="btn btn-success">Guardar</button>
                <a href="detalle_reserva.php?id=<?= urlencode($id) ?>" class="btn btn-secondary">Cancelar</a>
            </form>
        <?php else: ?>
            <ul class="list-group">
                <li class="list-group-item"><strong>Zona:</strong> <?= htmlspecialchars($reserva['zona']) ?></li>
                <li class="list-group-item"><strong>Fecha:</strong> <?= htmlspecialchars($reserva['fecha']) ?></li>
                <li class="list-group-item"><strong>Horario:</strong> <?= htmlspecialchars($reserva['horario']) ?></li>
                <li class="list-group-item"><strong>Motivo:</strong> <?= htmlspecialchars($reserva['motivo_zonas']) ?></li>
                <li class="list-group-item"><strong>Residente:</strong> <?= htmlspecialchars($reserva['nombre_residente'] . ' ' . $reserva['apellido_residente']) ?></li>
                <li class="list-group-item"><strong>Observaciones:</strong> <?= htmlspecialchars($reserva['observaciones']) ?></li>
            </ul>
            <?php if (in_array($_SESSION['user']['role'], [1, 2])): ?>
                <a href="detalle_reserva.php?id=<?= urlencode($id) ?>&editar=1" class="btn btn-primary mt-3">Editar</a>
            <?php endif; ?>
            <a href="gestion_reservas.php" class="btn btn-outline-primary mt-3">Volver</a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
