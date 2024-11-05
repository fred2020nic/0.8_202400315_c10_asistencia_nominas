<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Deducciones
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
          <li class="active">Deducciones</li>
        </ol>
      </section>
      <!-- Main content -->
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
              <h4><i class='icon fa fa-check'></i> ¡Proceso Exitoso!</h4>
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
                <a href="#addDeduccion" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Nueva Deducción</a>
              </div>
              <div class="box-body">
                <table id="example1" class="table table-bordered">
                  <thead>
                    <th class="hidden"></th>
                    <th>ID Empleado</th>
                    <th>Nombre</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th>Motivo</th> <!-- Nueva columna para el motivo -->
                    <th>Estado</th>
                    <th>Acción</th>
                  </thead>
                  <tbody>
                  <?php
                  $sql = "SELECT deducciones.*, employees.employee_id AS emp_number, employees.firstname, employees.lastname 
                          FROM deducciones 
                          LEFT JOIN employees ON employees.id=deducciones.employee_id 
                          ORDER BY deducciones.date DESC";
                  $query = $conn->query($sql);

                  while ($row = $query->fetch_assoc()) {
                    $status = ($row['status']) ? '<span class="label label-success pull-right">Activo</span>' : '<span class="label label-danger pull-right">Inactivo</span>';
                    echo "
                      <tr>
                        <td class='hidden'></td>
                        <td>" . $row['emp_number'] . "</td>
                        <td>" . $row['firstname'] . ' ' . $row['lastname'] . "</td>
                        <td>$" . number_format($row['monto'], 2) . "</td>
                        <td>" . date('M d, Y', strtotime($row['date'])) . "</td>
                        <td>" . htmlspecialchars($row['motivo']) . "</td> <!-- Mostrar el motivo -->
                        <td>" . $status . "</td>
                        <td>
                          <button class='btn btn-success btn-sm btn-flat edit' data-id='" . $row['id'] . "'><i class='fa fa-edit'></i> Editar</button>
                          <button class='btn btn-danger btn-sm btn-flat delete' data-id='" . $row['id'] . "'><i class='fa fa-trash'></i> Eliminar</button>
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

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/deduccion_modal.php'; ?>
  </div>
  <?php include 'includes/scripts.php'; ?>
  <script>
  $(function() {
    $('.edit').click(function(e) {
      e.preventDefault();
      $('#editDeduccion').modal('show');
      var id = $(this).data('id');
      getEditRow(id);
    });

    $('.delete').click(function(e) {
      e.preventDefault();
      $('#deleteDeduccion').modal('show');
      var id = $(this).data('id');
      getDeleteRow(id);
    });
  });

  function getEditRow(id) {
    $.ajax({
      type: 'POST',
      url: 'deduccion_row.php',
      data: { id: id },
      dataType: 'json',
      success: function(response) {
        $('#edit_deduccion_id').val(response.id);
        $('#empleados_edit').val(response.employee_id).trigger('change');
        $('#employee_id_edit').val(response.emp_number);
        $('#monto_edit').val(response.monto);
        $('#date_edit').val(response.date);
        $('#motivo_edit').val(response.motivo); // Rellenar el motivo
        $('#status_edit').val(response.status);
      }
    });
  }

  function getDeleteRow(id) {
    $.ajax({
      type: 'POST',
      url: 'deduccion_row.php',
      data: { id: id },
      dataType: 'json',
      success: function(response) {
        $('#delete_deduccion_id').val(response.id);
        $('#delete_employee_name').html(response.firstname + ' ' + response.lastname);
      }
    });
  }
  </script>
</body>

</html>
