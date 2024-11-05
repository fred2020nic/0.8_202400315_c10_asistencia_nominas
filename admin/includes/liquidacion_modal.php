<!-- liquidacion_modal.php -->
<?php include 'includes/session.php'; ?>

<!-- Modal para agregar nuevas liquidaciones -->
<div class="modal fade" id="addLiquidaciones" tabindex="-1" role="dialog" aria-labelledby="addLiquidacionesLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"> <!-- Usamos modal-lg para mayor espacio -->
    <div class="modal-content">
      <form class="form-horizontal" method="POST" action="liquidaciones_add.php">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><b>Agregar Nuevas Liquidaciones</b></h4>
        </div>
        <div class="modal-body">
          <!-- Selección de Empleado -->
          <div class="form-group">
            <label for="empleados_add" class="col-sm-3 control-label">Empleado</label>
            <div class="col-sm-9">
              <select class="form-control" id="empleados_add" name="employee" required>
                <option value="" selected disabled>Selecciona un empleado</option>
                <?php
                $sql = "SELECT employees.*, position.rate, employees.fecha_ingreso 
                        FROM employees 
                        LEFT JOIN position ON employees.position_id = position.id 
                        ORDER BY employees.firstname ASC";
                $query = $conn->query($sql);
                while ($row = $query->fetch_assoc()) {
                  $rate = isset($row['rate']) ? $row['rate'] : 0;
                  $fecha_ingreso = isset($row['fecha_ingreso']) ? $row['fecha_ingreso'] : '';
                  echo "<option value='" . $row['id'] . "' data-salary='" . $rate . "' data-empid='" . htmlspecialchars($row['employee_id']) . "' data-fecha-ingreso='" . htmlspecialchars($fecha_ingreso) . "'>" . htmlspecialchars($row['firstname'] . " " . $row['lastname']) . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <!-- Mostrar ID Empleado -->
          <div class="form-group">
            <label for="employee_id_add" class="col-sm-3 control-label">ID Empleado</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="employee_id_add" name="employee_id" readonly>
            </div>
          </div>

          <!-- Mostrar Salario del Empleado -->
          <div class="form-group">
            <label for="salary_add" class="col-sm-3 control-label">Salario</label>
            <div class="col-sm-9">
              <input type="number" step="0.01" class="form-control" id="salary_add" name="salary" readonly>
            </div>
          </div>

          <!-- Secciones de Liquidaciones -->
          <div class="row">
            <!-- Vacaciones -->
            <div class="col-md-4">
              <h4>Vacaciones</h4>
              <div class="form-group">
                <label for="inicio_vacaciones_add">Inicio</label>
                <input type="date" class="form-control inicio_fecha" data-tipo="vacaciones" id="inicio_vacaciones_add" name="vacaciones[inicio]" required>
              </div>
              <div class="form-group">
                <label for="fin_vacaciones_add">Fin</label>
                <input type="date" class="form-control fin_fecha" data-tipo="vacaciones" id="fin_vacaciones_add" name="vacaciones[fin]" required>
              </div>
              <div class="form-group">
                <label for="dias_vacaciones_add">Días</label>
                <input type="number" step="0.01" class="form-control dias_calculado" id="dias_vacaciones_add" name="vacaciones[dias]" readonly>
              </div>
              <div class="form-group">
                <label for="valor_dia_vacaciones_add">Valor Día</label>
                <input type="number" step="0.01" class="form-control valor_dia_calculado" id="valor_dia_vacaciones_add" name="vacaciones[valor_dia]" readonly>
              </div>
              <div class="form-group">
                <label for="monto_vacaciones_add">Monto</label>
                <input type="number" step="0.01" class="form-control monto_calculado" id="monto_vacaciones_add" name="vacaciones[monto]" readonly>
              </div>
            </div>

            <!-- Aguinaldo -->
            <div class="col-md-4">
              <h4>Aguinaldo</h4>
              <div class="form-group">
                <label for="inicio_aguinaldo_add">Inicio</label>
                <input type="date" class="form-control inicio_fecha" data-tipo="aguinaldo" id="inicio_aguinaldo_add" name="aguinaldo[inicio]" required>
              </div>
              <div class="form-group">
                <label for="fin_aguinaldo_add">Fin</label>
                <input type="date" class="form-control fin_fecha" data-tipo="aguinaldo" id="fin_aguinaldo_add" name="aguinaldo[fin]" required>
              </div>
              <div class="form-group">
                <label for="dias_aguinaldo_add">Días</label>
                <input type="number" step="0.01" class="form-control dias_calculado" id="dias_aguinaldo_add" name="aguinaldo[dias]" readonly>
              </div>
              <div class="form-group">
                <label for="valor_dia_aguinaldo_add">Valor Día</label>
                <input type="number" step="0.01" class="form-control valor_dia_calculado" id="valor_dia_aguinaldo_add" name="aguinaldo[valor_dia]" readonly>
              </div>
              <div class="form-group">
                <label for="monto_aguinaldo_add">Monto</label>
                <input type="number" step="0.01" class="form-control monto_calculado" id="monto_aguinaldo_add" name="aguinaldo[monto]" readonly>
              </div>
            </div>

            <!-- Indemnización -->
            <div class="col-md-4">
              <h4>Indemnización</h4>
              <div class="form-group">
                <label for="inicio_indemnizacion_add">Inicio</label>
                <input type="date" class="form-control inicio_fecha" data-tipo="indemnizacion" id="inicio_indemnizacion_add" name="indemnizacion[inicio]" required>
              </div>
              <div class="form-group">
                <label for="fin_indemnizacion_add">Fin</label>
                <input type="date" class="form-control fin_fecha" data-tipo="indemnizacion" id="fin_indemnizacion_add" name="indemnizacion[fin]" required>
              </div>
              <div class="form-group">
                <label for="dias_indemnizacion_add">Días</label>
                <input type="number" step="0.01" class="form-control dias_calculado" id="dias_indemnizacion_add" name="indemnizacion[dias]" readonly>
              </div>
              <div class="form-group">
                <label for="valor_dia_indemnizacion_add">Valor Día</label>
                <input type="number" step="0.01" class="form-control valor_dia_calculado" id="valor_dia_indemnizacion_add" name="indemnizacion[valor_dia]" readonly>
              </div>
              <div class="form-group">
                <label for="monto_indemnizacion_add">Monto</label>
                <input type="number" step="0.01" class="form-control monto_calculado" id="monto_indemnizacion_add" name="indemnizacion[monto]" readonly>
              </div>
            </div>
          </div>

          <!-- Campo de Suma Total -->
          <div class="form-group">
            <label for="total_monto_add" class="col-sm-3 control-label">Total Montos</label>
            <div class="col-sm-9">
              <input type="number" step="0.01" class="form-control" id="total_monto_add" name="total_monto" readonly>
            </div>
          </div>

          <!-- Estado -->
          <div class="form-group">
            <label for="status_add" class="col-sm-3 control-label">Estado</label>
            <div class="col-sm-9">
              <select class="form-control" id="status_add" name="status" required>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
          <button type="submit" class="btn btn-primary btn-flat" name="add"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal para editar liquidaciones -->
<div class="modal fade" id="editLiquidaciones" tabindex="-1" role="dialog" aria-labelledby="editLiquidacionesLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form class="form-horizontal" method="POST" action="liquidacion_edit.php">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><b>Editar Liquidaciones</b></h4>
        </div>
        <div class="modal-body">
          <input id="edit_liquidacion_id" name="liquidacion_id">

          <!-- Selección de Empleado -->
          <div class="form-group">
            <label for="empleados_edit" class="col-sm-3 control-label">Empleado</label>
            <div class="col-sm-9">
              <select class="form-control" id="empleados_edit" name="edit_employee" required>
                <option value="" selected disabled>Selecciona un empleado</option>
                <?php
                $sql = "SELECT employees.*, position.rate, employees.fecha_ingreso 
                        FROM employees 
                        LEFT JOIN position ON employees.position_id = position.id 
                        ORDER BY employees.firstname ASC";
                $query = $conn->query($sql);
                while ($row = $query->fetch_assoc()) {
                  $rate = isset($row['rate']) ? $row['rate'] : 0;
                  $fecha_ingreso = isset($row['fecha_ingreso']) ? $row['fecha_ingreso'] : '';
                  echo "<option value='" . $row['id'] . "' data-salary='" . $rate . "' data-empid='" . htmlspecialchars($row['employee_id']) . "' data-fecha-ingreso='" . htmlspecialchars($fecha_ingreso) . "'>" . htmlspecialchars($row['firstname'] . " " . $row['lastname']) . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <!-- Mostrar ID Empleado -->
          <div class="form-group">
            <label for="employee_id_edit" class="col-sm-3 control-label">ID Empleado</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="employee_id_edit" name="employee_id_display" readonly>
            </div>
          </div>

          <!-- Mostrar Salario del Empleado -->
          <div class="form-group">
            <label for="salary_edit" class="col-sm-3 control-label">Salario</label>
            <div class="col-sm-9">
              <input type="number" step="0.01" class="form-control" id="salary_edit" name="salary" readonly>
            </div>
          </div>

          <!-- Secciones de Liquidaciones -->
          <div class="row">
            <!-- Vacaciones -->
            <div class="col-md-4">
              <h4>Vacaciones</h4>
              <div class="form-group">
                <label for="inicio_vacaciones_edit">Inicio</label>
                <input type="date" class="form-control inicio_fecha" data-tipo="vacaciones" id="inicio_vacaciones_edit" name="vacaciones[inicio]" required>
              </div>
              <div class="form-group">
                <label for="fin_vacaciones_edit">Fin</label>
                <input type="date" class="form-control fin_fecha" data-tipo="vacaciones" id="fin_vacaciones_edit" name="vacaciones[fin]" required>
              </div>
              <div class="form-group">
                <label for="dias_vacaciones_edit">Días</label>
                <input type="number" step="0.01" class="form-control dias_calculado" id="dias_vacaciones_edit" name="vacaciones[dias]" readonly>
              </div>
              <div class="form-group">
                <label for="valor_dia_vacaciones_edit">Valor Día</label>
                <input type="number" step="0.01" class="form-control valor_dia_calculado" id="valor_dia_vacaciones_edit" name="vacaciones[valor_dia]" readonly>
              </div>
              <div class="form-group">
                <label for="monto_vacaciones_edit">Monto</label>
                <input type="number" step="0.01" class="form-control monto_calculado" id="monto_vacaciones_edit" name="vacaciones[monto]" readonly>
              </div>
            </div>

            <!-- Aguinaldo -->
            <div class="col-md-4">
              <h4>Aguinaldo</h4>
              <div class="form-group">
                <label for="inicio_aguinaldo_edit">Inicio</label>
                <input type="date" class="form-control inicio_fecha" data-tipo="aguinaldo" id="inicio_aguinaldo_edit" name="aguinaldo[inicio]" required>
              </div>
              <div class="form-group">
                <label for="fin_aguinaldo_edit">Fin</label>
                <input type="date" class="form-control fin_fecha" data-tipo="aguinaldo" id="fin_aguinaldo_edit" name="aguinaldo[fin]" required>
              </div>
              <div class="form-group">
                <label for="dias_aguinaldo_edit">Días</label>
                <input type="number" step="0.01" class="form-control dias_calculado" id="dias_aguinaldo_edit" name="aguinaldo[dias]" readonly>
              </div>
              <div class="form-group">
                <label for="valor_dia_aguinaldo_edit">Valor Día</label>
                <input type="number" step="0.01" class="form-control valor_dia_calculado" id="valor_dia_aguinaldo_edit" name="aguinaldo[valor_dia]" readonly>
              </div>
              <div class="form-group">
                <label for="monto_aguinaldo_edit">Monto</label>
                <input type="number" step="0.01" class="form-control monto_calculado" id="monto_aguinaldo_edit" name="aguinaldo[monto]" readonly>
              </div>
            </div>

            <!-- Indemnización -->
            <div class="col-md-4">
              <h4>Indemnización</h4>
              <div class="form-group">
                <label for="inicio_indemnizacion_edit">Inicio</label>
                <input type="date" class="form-control inicio_fecha" data-tipo="indemnizacion" id="inicio_indemnizacion_edit" name="indemnizacion[inicio]" required>
              </div>
              <div class="form-group">
                <label for="fin_indemnizacion_edit">Fin</label>
                <input type="date" class="form-control fin_fecha" data-tipo="indemnizacion" id="fin_indemnizacion_edit" name="indemnizacion[fin]" required>
              </div>
              <div class="form-group">
                <label for="dias_indemnizacion_edit">Días</label>
                <input type="number" step="0.01" class="form-control dias_calculado" id="dias_indemnizacion_edit" name="indemnizacion[dias]" readonly>
              </div>
              <div class="form-group">
                <label for="valor_dia_indemnizacion_edit">Valor Día</label>
                <input type="number" step="0.01" class="form-control valor_dia_calculado" id="valor_dia_indemnizacion_edit" name="indemnizacion[valor_dia]" readonly>
              </div>
              <div class="form-group">
                <label for="monto_indemnizacion_edit">Monto</label>
                <input type="number" step="0.01" class="form-control monto_calculado" id="monto_indemnizacion_edit" name="indemnizacion[monto]" readonly>
              </div>
            </div>
          </div>

          <!-- Campo de Suma Total -->
          <div class="form-group">
            <label for="total_monto_edit" class="col-sm-3 control-label">Total Montos</label>
            <div class="col-sm-9">
              <input type="number" step="0.01" class="form-control" id="total_monto_edit" name="total_monto" readonly>
            </div>
          </div>

          <!-- Estado -->
          <div class="form-group">
            <label for="status_edit" class="col-sm-3 control-label">Estado</label>
            <div class="col-sm-9">
              <select class="form-control" id="status_edit" name="status" required>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
          <button type="submit" class="btn btn-success btn-flat" name="edit"><i class="fa fa-check-square-o"></i> Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>
