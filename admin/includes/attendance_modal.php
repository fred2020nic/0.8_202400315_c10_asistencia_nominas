<!-- Add -->
<div class="modal fade" id="addnew">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><b>Agregar Asistencia</b></h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="attendance_add.php">
					<div class="form-group">
						<label for="empleados" class="col-sm-3 control-label">Empleado</label>

						<div class="col-sm-9">
							<select class="form-control" id="empleados">
								<option value=""></option>
								<?php include 'session.php';
								$sql = "SELECT * FROM employees";
								$query = $conn->query($sql);
								$total = 0;
								while ($row = $query->fetch_assoc()) {
									echo "<option value='" . $row['employee_id'] . "'>" . $row['firstname'] . " " . $row['lastname'] . "</option>";
								}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="employee" class="col-sm-3 control-label">ID Empleado</label>

						<div class="col-sm-9">
							<input type="text" class="form-control" id="employee" name="employee" required>
						</div>
					</div>
					<div class="form-group">
						<label for="datepicker_add" class="col-sm-3 control-label">Fecha</label>

						<div class="col-sm-9">
							<div class="date">
								<input type="text" class="form-control" id="datepicker_add" name="date" required>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="time_in" class="col-sm-3 control-label">Hora de Entrada</label>

						<div class="col-sm-9">
							<div class="bootstrap-timepicker">
								<input type="text" class="form-control timepicker" id="time_in" name="time_in">
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="lunch_out" class="col-sm-3 control-label">Hora Comida Out</label>

						<div class="col-sm-9">
							<div class="bootstrap-timepicker">
								<input type="text" class="form-control timepicker" id="lunch_out" name="lunch_out">
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="lunch_in" class="col-sm-3 control-label">Hora Comida In</label>

						<div class="col-sm-9">
							<div class="bootstrap-timepicker">
								<input type="text" class="form-control timepicker" id="lunch_in" name="lunch_in">
							</div>
						</div>
					</div>


					<div class="form-group">
						<label for="time_out" class="col-sm-3 control-label">Hora de Salida</label>

						<div class="col-sm-9">
							<div class="bootstrap-timepicker">
								<input type="text" class="form-control timepicker" id="time_out" name="time_out">
							</div>
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
				<button type="submit" class="btn btn-primary btn-flat" name="add"><i class="fa fa-save"></i> Guardar</button>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Edit -->
<div class="modal fade" id="edit">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><b><span id="employee_name"></span></b></h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="attendance_edit.php">
					<input type="hidden" id="attid" name="id">
					<div class="form-group">
						<label for="datepicker_edit" class="col-sm-3 control-label">Fecha</label>

						<div class="col-sm-9">
							<div class="date">
								<input type="text" class="form-control" id="datepicker_edit" name="edit_date">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="edit_time_in" class="col-sm-3 control-label">Hora Entrada</label>

						<div class="col-sm-9">
							<div class="bootstrap-timepicker">
								<input type="text" class="form-control timepicker" id="edit_time_in" name="edit_time_in">
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="edit_lunch_out" class="col-sm-3 control-label">Hora Comida Out</label>

						<div class="col-sm-9">
							<div class="bootstrap-timepicker">
								<input type="text" class="form-control timepicker" id="edit_lunch_out" name="edit_lunch_out">
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="edit_lunch_in" class="col-sm-3 control-label">Hora Comida In</label>

						<div class="col-sm-9">
							<div class="bootstrap-timepicker">
								<input type="text" class="form-control timepicker" id="edit_lunch_in" name="edit_lunch_in">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="edit_time_out" class="col-sm-3 control-label">Hora Salida</label>

						<div class="col-sm-9">
							<div class="bootstrap-timepicker">
								<input type="text" class="form-control timepicker" id="edit_time_out" name="edit_time_out">
							</div>
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
				<button type="submit" class="btn btn-success btn-flat" name="edit"><i class="fa fa-check-square-o"></i> Actualizar</button>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Delete -->
<div class="modal fade" id="delete">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><b><span id="attendance_date"></span></b></h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="attendance_delete.php">
					<input type="hidden" id="del_attid" name="id">
					<div class="text-center">
						<p>ELIMINAR EMPLEADO</p>
						<h2 id="del_employee_name" class="bold"></h2>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
				<button type="submit" class="btn btn-danger btn-flat" name="delete"><i class="fa fa-trash"></i> Eliminar</button>
				</form>
			</div>
		</div>
	</div>
</div>