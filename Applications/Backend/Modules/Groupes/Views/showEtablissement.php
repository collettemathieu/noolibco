
<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-lg-11 centering maxWidth">
			<div class="col-sm-5 sousMenu">	
				<form method="post" class="well well-lg" action="/PourAdminSeulement/Groupes/Etablissement/">
					<legend>Add a new institution</legend>
					<label>Select a country</label>
					<select name="idPays" class="input-sm">
						<?php foreach($listePays as $pays)
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
				<?php if(isset($idPays)) { ?>
					<form method="POST" class="well well-lg" action="/PourAdminSeulement/Groupes/Etablissement/creerEtablissement">
					
						<div class="form-group">
							<label>Town</label>
							<select name="newIdVille" class="form-control input-sm">
								<option value="0" disabled selected>Select a town</option>
								<?php foreach($villes as $ville) {
									echo('<option value="'.$ville->getIdVille().'">'.$ville->getNomVille().'</option>');
								} ?>
							</select>
						</div>
						<div class="form-group">
							<label>Enter a name for this institution</label>
							<input type="text" name="newNom" class="form-control input-sm" placeholder="Enter a name for this institution"/>
						</div>
						<div class="form-group">
							<label>Enter an address for this institution</label>
							<textarea name="newAdresse" class="form-control input-sm" placeholder="Enter an address for this institution"></textarea>
						</div>
						<button class="btn btn-primary" type="submit">Create a new institution</button>
					</form>
				<?php } ?>
				
			
				<form method="POST" action="/PourAdminSeulement/Groupes/Etablissement/" class="well well-lg">
					<legend>Manage a institution</legend>
					<div class="form-group">
						<select name="idEtablissement" class="form-control input-sm">
							<?php foreach($allEtablissements as $allEtablissement) {
								echo('<option value="'.$allEtablissement->getIdEtablissement().'">'.$allEtablissement->getNomEtablissement().'</option>');
							} ?>
						</select>
					</div>
					<button class="btn btn-primary" type="submit">Manage this institution</button>
				</form>
			
			</div>
			
			<?php if(isset($etablissement)) { ?>
			<div class="col-sm-5 sousMenu col-sm-offset-2">
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Groupes/Etablissement/changerInfoEtablissement">
					<legend>Edit institution: <?php echo $etablissement->getNomEtablissement(); ?></legend>
					<input type="hidden" name="idEtablissement" value="<?php echo $etablissement->getIdEtablissement(); ?>"/>
					<div class="form-group">
						<label>Select a new name for this institution</label>
						<input type="text" name="newNom" class="form-control input-sm" value="<?php echo $etablissement->getNomEtablissement(); ?>"/>
					</div>
					<div class="form-group">
						<label>Select a new address for this institution</label>
						<textarea name="newAdresse" ><?php echo $etablissement->getAdresseEtablissement(); ?></textarea>
					</div>
					<div class="form-group">
						<label>Select a new town for this institution</label>
						<select name="newIdVille" class="form-control input-sm">
							<?php foreach($villes as $ville) {
								$option = '<option value="'.$ville->getIdVille().'"';
								if($ville->getIdVille() == $etablissement->getVille()->getIdVille())
								{
									$option .= ' selected';
								}
								$option .= '>'.$ville->getNomVille().'</option>';
								echo($option);
							} ?>
						</select>
					</div>

					<button class="btn btn-primary" type="submit">Edit</button>
				</form>
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Groupes/Etablissement/supprimerEtablissement">
					<legend>Delete this institution</legend>
					<input type="hidden" name="idEtablissement" value="<?php echo $etablissement->getIdEtablissement(); ?>"/>
				<button class="btn btn-primary" type="submit">Delete</button>
				</form>
			</div>
			<?php } ?>
			
		</div>
	</div>
</div>
