<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-lg-11 centering maxWidth">
			<div class="col-sm-5 sousMenu">
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Attributs/EditerStatutUtilisateur/AjouterStatutUtilisateur">
					<legend>Add a status for user</legend>
					<div class="form-group">
						<label>Name of the status</label>
						<input type="text" name="nomStatut" class="form-control input-lg" placeholder="Enter a name for the new status"/>
					</div>
					<button class="btn btn-primary" type="submit">Create a new status</button>
				</form>

				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Attributs/EditerStatutUtilisateur/">
					<legend>Manage a status of user</legend>
					<div class="form-group">
						<label>Select a status for managing</label>
						<select name="idStatutUtilisateur" class="form-control input-sm">
							<?php foreach($statuts as $statut)
							{
								if($statut->getIdStatut())
								{
									echo('<option value="'.$statut->getIdStatut().'">'.$statut->getNomStatut().'</option>');
								}
							} ?>
						</select>
					</div>
					<button class="btn btn-primary" type="submit">Manage</button>
				</form>
			</div>
			<?php if(isset($statutUtilisateur)) { ?>
			<div class="col-sm-5 sousMenu col-sm-offset-2">
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Attributs/EditerStatutUtilisateur/modifierStatutUtilisateur">
					<legend>Edit status : <?php echo $statutUtilisateur->getNomStatut(); ?></legend>
					<input type="hidden" name="idStatutUtilisateur" value="<?php echo $statutUtilisateur->getIdStatut(); ?>"/>
					<div class="form-group">
						<label>Select a new name for the status</label>
						<input type="text" name="newNom" value="<?php echo $statutUtilisateur->getNomStatut(); ?>" class="form-control input-lg"/>
					</div>
					<button class="btn btn-primary" type="submit">Edit</button>
				</form>
				<?php if($statutUtilisateur->getIdStatut() != 1) { ?>
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Attributs/EditerStatutUtilisateur/SupprimerStatutUtilisateur">
					<legend>Delete this status</legend>
					<input type="hidden" name="idStatutUtilisateur" value="<?php echo $statutUtilisateur->getIdStatut(); ?>"/>
					<button class="btn btn-primary" type="submit">Delete</button>
				</form>
				<?php } ?>
				
			</div>
			<?php } ?>
		</div>
	</div>
</div>
