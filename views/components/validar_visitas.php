<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h4 class="mb-0">Validar Código de Visita</h4>
        </div>
        <div class="card-body">
          <form method="POST" action="/validar_visitas.php">
            <div class="mb-3">
              <label class="form-label">Código de Verificación</label>
              <input type="text" name="codigo" class="form-control" placeholder="Ingresa el código" required>
            </div>
            <button type="submit" class="btn btn-success">Validar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
