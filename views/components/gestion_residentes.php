<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h4 class="mb-0">Gestión de Residentes</h4>
          <a href="exportar_excel.php">
            <img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Excel_2013_logo.svg" alt="Exportar a Excel" width="30">
          </a>
        </div>
        <div class="card-body">
          <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Contacto</th>
                <th>Casa</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <!-- Ejemplo de registro, reemplázalo por PHP si usas el controlador -->
              <tr>
                <td>101</td>
                <td>Sofía Enciso</td>
                <td>3022927343</td>
                <td>C - 3</td>
                <td>
                  <a href="ver_residente.php?id=101" class="btn btn-sm btn-outline-secondary me-1" title="Ver"><i class="bi bi-eye"></i></a>
                  <a href="editar_residente.php?id=101" class="btn btn-sm btn-outline-primary me-1" title="Editar"><i class="bi bi-pencil"></i></a>
                  <a href="eliminar_residente.php?id=101" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Eliminar este residente?');"><i class="bi bi-x"></i></a>
                </td>
              </tr>
              <!-- Fin del ejemplo -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
