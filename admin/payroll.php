<?php include 'includes/session.php'; ?>
<?php
include '../timezone.php';
$range_to = date('m/d/Y');
$range_from = date('m/d/Y', strtotime('-30 day', strtotime($range_to)));

if (isset($_GET['range'])) {
    $range = $_GET['range'];
    $ex = explode(' - ', $range);
    $from = date('Y-m-d', strtotime($ex[0]));
    $to = date('Y-m-d', strtotime($ex[1]));
} else {
    $to = date('Y-m-d');
    $from = date('Y-m-d', strtotime('-30 day', strtotime($to)));
}

// Consulta SQL para verificar si el día de asistencia es un día festivo
$sql = "
    SELECT 
        a.id, 
        a.employee_id, 
        a.date, 
        a.time_in, 
        a.status, 
        a.time_out, 
        a.num_hr,
        e.firstname, 
        e.lastname, 
        p.rate, 
        e.extras,
        CASE 
            WHEN f.holiday_date IS NOT NULL AND a.date <> '0000-00-00' THEN 1 
            ELSE 0 
        END AS is_holiday
    FROM 
        attendance a
    LEFT JOIN 
        festivos f ON a.date = f.holiday_date
    LEFT JOIN 
        employees e ON e.id = a.employee_id
    LEFT JOIN 
        position p ON p.id = e.position_id
    WHERE 
        a.date BETWEEN '$from' AND '$to'
    ORDER BY 
        e.lastname ASC, e.firstname ASC
";

$query = $conn->query($sql);
?>

<!-- Incluye el encabezado y la navegación -->
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>
  <div class="content-wrapper">
    <section class="content-header">
      <h1>Nómina</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Nómina</li>
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
              <div class="pull-right">
                <form method="POST" class="form-inline" id="payForm">
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control pull-right col-sm-8" id="reservation" name="date_range" value="<?php echo (isset($_GET['range'])) ? $_GET['range'] : $range_from . ' - ' . $range_to; ?>">
                  </div>
                  <button type="button" class="btn btn-success btn-sm btn-flat" id="payroll"><span class="glyphicon glyphicon-print"></span> Nómina de Sueldo</button>
                  <button type="button" class="btn btn-primary btn-sm btn-flat" id="payslip"><span class="glyphicon glyphicon-print"></span> Recibo de Sueldo</button>
                </form>
              </div>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <th>Nombre Empleado</th>
                  <th>ID Empleado</th>
                  <th>Total horas</th>
                  <th>Valor Festivo</th>
                  <th>Horas contrato</th>
                  <th>Gross</th>
                  <th>Valor por Hora</th>
                  <th>Hras Extras</th>
                  <th>Valor por Hora Extra</th>
                  <th>Monto Horas Extra</th>
                  <th>Pago Neto</th>
                </thead>
                <tbody>
                  <?php
                  $sql_deductions = "SELECT *, SUM(amount) as total_amount FROM deductions";
                  $query_deductions = $conn->query($sql_deductions);
                  $drow = $query_deductions->fetch_assoc();
                  $deduction = $drow['total_amount'];

                  while ($row = $query->fetch_assoc()) {
                    $empid = $row['employee_id'];
                    $tot_hrs = $row['num_hr'];
                    $extras = $row['extras'];
                    $salario_hr = $row['rate'];
                    $gross = $salario_hr * $tot_hrs;
                    $is_holiday = $row['is_holiday'];
                    $valor_festivo = $is_holiday ? $salario_hr * 0.1 : 0;

                    $casql = "SELECT *, SUM(amount) AS cashamount FROM cashadvance WHERE employee_id='$empid' AND date_advance BETWEEN '$from' AND '$to'";
                    $caquery = $conn->query($casql);
                    $carow = $caquery->fetch_assoc();
                    $cashadvance = $carow['cashamount'];

                    $oveql = "SELECT rate, SUM(hours) AS horas FROM overtime WHERE employee_id='$empid' AND date_overtime BETWEEN '$from' AND '$to'";
                    $ovequery = $conn->query($oveql);
                    $over = $ovequery->fetch_assoc();

                    $overtime = $over['horas'];
                    $rate_ove = $over['rate'];

                    $cantidad_hroas_extras = ($extras / 4) - $tot_hrs;
                    $valor_extra = ($row['rate'] * 1.5) * $cantidad_hroas_extras;
                    $total_deduction = $deduction + $cashadvance;
                    $net = $gross + ($rate_ove * $overtime * $row['rate']) - $total_deduction + $valor_extra + $valor_festivo;

                    echo "
                      <tr>
                        <td>" . $row['lastname'] . ", " . $row['firstname'] . "</td>
                        <td>" . $row['employee_id'] . "</td>
                        <td>" . number_format($tot_hrs, 2) . "</td>
                        <td>" . number_format($valor_festivo, 2) . "</td>
                        <td>" . number_format($extras, 2) . "</td>
                        <td>" . number_format($gross, 2) . "</td>
                        <td>" . number_format($salario_hr, 2) . "</td>
                        <td>" . number_format($extras, 2) . "</td>
                        <td>" . number_format($salario_hr * 1.5, 2) . "</td>
                        <td>" . number_format($valor_extra, 2) . "</td>
                        <td>" . number_format($net, 2) . "</td>
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
</div>
<?php include 'includes/scripts.php'; ?>
<script>
  $(document).ready(function() {
    if (!$.fn.DataTable.isDataTable('#example1')) {
      $('#example1').DataTable({
        responsive: true,
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
      });
    }
  });

  $(function() {
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

    $("#reservation").on('change', function() {
      var range = encodeURI($(this).val());
      window.location = 'payroll.php?range=' + range;
    });

    $('#payroll').click(function(e) {
      e.preventDefault();
      $('#payForm').attr('action', 'payroll_generate.php');
      $('#payForm').submit();
    });

    $('#payslip').click(function(e) {
      e.preventDefault();
      $('#payForm').attr('action', 'payslip_generate.php');
      $('#payForm').submit();
    });

  });

  function getRow(id) {
    $.ajax({
      type: 'POST',
      url: 'position_row.php',
      data: { id: id },
      dataType: 'json',
      success: function(response) {
        $('#posid').val(response.id);
        $('#edit_title').val(response.description);
        $('#edit_rate').val(response.rate);
        $('#del_posid').val(response.id);
        $('#del_position').html(response.description);
      }
    });
  }
</script>
</body>
</html>
