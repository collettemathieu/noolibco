<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-lg-11 centering maxWidth">
			<div class="col-sm-5 sousMenu">
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Groupes/Equipe/creerEquipe">
					<legend>Create a new team</legend>
					<div class="form-group">
						<label>Select an laboratory</label>
						<select name="newIdLaboratoire" class="form-control input-sm">
							<option disabled selected>Select a laboratory</option>
							<?php foreach($laboratoires as $laboratoire) {
								echo('<option value="'.$laboratoire->getIdLaboratoire().'">'.$laboratoire->getNomLaboratoire().'</option>');
							} ?>
						</select>
					</div>
					<div class="form-group">
						<label>Enter a name for this team</label>
						<input type="text" name="newNom" class="form-control input-sm" placeholder="Enter a name for this team"/>
					</div>
					<button class="btn btn-primary" type="submit">Create a new team</button>
				</form>
		
				<form method="post" class="well well-lg" action="/PourAdminSeulement/Groupes/Equipe/">
					<legend>Manage a team</legend>
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
					<form method="post" class="well well-lg" action="/PourAdminSeulement/Groupes/Equipe/">
						
						<input type="hidden" name="idEtablissement" value="<?php echo $idEtablissement ?>"/>
						<select name="idLaboratoire" class="input-sm">
							<?php foreach($listeLaboratoire as $laboratoire)
							{
								$echo = '<option value="' . $laboratoire->getIdLaboratoire() . '"';
								if(isset($idLaboratoire) && $laboratoire->getIdLaboratoire() == $idLaboratoire)
								{
									$echo .= ' selected';
								}
								$echo .= '>' . $laboratoire->getNomLaboratoire() . '</option>';
								echo($echo);
							} ?>
						</select>
						<button class="btn btn-primary" type="submit">↓</button>
					</form>
				<?php } ?>
				<?php if(isset($idLaboratoire)) { ?>
					<form method="post" class="well well-lg" action="/PourAdminSeulement/Groupes/Equipe/">
						
						<select name="idEquipe" class="input-sm">
							<?php foreach($listeEquipe as $e)
							{
								$echo = '<option value="' . $e->getIdEquipe() . '"';
								$echo .= '>' . $e->getNomEquipe() . '</option>';
								echo($echo);
							} ?>
						</select>
						<button class="btn btn-primary" type="submit">Manage this team</button>
					</form>
				<?php } ?>
			
			</div>
			
			<?php if(isset($equipe)) { ?>
			<div class="col-sm-5 sousMenu col-sm-offset-2">
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Groupes/Equipe/changerInfoEquipe">
					<legend>Edit team: <?php echo $equipe->getNomEquipe(); ?></legend>
					<input type="hidden" name="idEquipe" value="<?php echo $equipe->getIdEquipe(); ?>"/>
					<div class="form-group">
						<label>Select a new name for this team</label>
						<input type="text" name="newNom" class="form-control input-sm" value="<?php echo $equipe->getNomEquipe(); ?>"/>
					</div>
					<div class="form-group">
						<label>Select a new laboratory for this team</label>
						<select name="newIdLaboratoire" class="form-control input-sm">
							<?php foreach($laboratoires as $laboratoire) {
								$option = '<option value="'.$laboratoire->getIdLaboratoire().'"';
								if($laboratoire->getIdLaboratoire() == $equipe->getLaboratoire()->getIdLaboratoire())
								{
									$option .= ' selected';
								}
								$option .= '>'.$laboratoire->getNomLaboratoire().'</option>';
								echo($option);
							} ?>
						</select>
					</div>

					<button class="btn btn-primary" type="submit">Edit</button>
				</form>
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Groupes/Equipe/supprimerEquipe">
					<legend>Delete this team</legend>
					<input type="hidden" name="idEquipe" value="<?php echo $equipe->getIdEquipe(); ?>"/>
					<button class="btn btn-primary" type="submit">Delete</button>
				</form>
			</div>
			<?php } ?>
			
		</div>
	</div>
</div>