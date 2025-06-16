<?php
// VisitasController.php
// Versión sin conexión a base de datos. Simula operaciones de CRUD.

class VisitasController {

    private $visitas = [];

    public function __construct() {
        // Simulación de datos cargados (puedes eliminar este bloque si usarás BD)
        $this->visitas = [
            [
                'id' => 1,
                'fecha' => '2025-06-05',
                'visitante' => 'Luis Mora',
                'residente' => 'Sofía Enciso',
                'casa' => 'C - 3'
            ],
            [
                'id' => 2,
                'fecha' => '2025-06-07',
                'visitante' => 'Juan Vallejo',
                'residente' => 'Paula García',
                'casa' => 'A - 5'
            ]
        ];
    }

    // Listar todas las visitas
    public function index() {
        return $this->visitas;
    }

    // Registrar nueva visita
    public function store($data) {
        // Aquí deberías guardar en la BD, pero ahora solo simulamos
        $nueva = [
            'id' => count($this->visitas) + 1,
            'fecha' => $data['fecha'],
            'visitante' => $data['visitante'],
            'residente' => $data['residente'],
            'casa' => $data['casa']
        ];
        $this->visitas[] = $nueva;
        return true;
    }

    // Obtener una visita por ID
    public function show($id) {
        foreach ($this->visitas as $visita) {
            if ($visita['id'] == $id) {
                return $visita;
            }
        }
        return null;
    }

    // Actualizar una visita
    public function update($id, $data) {
        foreach ($this->visitas as &$visita) {
            if ($visita['id'] == $id) {
                $visita['fecha'] = $data['fecha'];
                $visita['visitante'] = $data['visitante'];
                $visita['residente'] = $data['residente'];
                $visita['casa'] = $data['casa'];
                return true;
            }
        }
        return false;
    }

    // Eliminar una visita
    public function delete($id) {
        foreach ($this->visitas as $index => $visita) {
            if ($visita['id'] == $id) {
                unset($this->visitas[$index]);
                return true;
            }
        }
        return false;
    }
}
?>
