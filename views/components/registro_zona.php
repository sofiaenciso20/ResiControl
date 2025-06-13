<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Registro de Zona Pública</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="/registro_zona.php">
                        <div class="mb-3">
                            <label class="form-label">Nombre de la Zona</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo de Zona</label>
                            <select class="form-select" name="tipo_zona" required>
                                <option value="">Selecciona</option>
                                <option value="salon">Salón</option>
                                <option value="piscina">Piscina</option>
                                <option value="gimnasio">Gimnasio</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Motivo de la Zona</label>
                            <textarea class="form-control" name="motivo_zona" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hora de Inicio</label>
                            <input type="time" class="form-control" name="hora_inicio" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hora de Fin</label>
                            <input type="time" class="form-control" name="hora_fin" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Registrar Zona</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
