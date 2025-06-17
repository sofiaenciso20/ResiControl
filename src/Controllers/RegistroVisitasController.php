<?php

class RegistroVisitasController {
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $visitante = $_POST['nombre_visitante'];
            $residente = $_POST['residente'];
            $casa = $_POST['casa'];
            $motivo = $_POST['motivo'];
            $fecha = $_POST['fecha'];
            $hora = $_POST['hora_llegada'];

            // Simulación de guardado
            $respuesta = "<h3>Visita registrada correctamente</h3>";
            $respuesta .= "<p><strong>Visitante:</strong> $visitante</p>";
            $respuesta .= "<p><strong>Residente:</strong> $residente</p>";
            $respuesta .= "<p><strong>Casa:</strong> $casa</p>";
            $respuesta .= "<p><strong>Motivo:</strong> $motivo</p>";
            $respuesta .= "<p><strong>Fecha:</strong> $fecha</p>";
            $respuesta .= "<p><strong>Hora estimada de llegada:</strong> $hora</p>";
            $respuesta .= "<a href='../index.php'>Volver al inicio</a>";

            return $respuesta;
        } else {
            return "<h4 style='color: red;'>Acceso no permitido</h4>";
        }
    }

    public function index() {
        // Aquí puedes retornar visitas desde la base de datos o un array de prueba
        return [];
    }
}
