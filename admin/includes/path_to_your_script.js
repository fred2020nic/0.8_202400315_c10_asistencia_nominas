// path_to_your_script.js
$(document).ready(function() {
    // Evento al hacer clic en el botón "Editar"
    $('.edit').click(function(e) {
      e.preventDefault();
      $('#editLiquidaciones').modal('show');
      var liquidacionId = $(this).data('id'); // Obtener el ID de la liquidación
      getEditData(liquidacionId);
    });
  
    // Evento al cambiar el empleado en el modal de agregar
    $('#empleados_add').change(function() {
      var selectedOption = $(this).find('option:selected');
      var empid = selectedOption.data('empid');
      var salary = parseFloat(selectedOption.data('salary'));
      var fecha_ingreso = selectedOption.data('fecha-ingreso');
  
      if (isNaN(salary)) {
        alert('El salario del empleado seleccionado no está disponible.');
        salary = 0;
      }
  
      $('#employee_id_add').val(empid);
      $('#salary_add').val(salary.toFixed(2));
  
      // Asignar la fecha de ingreso si está disponible
      if (fecha_ingreso) {
        $('#inicio_vacaciones_add').val(fecha_ingreso);
        $('#inicio_aguinaldo_add').val(fecha_ingreso);
        $('#inicio_indemnizacion_add').val(fecha_ingreso);
      }
  
      // Calcular y asignar valor_dia para todas las liquidaciones
      $('.valor_dia_calculado').each(function() {
        var valor_dia = salary / 8; // Asegúrate de que este cálculo sea correcto según tus necesidades
        $(this).val(valor_dia.toFixed(2));
      });
    });
  
    // Evento al cambiar el empleado en el modal de editar
    $('#empleados_edit').change(function() {
      var selectedOption = $(this).find('option:selected');
      var empid = selectedOption.data('empid');
      var salary = parseFloat(selectedOption.data('salary'));
      var fecha_ingreso = selectedOption.data('fecha-ingreso');
  
      if (isNaN(salary)) {
        alert('El salario del empleado seleccionado no está disponible.');
        salary = 0;
      }
  
      $('#employee_id_edit').val(empid);
      $('#salary_edit').val(salary.toFixed(2));
  
      // Asignar la fecha de ingreso si está disponible
      if (fecha_ingreso) {
        $('#inicio_vacaciones_edit').val(fecha_ingreso);
        $('#inicio_aguinaldo_edit').val(fecha_ingreso);
        $('#inicio_indemnizacion_edit').val(fecha_ingreso);
      }
  
      // Calcular y asignar valor_dia para todas las liquidaciones
      $('.valor_dia_calculado').each(function() {
        var valor_dia = salary / 8; // Asegúrate de que este cálculo sea correcto según tus necesidades
        $(this).val(valor_dia.toFixed(2));
      });
    });
  
    // Función para calcular días y monto
    function calcularDiasMonto(prefix, suffix) {
      var inicio = $('#inicio_' + prefix + '_' + suffix).val();
      var fin = $('#fin_' + prefix + '_' + suffix).val();
      var diasInput = $('#dias_' + prefix + '_' + suffix);
      var montoInput = $('#monto_' + prefix + '_' + suffix);
  
      if (inicio && fin) {
        var startDate = new Date(inicio);
        var endDate = new Date(fin);
        var timeDiff = endDate - startDate;
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // Incluyendo el día de inicio
        diasInput.val(diffDays);
  
        var valor_dia = parseFloat($('#valor_dia_' + prefix + '_' + suffix).val());
        if (!isNaN(valor_dia)) {
          var monto = diffDays * valor_dia;
          montoInput.val(monto.toFixed(2));
        }
      }
    }
  
    // Eventos al cambiar las fechas en el modal de agregar
    $('#inicio_vacaciones_add, #fin_vacaciones_add, #inicio_aguinaldo_add, #fin_aguinaldo_add, #inicio_indemnizacion_add, #fin_indemnizacion_add').change(function() {
      var id_parts = $(this).attr('id').split('_');
      var prefix = id_parts[1]; // 'vacaciones', 'aguinaldo', 'indemnizacion'
      calcularDiasMonto(prefix, 'add');
    });
  
    // Eventos al cambiar las fechas en el modal de editar
    $('#inicio_vacaciones_edit, #fin_vacaciones_edit, #inicio_aguinaldo_edit, #fin_aguinaldo_edit, #inicio_indemnizacion_edit, #fin_indemnizacion_edit').change(function() {
      var id_parts = $(this).attr('id').split('_');
      var prefix = id_parts[1]; // 'vacaciones', 'aguinaldo', 'indemnizacion'
      calcularDiasMonto(prefix, 'edit');
    });
  
    // Prellenar el Modal de Edición con los Datos de la Liquidación
    function getEditData(liquidacionId) {
      $.ajax({
        type: 'POST',
        url: 'liquidaciones_row.php',
        data: { id: liquidacionId },
        dataType: 'json',
        success: function(response) {
          if(response.error){
            alert(response.error);
          } else {
            $('#edit_liquidacion_id').val(response.id);
            $('#empleados_edit').val(response.employee_id).trigger('change');
            $('#employee_id_edit').val(response.emp_number);
            $('#tipo_edit').val(response.tipo).trigger('change');
            $('#inicio_vacaciones_edit').val(response.vacaciones.inicio);
            $('#fin_vacaciones_edit').val(response.vacaciones.fin);
            $('#dias_vacaciones_edit').val(response.vacaciones.dias);
            $('#valor_dia_vacaciones_edit').val(response.vacaciones.valor_dia);
            $('#monto_vacaciones_edit').val(response.vacaciones.monto);
            $('#inicio_aguinaldo_edit').val(response.aguinaldo.inicio);
            $('#fin_aguinaldo_edit').val(response.aguinaldo.fin);
            $('#dias_aguinaldo_edit').val(response.aguinaldo.dias);
            $('#valor_dia_aguinaldo_edit').val(response.aguinaldo.valor_dia);
            $('#monto_aguinaldo_edit').val(response.aguinaldo.monto);
            $('#inicio_indemnizacion_edit').val(response.indemnizacion.inicio);
            $('#fin_indemnizacion_edit').val(response.indemnizacion.fin);
            $('#dias_indemnizacion_edit').val(response.indemnizacion.dias);
            $('#valor_dia_indemnizacion_edit').val(response.indemnizacion.valor_dia);
            $('#monto_indemnizacion_edit').val(response.indemnizacion.monto);
            $('#status_edit').val(response.status);
            $('#inactivar_edit').val(response.inactivar_empleado);
          }
        },
        error: function(xhr, status, error){
          console.error(xhr);
          alert('Ocurrió un error al obtener los datos.');
        }
      });
    }
  
    // Manejar errores al abrir el modal y limpiar campos al cerrarlo
    $('#addLiquidaciones, #editLiquidaciones').on('hidden.bs.modal', function () {
      $(this).find('form')[0].reset();
      // Limpiar todos los campos calculados
      $('.dias_calculado, .valor_dia_calculado, .monto_calculado').val('');
    });
  });
  