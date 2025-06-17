<?php

class ValidarVisitasController {
    public function index() {
        // Retorna un array vacío o datos simulados
        return [];
    }

    public function validar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $codigo = trim($_POST['codigo']);

            // Códigos válidos simulados (podrías remplazarlos por una consulta en BD después)
            $codigos_validos = ['ABC123', 'VISITA456', 'INVITADO789'];

            if (in_array($codigo, $codigos_validos)) {
                return "<h3 style='color: green;'>✅ Código válido. Acceso autorizado.</h3>
                        <a href='../index.php'>Volver al inicio</a>";
            } else {
                return "<h3 style='color: red;'>❌ Código inválido. Verifica e intenta nuevamente.</h3>
                        <a href='javascript:history.back()'>Volver</a>";
            }
        } else {
            return "<h4 style='color: red;'>Acceso no permitido.</h4>";
        }
    }
}
