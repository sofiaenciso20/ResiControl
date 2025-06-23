<?php
// Verifica si la sesión está iniciada, si no no va tener acceso
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../src/Config/permissions.php';
?>
<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link <?php echo ($pagina_actual ?? '') === 'inicio' ? 'active' : ''; ?>" href="index.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Inicio
                </a>
                <a class="nav-link <?php echo ($pagina_actual ?? '') === 'Sobre Nosotros' ? 'active' : ''; ?>"
                    href="sobre_nosotros.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Sobre Nosotros
                </a>
                <a class="nav-link <?php echo ($pagina_actual ?? '') === 'contactanos' ? 'active' : ''; ?>"
                    href="contactanos.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    contactanos
                </a>
                <a class="nav-link <?php echo ($pagina_actual ?? '') === 'dashboard' ? 'active' : ''; ?>"
                    href="/dashboard.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                    Dashboard
                </a>
                <?php if (tienePermiso('registro_persona')): ?>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'registro' ? 'active' : ''; ?>"
                        href="/registro_persona.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                        Registro de Persona
                    </a>
                <?php endif; ?>
                <?php if (tienePermiso('registro_zona')): ?>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'registro' ? 'active' : ''; ?>"
                        href="/registro_zona.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                        Registro de zona
                    </a>
                <?php endif; ?>
                <?php if (tienePermiso('registro_terreno')): ?>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'registro' ? 'active' : ''; ?>"
                        href="/registro_terreno.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                        Registro de Terreno
                    </a>
                <?php endif; ?>
                <?php if (tienePermiso('historial_visitas')): ?>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'registro' ? 'active' : ''; ?>"
                        href="/historial_visitas.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                        Historial de Visitas
                    </a>
                <?php endif; ?>
                <?php if (tienePermiso('gestion_reservas')): ?>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'registro' ? 'active' : ''; ?>"
                        href="/gestion_reservas.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                        Gestion de Reservas
                    </a>
                <?php endif; ?>
                <?php if (tienePermiso('gestion_residentes')): ?>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'registro' ? 'active' : ''; ?>"
                        href="/gestion_residentes.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                        Gestion de Residentes
                    </a>
                <?php endif; ?>
                <?php if (tienePermiso('registro_paquete')): ?>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'registro' ? 'active' : ''; ?>"
                        href="/registro_paquete.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                        Registro de Paquete
                    </a>
                <?php endif; ?>
                <?php if (tienePermiso('registro_visitas')): ?>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'registro' ? 'active' : ''; ?>"
                        href="/registro_visitas.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                        Registro de Visitas
                    </a>
                <?php endif; ?>
                <?php if (tienePermiso('validar_visitas')): ?>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'registro' ? 'active' : ''; ?>"
                        href="/validar_visitas.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                        Validar Visitas
                    </a>
                <?php endif; ?>
                <?php if (tienePermiso('registro_reservas')): ?>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'registro' ? 'active' : ''; ?>"
                        href="/registro_reservas.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                        Registro de Reservas
                    </a>
                <?php endif; ?>
                <?php if (tienePermiso('gestion_roles')): ?>
                    <a class="nav-link <?php echo ($pagina_actual ?? '') === 'registro' ? 'active' : ''; ?>"
                        href="/gestion_roless.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                        Gestion de Roles
                    </a>
                <?php endif; ?>
            </div>

        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Estado:</div>
            <?php echo (!empty($_SESSION['is_logged_in']) && !empty($_SESSION['user'])) ? htmlspecialchars($_SESSION['user']['name']) : 'No hay sesión activa'; ?>
        </div>
    </nav>
</div>