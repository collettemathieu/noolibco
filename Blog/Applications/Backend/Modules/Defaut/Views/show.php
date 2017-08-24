<?php if(!$user->getAttribute('isAdmin')) { ?>
	<div class="container-fluid backend">
		<div class="row-fluid ">
			<div class="col-lg-6 centering maxWidth">
				<form method="post" class="well well-lg" action="/ForAdminOnly/PasserEnAdmin">
					<h2>Authentification requise</h2>
					<div class="form-group">
						<input type="email" class="form-control input-lg" name="mailAdmin" placeholder="Entrer votre adresse électronique">
					</div>
					<div class="form-group">
						<input type="password" class="form-control input-lg" name="passwordAdmin" placeholder="Entrer votre mot de passe administrateur">
					</div>
					<button class="btn btn-primary" type="submit">Envoyer</button>
				</form>
			</div>
		</div>
	</div>
<?php }?>
