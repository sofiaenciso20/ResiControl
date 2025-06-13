<?php
 
class ZonaController {
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $tipo_zona = $_POST['tipo_zona'];
            $motivo_zona = $_POST['motivo_zona'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];
 
            // Lógica para guardar en la base de datos (simulada por ahora)
            $mensaje = "Zona registrada correctamente.";
            return $mensaje;
        }
    }
}