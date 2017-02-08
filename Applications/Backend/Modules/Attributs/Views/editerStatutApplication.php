<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-lg-11 centering maxWidth">
			<div class="col-sm-5 sousMenu">
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Attributs/EditerStatutApplication/AjouterStatutApplication">
					<legend>Add a status for application</legend>
					<div class="form-group">
						<label>Name of the status</label>
						<input type="text" name="nomStatut" class="form-control input-lg" placeholder="Enter a name for the new status"/>
					</div>
					<div class="form-group">
						<label>Select a color of the status</label>
						<input type="color" name="couleurStatut" value="#ffffff" class="form-control input-lg"/>
					</div>
					<button class="btn btn-primary" type="submit">Create a new status</button>
				</form>

				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Attributs/EditerStatutApplication/">
					<legend>Manage a status of application</legend>
					<div class="form-group">
						<label>Select a status for managing</label>
						<select name="idStatutApplication" class="form-control input-sm">
							<?php foreach($statuts as $statut)
							{
								if($statut->getIdStatut() != 0)
								{
									echo('<option value="'.$statut->getIdStatut().'">'.$statut->getNomStatut().'</option>');
								}
							} ?>
						</select>
					</div>
					<button class="btn btn-primary" type="submit">Manage</button>
				</form>
			</div>
			<?php if(isset($statutApplication)) { ?>
			<div class="col-sm-5 sousMenu col-sm-offset-2">
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Attributs/EditerStatutApplication/modifierStatutApplication">
					<legend>Edit status : <?php echo $statutApplication->getNomStatut(); ?></legend>
					<input type="hidden" name="idStatutApplication" value="<?php echo $statutApplication->getIdStatut(); ?>"/>
					<div class="form-group">
						<label>Select a new name for the status</label>
						<input type="text" name="newNom" value="<?php echo $statutApplication->getNomStatut(); ?>" class="form-control input-lg"/>
					</div>
					<div class="form-group">
						<label>Select a new color for the status</label>
						<input type="color" name="newCouleurStatut" value="<?php echo $statutApplication->getCouleurStatut(); ?>" class="form-control input-lg"/>
					</div>
					<button class="btn btn-primary" type="submit">Edit</button>
				</form>

				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Attributs/EditerStatutApplication/SupprimerStatutApplication">
					<legend>Delete this status</legend>
					<input type="hidden" name="idStatutApplication" value="<?php echo $statutApplication->getIdStatut(); ?>"/>
					<button class="btn btn-primary" type="submit">Delete</button>
				</form>
				
			</div>
			<?php } ?>
		</div>
	</div>
</div>