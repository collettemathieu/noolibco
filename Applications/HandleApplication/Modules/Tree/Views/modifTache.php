<!-- Formulaire pour ajouter une nouvelle tâche -->
	<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">x</button>
        <h4 class="modal-title">Task <?php echo $tacheAModifier->getNomTache(); ?></h4>
    </div>
    <div class="modal-body">
		
			<ul class="nav nav-pills">
				<li class="active"><a href="#edit" data-toggle="tab">Edit this task</a></li>
				<li><a href="#delete" data-toggle="tab">Delete this task</a></li>
			</ul><br>
			<div class="tab-content">
				<div class="tab-pane active" id="edit">
					<form class="well well-lg">
						<div class="form-group has-feedback">
							<span class="label label-primary">Name of the task</span>
							<input type="text" class="form-control" name="nomTache" maxlength="50" placeholder="Enter the name of the task (between 5 and 50 chars)." value="<?php echo $tacheAModifier->getNomTache(); ?>"/>
							<span class="glyphicon form-control-feedback"></span>
							<span class="help-block"></span>
						</div>
						<div class="form-group has-feedback">
							<span class="label label-primary">Name of the task</span>
							<textarea class="form-control" name="descriptionTache" placeholder="Enter a short description of the task (between 10 and 1000 chars)."><?php echo $tacheAModifier->getDescriptionTache(); ?></textarea>
							<span class="glyphicon form-control-feedback"></span>
							<span class="help-block"></span>
						</div>
						<div class="form-group has-feedback col-lg-8 centering">
							<label>Data required for running the task</label>
							<?php foreach($tacheAModifier->getTacheTypeDonneeUtilisateurs() as $k=>$tacheTypeDonneeUtilisateur){?>
							<div class="alert">
								<span class="label label-primary">Description</span><input name="description<?php echo $k;?>" type="text" class="form-control" maxlength="40" placeholder="Enter a short description of the data (between 1 and 40 chars)." value="<?php echo $tacheTypeDonneeUtilisateur->getDescription();?>"/>
								<span class="label label-primary">Data type</span>
								<select name="typeDonneeUtilisateur<?php echo $k;?>" class="form-control input-sm selectTypeDonneeUtilisateur">
									<?php foreach($typesDonneeUtilisateur as $type){?>
										<option value="<?php echo $type->getNomTypeDonneeUtilisateur(); ?>" <?php if($tacheTypeDonneeUtilisateur->getTypeDonneeUtilisateur()->getIdTypeDonneeUtilisateur() === $type->getIdTypeDonneeUtilisateur()){echo 'selected';}?>><?php echo $type->getNomTypeDonneeUtilisateur();?></option>
									<?php }?>
								</select>
								<span class="label label-primary">Data unit</span>
								<select name="uniteDonneeUtilisateur<?php echo $k;?>" class="form-control input-sm selectUniteDonneeUtilisateur">
									<?php foreach($uniteDonneeUtilisateurs as $unite){?>
										<option value="<?php echo $unite->getNomUniteDonneeUtilisateur(); ?>" <?php if($tacheTypeDonneeUtilisateur->getUniteDonneeUtilisateur()->getIdUniteDonneeUtilisateur() === $unite->getIdUniteDonneeUtilisateur()){echo 'selected';}?>><?php echo $unite->getNomUniteDonneeUtilisateur().' ('.$unite->getSymboleUniteDonneeUtilisateur().')';?></option>
									<?php }?>
								</select>
							</div>
							<?php }?>
							<button class="btn btn-default btn-sm pull-right" disabled id="deleteTypeDonneeUtilisateur">Delete the last data</button>
							<button class="btn btn-default btn-sm pull-right" id="addNewTypeDonneeUtilisateur">Add a new data</button>
						</div>
						<input type="hidden" name="idTache" value="<?php echo $tacheAModifier->getIdTache(); ?>"/>
						<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Loading..." type="submit">
					     Update the task
					 	</button>
					</form>
				</div>
				<div class="tab-pane" id="delete">
					<form class="well well-lg">
						<legend>Do you want to delete this task ?</legend>
						<input type="hidden" name="idTache" value="<?php echo $tacheAModifier->getIdTache(); ?>"/>
						<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Loading..." type="submit">
					     Delete
					 	</button>
					</form>
				</div>
			</div>
    </div>