<?php

class EditarResidenteController {

    // Simulación de datos estáticos
    private $residentes = [
        ['id' => 101, 'nombre' => 'Sofia Enciso', 'contacto' => '3022927343', 'casa' => 'C - 3'],
        ['id' => 102, 'nombre' => 'Paula Garcia', 'contacto' => '3016849918', 'casa' => 'A - 5'],
        ['id' => 103, 'nombre' => 'Nicolas Mora', 'contacto' => '3177650234', 'casa' => 'G - 4'],
    ];

    public function index() {
        return $this->residentes;
    }

    public function show($id) {
        foreach ($this->residentes as $residente) {
            if ($residente['id'] == $id) {
                return $residente;
            }
        }
        return null;
    }

    public function update($id, $nuevoData) {
        // Simulación de actualización
        foreach ($this->residentes as &$residente) {
            if ($residente['id'] == $id) {
                $residente['nombre'] = $nuevoData['nombre'] ?? $residente['nombre'];
                $residente['contacto'] = $nuevoData['contacto'] ?? $residente['contacto'];
                $residente['casa'] = $nuevoData['casa'] ?? $residente['casa'];
                return $residente; // Retorna el residente actualizado
            }
        }
        return null;
    }

    public function delete($id) {
        foreach ($this->residentes as $key => $residente) {
            if ($residente['id'] == $id) {
                unset($this->residentes[$key]);
                return true;
            }
        }
        return false;
    }
}
