<?php
// Mapeo de roles
$roles = [
    1 => 'Super Admin',
    2 => 'Administrador',
    3 => 'Residente',
    4 => 'Vigilante'
];
 
// Permisos por rol
$role_permissions = [
    1 => ['*'], // Super Admin: acceso total
    2 => [
        'registro_persona',
        'registro_terreno',
        'historial_visitas',
        'gestion_roles',
        'gestion_residentes',
        'historial_paquetes',
        'gestion_reservas'
    ],
    3 => [
        'validar_visitas',
        'registro_reserva',
        'registro_visita',
        'historial_visitas',
        'historial_paquetes',
        'gestion_reservas',
        'registro_persona'
        
    ],
    4 => [
        'historial_visitas',
        'gestion_reservas',
        'gestion_residentes',
        'historial_paquetes',
        'registro_paquete'
    ]
];
 
// Función para verificar permisos
//la pagina que necesitamos acceder
function tienePermiso($pagina) {
    if (!isset($_SESSION['user'])) return false;
    $rol = $_SESSION['user']['role'];
    global $role_permissions;
    // Super Admin tiene acceso a todo
    if (in_array('*', $role_permissions[$rol])) {
        return true;
    }
    return in_array($pagina, $role_permissions[$rol]);
}