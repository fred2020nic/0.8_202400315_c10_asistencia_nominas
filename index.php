<?php session_start(); ?>
<?php include 'header.php'; ?>

<body class="hold-transition login-page">
  <div class="login-box" style="margin: 1% auto;">
    <div class="login-logo" style="margin-bottom: 20px;">
      <p id="date"></p>
      <p id="time" class="bold"></p>
    </div>

    <div class="alert alert-success alert-dismissible text-center" style="display:none;">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <span class="result"><i class="icon fa fa-check"></i> <span class="message"></span></span>
    </div>
    <div class="alert alert-danger alert-dismissible text-center" style="display:none;">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <span class="result"><i class="icon fa fa-warning"></i> <span class="message"></span></span>
    </div>

    <div class="login-box-body">
      <h4 class="login-box-msg">Ingrese su ID de Empleado 24</h4>

      <form id="attendance">
        <!-- <div class="form-group">
          <select class="form-control" name="status">
            <option value="in">Hora de Entrada</option>
            <option value="out">Hora de Salida</option>
          </select>
        </div> -->
        <div class="form-group">
          <select class="form-control" name="status">
            <option value="in">Hora de Entrada</option>
            <option value="out">Hora de Salida</option>
            <option value="lunch_out">Hora de Salida a Comer</option>
            <option value="lunch_in">Hora de Entrada de Comer</option>
          </select>
        </div>

        <div class="form-group has-feedback">
          <input type="text" class="form-control input-lg" id="employee" name="employee" required>
          <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
          <div class="text-center scanner">
            <video id="preview" width="300px" height="300px"></video>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <button type="submit" class="btn btn-primary btn-block btn-flat" name="signin" id="btnSub"><i class="fa fa-sign-in"></i> Login</button>
          </div>
        </div>
      </form>
    </div>

  </div>

  <?php include 'scripts.php' ?>
  <script type="text/javascript">
    $(function() {
      var interval = setInterval(function() {
        var momentNow = moment();
        $('#date').html(momentNow.format('dddd').substring(0, 3).toUpperCase() + ' - ' + momentNow.format('MMMM DD, YYYY'));
        $('#time').html(momentNow.format('hh:mm:ss A'));
      }, 100);

      $('#attendance').submit(function(e) {
        e.preventDefault();
        var attendance = $(this).serialize();
        console.log('attendance: ', attendance);
        $.ajax({
          type: 'POST',
          url: 'attendance.php',
          data: attendance,
          dataType: 'json',
          success: function(response) {
            if (response.error) {
              $('.alert').hide();
              $('.alert-danger').show();
              $('.message').html(response.message);
            } else {
              $('.alert').hide();
              $('.alert-success').show();
              $('.message').html(response.message);
              $('#employee').val('');
            }
          }
        });
      });

    });

    //scanner qr

    let vid = document.getElementById('preview');
    let employeeID = document.getElementById('employee');
    let btnSubmit = document.getElementById('btnSub');
    vid.removeAttribute("hidden");
    let scanner = new Instascan.Scanner({
      video: document.getElementById('preview')
    });
    scanner.addListener('scan', function(content) {
      console.log(content);
      employeeID.value = content;
      btnSubmit.click();
    });
    Instascan.Camera.getCameras().then(function(cameras) {
      if (cameras.length > 0) {
        scanner.start(cameras[0]);
      } else {
        console.error('No se encontró ninguna cámara en el dispositivo.');
      }
    }).catch(function(e) {
      console.error(e);
    });
  </script>
</body>

</html>