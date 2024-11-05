<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          Solicitud de Vacaciones
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
          <li>Empleados</li>
          <li class="active">Solicitud de Vacaciones</li>
        </ol>
      </section>

      <section class="content">
        <?php
        if (isset($_SESSION['error'])) {
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              " . $_SESSION['error'] . "
            </div>
          ";
          unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Éxito!</h4>
              " . $_SESSION['success'] . "
            </div>
          ";
          unset($_SESSION['success']);
        }
        ?>
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header with-border">
                <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Nuevo</a>
              </div>
              <div class="box-body">
                <table id="example1" class="table table-bordered">
                  <thead>
                    <th class="hidden"></th>
                    <th>Fecha de Solicitud</th>
                    <th>Nombre</th>
                    <th>Fecha de Inicio</th>
                    <th>Fecha de Fin</th>
                    <th>Fecha de Regreso</th>
                    <th>Acción</th>
                  </thead>
                  <tbody>
                    <?php
                    $sql = "SELECT *, vacacion.id AS vacid, employees.employee_id AS empid FROM vacacion LEFT JOIN employees ON employees.id=vacacion.employee_id ORDER BY fecha_solicitud DESC";
                    $query = $conn->query($sql);
                    while ($row = $query->fetch_assoc()) {
                      echo "
                        <tr>
                          <td class='hidden'></td>
                          <td>" . date('M d, Y', strtotime($row['fecha_solicitud'])) . "</td>
                          <td>" . $row['firstname'] . ' ' . $row['lastname'] . "</td>
                          <td>" . date('M d, Y', strtotime($row['fecha_inicio'])) . "</td>
                          <td>" . date('M d, Y', strtotime($row['fecha_fin'])) . "</td>
                          <td>" . date('M d, Y', strtotime($row['fecha_regreso'])) . "</td>
                          <td>
                            <button class='btn btn-info btn-sm view btn-flat' data-id='" . $row['vacid'] . "'><i class='fa fa-eye'></i> Ver</button>
                            <button class='btn btn-success btn-sm edit btn-flat' data-id='" . $row['vacid'] . "'><i class='fa fa-edit'></i> Editar</button>
                            <button class='btn btn-danger btn-sm delete btn-flat' data-id='" . $row['vacid'] . "'><i class='fa fa-trash'></i> Eliminar</button>
                          </td>
                        </tr>
                      ";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- Modal para ver solicitud de permiso -->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="viewModalLabel">Solicitud de Permiso</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="container-fluid" id="printContent">
              <h2 class="text-center">Solicitud de Permiso</h2>
              <form>
                <div class="form-group row">
                  <label for="view_empresa" class="col-sm-2 col-form-label">Empresa:</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="view_empresa" readonly>
                  </div>
                  <label for="view_fecha_solicitud" class="col-sm-2 col-form-label">Fecha de Solicitud:</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="view_fecha_solicitud" readonly>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="view_nombre_empleado" class="col-sm-2 col-form-label">Nombre del Empleado:</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="view_nombre_empleado" readonly>
                  </div>
                  <label for="view_id_empleado" class="col-sm-2 col-form-label">ID Empleado:</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" id="view_id_empleado" readonly>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="view_puesto" class="col-sm-2 col-form-label">Puesto:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="view_puesto" readonly>
                  </div>
                </div>
                <hr>
                <div class="form-group row">
                  <div class="col-sm-12">
                    <strong>Tipo de Permiso:</strong>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="view_con_goce" class="col-sm-2 col-form-label">Con Goce de Sueldo:</label>
                  <div class="col-sm-1">
                    <input type="checkbox" class="form-control check-group-1" id="view_con_goce" disabled>
                  </div>
                  <label for="view_sin_goce" class="col-sm-2 col-form-label">Sin Goce de Sueldo:</label>
                  <div class="col-sm-1">
                    <input type="checkbox" class="form-control check-group-1" id="view_sin_goce" disabled>
                  </div>
                </div>
                <hr>
                <div class="form-group row">
                  <div class="col-sm-6">
                    <strong>Salida de Oficina:</strong>
                  </div>
                  <div class="col-sm-6">
                    <strong>Motivo:</strong>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="view_hora_retiro" class="col-sm-2 col-form-label">Hora Retiro:</label>
                  <div class="col-sm-4">
                    <input type="time" class="form-control" id="view_hora_retiro" readonly>
                  </div>
                  <label for="view_motivo_paterno" class="col-sm-2 col-form-label">Paternidad:</label>
                  <div class="col-sm-1">
                    <input type="checkbox" class="form-control check-group-2" id="view_motivo_paterno" disabled>
                  </div>
                  <label for="view_motivo_materno" class="col-sm-2 col-form-label">Maternidad:</label>
                  <div class="col-sm-1">
                    <input type="checkbox" class="form-control check-group-2" id="view_motivo_materno" disabled>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="view_fecha_regreso" class="col-sm-2 col-form-label">Regreso:</label>
                  <div class="col-sm-4">
                    <input type="date" class="form-control" id="view_fecha_regreso" readonly>
                  </div>
                  <label for="view_motivo_fallecimiento" class="col-sm-2 col-form-label">Fallecimiento:</label>
                  <div class="col-sm-1">
                    <input type="checkbox" class="form-control check-group-2" id="view_motivo_fallecimiento" disabled>
                  </div>
                  <label for="view_motivo_tramites" class="col-sm-2 col-form-label">Trámites Personales:</label>
                  <div class="col-sm-1">
                    <input type="checkbox" class="form-control check-group-2" id="view_motivo_tramites" disabled>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="view_motivo_lactancia" class="col-sm-2 col-form-label">Lactancia:</label>
                  <div class="col-sm-1">
                    <input type="checkbox" class="form-control check-group-2" id="view_motivo_lactancia" disabled>
                  </div>
                  <label for="view_motivo_otra_situacion" class="col-sm-2 col-form-label">Otra Situación:</label>
                  <div class="col-sm-1">
                    <input type="checkbox" class="form-control check-group-2" id="view_motivo_otra_situacion" disabled>
                  </div>
                </div>
                <hr>
                <div class="form-group row">
                  <label for="view_fecha_inicio" class="col-sm-2 col-form-label">Faltar al Trabajo Desde:</label>
                  <div class="col-sm-4">
                    <input type="date" class="form-control" id="view_fecha_inicio" readonly>
                  </div>
                  <label for="view_fecha_fin" class="col-sm-2 col-form-label">Hasta:</label>
                  <div class="col-sm-4">
                    <input type="date" class="form-control" id="view_fecha_fin" readonly>
                  </div>
                </div>
                <hr>
                <div class="form-group row">
                  <label for="view_observaciones" class="col-sm-2 col-form-label">Observaciones:</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" id="view_observaciones" rows="3" readonly></textarea>
                  </div>
                </div>
                <hr>
                <div class="form-group row text-center">
                  <div class="col-sm-4"></div>
                  <div class="col-sm-4"></div>
                  <div class="col-sm-4"></div>
                </div>
                <div class="form-group row text-center">
                  <div class="col-sm-4">
                    <strong>Firma del Empleado</strong>
                  </div>
                  <div class="col-sm-4">
                    <strong>Firma de Jefe Inmediato</strong>
                  </div>
                  <div class="col-sm-4">
                    <strong>Firma de RRHH</strong>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" onclick="printModalContent()">Guardar como PDF</button>
          </div>
        </div>
      </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/vacacion_modal.php'; ?>
  </div>
  <?php include 'includes/scripts.php'; ?>
  <script>
    function getEditData(id) {
      console.log("Edit ID:", id);  // Añadir log para depurar el ID
      $.ajax({
          type: 'POST',
          url: 'vacacion_row.php',
          data: { id: id },
          dataType: 'json',
          success: function(response) {
              console.log("Edit Response:", response);  // Añadir log para depurar la respuesta
              
              $('#edit_empresa').val(response.empresa);
              $('#edit_puesto').val(response.puesto);
              $('#edit_con_goce').prop('checked', response.con_goce == 1);
              $('#edit_sin_goce').prop('checked', response.sin_goce == 1);
              $('#edit_fecha_inicio').val(response.fecha_inicio);
              $('#edit_fecha_fin').val(response.fecha_fin);
              $('#edit_fecha_regreso').val(response.fecha_regreso);
              $('#edit_hora_retiro').val(response.hora_retiro);
              $('#edit_fecha_salida').val(response.fecha_salida);
              $('#edit_observaciones').val(response.observaciones);
              $('#edit_estado').val(response.estado);

              // Motivos
              $('#edit_motivo_paterno').prop('checked', response.motivo_paterno == 1);
              $('#edit_motivo_materno').prop('checked', response.motivo_materno == 1);
              $('#edit_motivo_fallecimiento').prop('checked', response.motivo_fallecimiento == 1);
              $('#edit_motivo_tramites').prop('checked', response.motivo_tramites == 1);
              $('#edit_motivo_lactancia').prop('checked', response.motivo_lactancia == 1);
              $('#edit_motivo_otra_situacion').prop('checked', response.motivo_otra_situacion == 1);
              
              // Asignar el valor del id al campo oculto
              $('input[name="id"]').val(response.vacid);

              // Asignar los valores de nombre y apellido
              $('#edit_nombre_empleado').val(response.firstname + " " + response.lastname);
              $('#edit_id_empleado').val(response.employee_id);
              
              $('#edit').modal('show');  // Mostrar el modal de edición
          },
          error: function(xhr, status, error) {
              console.log("Error:", error);  // Log de errores
          }
      });
    }


    function getViewData(id) {
        console.log("View ID:", id);  // Añadir log para depurar el ID
        $.ajax({
            type: 'POST',
            url: 'vacacion_row.php',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                console.log("View Response:", response);  // Añadir log para depurar la respuesta
                
                $('#view_empresa').val(response.empresa);
                $('#view_fecha_solicitud').val(response.fecha_solicitud);
                $('#view_puesto').val(response.puesto);
                $('#view_con_goce').prop('checked', response.con_goce == 1);
                $('#view_sin_goce').prop('checked', response.sin_goce == 1);
                $('#view_fecha_inicio').val(response.fecha_inicio);
                $('#view_fecha_fin').val(response.fecha_fin);
                $('#view_fecha_regreso').val(response.fecha_regreso);
                $('#view_hora_retiro').val(response.hora_retiro);
                $('#view_fecha_salida').val(response.fecha_salida);
                $('#view_observaciones').val(response.observaciones);

                // Motivos
                $('#view_motivo_paterno').prop('checked', response.motivo_paterno == 1);
                $('#view_motivo_materno').prop('checked', response.motivo_materno == 1);
                $('#view_motivo_fallecimiento').prop('checked', response.motivo_fallecimiento == 1);
                $('#view_motivo_tramites').prop('checked', response.motivo_tramites == 1);
                $('#view_motivo_lactancia').prop('checked', response.motivo_lactancia == 1);
                $('#view_motivo_otra_situacion').prop('checked', response.motivo_otra_situacion == 1);
                
                // Asignar los valores de nombre y apellido
                $('#view_nombre_empleado').val(response.firstname + " " + response.lastname);
                $('#view_id_empleado').val(response.employee_id);

                // Mostrar el modal de vista
                $('#viewModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.log("Error:", error);  // Log de errores
            }
        });
    }

    $(function() {
        $('.view').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            getViewData(id);
        });

        $('.edit').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            getEditData(id);
        });

        $('.delete').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            getEditData(id); // Asumo que eliminar y editar usan la misma lógica
            $('#delete').modal('show');
        });

        // Solo permitir seleccionar una opción por grupo
        $('.check-group-1').on('change', function() {
            $('.check-group-1').not(this).prop('checked', false);
        });

        $('.check-group-2').on('change', function() {
            $('.check-group-2').not(this).prop('checked', false);
        });
    });

    function printModalContent() {
    const printContent = document.getElementById('printContent').cloneNode(true);

    printContent.querySelectorAll('input, textarea').forEach(function(input) {
        if (input.type === 'checkbox' || input.type === 'radio') {
            if (input.checked) {
                input.setAttribute('checked', 'checked');
            } else {
                input.removeAttribute('checked');
            }
        } else if (input.tagName.toLowerCase() === 'textarea') {
            // Clonar correctamente el valor del textarea
            input.innerHTML = input.value;
        } else {
            input.setAttribute('value', input.value);
        }
    });

    const win = window.open('', '_blank');
    win.document.write('<html><head><title>Solicitud de Permiso</title><style>');
    win.document.write('body { font-family: Arial, sans-serif; padding: 20px; }');
    win.document.write('.form-group { margin-bottom: 1rem; display: flex; align-items: center; }');
    win.document.write('.form-group label { width: 25%; font-weight: bold; }');
    win.document.write('.form-group div { width: 75%; }');
    win.document.write('input, textarea { width: 100%; padding: 5px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }');
    win.document.write('textarea { resize: none; }');
    win.document.write('hr { border-top: 1px solid #000; margin-top: 20px; margin-bottom: 20px; }');
    win.document.write('.text-center { text-align: center; margin-bottom: 20px; }');
    win.document.write('strong { display: block; margin-bottom: 10px; text-align: center; }');
    win.document.write('input[type="checkbox"] { margin-right: 10px; }');
    win.document.write('label { margin-bottom: 10px; }');
    win.document.write('</style></head><body>');
    win.document.write(printContent.innerHTML);
    win.document.write('</body></html>');
    win.document.close();
    win.focus();
    win.print();
    win.close();
}

  </script>
</body>
</html>