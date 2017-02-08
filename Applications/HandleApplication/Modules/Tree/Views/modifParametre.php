<!-- Formulaire pour ajouter un nouveau paramètre à une fonction -->
	<?php $application = unserialize($user->getAttribute('application'));?>
	<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h4 class="modal-title">Parameter <?php echo $parametreAModifier->getNomParametre(); ?></h4>
    </div>
    <div class="modal-body">
    	<ul class="nav nav-pills">
			<li class="active"><a href="#edit" data-toggle="tab">Edit this parameter</a></li>
			<li><a href="#delete" data-toggle="tab">Delete this parameter</a></li>
		</ul><br>

		<div class="tab-content">
			<div class="tab-pane active" id="edit">
				<form class="well well-lg">
					<div class="form-group has-feedback">
						<label for="nomParametre">Name of the parameter</label>
						<input type="text" class="form-control input-sm" maxlength="50" id="nomParametre" name="nomParametre" placeholder="Enter the name of the parameter (between 2 and 50 chars)." value="<?php echo $parametreAModifier->getNomParametre(); ?>"/>
						<span class="glyphicon form-control-feedback"></span>
						<span class="help-block"></span>
					</div>
					<div class="form-group has-feedback">
						<label for="descriptionParametre">Short description of the parameter</label>
						<textarea class="form-control input-sm" maxlength="200" id="descriptionParametre" name="descriptionParametre" placeholder="Enter a short description of the parameter (between 10 and 200 chars)."><?php echo $parametreAModifier->getDescriptionParametre(); ?></textarea>
						<span class="glyphicon form-control-feedback"></span>
						<span class="help-block"></span>
					</div>
					<div class="form-group has-feedback">
						<label for="valeurDefautParametre">Default value of the parameter</label>
						<input type="text" class="form-control input-sm" maxlength="10" id="valeurDefautParametre" name="valeurDefautParametre" placeholder="Enter a default value of the parameter." value="<?php echo $parametreAModifier->getValeurDefautParametre(); ?>"/>
						<span class="glyphicon form-control-feedback"></span>
						<span class="help-block"></span>
					</div>
					<div class="form-group has-feedback">
						<label for="valeurMinParametre">Minimum value of the parameter</label>
						<input type="text" class="form-control input-sm" maxlength="10" id="valeurMinParametre" name="valeurMinParametre" placeholder="Enter a minimum value of the parameter." value="<?php echo $parametreAModifier->getValeurMinParametre(); ?>"/>
						<span class="glyphicon form-control-feedback"></span>
						<span class="help-block"></span>
					</div>
					<div class="form-group has-feedback">
						<label for="valeurMaxParametre">Maximum value of the parameter</label>
						<input type="text" class="form-control input-sm" maxlength="10" id="valeurMaxParametre" name="valeurMaxParametre" placeholder="Enter a maximum value of the parameter." value="<?php echo $parametreAModifier->getValeurMaxParametre(); ?>"/>
						<span class="glyphicon form-control-feedback"></span>
						<span class="help-block"></span>
					</div>
					<div class="form-group has-feedback">
						<label for="valeurPasParametre">Step value of the parameter</label>
						<input type="text" class="form-control input-sm" maxlength="10" id="valeurPasParametre" name="valeurPasParametre" placeholder="Enter a step value of the parameter." value="<?php echo $parametreAModifier->getValeurPasParametre(); ?>"/>
						<span class="glyphicon form-control-feedback"></span>
						<span class="help-block"></span>
					</div>
					<div class="form-group has-feedback">
						<label for="typeAffichageParametre">Type of display of the parameter</label>
						<select name="typeAffichageParametre" id="typeAffichageParametre" class="form-control input-sm">
							<?php foreach($listeTypeAffichageParametre as $type){?>
								<option value="<?php echo $type->getNomTypeAffichageParametre(); ?>" <?php if($parametreAModifier->getTypeAffichageParametre()->getIdTypeAffichageParametre() === $type->getIdTypeAffichageParametre()){echo 'selected';}?>><?php echo $type->getNomTypeAffichageParametre();?></option>
							<?php }?>
						</select>
					</div>
					<div class="form-group has-feedback">
						<label for="statutPublicParametre">Status of the parameter</label>
						<select name="statutPublicParametre" id="statutPublicParametre" class="form-control input-sm">
							<option value="1">Public</option>
							<option value="0">Private</option>
						</select>
					</div>
					<input type="hidden" name="idParametre" value="<?php echo $parametreAModifier->getIdParametre(); ?>"/>
					<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Loading..." type="submit">
				     Update the parameter
				 	</button>
				</form>
			</div>
			<div class="tab-pane" id="delete">
				<form class="well well-lg">
						<legend>Do you want to delete this parameter ?</legend>
						<input type="hidden" name="idParametre" value="<?php echo $parametreAModifier->getIdParametre(); ?>"/>
						<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Loading..." type="submit">
					     Delete
					 	</button>
					</form>
			</div>
		</div>
    </div>