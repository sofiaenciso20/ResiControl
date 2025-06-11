<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
        // Título de la página
        echo $titulo ?? 'Mi Proyecto PHP';

        ?>
    </title>

    <!-- CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <!-- JavaScript de Bootstrap (requiere Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <?php require_once __DIR__ . '/../components/navbar.php'; ?>
    
    <main class="container mt-4 flex-grow-1">
        <?php echo $contenido ?? '';?>
    </main>
    <!-- Footer -->
    <?php
    require_once __DIR__ . '/../components/footer.php';

    ?>
</body>

</html>