<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-lg-11 centering maxWidth">
			<div class="col-sm-5 sousMenu">
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Attributs/EditerCategorie/AjouterCategorie">
					<legend>Add a new category of application</legend>
					<div class="form-group">
						<label>Name of the category</label>
						<input type="text" name="nom" class="form-control input-lg" placeholder="Enter a name for the new category"/>
					</div>
					<div class="form-group">
						<label>Select a description of the category</label>
						<textarea name="description" class="form-control input-lg"></textarea>
					</div>
					<div class="form-group">
						<label>Select an on-category for the category</label>
						<select name="idSurcategorie" class="form-control input-sm">
							<?php foreach($surcategories as $surcategorie_i)
							{
								echo('<option value="'.$surcategorie_i->getIdSurcategorie().'">'.$surcategorie_i->getNomSurcategorie().'</option>');
							} ?>
						</select>
					</div>
					<button class="btn btn-primary" type="submit">Create a new category</button>
				</form>

				<form method="POST" class="well" action="/PourAdminSeulement/Attributs/EditerCategorie/">
					<legend>Select an on-category of application</legend>
					<div class="form-group">
						<label>Select an on-category</label>
						<select name="idSurcategorie" class="form-control input-sm">
							<?php foreach($surcategories as $surcatego)
							{
								$echo = '<option value="' . $surcatego->getIdSurcategorie() . '"';
								if(isset($idSurcategorie) && $surcatego->getIdSurcategorie() == $idSurcategorie)
								{
									$echo .= ' selected';
								}
								$echo .= '>' . $surcatego->getNomSurcategorie() . '</option>';
								echo($echo);
							} ?>
						</select>
					</div>
					
					<button class="btn btn-primary" type="submit">-></button>
				</form>
				<form method="POST" class="well" action="/PourAdminSeulement/Attributs/EditerCategorie/">
					<legend>Manage a category of application</legend>
					
					<div class="form-group">
						<label>Select a category for managing</label>
						<select name="idCategorie" class="form-control input-sm">
							<?php foreach($categories as $c)
							{
								$echo = '<option value="' . $c->getIdCategorie() . '"';
								if(isset($idLaboratoire) && $c->getIdCategorie() == $idCategorie)
								{
									$echo .= ' selected';
								}
								$echo .= '>' . $c->getNomCategorie() . '</option>';
								echo($echo);
							} ?>
						</select>
					</div>
					<button class="btn btn-primary" type="submit">Manage</button>
				</form>
			</div>
			<?php if(isset($categorie)) { ?>
			<div class="col-sm-5 sousMenu col-sm-offset-2">
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Attributs/EditerCategorie/modifierCategorie">
					<legend>Edit category: <?php echo $categorie->getNomCategorie(); ?></legend>
					<input type="hidden" name="idCategorie" value="<?php echo $categorie->getIdCategorie(); ?>"/>
					<div class="form-group">
						<label>Select a new name for the category</label>
						<input type="text" name="nom" value="<?php echo $categorie->getNomCategorie(); ?>" class="form-control input-lg"/>
					</div>
					<div class="form-group">
						<label>Select a new description for the category</label>
						<textarea name="description" class="form-control input-lg"><?php echo $categorie->getDescriptionCategorie(); ?></textarea>
					</div>
					<div class="form-group">
						<label>Select an on-category for this category</label>
						<select name="idSurcategorie" class="form-control input-sm">
							<?php foreach($surcategories as $surcategorie_i)
							{
								echo('<option value="'.$surcategorie_i->getIdSurcategorie().'"');
								if($surcategorie_i->getIdSurcategorie() == $categorie->getSurcategorie()->getIdSurcategorie()) {
									echo(' selected');
								}
								echo('>'.$surcategorie_i->getNomSurcategorie().'</option>');
							} ?>
						</select>
					</div>
					<button class="btn btn-primary" type="submit">Edit</button>
				</form>
				<?php if($categorie->getIdCategorie()) { ?>
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Attributs/EditerCategorie/SupprimerCategorie">
					<legend>Delete this category</legend>
					<input type="hidden" name="idCategorie" value="<?php echo $categorie->getIdCategorie(); ?>"/>
					<button class="btn btn-primary" type="submit">Delete</button>
				</form>
				<?php } ?>
				
			</div>
			<?php } ?>
		</div>
	</div>
</div>
