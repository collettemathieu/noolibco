<!-- Formulaire pour ajouter un nouveau paramètre à une fonction -->
	<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h4 class="modal-title">Add a new parameter</h4>
    </div>
    <div class="modal-body">
    	<?php if($user->getMessageClient()->hasErreur()){
    		$user->getMessageClient()->getErreurs(); // On supprime les erreurs
		?>
		<div class="alert alert-warning">
			Firstly, you need to add a function to the task.
		</div>
		<?php }else{?>
		<form class="well well-lg">
			<div class="form-group has-feedback">
				<input type="text" class="form-control" name="nomParametre" maxlength="50" placeholder="Enter a name for the parameter (between 3 and 50 chars)" value=""/>
				<span class="glyphicon form-control-feedback"></span>
				<span class="help-block"></span>
			</div>
			<div class="form-group has-feedback">
				<textarea class="form-control" name="descriptionParametre" placeholder="Enter a short description of the parameter (between 10 and 200 chars)."></textarea>
				<span class="glyphicon form-control-feedback"></span>
				<span class="help-block"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="text" class="form-control" name="valeurDefautParametre" maxlength="10" placeholder="Enter the default value of the parameter" value=""/>
				<span class="glyphicon form-control-feedback"></span>
				<span class="help-block"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="text" class="form-control" name="valeurMinParametre" maxlength="10" placeholder="Enter the minimum value of the parameter" value=""/>
				<span class="glyphicon form-control-feedback"></span>
				<span class="help-block"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="text" class="form-control" name="valeurMaxParametre" maxlength="10" placeholder="Enter the maximum value of the parameter" value=""/>
				<span class="glyphicon form-control-feedback"></span>
				<span class="help-block"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="text" class="form-control" name="valeurPasParametre" maxlength="10" placeholder="Enter the step value of the parameter" value=""/>
				<span class="glyphicon form-control-feedback"></span>
				<span class="help-block"></span>
			</div>
			<div class="form-group has-feedback">
				<label for="typeAffichageParametre">Select the type of display of the parameter</label>
				<select name="typeAffichageParametre" id="typeAffichageParametre" class="form-control input-sm">
					<?php echo $lesTypeAffichageParametre; ?>
				</select>
			</div>
			<div class="form-group has-feedback">
				<label for="statutPublicParametre">Select the status of the parameter</label>
				<select name="statutPublicParametre" id="statutPublicParametre" class="form-control input-sm">
					<option value="1">Public</option>
					<option value="0">Private</option>
				</select>
			</div>
			<input type="hidden" value="<?php echo $idFonction; ?>" name="idFonction"/>
			<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Loading..." type="submit">
		     Add this parameter
		 	</button>
		</form>
		<?php }?>
    </div>