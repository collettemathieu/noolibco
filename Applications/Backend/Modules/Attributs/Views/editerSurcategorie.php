<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-lg-11 centering maxWidth">
			<div class="col-sm-5 sousMenu">
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Attributs/EditerSurcategorie/AjouterSurcategorie">
					<legend>Add an on-category</legend>
					<div class="form-group">
						<label>Name of the on-category</label>
						<input type="text" name="nom" class="form-control input-lg" placeholder="Enter a name for the new on-category"/>
					</div>
					<button class="btn btn-primary" type="submit">Create a new on-category</button>
				</form>

				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Attributs/EditerSurcategorie/">
					<legend>Manage an on-category</legend>
					<div class="form-group">
						<label>Select an on-category for managing</label>
						<select name="idSurcategorie" class="form-control input-sm">
							<?php foreach($surcategories as $surcategorie_i)
							{
								echo('<option value="'.$surcategorie_i->getIdSurcategorie().'">'.$surcategorie_i->getNomSurcategorie().'</option>');
							} ?>
						</select>
					</div>
					<button class="btn btn-primary" type="submit">Manage</button>
				</form>
			</div>
			<?php if(isset($surcategorie)) { ?>
			<div class="col-sm-5 sousMenu col-sm-offset-2">
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Attributs/EditerSurcategorie/modifierSurcategorie">
					<legend>Edit on-category : <?php echo $surcategorie->getNomSurcategorie(); ?></legend>
					<input type="hidden" name="idSurcategorie" value="<?php echo $surcategorie->getIdSurcategorie(); ?>"/>
					<div class="form-group">
						<label>Select a new name for the on-category</label>
						<input type="text" name="nom" value="<?php echo $surcategorie->getNomSurcategorie(); ?>" class="form-control input-lg"/>
					</div>
					<button class="btn btn-primary" type="submit">Edit</button>
				</form>

				<?php if($surcategorie->getIdSurcategorie()) { ?>
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Attributs/EditerSurcategorie/SupprimerSurcategorie">
					<legend>Delete this on-category</legend>
					<input type="hidden" name="idSurcategorie" value="<?php echo $surcategorie->getIdSurcategorie(); ?>"/>
					<button class="btn btn-primary" type="submit">Delete</button>
				</form>
				<?php } ?>
				
			</div>
			<?php } ?>
		</div>
	</div>
</div>
