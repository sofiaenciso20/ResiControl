<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Registro de Terreno</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="/registro_terreno.php">
                        <div class="mb-3">
                            <label class="form-label">Tipo de Terreno</label>
                            <select class="form-select" name="tipo_terreno" id="tipo_terreno" required>
                                <option value="">Selecciona</option>
                                <option value="bloque">Bloque</option>
                                <option value="manzana">Manzana</option>
                            </select>
                        </div>
                        <div id="cantidad_apartamentos" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Cantidad de Apartamentos</label>
                                <input type="number" class="form-control" name="apartamentos" required>
                            </div>
                        </div>
                        <div id="cantidad_casas" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Cantidad de Casas</label>
                                <input type="number" class="form-control" name="casas" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Registrar Terreno</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('tipo_terreno').addEventListener('change', function() {
    const tipo = this.value;
    document.getElementById('cantidad_apartamentos').style.display = tipo === 'bloque' ? 'block' : 'none';
    document.getElementById('cantidad_casas').style.display = tipo === 'manzana' ? 'block' : 'none';
    
});
</script>