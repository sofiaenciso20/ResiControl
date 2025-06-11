<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Mi Proyecto</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'inicio' ? 'active' : ''; ?>" href="/">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'acerca' ? 'active' : ''; ?>" href="/sobre_nosotros.php">Sobre Nosotros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'contacto' ? 'active' : ''; ?>" href="/contacto">Contacto</a>
                </li>
            </ul>
        </div>
    </div>
</nav>