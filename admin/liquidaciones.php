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
          Liquidaciones
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
          <li class="active">Liquidaciones</li>
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
                <a href="#addLiquidaciones" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Nueva Liquidación</a>
              </div>
              <div class="box-body">
                <table id="example1" class="table table-bordered">
                  <thead>
                    <th>ID Liq</th>
                    <th>ID Empleado</th>
                    <th>Nombre</th>
                    <th>Total Monto</th>
                    <th>Estado</th>
                    <th>Acción</th>
                  </thead>
                  <tbody>
                  <?php
                  // Consulta para obtener las sumas de liquidaciones por empleado
                  $sql = "SELECT liquidaciones.id, liquidaciones.employee_id, liquidaciones.total_monto, liquidaciones.status, 
                           employees.employee_id AS emp_number, 
                           employees.firstname, 
                           employees.lastname 
                    FROM liquidaciones 
                    LEFT JOIN employees ON employees.id = liquidaciones.employee_id 
                    ORDER BY liquidaciones.created_at DESC";
                  $query = $conn->query($sql);

                  while ($row = $query->fetch_assoc()) {
                    $status_label = ($row['status']) ? '<span class="label label-success pull-right">Activo</span>' : '<span class="label label-danger pull-right">Inactivo</span>';
                    echo "
                      <tr>
                        <td>" . htmlspecialchars($row['emp_number']) . "</td>
                        <td>" . htmlspecialchars($row['emp_number']) . "</td>
                        <td>" . htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) . "</td>
                        <td>$" . number_format($row['total_monto'], 2) . "</td>
                        <td>" . $status_label . "</td>
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
    <?php include 'includes/liquidacion_modal.php'; ?>
  </div>
  <?php include 'includes/scripts.php'; ?>

  <script>
  $(document).ready(function(){
      // Función para calcular la diferencia de días entre dos fechas
      function calcularDiferenciaDias(inicio, fin){
          var fechaInicio = new Date(inicio);
          var fechaFin = new Date(fin);
          var timeDiff = fechaFin - fechaInicio;
          var diffDays = Math.ceil(timeDiff / ((1000 * 60 * 60 * 24)) + 1)/12; // Incluye el día de inicio
          return diffDays;
      }

      // Función para calcular el valor del día
      function calcularValorDia(salario){
          // Ajusta este cálculo según tus necesidades
          return (salario * 8).toFixed(2); // Ejemplo: salario mensual dividido por 30 días
      }

      // Función para calcular el monto
      function calcularMonto(dias, valorDia){
          return (dias * valorDia).toFixed(2);
      }

      // Función para calcular el total monto
      function calcularTotalMonto(selectorSuffix){
          var total = 0;
          $('#'+selectorSuffix+'Liquidaciones .monto_calculado').each(function(){
              var monto = parseFloat($(this).val()) || 0;
              total += monto;
          });
          $('#total_monto_' + selectorSuffix).val(total.toFixed(2));
      }

      // Actualizar información del empleado al seleccionar en agregar
      $('#empleados_add').change(function(){
          var selected = $(this).find('option:selected');
          $('#employee_id_add').val(selected.data('empid'));
          var salario = parseFloat(selected.data('salary')) || 0;
          $('#salary_add').val(salario.toFixed(2));
          var valorDia = calcularValorDia(salario);
          $('#valor_dia_vacaciones_add').val(valorDia);
          $('#valor_dia_aguinaldo_add').val(valorDia);
          $('#valor_dia_indemnizacion_add').val(valorDia);
          calcularTotalMonto('add');
      });

      // Calcular días y montos cuando se cambian las fechas en agregar
      $('#addLiquidaciones .inicio_fecha, #addLiquidaciones .fin_fecha').change(function(){
          var tipo = $(this).data('tipo');
          var inicio = $('#inicio_' + tipo + '_add').val();
          var fin = $('#fin_' + tipo + '_add').val();
          if(inicio && fin){
              var dias = calcularDiferenciaDias(inicio, fin);
              $('#dias_' + tipo + '_add').val(dias);
              var valorDia = parseFloat($('#valor_dia_' + tipo + '_add').val()) || 0;
              var monto = calcularMonto(dias, valorDia);
              $('#monto_' + tipo + '_add').val(monto);
              calcularTotalMonto('add');
          }
      });

      // Funciones similares para el modal de editar
      $('#empleados_edit').change(function() {
          var selected = $(this).find('option:selected');
          $('#employee_id_edit').val(selected.data('empid'));
          var salario = parseFloat(selected.data('salary')) || 0;
          $('#salary_edit').val(salario.toFixed(2));
          var valorDia = calcularValorDia(salario);
          $('#valor_dia_vacaciones_edit').val(valorDia);
          $('#valor_dia_aguinaldo_edit').val(valorDia);
          $('#valor_dia_indemnizacion_edit').val(valorDia);
          calcularTotalMonto('edit');
      });

      $('#editLiquidaciones .inicio_fecha, #editLiquidaciones .fin_fecha').change(function(){
          var tipo = $(this).data('tipo');
          var inicio = $('#inicio_' + tipo + '_edit').val();
          var fin = $('#fin_' + tipo + '_edit').val();
          if(inicio && fin){
              var dias = calcularDiferenciaDias(inicio, fin);
              $('#dias_' + tipo + '_edit').val(dias);
              var valorDia = parseFloat($('#valor_dia_' + tipo + '_edit').val()) || 0;
              var monto = calcularMonto(dias, valorDia);
              $('#monto_' + tipo + '_edit').val(monto);
              calcularTotalMonto('edit');
          }
      });

      

      // Prellenar el Modal de Edición con los Datos de la Liquidación
      function getEditData(liquidacionId) {
    $.ajax({
        type: 'POST',
        url: 'liquidacion_row.php', // Asegúrate de que el nombre del archivo sea correcto
        data: { id: liquidacionId },
        dataType: 'json',
        success: function(response) {
            if(response.error){
                alert(response.error);
            } else {
                // Desactivar temporalmente el evento 'change' para evitar cálculos no deseados
                $('#empleados_edit').off('change');

                $('#edit_liquidacion_id').val(response.id);
                $('#empleados_edit').val(response.employee_id);
                $('#employee_id_edit').val(response.emp_number);
                $('#salary_edit').val(parseFloat(response.salary).toFixed(2));
                $('#status_edit').val(response.status);

                // Asignar las fechas y montos para cada tipo de liquidación
                if(response.vacaciones){
                    $('#inicio_vacaciones_edit').val(response.vacaciones.inicio);
                    $('#fin_vacaciones_edit').val(response.vacaciones.fin);
                    $('#dias_vacaciones_edit').val(response.vacaciones.dias);
                    $('#valor_dia_vacaciones_edit').val(response.vacaciones.valor_dia);
                    $('#monto_vacaciones_edit').val(response.vacaciones.monto);
                } else {
                    $('#inicio_vacaciones_edit').val('');
                    $('#fin_vacaciones_edit').val('');
                    $('#dias_vacaciones_edit').val('');
                    $('#valor_dia_vacaciones_edit').val('');
                    $('#monto_vacaciones_edit').val('');
                }

                if(response.aguinaldo){
                    $('#inicio_aguinaldo_edit').val(response.aguinaldo.inicio);
                    $('#fin_aguinaldo_edit').val(response.aguinaldo.fin);
                    $('#dias_aguinaldo_edit').val(response.aguinaldo.dias);
                    $('#valor_dia_aguinaldo_edit').val(response.aguinaldo.valor_dia);
                    $('#monto_aguinaldo_edit').val(response.aguinaldo.monto);
                } else {
                    $('#inicio_aguinaldo_edit').val('');
                    $('#fin_aguinaldo_edit').val('');
                    $('#dias_aguinaldo_edit').val('');
                    $('#valor_dia_aguinaldo_edit').val('');
                    $('#monto_aguinaldo_edit').val('');
                }

                if(response.indemnizacion){
                    $('#inicio_indemnizacion_edit').val(response.indemnizacion.inicio);
                    $('#fin_indemnizacion_edit').val(response.indemnizacion.fin);
                    $('#dias_indemnizacion_edit').val(response.indemnizacion.dias);
                    $('#valor_dia_indemnizacion_edit').val(response.indemnizacion.valor_dia);
                    $('#monto_indemnizacion_edit').val(response.indemnizacion.monto);
                } else {
                    $('#inicio_indemnizacion_edit').val('');
                    $('#fin_indemnizacion_edit').val('');
                    $('#dias_indemnizacion_edit').val('');
                    $('#valor_dia_indemnizacion_edit').val('');
                    $('#monto_indemnizacion_edit').val('');
                }

                // Reactivar el evento 'change'
                $('#empleados_edit').on('change', function() {
                    var selected = $(this).find('option:selected');
                    $('#employee_id_edit').val(selected.data('empid'));
                    var salario = parseFloat(selected.data('salary')) || 0;
                    $('#salary_edit').val(salario.toFixed(2));
                    var valorDia = calcularValorDia(salario);
                    $('#valor_dia_vacaciones_edit').val(valorDia);
                    $('#valor_dia_aguinaldo_edit').val(valorDia);
                    $('#valor_dia_indemnizacion_edit').val(valorDia);
                    calcularTotalMonto('edit');
                });

                calcularTotalMonto('edit'); // Actualizar el total_monto_edit
                $('#editLiquidaciones').modal('show');
            }
        },
        error: function(xhr, status, error){
            console.error(xhr);
            alert('Ocurrió un error al obtener los datos.');
        }
    });
}


      // Manejar la apertura del modal de edición
      $('.edit').click(function(e) {
          e.preventDefault();
          var liquidacionId = $(this).data('id'); // Obtener el ID de la liquidación
          getEditData(liquidacionId);
      });

      // Manejar errores al abrir el modal y limpiar campos al cerrarlo
      $('#addLiquidaciones, #editLiquidaciones').on('hidden.bs.modal', function () {
          $(this).find('form')[0].reset();
          // Limpiar todos los campos calculados
          $('.dias_calculado, .valor_dia_calculado, .monto_calculado').val('');
          $('#total_monto_add, #total_monto_edit').val('');
      });

      // Obtener Datos para Eliminar Liquidación (si tienes implementado el modal de eliminación)
      function getDeleteRow(id) {
          $.ajax({
              type: 'POST',
              url: 'liquidacion_row.php',
              data: { id: id },
              dataType: 'json',
              success: function(response) {
                  if(response.error){
                      alert(response.error);
                  } else {
                      $('#delete_liquidacion_id').val(response.id);
                      $('#delete_employee_name').html(response.firstname + ' ' + response.lastname);
                  }
              }
          });
      }

      // Manejar la apertura del modal de eliminación
      $('.delete').click(function(e) {
          e.preventDefault();
          var id = $(this).data('id');
          getDeleteRow(id);
          $('#deleteLiquidacion').modal('show');
      });
  });
  </script>

</body>
</html>
