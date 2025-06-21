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
        'registro_zona',
        'registro_terreno',
        'historial_visitas',
        'gestion_roles',
        'gestion_residentes',
        'gestion_reservas'
    ],
    3 => [
        'registro_visita',
        'registro_reserva'
    ],
    4 => [
        'gestion_reservas',
        'gestion_residentes',
        'registro_paquete'
    ]
];
 
// Función para verificar permisos
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