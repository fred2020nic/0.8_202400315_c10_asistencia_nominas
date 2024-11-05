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
          Bonos
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
          <li class="active">Bonos</li>
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
                <a href="#addBono" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Nuevo Bono</a>
              </div>
              <div class="box-body">
                <table id="example1" class="table table-bordered">
                  <thead>
                    <th class="hidden"></th>
                    <th>ID Empleado</th>
                    <th>Nombre</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acción</th>
                  </thead>
                  <tbody>
                  <?php
$sql = "SELECT bono.*, employees.employee_id AS emp_number, employees.firstname, employees.lastname 
        FROM bono 
        LEFT JOIN employees ON employees.id=bono.employee_id 
        ORDER BY bono.date DESC";
$query = $conn->query($sql);

while ($row = $query->fetch_assoc()) {
  $status = ($row['status']) ? '<span class="label label-success pull-right">Activo</span>' : '<span class="label label-danger pull-right">Inactivo</span>';
  echo "
    <tr>
      <td class='hidden'></td>
      <td>" . $row['emp_number'] . "</td> <!-- Aquí muestra el ID del empleado correctamente -->
      <td>" . $row['firstname'] . ' ' . $row['lastname'] . "</td> <!-- Nombre del empleado -->
      <td>$" . number_format($row['monto'], 2) . "</td> <!-- Monto del bono -->
      <td>" . date('M d, Y', strtotime($row['date'])) . "</td> <!-- Fecha del bono -->
      <td>" . $status . "</td> <!-- Estado del bono -->
      <td>
        <button class='btn btn-success btn-sm btn-flat edit' data-id='" . $row['id'] . "'><i class='fa fa-edit'></i> Editar</button> <!-- Corregido data-id para bono -->
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
    <?php include 'includes/bono_modal.php'; ?>
  </div>
  <?php include 'includes/scripts.php'; ?>
  <script>
  $(function() {
  $('.edit').click(function(e) {
    e.preventDefault();
    $('#editBono').modal('show');
    var id = $(this).data('id');
    getEditRow(id);
  });

  $('.delete').click(function(e) {
    e.preventDefault();
    $('#deleteBono').modal('show');
    var id = $(this).data('id');
    getDeleteRow(id);
  });
});

function getEditRow(id) {
  $.ajax({
    type: 'POST',
    url: 'bono_row.php',
    data: { id: id },
    dataType: 'json',
    success: function(response) {
  $('#edit_bono_id').val(response.id);
  $('#empleados_edit').val(response.employee_id).trigger('change');
  $('#employee_id_edit').val(response.emp_number); // Asegúrate de que 'emp_number' está incluido en la respuesta JSON
  $('#monto_edit').val(response.monto);
  $('#date_edit').val(response.date);
  $('#status_edit').val(response.status);
}
  });
}

function getDeleteRow(id) {
  $.ajax({
    type: 'POST',
    url: 'bono_row.php',
    data: { id: id },
    dataType: 'json',
    success: function(response) {
      $('#delete_bono_id').val(response.id);
      $('#delete_employee_name').html(response.firstname + ' ' + response.lastname);
    }
  });
}


    function getRow(id) {
  $.ajax({
    type: 'POST',
    url: 'bono_row.php',
    data: { id: id },
    dataType: 'json',
    success: function(response) {
      $('#edit_bono_id').val(response.id); // Corregido de bono_id a id
      $('#empleados_edit').val(response.employee_id).trigger('change'); // Selecciona el empleado correcto en el select
      $('#monto_edit').val(response.monto); // Monto del bono
      $('#date_edit').val(response.date); // Fecha del bono
      $('#status_edit').val(response.status); // Estado del bono (Activo/Inactivo)
      $('#delete_bono_id').val(response.id); // Corregido de bono_id a id
      $('#delete_employee_name').html(response.firstname + ' ' + response.lastname); // Nombre completo del empleado
    }
  });
}






  </script>
</body>

</html>
