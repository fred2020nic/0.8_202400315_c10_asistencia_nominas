<!-- Add Modal -->
<div class="modal fade" id="addnew">
  <div class="modal-dialog">
    <div class="modal-content">
    	<div class="modal-header">
      	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
      		<span aria-hidden="true">&times;</span></button>
      	<h4 class="modal-title"><b>Agregar Nuevo Registro de Asistencia</b></h4>
    	</div>
    	<div class="modal-body">
      	<form class="form-horizontal" method="POST" action="chequeador_add.php">
      		<div class="form-group">
        		<label for="employee" class="col-sm-3 control-label">Colaborador</label>

        		<div class="col-sm-9">
          			<select class="form-control" id="employee" name="employee_id" required>
            			<option value="" disabled selected>Selecciona un Colaborador</option>
            			<?php
              				$sql = "SELECT * FROM employees ORDER BY firstname ASC, lastname ASC";
              				$employee_query = $conn->query($sql);
              				while($employee = $employee_query->fetch_assoc()){
                				echo "<option value='".$employee['id']."'>".$employee['firstname'].' '.$employee['lastname']."</option>";
              				}
            			?>
          			</select>
        		</div>
      		</div>
      		<div class="form-group">
        		<label for="ocurrencia" class="col-sm-3 control-label">Ocurrencia</label>

        		<div class="col-sm-9">
          			<select class="form-control" id="ocurrencia" name="ocurrencia_id" required>
            			<option value="" disabled selected>Selecciona una Ocurrencia</option>
            			<?php
              				$sql = "SELECT * FROM ocurrencia ORDER BY name ASC";
              				$ocurrencia_query = $conn->query($sql);
              				while($ocurrencia = $ocurrencia_query->fetch_assoc()){
                				echo "<option value='".$ocurrencia['id']."'>".$ocurrencia['name']."</option>";
              				}
            			?>
          			</select>
        		</div>
      		</div>
      		<div class="form-group">
        		<label for="date" class="col-sm-3 control-label">Fecha</label>

        		<div class="col-sm-9">
          			<input type="date" class="form-control" id="date" name="date" required>
        		</div>
      		</div>
      		<div class="form-group">
        		<label for="justificacion" class="col-sm-3 control-label">Justificación</label>

        		<div class="col-sm-9">
          			<textarea class="form-control" id="justificacion" name="justificacion" rows="3" required></textarea>
        		</div>
      		</div>
      		<div class="form-group">
        		<label for="retardos" class="col-sm-3 control-label">Retardos Acumulados</label>

        		<div class="col-sm-9">
          			<input type="number" step="0.1" class="form-control" id="retardos" name="retardos" required>
        		</div>
      		</div>
      		<div class="form-group">
        		<label for="status" class="col-sm-3 control-label">Estado</label>

        		<div class="col-sm-9">
          			<select class="form-control" id="status" name="status" required>
            			<option value="" disabled selected>Selecciona un Estado</option>
            			<option value="1">Activo</option>
            			<option value="0">Inactivo</option>
          			</select>
        		</div>
      		</div>
    	</div>
    	<div class="modal-footer">
      	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">
        	<i class="fa fa-close"></i> Cerrar
      	</button>
      	<button type="submit" class="btn btn-primary btn-flat" name="add">
        	<i class="fa fa-save"></i> Guardar
      	</button>
      	</form>
    	</div>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="edit">
  <div class="modal-dialog">
    <div class="modal-content">
    	<div class="modal-header">
      	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
      		<span aria-hidden="true">&times;</span></button>
      	<h4 class="modal-title"><b>Actualizar Registro de Asistencia</b></h4>
    	</div>
    	<div class="modal-body">
      	<form class="form-horizontal" method="POST" action="chequeador_edit.php">
      		<input type="hidden" id="chequeadorid" name="id">
      		<div class="form-group">
        		<label for="edit_employee" class="col-sm-3 control-label">Colaborador</label>

        		<div class="col-sm-9">
          			<select class="form-control" id="edit_employee" name="employee_id" required>
            			<option value="" disabled>Selecciona un Colaborador</option>
            			<?php
              				$sql = "SELECT * FROM employees ORDER BY firstname ASC, lastname ASC";
              				$employee_query = $conn->query($sql);
              				while($employee = $employee_query->fetch_assoc()){
                				echo "<option value='".$employee['id']."'>".$employee['firstname'].' '.$employee['lastname']."</option>";
              				}
            			?>
          			</select>
        		</div>
      		</div>
      		<div class="form-group">
        		<label for="edit_ocurrencia" class="col-sm-3 control-label">Ocurrencia</label>

        		<div class="col-sm-9">
          			<select class="form-control" id="edit_ocurrencia" name="ocurrencia_id" required>
            			<option value="" disabled>Selecciona una Ocurrencia</option>
            			<?php
              				$sql = "SELECT * FROM ocurrencia ORDER BY name ASC";
              				$ocurrencia_query = $conn->query($sql);
              				while($ocurrencia = $ocurrencia_query->fetch_assoc()){
                				echo "<option value='".$ocurrencia['id']."'>".$ocurrencia['name']."</option>";
              				}
            			?>
          			</select>
        		</div>
      		</div>
      		<div class="form-group">
        		<label for="edit_date" class="col-sm-3 control-label">Fecha</label>

        		<div class="col-sm-9">
          			<input type="date" class="form-control" id="edit_date" name="date" required>
        		</div>
      		</div>
      		<div class="form-group">
        		<label for="edit_justificacion" class="col-sm-3 control-label">Justificación</label>

        		<div class="col-sm-9">
          			<textarea class="form-control" id="edit_justificacion" name="justificacion" rows="3" required></textarea>
        		</div>
      		</div>
      		<div class="form-group">
        		<label for="edit_retardos" class="col-sm-3 control-label">Retardos Acumulados</label>

        		<div class="col-sm-9">
          			<input type="number" step="0.1" class="form-control" id="edit_retardos" name="retardos" required>
        		</div>
      		</div>
      		<div class="form-group">
        		<label for="edit_status" class="col-sm-3 control-label">Estado</label>

        		<div class="col-sm-9">
          			<select class="form-control" id="edit_status" name="status" required>
            			<option value="" disabled>Selecciona un Estado</option>
            			<option value="1">Activo</option>
            			<option value="0">Inactivo</option>
          			</select>
        		</div>
      		</div>
    	</div>
    	<div class="modal-footer">
      	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">
        	<i class="fa fa-close"></i> Cerrar
      	</button>
      	<button type="submit" class="btn btn-success btn-flat" name="edit">
        	<i class="fa fa-check-square-o"></i> Actualizar
      	</button>
      	</form>
    	</div>
    </div>
  </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="view">
  <div class="modal-dialog">
    <div class="modal-content">
    	<div class="modal-header">
      	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
      		<span aria-hidden="true">&times;</span></button>
      	<h4 class="modal-title"><b>Detalles del Registro de Asistencia</b></h4>
    	</div>
    	<div class="modal-body">
      	<div id="reportContent">
      		<div class="form-group">
        		<label for="view_employee" class="col-sm-3 control-label">Colaborador:</label>
        		<div class="col-sm-9">
          			<p id="view_employee"></p>
        		</div>
      		</div>
      		<div class="form-group">
        		<label for="view_ocurrencia" class="col-sm-3 control-label">Ocurrencia:</label>
        		<div class="col-sm-9">
          			<p id="view_ocurrencia"></p>
        		</div>
      		</div>
      		<div class="form-group">
        		<label for="view_date" class="col-sm-3 control-label">Fecha:</label>
        		<div class="col-sm-9">
          			<p id="view_date"></p>
        		</div>
      		</div>
      		<div class="form-group">
        		<label for="view_justificacion" class="col-sm-3 control-label">Justificación:</label>
        		<div class="col-sm-9">
          			<p id="view_justificacion"></p>
        		</div>
      		</div>
      		<div class="form-group">
        		<label for="view_retardos" class="col-sm-3 control-label">Retardos Acumulados:</label>
        		<div class="col-sm-9">
          			<p id="view_retardos"></p>
        		</div>
      		</div>
      		<div class="form-group">
        		<label for="view_status" class="col-sm-3 control-label">Estado:</label>
        		<div class="col-sm-9">
          			<p id="view_status"></p>
        		</div>
      		</div>
      	</div>
    	</div>
    	<div class="modal-footer">
      	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">
        	<i class="fa fa-close"></i> Cerrar
      	</button>
      	<!-- <button type="button" class="btn btn-primary btn-flat" id="printReport">
        	<i class="fa fa-print"></i> Imprimir
      	</button> -->
    	</div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="delete">
  <div class="modal-dialog">
    <div class="modal-content">
    	<div class="modal-header">
      	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
      		<span aria-hidden="true">&times;</span></button>
      	<h4 class="modal-title"><b>Eliminar Registro de Asistencia</b></h4>
    	</div>
    	<div class="modal-body">
      	<form class="form-horizontal" method="POST" action="chequeador_delete.php">
      		<input type="hidden" id="del_chequeadorid" name="id">
      		<div class="text-center">
        		<p>¿Estás seguro de eliminar este registro de asistencia?</p>
        		<h2 id="del_employee" class="bold"></h2>
        		<p id="del_ocurrencia"></p>
        		<p id="del_date"></p>
      		</div>
    	</div>
    	<div class="modal-footer">
      	<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">
        	<i class="fa fa-close"></i> Cerrar
      	</button>
      	<button type="submit" class="btn btn-danger btn-flat" name="delete">
        	<i class="fa fa-trash"></i> Eliminar
      	</button>
      	</form>
    	</div>
    </div>
  </div>
</div>
