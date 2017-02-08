<?php if(!$user->getAttribute('isAdmin') && $admisSurLaPage) { ?>
	<div class="container">
		<div class="row">
			<div class="col-sm-8 centering maxWidth">
				<form method="post" class="well well-lg" action="/PourAdminSeulement/PasserEnAdmin">
					<h2>Authentication required</h2>
					<div class="form-group">
						<input type="password" class="form-control input-lg" name="passwordAdmin" placeholder="Enter your administrator password...">
					</div>
					<button class="btn btn-primary" type="submit">Valid</button>
				</form>
			</div>
		</div>
	</div>
	
<?php } ?>
	
<?php if($user->getAttribute('isAdmin')) { ?>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="col-sm-8 text-center centering maxWidth fadeIn">
				<a class="caseMenu text-left" href="/PourAdminSeulement/Utilisateurs/">
					<p>Users</p>
					<img src="/Images/profile.png"/>
				</a>
				<a class="caseMenu text-left" href="/PourAdminSeulement/Groupes/">
					<p>Groups</p>
					<img src="/Images/localisation.png"/>
				</a>
				<a class="caseMenu text-left" href="/PourAdminSeulement/Applications/">
					<p>Applications</p>
					<img src="/Images/Application.png"/>
				</a>
				<a class="caseMenu text-left" href="/PourAdminSeulement/DonneesUtilisateur/">
					<p>Data</p>
					<img src="/Images/DonneesUtilisateurIcone.png"/>
				</a>
				<a class="caseMenu text-left" href="/PourAdminSeulement/Attributs/">
					<p>Attributes</p>
					<img src="/Images/Attributs.png"/>
				</a>
				<a class="caseMenu text-left" href="/PourAdminSeulement/Configurations/">
					<p>Configurations</p>
					<img src="/Images/parameters.png"/>
				</a>
			</div>
		</div>
	</div>
<?php }?>
