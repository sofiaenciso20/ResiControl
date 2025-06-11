<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ResiControl - Recuperar Contraseña</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">

</head>
<body class="body-recuperar">
<div class="login-wrapper">
    <div class="login-card">
        <div class="login-image">
            <div class="logo-superior">
                <img src="assets/img/logo.png" alt="Logo ResiControl" style="height: 50px;">
            </div>
        </div>
        <div class="login-form">
            <div class="text-center">
                <h4>Recuperar contraseña</h4>
                <p class="text-muted">Ingresa tu correo para recibir el enlace de recuperación</p>
            </div>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="correo" class="form-control" placeholder="tu@correo.com" required>
                </div>
                
                <button type="submit" class="btn btn-login">Enviar enlace</button>
            </form>

            <?php if (isset($mensaje)): ?>
                <div class="alert alert-info mt-3">
                    <?= $mensaje ?>
                </div>
            <?php endif; ?>
            
            <div class="text-center mt-3">
                <a href="login.php">← Volver al login</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>