<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h4 class="mb-0">Historial de Visitas</h4>
          <a href="exportar_excel.php">
            <img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Excel_2013_logo.svg" alt="Exportar a Excel" width="30">
          </a>
        </div>
        <div class="card-body">
          <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
              <tr>
                <th>Fecha</th>
                <th>Visitante</th>
                <th>Residente</th>
                <th>Casa</th>
                <th>Motivo</th>
                <th>Hora</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              <!-- Aquí se insertarán dinámicamente los registros -->
              <tr>
                <td>07/06/25</td>
                <td>Juan Vallejo</td>
                <td>Paula García</td>
                <td>A - 5</td>
                <td>Entrega de documentos</td>
                <td>2:00 p.m</td>
                <td>
                  <a href="ver_visita.php?id=1" class="btn btn-sm btn-outline-secondary me-1" title="Ver"><i class="bi bi-eye"></i></a>
                  <a href="editar_visita.php?id=1" class="btn btn-sm btn-outline-primary me-1" title="Editar"><i class="bi bi-pencil"></i></a>
                  <a href="eliminar_visita.php?id=1" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar esta visita?');"><i class="bi bi-x"></i></a>
                </td>
              </tr>
              <!-- Fin ejemplo, puedes reemplazar por PHP dinámico -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
