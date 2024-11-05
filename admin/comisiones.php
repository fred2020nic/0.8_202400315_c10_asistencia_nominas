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
      <h1>Comisiones</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Comisiones</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <!-- Botón que abre el modal para agregar un nuevo registro -->
              <button type="button" class="btn btn-primary btn-sm btn-flat" data-toggle="modal" data-target="#addComision"><i class="fa fa-plus"></i> Nueva Comisión</button>
            </div>
            <div class="box-body">
              <!-- Tabla -->
              <table id="example1" class="table table-bordered">
                <thead>
                  <th>ID</th>
                  <th>Colaborador</th>
                  <th>Fecha</th>
                  <th>Monto</th>
                  <th>Acción</th>
                </thead>
                <tbody>
                  <!-- Ejemplo de fila de tabla sin lógica de backend -->
                  <tr>
                    <td>1</td>
                    <td>Carlos Rodríguez</td>
                    <td>08/09/2024</td>
                    <td>$800</td>
                    <td>
                      <button class='btn btn-success btn-sm edit btn-flat'><i class='fa fa-edit'></i> Editar</button>
                      <button class='btn btn-danger btn-sm delete btn-flat'><i class='fa fa-trash'></i> Eliminar</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>   
  </div>

  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/comisiones_modal.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
  // Al hacer clic en el botón "Editar"
  $('#example1').on('click', '.edit', function(e){
    e.preventDefault();
    $('#editComision').modal('show');  // Abre el modal para editar
  });

  // Al hacer clic en el botón "Eliminar"
  $('#example1').on('click', '.delete', function(e){
    e.preventDefault();
    $('#deleteComision').modal('show');  // Abre el modal de confirmación de eliminar
  });
});
</script>
</body>
</html>
