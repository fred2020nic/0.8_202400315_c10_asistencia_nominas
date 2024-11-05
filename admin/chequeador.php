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
          Chequeador de Asistencia
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
          <li class="active">Chequeador</li>
        </ol>
      </section>
      <!-- Main content -->
      <section class="content">
        <?php
          if(isset($_SESSION['error'])){
            echo "
              <div class='alert alert-danger alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4><i class='icon fa fa-warning'></i> ¡Error!</h4>
                ".$_SESSION['error']."
              </div>
            ";
            unset($_SESSION['error']);
          }
          if(isset($_SESSION['success'])){
            echo "
              <div class='alert alert-success alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4><i class='icon fa fa-check'></i> ¡Proceso Exitoso!</h4>
                ".$_SESSION['success']."
              </div>
            ";
            unset($_SESSION['success']);
          }
        ?>
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header with-border">
                <!-- Botón que abre el modal para agregar un nuevo registro -->
                <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat">
                  <i class="fa fa-plus"></i> Nuevo Registro
                </a>
              </div>
              <div class="box-body">
                <!-- Tabla de Chequeador de Asistencia -->
                <table id="example1" class="table table-bordered">
                  <thead>
                    <th>ID</th>
                    <th>Colaborador</th>
                    <th>Ocurrencia</th>
                    <th>Fecha</th>
                    <th>Justificación</th>
                    <th>Retardos Acumulados</th>
                    <th>Estado</th>
                    <th>Acción</th>
                  </thead>
                  <tbody>
                    <?php
                      // Consulta para obtener los registros de asistencia con información de empleado y ocurrencia
                      $sql = "SELECT chequeador.*, employees.firstname, employees.lastname, ocurrencia.name AS ocurrencia_name 
                              FROM chequeador 
                              LEFT JOIN employees ON employees.id = chequeador.employee_id 
                              LEFT JOIN ocurrencia ON ocurrencia.id = chequeador.ocurrencia_id 
                              ORDER BY chequeador.date DESC, chequeador.id DESC";
                      $query = $conn->query($sql);
                      
                      if(!$query){
                          echo "<tr><td colspan='8'>Error en la consulta: " . $conn->error . "</td></tr>";
                      }
                      else{
                          while($row = $query->fetch_assoc()){
                            // Determinar el estado basado en la columna 'status'
                            $estado = ($row['status'] == 1) ? 'Activo' : 'Inactivo';
                            echo "
                              <tr>
                                <td>".$row['id']."</td>
                                <td>".$row['firstname'].' '.$row['lastname']."</td>
                                <td>".$row['ocurrencia_name']."</td>
                                <td>".date('d-M-Y', strtotime($row['date']))."</td>
                                <td>".$row['justificacion']."</td>
                                <td>".$row['retardos']."</td>
                                <td>".$estado."</td>
                                <td>
                                  <button class='btn btn-success btn-sm edit btn-flat' data-id='".$row['id']."'><i class='fa fa-edit'></i> Editar</button>
                                  <button class='btn btn-info btn-sm view btn-flat' data-id='".$row['id']."'><i class='fa fa-eye'></i> Ver</button>
                                  <button class='btn btn-danger btn-sm delete btn-flat' data-id='".$row['id']."'><i class='fa fa-trash'></i> Eliminar</button>
                                </td>
                              </tr>
                            ";
                          }
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
    <?php include 'includes/chequeador_modal.php'; ?>
  </div>
  <?php include 'includes/scripts.php'; ?>
  <script>
    $(function(){
      // Al hacer clic en el botón "Editar"
      $('.edit').click(function(e){
        e.preventDefault();
        $('#edit').modal('show');
        var id = $(this).data('id');
        getRow(id);
      });

      // Al hacer clic en el botón "Ver"
      $('.view').click(function(e){
        e.preventDefault();
        $('#view').modal('show');
        var id = $(this).data('id');
        getRow(id);
      });

      // Al hacer clic en el botón "Eliminar"
      $('.delete').click(function(e){
        e.preventDefault();
        $('#delete').modal('show');
        var id = $(this).data('id');
        getRow(id);
      });
    });

    function getRow(id){
      $.ajax({
        type: 'POST',
        url: 'chequeador_row.php',
        data: {id:id},
        dataType: 'json',
        success: function(response){
          if(response.error){
            alert("Error: " + response.error);
          }
          else{
            // Modal de Editar
            $('#chequeadorid').val(response.id);
            $('#edit_employee').val(response.employee_id);
            $('#edit_ocurrencia').val(response.ocurrencia_id);
            $('#edit_date').val(response.date);
            $('#edit_justificacion').val(response.justificacion);
            $('#edit_retardos').val(response.retardos);
            $('#edit_status').val(response.status);

            // Modal de Eliminar
            $('#del_chequeadorid').val(response.id);
            $('#del_employee').html(response.firstname + ' ' + response.lastname);
            $('#del_ocurrencia').html(response.ocurrencia_name);
            $('#del_date').html(response.date);

            // Modal de Ver
            $('#view_employee').html(response.firstname + ' ' + response.lastname);
            $('#view_ocurrencia').html(response.ocurrencia_name);
            $('#view_date').html(response.date);
            $('#view_justificacion').html(response.justificacion);
            $('#view_retardos').html(response.retardos);
            $('#view_status').html(response.status == 1 ? 'Activo' : 'Inactivo');
          }
        }
      });
    }
  </script>
</body>
</html>
