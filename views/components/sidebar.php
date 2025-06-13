
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
                    </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Estado:</div>
                <?php echo (!empty($_SESSION['is_logged_in']) && !empty($_SESSION['user'])) ? htmlspecialchars($_SESSION['user']['name']) : 'No hay sesión activa'; ?>
            </div>
        </nav>
    </div>
    