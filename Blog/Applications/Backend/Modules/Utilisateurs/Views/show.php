<div class="container-fluid backend paddingTopBackend">
	<div class="row-fluid">
		<div class="col-lg-12 centering maxWidth">
			<div class="col-lg-4 sousMenu">
				<form method="post" class="well well-lg" action="/ForAdminOnly/Utilisateurs/CreerUtilisateur">
					<legend>Créer un nouvel utilisateur</legend>
					<div class="form-group">
						<label>Nom</label>
						<input type="text" name="nom" class="form-control" placeholder="Entrer le nom de l'utilisateur"/>
					</div>
					<div class="form-group">
						<label>Adresse électronique</label>
						<input type="email" name="adresseMail" class="form-control" placeholder="Entrer une adresse électonique valide"/>
					</div>
					<div class="form-group">
						<label>Mot de passe administrateur (comprenant au moins 8 lettres dont un chiffre, une majuscule et une miniuscle)</label>
						<input type="password" name="motDePasseAdmin" class="form-control" placeholder="Entrer un mot de passe administrateur."/>
					</div>
					<div class="form-group">
						<label>Confirmation du mot de passe administrateur</label>
						<input type="password" name="motDePasseAdminConfirme" class="form-control" placeholder="Confirmer le mot de passe administrateur"/>
					</div>
					<div class="form-group">
						<div class="checkbox">
						  <label><input type="checkbox" name="superAdmin" value="1">Super administrateur ?</label>
						</div>
					</div>
					<div class="form-group">
						<div class="checkbox">
						  <label><input type="checkbox" name="newsletter" value="1">Inscription à la newsletter ?</label>
						</div>
					</div>
					
					<button class="btn btn-primary" type="submit">Créer cet utilisateur</button>	
				</form>
			</div>
			<div class="col-lg-offset-1  col-lg-5 sousMenu">
				<h2>Liste des utilisateurs</h2>
				<ul class="list-group" id="listeUtilisateurs">
					<?php 
					if(isset($utilisateurs)){
						foreach($utilisateurs as $utilisateur){?>
					<li class="list-group-item utilisateur" idUser="<?php echo $utilisateur->getIdUtilisateur();?>" nameUser="<?php echo $utilisateur->getNomUtilisateur();?>" mailUser="<?php echo $utilisateur->getMailUtilisateur();?>" dateInscriptionUser="<?php echo $utilisateur->getDateInscriptionUtilisateur();?>" newsletter="<?php if($utilisateur->getNewsletterUtilisateur()){echo '1';}else{echo '0';}?>"><?php echo $utilisateur->getMailUtilisateur();?></li>
					<?php }} ?>
				</ul>

			</div>
			
		</div>
	</div>
</div>


<div id="afficherProfilUtilisateur" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2 class="modal-title">Profil de l'utilisateur</h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering">
						<form id="formModifierUtilisateur" method="post" class="well well-lg">
							<legend>Modifier l'utilisateur ?</legend>
							<input type="hidden" name="idUtilisateur" class="form-control" value=""/>
							<input type="hidden" name="ancienMailUtilisateur" class="form-control" value=""/>
							<div class="form-group">
								<label>Nom</label>
								<input type="text" name="nom" class="form-control" placeholder="Entrer le nom de l'utilisateur"/>
							</div>
							<div class="form-group">
								<label>Adresse électronique</label>
								<input type="email" name="adresseMail" class="form-control" placeholder="Entrer une adresse électonique valide"/>
							</div>
							<div class="form-group">
								<label>Date d'inscription</label>
								<input readonly/>
							</div>
							<div class="form-group">
								<div class="checkbox">
								  <label><input type="checkbox" name="newsletter" checked="" value="1">Inscription à la newsletter ?</label>
								</div>
							</div>
							<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Traitement..." type="submit">Modifier</button>	
						</form>
						<form id="formSupprimerUtilisateur" method="post" class="well well-lg">
							<legend>Supprimer l'utilisateur ?</legend>
							<input type="hidden" name="idUtilisateur" class="form-control" value=""/>
							<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Traitement..." type="submit">Supprimer</button>	
						</form>
					</div>
				</div>
			</div>
      	</div>
    </div>
  </div>
</div>