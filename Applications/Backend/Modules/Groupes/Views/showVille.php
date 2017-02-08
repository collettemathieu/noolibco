<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-lg-11 centering maxWidth">
			<div class="col-sm-5 sousMenu">
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Groupes/Ville/creerVille">
					<legend>Create a new town</legend>		
					<div class="form-group">
						<label>Select a country</label>
						<select name="idPays" class="form-control input-sm">
							<?php foreach($allPays as $pays)
							{
								echo('<option value="' . $pays->getIdPays() . '"' . '>' . $pays->getNomPays() . '</option>');
							} ?>
						</select>
					</div>
					<div class="form-group">
						<label>Enter a name for this town</label>
						<input type="text" name="nom" class="form-control input-sm" placeholder="Enter a name for this town"/>
					</div>
					<button class="btn btn-primary" type="submit">Create a new town</button>
				</form>
				
				<form method="post" class="well well-lg" action="/PourAdminSeulement/Groupes/Ville/">
					<legend>Manage a town</legend>
					<select name="idPays" class="input-sm">
						<?php foreach($allPays as $pays)
						{
							$echo = '<option value="' . $pays->getIdPays() . '"';
							if(isset($idPays) && $pays->getIdPays() == $idPays)
							{
								$echo .= ' selected';
							}
							$echo .= '>' . $pays->getNomPays() . '</option>';
							echo($echo);
						} ?>
					</select>
					<button class="btn btn-primary" type="submit">↓</button>
				</form>
				<?php if(isset($listeVille)) { ?>
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Groupes/Ville/">
					<select name="idVille" class="input-sm">
						<?php foreach($listeVille as $villei) {
							echo('<option value="'.$villei->getIdVille().'">'.$villei->getNomVille().'</option>');
						} ?>
					</select>
					<button class="btn btn-primary" type="submit">Manage this town</button>
				</form>
				<?php } ?>
			</div>
			
			<?php if(isset($ville)) { ?>
			<div class="col-sm-5 sousMenu col-sm-offset-2">
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Groupes/Ville/changerInfoVille">
					<legend>Edit town: <?php echo $ville->getNomVille(); ?></legend>
					<input type="hidden" name="idVille" value="<?php echo $ville->getIdVille(); ?>"/>
					<div class="form-group">
						<label>Select a new name for this town</label>
						<input type="text" name="nom" class="form-control input-sm" value="<?php echo $ville->getNomVille(); ?>"/>
					</div>
					<div class="form-group">
						<label>Select a new country for this town</label>
						<select name="idPays" class="form-control input-sm">
							<?php foreach($allPays as $pays) {
								$option = '<option value="'.$pays->getIdPays().'"';
								if($pays->getIdPays() == $ville->getPays()->getIdPays())
								{
									$option .= ' selected';
								}
								$option .= '>'.$pays->getNomPays().'</option>';
								echo($option);
							} ?>
						</select>
					</div>

					<button class="btn btn-primary" type="submit">Edit</button>
				</form>
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Groupes/Ville/supprimerVille">
					<legend>Delete this town</legend>
					<input type="hidden" name="idVille" value="<?php echo $ville->getIdVille(); ?>"/>
					<button class="btn btn-primary" type="submit">Delete</button>
				</form>
			</div>
			<?php } ?>
			
		</div>
	</div>
</div>