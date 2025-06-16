<?php
// ResidentesController.php

class ResidentesController {
    private $residentes = [];

    public function __construct() {
        // Datos de ejemplo (puedes modificarlos o agregar más)
        $this->residentes = [
            [
                'id' => 101,
                'nombre' => 'Sofía Enciso',
                'contacto' => '3022927343',
                'casa' => 'C - 3'
            ],
            [
                'id' => 102,
                'nombre' => 'Paula García',
                'contacto' => '3016849918',
                'casa' => 'A - 5'
            ],
            [
                'id' => 103,
                'nombre' => 'Nicolás Mora',
                'contacto' => '3177650234',
                'casa' => 'G - 4'
            ]
        ];
    }

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

    public function store($data) {
        $nuevo_id = end($this->residentes)['id'] + 1;
        $data['id'] = $nuevo_id;
        $this->residentes[] = $data;
        return $data;
    }

    public function update($id, $data) {
        foreach ($this->residentes as &$residente) {
            if ($residente['id'] == $id) {
                $residente = array_merge($residente, $data);
                return $residente;
            }
        }
        return null;
    }

    public function delete($id) {
        foreach ($this->residentes as $i => $residente) {
            if ($residente['id'] == $id) {
                unset($this->residentes[$i]);
                return true;
            }
        }
        return false;
    }
}
