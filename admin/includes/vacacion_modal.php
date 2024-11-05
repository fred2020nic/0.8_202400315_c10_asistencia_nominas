<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Agregar Solicitud de Vacaciones</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="vacacion_add.php">
                    <div class="form-group">
                        <label for="add_empleados" class="col-sm-3 control-label">Empleado</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="add_empleados" name="employee" required>
                                <option value=""></option>
                                <?php
                                $sql = "SELECT employees.id, employees.employee_id, employees.firstname, employees.lastname, position.description AS puesto 
                                        FROM employees 
                                        LEFT JOIN position ON position.id = employees.position_id";
                                $query = $conn->query($sql);
                                while ($row = $query->fetch_assoc()) {
                                    echo "<option value='" . $row['employee_id'] . "' data-puesto='" . $row['puesto'] . "'>" . $row['firstname'] . " " . $row['lastname'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add_empresa" class="col-sm-3 control-label">Empresa</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="add_empresa" name="empresa" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add_puesto" class="col-sm-3 control-label">Puesto</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="add_puesto" name="puesto">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add_con_goce" class="col-sm-3 control-label">Con Goce de Sueldo</label>
                        <div class="col-sm-9">
                            <input type="checkbox" id="add_con_goce" name="con_goce">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add_sin_goce" class="col-sm-3 control-label">Sin Goce de Sueldo</label>
                        <div class="col-sm-9">
                            <input type="checkbox" id="add_sin_goce" name="sin_goce">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add_fecha_inicio" class="col-sm-3 control-label">Fecha de Inicio</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" id="add_fecha_inicio" name="fecha_inicio" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add_fecha_fin" class="col-sm-3 control-label">Fecha de Fin</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" id="add_fecha_fin" name="fecha_fin" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add_fecha_regreso" class="col-sm-3 control-label">Fecha de Regreso</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" id="add_fecha_regreso" name="fecha_regreso" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add_hora_retiro" class="col-sm-3 control-label">Hora de Retiro</label>
                        <div class="col-sm-9">
                            <input type="time" class="form-control" id="add_hora_retiro" name="hora_retiro">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add_fecha_salida" class="col-sm-3 control-label">Fecha de Salida</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" id="add_fecha_salida" name="fecha_salida">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add_motivo_paterno" class="col-sm-3 control-label">Paternidad</label>
                        <div class="col-sm-9">
                            <input type="checkbox" id="add_motivo_paterno" name="motivo_paterno">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add_motivo_materno" class="col-sm-3 control-label">Maternidad</label>
                        <div class="col-sm-9">
                            <input type="checkbox" id="add_motivo_materno" name="motivo_materno">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add_motivo_fallecimiento" class="col-sm-3 control-label">Fallecimiento</label>
                        <div class="col-sm-9">
                            <input type="checkbox" id="add_motivo_fallecimiento" name="motivo_fallecimiento">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add_motivo_tramites" class="col-sm-3 control-label">Trámites</label>
                        <div class="col-sm-9">
                            <input type="checkbox" id="add_motivo_tramites" name="motivo_tramites">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add_motivo_lactancia" class="col-sm-3 control-label">Lactancia</label>
                        <div class="col-sm-9">
                            <input type="checkbox" id="add_motivo_lactancia" name="motivo_lactancia">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add_motivo_otra_situacion" class="col-sm-3 control-label">Otra Situación</label>
                        <div class="col-sm-9">
                            <input type="checkbox" id="add_motivo_otra_situacion" name="motivo_otra_situacion">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add_observaciones" class="col-sm-3 control-label">Observaciones</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="add_observaciones" name="observaciones" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add_estado" class="col-sm-3 control-label">Estado</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="add_estado" name="estado">
                                <option value="pendiente">Pendiente</option>
                                <option value="aprobado">Aprobado</option>
                                <option value="rechazado">Rechazado</option>
                            </select>
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
                <h4 class="modal-title"><b><span class="fecha"></span> - <span class="employee_name"></span></b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="vacacion_edit.php">
                    <input type="hidden" class="vacid" name="id">
                    <div class="form-group">
                        <label for="edit_empresa" class="col-sm-3 control-label">Empresa</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="edit_empresa" name="empresa" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_puesto" class="col-sm-3 control-label">Puesto</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="edit_puesto" name="puesto" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_con_goce" class="col-sm-3 control-label">Con Goce de Sueldo</label>
                        <div class="col-sm-9">
                            <input type="checkbox" id="edit_con_goce" name="con_goce">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_sin_goce" class="col-sm-3 control-label">Sin Goce de Sueldo</label>
                        <div class="col-sm-9">
                            <input type="checkbox" id="edit_sin_goce" name="sin_goce">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_fecha_inicio" class="col-sm-3 control-label">Fecha de Inicio</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" id="edit_fecha_inicio" name="fecha_inicio" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_fecha_fin" class="col-sm-3 control-label">Fecha de Fin</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" id="edit_fecha_fin" name="fecha_fin" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_fecha_regreso" class="col-sm-3 control-label">Fecha de Regreso</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" id="edit_fecha_regreso" name="fecha_regreso" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_hora_retiro" class="col-sm-3 control-label">Hora de Retiro</label>
                        <div class="col-sm-9">
                            <input type="time" class="form-control" id="edit_hora_retiro" name="hora_retiro">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_fecha_salida" class="col-sm-3 control-label">Fecha de Salida</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" id="edit_fecha_salida" name="fecha_salida">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_motivo_paterno" class="col-sm-3 control-label">Paternidad</label>
                        <div class="col-sm-9">
                            <input type="checkbox" id="edit_motivo_paterno" name="motivo_paterno">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_motivo_materno" class="col-sm-3 control-label">Maternidad</label>
                        <div class="col-sm-9">
                            <input type="checkbox" id="edit_motivo_materno" name="motivo_materno">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_motivo_fallecimiento" class="col-sm-3 control-label">Fallecimiento</label>
                        <div class="col-sm-9">
                            <input type="checkbox" id="edit_motivo_fallecimiento" name="motivo_fallecimiento">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_motivo_tramites" class="col-sm-3 control-label">Trámites</label>
                        <div class="col-sm-9">
                            <input type="checkbox" id="edit_motivo_tramites" name="motivo_tramites">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_motivo_lactancia" class="col-sm-3 control-label">Lactancia</label>
                        <div class="col-sm-9">
                            <input type="checkbox" id="edit_motivo_lactancia" name="motivo_lactancia">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_motivo_otra_situacion" class="col-sm-3 control-label">Otra Situación</label>
                        <div class="col-sm-9">
                            <input type="checkbox" id="edit_motivo_otra_situacion" name="motivo_otra_situacion">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_observaciones" class="col-sm-3 control-label">Observaciones</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="edit_observaciones" name="observaciones" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_estado" class="col-sm-3 control-label">Estado</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="edit_estado" name="estado">
                                <option value="pendiente">Pendiente</option>
                                <option value="aprobado">Aprobado</option>
                                <option value="rechazado">Rechazado</option>
                            </select>
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

<script>
    $(document).ready(function() {
    // Completar automáticamente el campo "Puesto" al seleccionar un empleado en el modal de agregar
    $('#add_empleados').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var puesto = selectedOption.data('puesto');
        console.log("Empleado seleccionado:", selectedOption.text());
        console.log("Puesto encontrado:", puesto);
        $('#add_puesto').val(puesto);
    });

    // Completar automáticamente el campo "Puesto" al seleccionar un empleado en el modal de editar
    $('#edit_empleados').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var puesto = selectedOption.data('puesto');
        console.log("Empleado seleccionado (edit):", selectedOption.text());
        console.log("Puesto encontrado (edit):", puesto);
        $('#edit_puesto').val(puesto);
    });

    // Validación al seleccionar "Con Goce de Sueldo" y "Sin Goce de Sueldo"
    $('#add_con_goce, #edit_con_goce, #add_sin_goce, #edit_sin_goce').on('change', function() {
        if ($(this).prop('checked')) {
            var group = $(this).attr('name');
            if (group === 'con_goce' && $('#add_sin_goce').prop('checked') || group === 'sin_goce' && $('#add_con_goce').prop('checked')) {
                alert('Solo puede seleccionar una opción entre "Con Goce de Sueldo" y "Sin Goce de Sueldo".');
                $(this).prop('checked', false);
            }
        }
    });

    // Validación al seleccionar motivos
    $('#add_motivo_paterno, #edit_motivo_paterno, #add_motivo_materno, #edit_motivo_materno, #add_motivo_fallecimiento, #edit_motivo_fallecimiento, #add_motivo_tramites, #edit_motivo_tramites, #add_motivo_lactancia, #edit_motivo_lactancia, #add_motivo_otra_situacion, #edit_motivo_otra_situacion').on('change', function() {
        if ($('input[name="motivo_paterno"]:checked, input[name="motivo_materno"]:checked, input[name="motivo_fallecimiento"]:checked, input[name="motivo_tramites"]:checked, input[name="motivo_lactancia"]:checked, input[name="motivo_otra_situacion"]:checked').length > 1) {
            alert('Solo puede seleccionar un motivo.');
            $(this).prop('checked', false);
        }
    });
});
</script>