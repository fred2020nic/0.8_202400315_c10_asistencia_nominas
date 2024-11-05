<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<style>
  #contenido {
    background-color: aquamarine;
    display: flex;
    justify-content: center;
    gap: 2rem;
    padding: 20px; /* Añadido padding para mejor visualización */
  }

  #contenido > div {
    width: 50%;
    text-align: center; /* Centrar el contenido dentro del div */
  }

  .datos {
    display: flex;
    justify-content: space-between;
    flex-direction: column;
    margin-top: 20px; /* Separar los datos del QR */
  }

  /* Estilos adicionales para mejorar la apariencia */
  .modal-header {
    background-color: #f39c12;
    color: white;
  }

  .modal-footer {
    background-color: #f9f9f9;
  }

  #qrGen {
    margin-bottom: 20px;
  }
</style>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Lista de Empleados
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
          <li>Empleados</li>
          <li class="active">Lista de Empleados</li>
        </ol>
      </section>
      <!-- Main content -->
      <section class="content">
        <?php
        if (isset($_SESSION['error'])) {
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> ¡Error!</h4>
              " . htmlspecialchars($_SESSION['error']) . "
            </div>
          ";
          unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> ¡Proceso Exitoso!</h4>
              " . htmlspecialchars($_SESSION['success']) . "
            </div>
          ";
          unset($_SESSION['success']);
        }
        ?>
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header with-border">
                <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat">
                  <i class="fa fa-plus"></i> Nuevo
                </a>
              </div>
              <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>ID Empleado</th>
                      <th>Foto</th>
                      <th>Nombre</th>
                      <th>Posición</th>
                      <th>Horarios</th>
                      <th>Miembro Desde</th>
                      <th>Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $sql = "SELECT *, employees.id AS empid FROM employees 
                            LEFT JOIN position ON position.id=employees.position_id 
                            LEFT JOIN schedules ON schedules.id=employees.schedule_id
                            ORDER BY employees.lastname ASC, employees.firstname ASC";
                    $query = $conn->query($sql);
                    while ($row = $query->fetch_assoc()) {
                    ?>
                      <tr>
                        <td><?php echo htmlspecialchars($row['employee_id']); ?></td>
                        <td>
                          <img src="<?php echo (!empty($row['photo'])) ? '../images/' . htmlspecialchars($row['photo']) : '../images/profile.jpg'; ?>" width="30px" height="30px" alt="Foto de <?php echo htmlspecialchars($row['firstname']); ?>">
                          <a href="#edit_photo" data-toggle="modal" class="pull-right photo" data-id="<?php echo htmlspecialchars($row['empid']); ?>">
                            <span class="fa fa-edit"></span>
                          </a>
                        </td>
                        <td><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars(date('h:i A', strtotime($row['time_in'])) . ' - ' . date('h:i A', strtotime($row['time_out']))); ?></td>
                        <td><?php echo htmlspecialchars(date('M d, Y', strtotime($row['created_on']))); ?></td>
                        <td>
                          <button class="btn btn-success btn-sm edit btn-flat" data-id="<?php echo htmlspecialchars($row['empid']); ?>">
                            <i class="fa fa-edit"></i> Editar
                          </button>
                          <button class="btn btn-danger btn-sm delete btn-flat" data-id="<?php echo htmlspecialchars($row['empid']); ?>">
                            <i class="fa fa-trash"></i> Eliminar
                          </button>
                          <button data-toggle="modal" data-target="#generateQR" class="btn btn-warning btn-sm qrGenerate btn-flat" 
                                  data-tel="<?php echo htmlspecialchars($row['contact_info'] ?? ''); ?>" 
                                  data-cargo="<?php echo htmlspecialchars($row['description'] ?? ''); ?>" 
                                  data-name="<?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname'] ?? ''); ?>" 
                                  data-id="<?php echo htmlspecialchars($row['employee_id'] ?? ''); ?>">
                            <i class="fa fa-qrcode"></i> Generar QR
                          </button>
                        </td>
                      </tr>
                    <?php
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal para Generar QR -->
        <div class="modal fade" id="generateQR" tabindex="-1" role="dialog" aria-labelledby="generateQRTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h2 class="modal-title" id="generateQRTitle">Generar QR</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="contenido">
                <div id="qrGen"></div>
                <div class="datos">
                  <h3 id="name"></h3>
                  <h3 id="cargo"></h3>
                  <h3 id="tel"></h3>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <a href="#" id="descarga" class="btn btn-primary btn-sm btn-flat" download>
                  <i class="fa fa-download"></i> Descargar
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/employee_modal.php'; ?>
  </div>
  <?php include 'includes/scripts.php'; ?>
  <!-- Asegúrate de que la ruta sea correcta -->
  <script defer src="../bower_components/qrGenerate/qrcode.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/html2canvas@1.0.0-rc.1/dist/html2canvas.min.js"></script>
  <script>
    $(document).ready(function() {

      // Manejo de eventos para botones dinámicos
      $(document).on('click', '.edit', function(e) {
        e.preventDefault();
        $('#edit').modal('show');
        var id = $(this).data('id');
        getRow(id);
      });

      $(document).on('click', '.delete', function(e) {
        e.preventDefault();
        $('#delete').modal('show');
        var id = $(this).data('id');
        getRow(id);
      });

      $(document).on('click', '.photo', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        getRow(id);
      });

      // Evento para generar QR
      $(document).on('click', '.qrGenerate', function(e) {
        e.preventDefault();

        // Obtener los datos del botón clicado usando .attr() para asegurar que sean strings
        let id = $(this).attr('data-id') ? $(this).attr('data-id').trim() : '';
        let name = $(this).attr('data-name') ? $(this).attr('data-name').trim() : '';
        let cargo = $(this).attr('data-cargo') ? $(this).attr('data-cargo').trim() : '';
        let tel = $(this).attr('data-tel') ? $(this).attr('data-tel').trim() : '';

        // Depuración: Verificar los datos
        console.log("QRGenerate clicked:", { id, name, cargo, tel });

        // Validar que el ID no esté vacío
        if (!id) {
          console.error("ID del empleado ausente.");
          alert("No se pudo generar el QR porque el ID del empleado está ausente.");
          return;
        }

        // Actualizar el contenido del modal con los datos del empleado
        $("#name").text(name);
        $("#cargo").text(cargo);
        $("#tel").text(tel);

        // Limpiar cualquier QR previo
        $("#qrGen").empty();

        // Generar un nuevo QR Code
        new QRCode(document.getElementById("qrGen"), {
          text: id, // Puedes personalizar el contenido del QR aquí
          width: 128,
          height: 128
        });

        // Asegurarse de que el modal se muestre después de generar el QR
        $('#generateQR').modal('show');
      });

      // Evento para descargar el QR como imagen
      $(document).on('click', '#descarga', function(e) {
        e.preventDefault();
        let nombre = $("#name").text().trim();

        // Depuración: Verificar el nombre
        console.log("Descargando QR para:", nombre);

        // Validar que el nombre no esté vacío
        if (!nombre) {
          console.error("Nombre del empleado ausente.");
          alert("No se pudo descargar el QR porque el nombre del empleado está ausente.");
          return;
        }

        // Utilizar html2canvas para capturar el contenido del modal
        html2canvas(document.querySelector("#contenido")).then(canvas => {
          // Eliminar canvas previo si existe
          $('canvas').remove();

          // Crear un elemento de enlace para descargar la imagen
          var link = document.createElement('a');
          link.download = 'qr_' + nombre + '.png';
          link.href = canvas.toDataURL();
          link.click();
        }).catch(function(error) {
          console.error("Error al generar la imagen del QR:", error);
          alert("Hubo un error al generar la imagen del QR.");
        });
      });

      // Función para obtener los datos del empleado mediante AJAX
      function getRow(id) {
        $.ajax({
          type: 'POST',
          url: 'employee_row.php',
          data: { id: id },
          dataType: 'json',
          success: function(response) {
            $('.empid').val(response.empid);
            $('.employee_id').html(response.employee_id);
            $('.del_employee_name').html(response.firstname + ' ' + response.lastname);
            $('#employee_name').html(response.firstname + ' ' + response.lastname);
            $('#edit_firstname').val(response.firstname);
            $('#edit_lastname').val(response.lastname);
            $('#edit_address').val(response.address);
            $('#datepicker_edit').val(response.birthdate);
            $('#edit_contact').val(response.contact_info);

            $('#edit_curp').val(response.curp);
            $('#edit_rfc').val(response.rfc);
            $('#edit_nss').val(response.nss);
            $('#edit_tipo_sangre').val(response.tipo_sangre);
            $('#edit_domicilio').val(response.domicilio);
            $('#edit_colonia').val(response.colonia);
            $('#edit_cp').val(response.cp);
            $('#edit_fecha_ingreso').val(response.fecha_ingreso);
            $('#edit_fecha_alta_imss').val(response.fecha_alta_imss);
            $('#edit_tel_emergencia_1').val(response.tel_emergencia_1);
            $('#edit_parentesco_1').val(response.parentesco_1);
            $('#edit_tel_emergencia_2').val(response.tel_emergencia_2);
            $('#edit_parentesco_2').val(response.parentesco_2);

            $('#edit_extras').val(response.extras);
            $('#gender_val').val(response.gender).html(response.gender);
            $('#position_val').val(response.position_id).html(response.description);
            $('#schedule_val').val(response.schedule_id).html(response.time_in + ' - ' + response.time_out);
          },
          error: function(xhr, status, error) {
            console.error("Error al obtener los datos del empleado:", error);
            alert("Hubo un error al obtener los datos del empleado.");
          }
        });
      }

      // Opcional: Limpiar el contenido del modal cuando se cierre
      $('#generateQR').on('hidden.bs.modal', function () {
        $("#qrGen").empty();
        $("#name").text('');
        $("#cargo").text('');
        $("#tel").text('');
      });

    });
  </script>
</body>

</html>
