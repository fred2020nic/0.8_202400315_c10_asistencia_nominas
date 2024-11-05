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

// Consulta SQL modificada para incluir los nuevos campos y agrupar por empleado
$sql = "
SELECT 
    e.id AS employee_id,
    CONCAT(e.firstname, ' ', e.lastname) AS nombre_empleado,
    p.rate AS salario_hr,
    e.extras AS horas_contrato,
    IFNULL(att.total_hours, 0) AS total_hours,
    IFNULL(att.valor_festivo, 0) AS valor_festivo,
    IFNULL(ot.total_hours, 0) AS horas_extras,
    IFNULL(p.rate * 1.5, 0) AS valor_hora_extra,
    IFNULL(att.gross, 0) AS gross,
    IFNULL(ot.monto_horas_extra, 0) AS monto_horas_extra,
    IFNULL(prop.total_propinas, 0) AS propina,
    IFNULL(bono.total_bonos, 0) AS bonos,
    IFNULL(prestamo.total_prestamos, 0) AS prestamos,
    IFNULL(comision.total_comisiones, 0) AS comisiones,
    0 AS horas_festivas,         -- Sin datos específicos
    0 AS domingo,                -- Sin datos específicos
    0 AS horas_dominicales,      -- Sin datos específicos
    0 AS vacaciones,             -- Sin datos específicos
    (
        IFNULL(att.gross, 0) +
        IFNULL(ot.monto_horas_extra, 0) +
        IFNULL(prop.total_propinas, 0) +
        IFNULL(bono.total_bonos, 0) +
        IFNULL(comision.total_comisiones, 0) +
        IFNULL(att.valor_festivo, 0)
    ) AS devengado,
    IFNULL(deducciones.total_deducciones, 0) AS total_deducciones,
    (
        (
            IFNULL(att.gross, 0) +
            IFNULL(ot.monto_horas_extra, 0) +
            IFNULL(prop.total_propinas, 0) +
            IFNULL(bono.total_bonos, 0) +
            IFNULL(comision.total_comisiones, 0) +
            IFNULL(att.valor_festivo, 0)
        ) - 
        IFNULL(deducciones.total_deducciones, 0)
    ) AS neto,
    (
        IFNULL(att.gross, 0) +
        IFNULL(ot.monto_horas_extra, 0) +
        IFNULL(prop.total_propinas, 0) +
        IFNULL(bono.total_bonos, 0) +
        IFNULL(comision.total_comisiones, 0) +
        IFNULL(att.valor_festivo, 0)
    ) AS importe_total
FROM 
    employees e
LEFT JOIN 
    position p ON p.id = e.position_id
LEFT JOIN (
    SELECT 
        a.employee_id,
        SUM(a.num_hr) AS total_hours,
        SUM(a.num_hr * p.rate * 0.1) AS valor_festivo,
        SUM(a.num_hr * p.rate) AS gross
    FROM 
        attendance a
    JOIN 
        employees emp ON emp.id = a.employee_id
    JOIN 
        position p ON p.id = emp.position_id
    JOIN 
        festivos f ON a.date = f.holiday_date
    WHERE 
        a.date BETWEEN '$from' AND '$to'
    GROUP BY 
        a.employee_id
) att ON att.employee_id = e.id
LEFT JOIN (
    SELECT 
        employee_id,
        SUM(hours) AS total_hours,
        SUM(hours * rate * 1.5) AS monto_horas_extra
    FROM 
        overtime
    WHERE 
        date_overtime BETWEEN '$from' AND '$to'
    GROUP BY 
        employee_id
) ot ON ot.employee_id = e.id
LEFT JOIN (
    SELECT 
        employee_id,
        SUM(monto) AS total_propinas
    FROM 
        propinas
    WHERE 
        date BETWEEN '$from' AND '$to' AND status = 1
    GROUP BY 
        employee_id
) prop ON prop.employee_id = e.id
LEFT JOIN (
    SELECT 
        employee_id,
        SUM(monto) AS total_bonos
    FROM 
        bono
    WHERE 
        date BETWEEN '$from' AND '$to' AND status = 1
    GROUP BY 
        employee_id
) bono ON bono.employee_id = e.id
LEFT JOIN (
    SELECT 
        employee_id,
        SUM(amount) AS total_prestamos
    FROM 
        cashadvance
    WHERE 
        date_advance BETWEEN '$from' AND '$to'
    GROUP BY 
        employee_id
) prestamo ON prestamo.employee_id = e.id
LEFT JOIN (
    SELECT 
        employee_id,
        SUM(monto) AS total_comisiones
    FROM 
        cosiones
    WHERE 
        date BETWEEN '$from' AND '$to' AND status = 1
    GROUP BY 
        employee_id
) comision ON comision.employee_id = e.id
LEFT JOIN (
    SELECT 
        employee_id,
        SUM(monto) AS total_deducciones
    FROM 
        deducciones
    WHERE 
        date BETWEEN '$from' AND '$to' AND status = 1
    GROUP BY 
        employee_id
) deducciones ON deducciones.employee_id = e.id
WHERE 
    e.status = 1
