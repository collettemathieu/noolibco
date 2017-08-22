<div class="container-fluid backend paddingTopBackend">
	<div class="row-fluid">
		<div class="col-lg-12 centering maxWidth">
			<div class="row">
				<div class="col-lg-8 sousMenu centering">
					<h1>Commentaires des articles</h1>
				</div>
				<div class="col-lg-8 sousMenu centering">
					<h2>Commentaires en attente d'approbation</h2>
					<ul class="list-group listeCommentaires listeCommentairesEnAttente">
						<?php 
						if(isset($commentaires)){
							foreach($commentaires as $commentaire){
								if(!$commentaire->getEnAttenteValidationAuteurCommentaire() && $commentaire->getArticle() instanceof \Library\Entities\Article){
								?>
							<li class="list-group-item commentaire" enLigneComment="<?php echo (int) $commentaire->getEnLigneCommentaire();?>" titreArticle = "<?php echo $commentaire->getArticle()->getTitreArticle();?>" texteComment = "<?php echo $commentaire->getTexteCommentaire();?>" auteurComment = "<?php echo $commentaire->getUtilisateur()->getNomUtilisateur();?>" idComment = "<?php echo $commentaire->getIdCommentaire();?>"><?php echo $commentaire->getArticle()->getTitreArticle().' | de '.$commentaire->getUtilisateur()->getNomUtilisateur().' | '.substr($commentaire->getTexteCommentaire(), 0, 20);?></li>
						<?php }}} ?>
					</ul>

				</div>
				<div class="col-lg-8 sousMenu centering">
					<h2>Commentaires en attente de validation de l'auteur</h2>
					<ul class="list-group listeCommentaires listeCommentairesEnAttenteValidationAuteur">
						<?php 
						if(isset($commentaires)){
							foreach($commentaires as $commentaire){
								if(!$commentaire->getEnLigneCommentaire() && $commentaire->getEnAttenteValidationAuteurCommentaire() && $commentaire->getArticle() instanceof \Library\Entities\Article){
								?>
						<li class="list-group-item commentaire" enLigneComment="<?php echo (int) $commentaire->getEnLigneCommentaire();?>" titreArticle = "<?php echo $commentaire->getArticle()->getTitreArticle();?>" texteComment = "<?php echo $commentaire->getTexteCommentaire();?>" auteurComment = "<?php echo $commentaire->getUtilisateur()->getNomUtilisateur();?>" idComment = "<?php echo $commentaire->getIdCommentaire();?>"><?php echo $commentaire->getArticle()->getTitreArticle().' | de '.$commentaire->getUtilisateur()->getNomUtilisateur().' | '.substr($commentaire->getTexteCommentaire(), 0, 20);?></li>
						<?php }}} ?>
					</ul>

				</div>
				<div class="col-lg-8 sousMenu centering">
					<h2>Commentaires validés</h2>
					<ul class="list-group listeCommentaires listeCommentairesEnLigne">
						<?php 
						if(isset($commentaires)){
							foreach($commentaires as $commentaire){
								if($commentaire->getEnLigneCommentaire() && $commentaire->getArticle() instanceof \Library\Entities\Article){
								?>
						<li class="list-group-item commentaire" enLigneComment="<?php echo (int) $commentaire->getEnLigneCommentaire();?>" titreArticle = "<?php echo $commentaire->getArticle()->getTitreArticle();?>" texteComment = "<?php echo $commentaire->getTexteCommentaire();?>" auteurComment = "<?php echo $commentaire->getUtilisateur()->getNomUtilisateur();?>" idComment = "<?php echo $commentaire->getIdCommentaire();?>"><?php echo $commentaire->getArticle()->getTitreArticle().' | de '.$commentaire->getUtilisateur()->getNomUtilisateur().' | '.substr($commentaire->getTexteCommentaire(), 0, 20);?></li>
						<?php }}} ?>
					</ul>

				</div>
			</div>
			
		</div>
	</div>
</div>


<div id="afficherCommentaire" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2 class="modal-title">Commentaire</h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering">
						<div class="alert alert-info">
							<h3></h3>
							<h4></h4>
							<p></p>
						</div>
						<form id="formAccepterCommentaire" method="post" class="well well-lg">
							<legend>Accepter le commentaire ?</legend>
							<input type="hidden" name="idCommentaire" class="form-control" value=""/>
							<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Traitement..." type="submit">Accepter</button>	
						</form>
						<form id="formSupprimerCommentaire" method="post" class="well well-lg">
							<legend>Supprimer le commentaire ?</legend>
							<input type="hidden" name="idCommentaire" class="form-control" value=""/>
							<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Traitement..." type="submit">Supprimer</button>	
						</form>
					</div>
				</div>
			</div>
      	</div>
    </div>
  </div>
</div>