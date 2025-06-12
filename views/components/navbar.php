<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-2">
  <div class="container">
    <!-- Logo con imagen -->
    <a class="navbar-brand d-flex align-items-center" href="/">
      <img src="assets/img/logo2.jpg" alt="ResiControl Logo" width="35" height="35" class="me-2">
      
    </a>

    <!-- Botón responsive -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navegación -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto me-3">
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

      <!-- Botón Iniciar Sesión -->
      <a href="/login.php" class="btn btn-outline-light btn-sm d-flex align-items-center">
        <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
      </a>
    </div>
  </div>
</nav>
