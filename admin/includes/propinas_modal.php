<?php include 'includes/session.php'; ?>

<!-- Modal para agregar nueva propina -->
<div class="modal fade" id="addPropina" tabindex="-1" role="dialog" aria-labelledby="addPropinaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form class="form-horizontal" method="POST" action="propinas_add.php">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><b>Agregar Nueva Propina</b></h4>
        </div>
        <div class="modal-body">
          <!-- Selección de Empleado -->
          <div class="form-group">
            <label for="empleados_add" class="col-sm-3 control-label">Empleado</label>
            <div class="col-sm-9">
              <select class="form-control" id="empleados_add" name="employee" required>
                <option value="" selected disabled>Selecciona un empleado</option>
                <?php
                $sql = "SELECT * FROM employees ORDER BY firstname ASC";
                $query = $conn->query($sql);
                while ($row = $query->fetch_assoc()) {
                  echo "<option value='" . $row['id'] . "' data-empid='" . $row['employee_id'] . "'>" . $row['firstname'] . " " . $row['lastname'] . "</option>";
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
          <!-- Monto -->
          <div class="form-group">
            <label for="monto_add" class="col-sm-3 control-label">Monto</label>
            <div class="col-sm-9">
              <input type="number" step="0.01" class="form-control" id="monto_add" name="monto" required>
            </div>
          </div>
          <!-- Fecha -->
          <div class="form-group">
            <label for="date_add" class="col-sm-3 control-label">Fecha</label>
            <div class="col-sm-9">
              <input type="date" class="form-control" id="date_add" name="date" required>
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


<!-- Modal para editar propina -->
<div class="modal fade" id="editPropina" tabindex="-1" role="dialog" aria-labelledby="editPropinaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form class="form-horizontal" method="POST" action="propinas_edit.php">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><b>Editar Propina</b></h4>
        </div>
        <div class="modal-body">
          <input type="hidden" id="edit_propina_id" name="id"> <!-- ID oculto de la propina -->
          <!-- Selección de Empleado -->
          <div class="form-group">
            <label for="empleados_edit" class="col-sm-3 control-label">Empleado</label>
            <div class="col-sm-9">
              <select class="form-control" id="empleados_edit" name="edit_employee" required>
                <option value="" selected disabled>Selecciona un empleado</option>
                <?php
                $sql = "SELECT * FROM employees ORDER BY firstname ASC";
                $query = $conn->query($sql);
                while ($row = $query->fetch_assoc()) {
                  echo "<option value='" . $row['id'] . "' data-empid='" . $row['employee_id'] . "'>" . $row['firstname'] . " " . $row['lastname'] . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <!-- Mostrar ID Empleado -->
          <div class="form-group">
            <label for="employee_id_edit" class="col-sm-3 control-label">ID Empleado</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="employee_id_edit" name="employee_id_edit" readonly>
            </div>
          </div>
          <!-- Monto -->
          <div class="form-group">
            <label for="monto_edit" class="col-sm-3 control-label">Monto</label>
            <div class="col-sm-9">
              <input type="number" step="0.01" class="form-control" id="monto_edit" name="edit_monto" required>
            </div>
          </div>
          <!-- Fecha -->
          <div class="form-group">
            <label for="date_edit" class="col-sm-3 control-label">Fecha</label>
            <div class="col-sm-9">
              <input type="date" class="form-control" id="date_edit" name="edit_date" required>
            </div>
          </div>
          <!-- Estado -->
          <div class="form-group">
            <label for="status_edit" class="col-sm-3 control-label">Estado</label>
            <div class="col-sm-9">
              <select class="form-control" id="status_edit" name="edit_status" required>
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



<!-- Modal para eliminar propina -->
<div class="modal fade" id="deletePropina" tabindex="-1" role="dialog" aria-labelledby="deletePropinaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form class="form-horizontal" method="POST" action="propinas_delete.php">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><b>Eliminar Propina</b></h4>
        </div>
        <div class="modal-body">
          <input type="hidden" id="delete_propina_id" name="id">
          <div class="text-center">
            <p>¿Estás seguro de eliminar esta propina?</p>
            <h2 id="delete_employee_name" class="bold"></h2>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
          <button type="submit" class="btn btn-danger btn-flat" name="delete"><i class="fa fa-trash"></i> Eliminar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Scripts para manejar la selección de empleados y mostrar IDs -->
<script>
  $(document).ready(function() {
    // Agregar Propina - Mostrar ID Empleado al seleccionar
    $('#empleados_add').change(function() {
      var empid = $(this).find('option:selected').data('empid');
      $('#employee_id_add').val(empid);
    });

    // Editar Propina - Mostrar ID Empleado al seleccionar
    $('#empleados_edit').change(function() {
      var empid = $(this).find('option:selected').data('empid');
      $('#employee_id_edit').val(empid);
    });
  });
</script>