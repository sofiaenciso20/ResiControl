<?php
// ReservasController.php

class ReservasController {
    private $reservas = [];

    public function __construct() {
        // Datos simulados (puedes quitarlos o modificarlos)
        $this->reservas = [
            [
                'id' => 1,
                'zona' => 'BBQ',
                'fecha' => '2025-06-05',
                'horario' => '6:00 pm a 8:00 pm',
                'residente' => 'Sofía Enciso'
            ],
            [
                'id' => 2,
                'zona' => 'Salón Comunal',
                'fecha' => '2025-06-07',
                'horario' => '8:00 am a 10:00 am',
                'residente' => 'Paula García'
            ]
        ];
    }

    public function index() {
        return $this->reservas;
    }

    public function show($id) {
        foreach ($this->reservas as $reserva) {
            if ($reserva['id'] == $id) {
                return $reserva;
            }
        }
        return null;
    }

    public function store($data) {
        $nuevo_id = count($this->reservas) + 1;
        $data['id'] = $nuevo_id;
        $this->reservas[] = $data;
        return $data;
    }

    public function update($id, $data) {
        foreach ($this->reservas as &$reserva) {
            if ($reserva['id'] == $id) {
                $reserva = array_merge($reserva, $data);
                return $reserva;
            }
        }
        return null;
    }

    public function delete($id) {
        foreach ($this->reservas as $index => $reserva) {
            if ($reserva['id'] == $id) {
                unset($this->reservas[$index]);
                return true;
            }
        }
        return false;
    }
}
