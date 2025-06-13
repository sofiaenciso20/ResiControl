<?php
 
class PersonaController {
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo_usuario = $_POST['tipo_usuario'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $telefono = $_POST['telefono'];
            $tipo_identificacion = $_POST['tipo_identificacion'];
            $numero_identificacion = $_POST['numero_identificacion'];
            $correo = $_POST['correo'];
            $contrasena = $_POST['contrasena'];
 
            // Lógica para guardar en la base de datos (simulada por ahora)
            $mensaje = "Usuario registrado correctamente.";
            return $mensaje;
        }
    }
}