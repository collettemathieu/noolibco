<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-lg-11 centering maxWidth">
			<div class="col-sm-5 sousMenu">
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Groupes/Pays/creerPays">
					<legend>Create a new country</legend>
					<div class="form-group">
						<label>Enter a name for this country</label>
						<input type="text" name="nom" class="form-control input-sm" placeholder="Enter a name for this country"/>
					</div>
					<button class="btn btn-primary" type="submit">Create a new country</button>
				</form>
				
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Groupes/Pays/">
					<legend>Manage a country</legend>
					<select name="idPays" class="input-sm">
						<?php foreach($allPays as $paysi) {
							echo('<option value="'.$paysi->getIdPays().'">'.$paysi->getNomPays().'</option>');
						} ?>
					</select>
					<button class="btn btn-primary" type="submit">Manage this country</button>
				</form>
			</div>
			
			<?php if(isset($pays)) { ?>
			<div class="col-sm-5 sousMenu col-sm-offset-2">
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Groupes/Pays/changerInfoPays">
					<legend>Edit country: <?php echo $pays->getNomPays(); ?></legend>
					<input type="hidden" name="idPays" value="<?php echo $pays->getIdPays(); ?>"/>
					<div class="form-group">
						<label>Select a new name for this country</label>
						<input type="text" name="nom" class="form-control input-sm" value="<?php echo $pays->getNomPays(); ?>"/>
					</div>

					<button class="btn btn-primary" type="submit">Edit</button>
				</form>
				<form method="POST" class="well well-lg" action="/PourAdminSeulement/Groupes/Pays/supprimerPays">
					<legend>Delete this country</legend>
					<input type="hidden" name="idPays" value="<?php echo $pays->getIdPays(); ?>"/>
					<button class="btn btn-primary" type="submit">Delete</button>
				</form>
			</div>
			<?php } ?>
			
		</div>
	</div>
</div>