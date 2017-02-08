<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-lg-11 centering maxWidth">
			<div class="col-sm-5 sousMenu">
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Groupes/Laboratoire/creerLaboratoire">
					<legend>Create a new laboratory</legend>
					<div class="form-group">
						<label>Select an institution</label>
						<select name="newIdEtablissement" class="form-control input-sm">
							<option disabled selected>Select an institution</option>
							<?php foreach($etablissements as $etablissement) {
								echo('<option value="'.$etablissement->getIdEtablissement().'">'.$etablissement->getNomEtablissement().'</option>');
							} ?>
						</select>
					</div>
					<div class="form-group">
						<label>Enter a name for this laboratory</label>
						<input type="text" name="newNom" class="form-control input-sm" placeholder="Enter a name for this laboratory"/>
					</div>
					<div class="form-group">
						<label>Enter an url for this laboratory</label>
						<textarea name="newUrl" class="form-control input-sm" placeholder="Enter an url for this laboratory"></textarea>
					</div>
					<button class="btn btn-primary" type="submit">Create a new laboratory</button>
				</form>
				
				<form method="post" class="well well-lg" action="/PourAdminSeulement/Groupes/Laboratoire/">
					<legend>Manage a laboratory</legend>
					<select name="idEtablissement" class="input-sm">
						<?php foreach($listeEtablissement as $etablissement)
						{
							$echo = '<option value="' . $etablissement->getIdEtablissement() . '"';
							if(isset($idEtablissement) && $etablissement->getIdEtablissement() == $idEtablissement)
							{
								$echo .= ' selected';
							}
							$echo .= '>' . $etablissement->getNomEtablissement() . '</option>';
							echo($echo);
						} ?>
					</select>
					<button class="btn btn-primary" type="submit">↓</button>
				</form>
				<?php if(isset($idEtablissement)) { ?>
					<form method="post" class="well well-lg" action="/PourAdminSeulement/Groupes/Laboratoire/">
						
						<select name="idLaboratoire" class="input-sm">
							<?php foreach($listeLaboratoire as $l)
							{
								$echo = '<option value="' . $l->getIdLaboratoire() . '"';
								if(isset($idLaboratoire) && $l->getIdLaboratoire() == $idLaboratoire)
								{
									$echo .= ' selected';
								}
								$echo .= '>' . $l->getNomLaboratoire() . '</option>';
								echo($echo);
							} ?>
						</select>
						<button class="btn btn-primary" type="submit">Manage this laboratory</button>
						
					</form>
				<?php } ?>
			
			</div>
			
			<?php if(isset($laboratoire)) { ?>
			<div class="col-sm-5 sousMenu col-sm-offset-2">
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Groupes/Laboratoire/changerInfoLaboratoire">
					<legend>Edit laboratory: <?php echo $laboratoire->getNomLaboratoire(); ?></legend>
					<input type="hidden" name="idLaboratoire" value="<?php echo $laboratoire->getIdLaboratoire(); ?>"/>
					<div class="form-group">
						<label>Select a new name for this laboratory</label>
						<input type="text" name="newNom" class="form-control input-sm" value="<?php echo $laboratoire->getNomLaboratoire(); ?>"/>
					</div>
					<div class="form-group">
						<label>Select a new url for this laboratory</label>
						<input type="text" name="newUrl" class="form-control input-sm" value="<?php echo $laboratoire->getUrlLaboratoire(); ?>"/>
					</div>
					<div class="form-group">
						<label>Select a new institution for this laboratory</label>
						<select name="newIdEtablissement" class="form-control input-sm">
							<?php foreach($etablissements as $etablissement) {
								$option = '<option value="'.$etablissement->getIdEtablissement().'"';
								if($etablissement->getIdEtablissement() == $laboratoire->getEtablissement()->getIdEtablissement())
								{
									$option .= ' selected';
								}
								$option .= '>'.$etablissement->getNomEtablissement().'</option>';
								echo($option);
							} ?>
						</select>
					</div>

					<button class="btn btn-primary" type="submit">Edit</button>
				</form>
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Groupes/Laboratoire/supprimerLaboratoire">
					<legend>Delete this laboratory</legend>
					<input type="hidden" name="idLaboratoire" value="<?php echo $laboratoire->getIdLaboratoire(); ?>"/>
					<button class="btn btn-primary" type="submit">Delete</button>
				</form>
			</div>
			<?php } ?>
			
		</div>
	</div>
</div>