ORDER BY 
    e.lastname ASC, e.firstname ASC
";

$query = $conn->query($sql);

if (!$query) {
    echo "Error en la consulta SQL: " . $conn->error;
    exit;
}
?>

<!-- Resto del código HTML y PHP -->


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
              <div style="overflow-x: auto;"> <!-- Barra de desplazamiento horizontal -->
                <table id="example1" class="table table-bordered">
                  <thead>
                    <tr>
                      <th>ID Empleado</th>
                      <th>Nombre Empleado</th>
                      <th>Salario por Hora</th>
                      <th>Horas Contrato</th>
                      <th>Total Horas</th>
                      <th>Valor Festivo</th>
                      <th>Horas Extras</th>
                      <th>Valor por Hora Extra</th>
                      <th>Gross</th>
                      <th>Monto Horas Extra</th>
                      <th>Propina</th>
                      <th>Bonos</th>
                      <th>Préstamos</th>
                      <th>Comisiones</th>
                      <th>Horas Festivas</th>
                      <th>Domingo</th>
                      <th>Horas Dominicales</th>
                      <th>Vacaciones</th>
                      <th>Devengado</th>
                      <th>Base ISPT</th>
                      <th>Retenciones</th>
                      <th>ISR</th>
                      <th>IMSS</th>
                      <th>PTU</th>
                      <th>Importe Total</th>
                      <th>Total Deducciones</th>
                      <th>Pago Neto</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    while ($row = $query->fetch_assoc()) {
                        echo "
                            <tr>
                                <td>" . htmlspecialchars($row['employee_id']) . "</td>
                                <td>" . htmlspecialchars($row['nombre_empleado']) . "</td>
                                <td>" . number_format($row['salario_hr'], 2) . "</td>
                                <td>" . number_format($row['horas_contrato'], 2) . "</td>
                                <td>" . number_format($row['total_hours'], 2) . "</td>
                                <td>" . number_format($row['valor_festivo'], 2) . "</td>
                                <td>" . number_format($row['horas_extras'], 2) . "</td>
                                <td>" . number_format($row['valor_hora_extra'], 2) . "</td>
                                <td>" . number_format($row['gross'], 2) . "</td>
                                <td>" . number_format($row['monto_horas_extra'], 2) . "</td>
                                <td>" . number_format($row['propina'], 2) . "</td>
                                <td>" . number_format($row['bonos'], 2) . "</td>
                                <td>" . number_format($row['prestamos'], 2) . "</td>
                                <td>" . number_format($row['comisiones'], 2) . "</td>
                                <td>0.00</td> <!-- Horas Festivas -->
                                <td>0.00</td> <!-- Domingo -->
                                <td>0.00</td> <!-- Horas Dominicales -->
                                <td>0.00</td> <!-- Vacaciones -->
                                <td>" . number_format($row['devengado'], 2) . "</td>
                                <td>0.00</td> <!-- Base ISPT -->
                                <td>0.00</td> <!-- Retenciones -->
                                <td>0.00</td> <!-- ISR -->
                                <td>0.00</td> <!-- IMSS -->
                                <td>0.00</td> <!-- PTU -->
                                <td>" . number_format($row['importe_total'], 2) . "</td>
                                <td>" . number_format($row['total_deducciones'], 2) . "</td>
                                <td>" . number_format($row['neto'], 2) . "</td>
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
