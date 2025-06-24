<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Paquetes</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <h2>Historial de Paquetes Registrados</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Residente</th>
                <th>Vigilante</th>
                <th>Descripción</th>
                <th>Recepción</th>
                <th>Entrega</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($paquetes as $p): ?>
                <tr>
                    <td><?= $p['id_paquete'] ?></td>
                    <td><?= $p['nombre_residente'] . ' ' . $p['apellido_residente'] ?></td>
                    <td><?= $p['nombre_vigilante'] . ' ' . $p['apellido_vigilante'] ?></td>
                    <td><?= $p['descripcion'] ?></td>
                    <td><?= $p['fech_hor_recep'] ?></td>
                    <td><?= $p['fech_hor_entre'] ?? 'Pendiente' ?></td>
                    <td><?= $p['estado'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
