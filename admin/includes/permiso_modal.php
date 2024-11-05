<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Agregar Solicitud de Permiso</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="permiso_add.php">
                    <div class="form-group">
                        <label for="empleados" class="col-sm-3 control-label">Empleado</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="empleados" name="employee">
                                <option value=""></option>
                                <?php
                                $sql = "SELECT * FROM employees";
                                $query = $conn->query($sql);
                                while ($row = $query->fetch_assoc()) {
                                    echo "<option value='" . $row['employee_id'] . "'>" . $row['firstname'] . " " . $row['lastname'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tipo_permiso" class="col-sm-3 control-label">Tipo de Permiso</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="tipo_permiso" name="tipo_permiso" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="motivo" class="col-sm-3 control-label">Motivo</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="motivo" name="motivo" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="observaciones" class="col-sm-3 control-label">Observaciones</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3" required></textarea>
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
                <form class="form-horizontal" method="POST" action="permiso_edit.php">
                    <input type="hidden" class="spid" name="id">
                    <div class="form-group">
                        <label for="edit_tipo_permiso" class="col-sm-3 control-label">Tipo de Permiso</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="edit_tipo_permiso" name="tipo_permiso" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_motivo" class="col-sm-3 control-label">Motivo</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="edit_motivo" name="motivo" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_observaciones" class="col-sm-3 control-label">Observaciones</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="edit_observaciones" name="observaciones" rows="3" required></textarea>
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
                <h4 class="modal-title"><b><span class="fecha"></span></b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="permiso_delete.php">
                    <input type="hidden" class="spid" name="id">
                    <div class="text-center">
                        <p>Eliminar Solicitud de Permiso</p>
                        <h2 class="employee_name bold"></h2>
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
