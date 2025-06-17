
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Core</div>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'inicio' ? 'active' : ''; ?>" href="index.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Inicio
                    </a>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'Sobre Nosotros' ? 'active' : ''; ?>" href="sobre_nosotros.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Sobre Nosotros
                    </a>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'contactanos' ? 'active' : ''; ?>" href="contactanos.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        contactanos
                    </a>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'registro' ? 'active' : ''; ?>" href="/registro_persona.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                        Registro de Persona
                    </a>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'registro_zona' ? 'active' : ''; ?>" href="/registro_zona.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-map-marker-alt"></i></div>
                        Registro de Zona
                    </a>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'registro_terreno' ? 'active' : ''; ?>" href="/registro_terreno.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                        Registro de Terreno
                    </a>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'historial_visitas' ? 'active' : ''; ?>" href="/historial_visitas.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                        Historial de Visitas
                    </a>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'gestion_reservas' ? 'active' : ''; ?>" href="/gestion_reservas.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                        Gestion de Reservas
                    </a>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'gestion_residentes' ? 'active' : ''; ?>" href="/gestion_residentes.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                        Gestion de Residentes
                    </a>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'registro_paquete' ? 'active' : ''; ?>" href="/registro_paquete.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                        Registro de Paquete
                    </a>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'registro_visitas' ? 'active' : ''; ?>" href="/registro_visita.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                        Registro de Visitas
                    </a>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'validar_visitas' ? 'active' : ''; ?>" href="/validar_visitas.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                        Validar Visitas
                    </a>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'registro_reservas' ? 'active' : ''; ?>" href="/registro_reserva.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                        Registro de Reservas
                    </a>
                    </div>
                    
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Estado:</div>
                <?php echo (!empty($_SESSION['is_logged_in']) && !empty($_SESSION['user'])) ? htmlspecialchars($_SESSION['user']['name']) : 'No hay sesión activa'; ?>
            </div>
        </nav>
    </div>
    