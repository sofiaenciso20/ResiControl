<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h4 class="mb-0">Gestión de Reservas</h4>
          <a href="exportar_reservas.php">
            <img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Excel_2013_logo.svg" alt="Exportar a Excel" width="30">
          </a>
        </div>
        <div class="card-body">
          <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
              <tr>
                <th>Zona</th>
                <th>Fecha</th>
                <th>Horario</th>
                <th>Residente</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              <!-- Aquí se insertarán dinámicamente los registros -->
              <tr>
                <td>Salón Comunal</td>
                <td>07/06/25</td>
                <td>8:00 am a 10:00 am</td>
                <td>Paula García</td>
                <td>
                  <a href="ver_reserva.php?id=1" class="btn btn-sm btn-outline-secondary me-1" title="Ver"><i class="bi bi-eye"></i></a>
                  <a href="editar_reserva.php?id=1" class="btn btn-sm btn-outline-primary me-1" title="Editar"><i class="bi bi-pencil"></i></a>
                  <a href="eliminar_reserva.php?id=1" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar esta reserva?');"><i class="bi bi-x"></i></a>
                </td>
              </tr>
              <!-- Reemplazar con PHP dinámico -->
            </tbody>
          </table>
          
        </div>
      </div>
    </div>
  </div>
</div>
