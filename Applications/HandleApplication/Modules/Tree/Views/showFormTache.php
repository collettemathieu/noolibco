<!-- Formulaire pour ajouter une nouvelle tâche -->
	<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h4 class="modal-title">Add a new task for <?php echo $application->getNomApplication();?></h4>
    </div>
    <div class="modal-body">
		<form class="well well-lg">
			<div class="form-group has-feedback">
				<span class="label label-primary label-lg">Name of the task</span>
				<input type="text" class="form-control" name="nomTache" maxlength="50" placeholder="Enter the name of the task (between 5 and 50 chars)." value=""/>
				<span class="glyphicon form-control-feedback"></span>
				<span class="help-block"></span>
			</div>
			<div class="form-group has-feedback">
				<span class="label label-primary label-lg">Description of the task</span>
				<textarea class="form-control" name="descriptionTache" placeholder="Enter a short description of the task (between 10 and 1000 chars)."></textarea>
				<span class="glyphicon form-control-feedback"></span>
				<span class="help-block"></span>
			</div>
			<div class="form-group has-feedback col-lg-9 centering">
				<label>Enter data required for running the task</label>
				<div class="alert">
					<span class="label label-primary">Description</span>
					<input name="description0" type="text" class="form-control" maxlength="40" placeholder="Enter a short description of the data (between 1 and 40 chars)." value=""/>
					<span class="label label-primary">Data type</span>
					<select name="typeDonneeUtilisateur0" class="form-control input-sm selectTypeDonneeUtilisateur">
						<?php echo $typeDonneeUtilisateurAAfficher; ?>
					</select>
					<span class="label label-primary">Data unit</span>
					<select name="uniteDonneeUtilisateur0" class="form-control input-sm selectUniteDonneeUtilisateur">
						<?php echo $uniteDonneeUtilisateurAAfficher; ?>
					</select>
				</div>
				<button class="btn btn-default btn-sm pull-right" disabled id="deleteTypeDonneeUtilisateur">Delete the last data</button>
				<button class="btn btn-default btn-sm pull-right" id="addNewTypeDonneeUtilisateur">Add a new data</button>
			</div>
			<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Loading..." type="submit">
		     Add this task
		 	</button>
		</form>

    </div>