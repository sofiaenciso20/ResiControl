<?php

class RegistroReservaController {
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $zona = trim($_POST['zona']);
            $fecha = trim($_POST['fecha']);
            $horario = trim($_POST['horario']);
            $residente = trim($_POST['residente']);

            // Validación simple
            if (empty($zona) || empty($fecha) || empty($horario) || empty($residente)) {
                return "<h4 style='color: red;'>Todos los campos son obligatorios.</h4>
                        <a href='javascript:history.back()'>Volver</a>";
            }

            // Simulación de éxito
            return "<h3>✅ Reserva registrada con éxito</h3>
                    <ul>
                      <li><strong>Zona:</strong> $zona</li>
                      <li><strong>Fecha:</strong> $fecha</li>
                      <li><strong>Horario:</strong> $horario</li>
                      <li><strong>Residente:</strong> $residente</li>
                    </ul>
                    <a href='../index.php'>Volver al inicio</a>";
        } else {
            return "<h4 style='color: red;'>Acceso no permitido</h4>";
        }
    }

    public function index() {
        // Retorna un array vacío o datos simulados
        return [];
    }
}
