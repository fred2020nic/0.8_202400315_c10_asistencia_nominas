<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<style>
  #contenido {
    background-color: aquamarine;
    display: flex;
    justify-content: center;
    gap: 2rem;
  }

  #contenido>div {
    width: 50%;
  }

  .datos {
    display: flex;
    justify-content: space-between;
    flex-direction: column;
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
              <h4><i class='icon fa fa-check'></i>¡Proceso Exitoso!</h4>
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
                    <th>ID Empleado</th>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Posición</th>
                    <th>Horarios</th>
                    <th>Miembro Desde</th>
                    <th>Acción</th>
                  </thead>
                  <tbody>
                    <?php
                    $sql = "SELECT *, employees.id AS empid FROM employees LEFT JOIN position ON position.id=employees.position_id LEFT JOIN schedules ON schedules.id=employees.schedule_id";
                    $query = $conn->query($sql);
                    while ($row = $query->fetch_assoc()) {
                    ?>
                      <tr>
                        <td><?php echo $row['employee_id']; ?></td>
                        <td><img src="<?php echo (!empty($row['photo'])) ? '../images/' . $row['photo'] : '../images/profile.jpg'; ?>" width="30px" height="30px"> <a href="#edit_photo" data-toggle="modal" class="pull-right photo" data-id="<?php echo $row['empid']; ?>"><span class="fa fa-edit"></span></a></td>
                        <td><?php echo $row['firstname'] . ' ' . $row['lastname']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo date('h:i A', strtotime($row['time_in'])) . ' - ' . date('h:i A', strtotime($row['time_out'])); ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['created_on'])) ?></td>
                        <td>
                          <button class="btn btn-success btn-sm edit btn-flat" data-id="<?php echo $row['empid']; ?>"><i class="fa fa-edit"></i> Editar</button>
                          <button class="btn btn-danger btn-sm delete btn-flat" data-id="<?php echo $row['empid']; ?>"><i class="fa fa-trash"></i> Eliminar</button>
                          <button data-toggle="modal" data-target="#generateQR" class="btn btn-warning btn-sm qrGenerate btn-flat" data-tel="<?php echo $row['contact_info']; ?>" data-cargo=" <?php echo $row['description']; ?>" data-name=" <?php echo $row['firstname'] . ' ' . $row['lastname']; ?>" data-id="<?php echo $row['employee_id']; ?>"><i class="fa fa-qrcode"></i> Generar QR</button>
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

        <!-- Modal -->
        <div class="modal fade" id="generateQR" tabindex="-1" role="dialog" aria-labelledby="generateQRTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLongTitle">Generar QR</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                <a href="..." id="descarga" class="btn btn-primary btn-sm btn-flat" download><i class="fa fa-download"></i> Descargar</a>
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
  <script defer src="../bower_components/qrGenerate/qrcode.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/html2canvas@1.0.0-rc.1/dist/html2canvas.min.js"></script>
  <script>
    $(function() {
      let qrGen = document.querySelector('#qrGen');

      $('.edit').click(function(e) {
        e.preventDefault();
        $('#edit').modal('show');
        var id = $(this).data('id');
        getRow(id);
      });

      $('.delete').click(function(e) {
        e.preventDefault();
        $('#delete').modal('show');
        var id = $(this).data('id');
        getRow(id);
      });

      $('.photo').click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        getRow(id);
      });

      $('.qrGenerate').click(function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        let name = $(this).data('name');
        let cargo = $(this).data('cargo');
        let tel = $(this).data('tel');

        $("#name").html(name)
        $("#cargo").html(cargo)
        $("#tel").html(tel)

        qrGen.innerHTML = "";
        const QR = new QRCode(qrGen);

        QR.makeCode(id);
        /* 
                setTimeout(() => {
                  let dowl = qrGen.querySelector('img').getAttribute('src');
                  document.querySelector('#descarga').href = dowl;
                }, 500); */
      });

      $('#descarga').click(e => {
        e.preventDefault()
        nombre = $("#name").text()
        html2canvas(document.querySelector("#contenido")).then(canvas => {
          document.body.appendChild(canvas)
          var link = document.createElement('a');
          link.download = 'qr' + nombre + '.png';
          link.href = canvas.toDataURL()
          link.click();
        });
      });
      // $('#descarga').click(e => {
      //   let btn = e.target;
      //   e.preventDefault();
      //   let dowl = qrGen.querySelector('img').getAttribute('src');

      //   btn.href = dowl;
      //   btn.download = true;
      //   btn.target = '_blank';
      //   console.log('btn: ', btn);

      //   // btn.click();

      // });

    });

    function getRow(id) {
      $.ajax({
        type: 'POST',
        url: 'employee_row.php',
        data: {
          id: id
        },
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
          $('#gender_val').val(response.gender).html(response.gender);
          $('#position_val').val(response.position_id).html(response.description);
          $('#schedule_val').val(response.schedule_id).html(response.time_in + ' - ' + response.time_out);
        }
      });
    }
  </script>
</body>

</html>