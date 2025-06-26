<div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8">
        <div class="card shadow-lg border-0">
          <div class="card-header bg-primary text-white text-center">
            <h3 class="mb-0">Validar Código de Visita</h3>
          </div>
          <div class="card-body">
            <form method="POST" action="../validar_visitas.php"> <!-- Corrige la ruta si es necesario -->
              <div class="mb-3">
                <label class="form-label">Código de Verificación</label>
                <input type="text" name="codigo" class="form-control" placeholder="Ingresa el código" required>
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-success">Validar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>