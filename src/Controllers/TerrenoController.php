<?php
 
class TerrenoController {
    public function registrar(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo_terreno = $_POST['tipo_terreno'] ?? '';
            $cantidad_apartamentos = $_POST['cantidad_apartamentos'] ?? 0;
            $cantidad_casas = $_POST['cantidad_casas'] ?? 0;
 
            // Aquí iría la lógica para guardar en la base de datos
            // Por ahora solo mostraremos un mensaje de éxito
            return "Terreno registrado exitosamente";
        }
        return"";
    }
}