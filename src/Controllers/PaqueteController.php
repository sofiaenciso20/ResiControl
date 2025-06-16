<?php

class PaqueteController {
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Captura de datos
            $casa = isset($_POST['casa']) ? htmlspecialchars(trim($_POST['casa'])) : '';
            $remitente = isset($_POST['remitente']) ? htmlspecialchars(trim($_POST['remitente'])) : '';
            $descripcion = isset($_POST['descripcion']) ? htmlspecialchars(trim($_POST['descripcion'])) : '';

            // Validación de campos obligatorios
            if (empty($casa) || empty($remitente)) {
                return "<h4 style='color: red;'>Error: Los campos 'Casa Destino' y 'Remitente' son obligatorios.</h4>
                        <a href='javascript:history.back()'>Volver al formulario</a>";
            }

            // Simulación de registro exitoso
            $mensaje = "<h3>Paquete Registrado Exitosamente</h3>";
            $mensaje .= "<p><strong>Casa Destino:</strong> $casa</p>";
            $mensaje .= "<p><strong>Remitente:</strong> $remitente</p>";
            $mensaje .= "<p><strong>Descripción:</strong> " . (!empty($descripcion) ? $descripcion : 'No especificada') . "</p>";
            $mensaje .= "<a href='../index.php'>Volver al inicio</a>";

            return $mensaje;
        } else {
            return "<h4 style='color: red;'>Acceso no permitido</h4>";
        }
    }
}
