<div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow-lg border-0">
          <div class="card-header bg-primary text-white text-center">
            <h3 class="mb-0">Registro de Terreno</h3>
          </div>
          <div class="card-body">
            <?php if (!empty($mensaje)): ?>
              <div class="alert alert-info text-center"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>

            <form method="POST" action="/registro_terreno.php">
              <div class="mb-3">
                <label class="form-label">Tipo de Terreno</label>
                <select class="form-select" name="tipo_terreno" id="tipo_terreno" required>
                  <option value="">Selecciona</option>
                  <option value="bloque">Bloque</option>
                  <option value="manzana">Manzana</option>
                </select>
              </div>

              <div id="cantidad_apartamentos" class="mb-3" style="display: none;">
                <label class="form-label">Cantidad de Apartamentos</label>
                <input type="number" class="form-control" name="apartamentos" id="apartamentos">
              </div>

              <div id="cantidad_casas" class="mb-3" style="display: none;">
                <label class="form-label">Cantidad de Casas</label>
                <input type="number" class="form-control" name="casas" id="casas">
              </div>

              <div class="text-end">
                <button type="submit" class="btn btn-success">Registrar Terreno</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Script para mostrar campos dinámicos -->
  <script>
    document.getElementById('tipo_terreno').addEventListener('change', function () {
      const tipo = this.value;
      document.getElementById('cantidad_apartamentos').style.display = tipo === 'bloque' ? 'block' : 'none';
      document.getElementById('cantidad_casas').style.display = tipo === 'manzana' ? 'block' : 'none';
    });
  </script>