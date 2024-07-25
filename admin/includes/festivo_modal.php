<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Agregar Festivos</b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="festivo_add.php">
          		  
                <div class="form-group">
                    <label for="holiday_date" class="col-sm-3 control-label">Fecha</label>

                    <div class="col-sm-9">
                      <input type="date" class="form-control" id="holiday_date" name="holiday_date" required>
                    </div>
                </div>

                <div class="form-group">
                  	<label for="description" class="col-sm-3 control-label">Descripcion</label>

                  	<div class="col-sm-9">
                    	<input type="text" class="form-control" id="description" name="description" required>
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

<!-- Editar Modal -->
<div class="modal fade" id="edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Editar Festivo</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="festivo_edit.php">
                    <input type="hidden" class="decid" name="id">
                    <div class="form-group">
                        <label for="edit_holiday_date" class="col-sm-3 control-label">Fecha</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" id="edit_holiday_date" name="holiday_date" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_description" class="col-sm-3 control-label">Descripción</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="edit_description" name="description" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" name="edit">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->



<!-- Delete -->
<div class="modal fade" id="delete">
    <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Eliminando...</b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="festivo_delete.php">
            		<input type="hidden" class="decid" name="id">
            		<div class="text-center">
	                	<p>Eliminar Deducción</p>
	                	<h2 id="del_deduction" class="bold"></h2>
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


     