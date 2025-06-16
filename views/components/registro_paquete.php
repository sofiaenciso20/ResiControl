<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Registro de Paquete</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="controllers/registro_paquete.php">
                        <div class="mb-3">
                            <label class="form-label">Casa Destino</label>
                            <input type="text" name="casa" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Remitente</label>
                            <input type="text" name="remitente" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción del Paquete</label>
                            <input type="text" name="descripcion" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Registrar Paquete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
