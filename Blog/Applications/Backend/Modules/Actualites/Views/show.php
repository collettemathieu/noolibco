<div class="container-fluid backend paddingTopBackend">
	<div class="row-fluid">
		<div class="col-lg-12 centering maxWidth">
			<div class="col-lg-4 sousMenu">
				<form method="post" class="well well-lg" action="/ForAdminOnly/Actualites/CreerActualite" enctype="multipart/form-data">
					<legend>Créer une nouvelle actualite</legend>
					<div class="form-group">
						<label>Titre</label>
						<input type="text" required maxlength="30" name="titreActualite" class="form-control" placeholder="Entrer le titre de l'actualité..."/>
					</div>
					<div class="form-group">
						<label>Texte</label>
						<textarea rows="4" required maxlength="450" name="texteActualite" class="form-control" placeholder="Entrer le texte..."></textarea>
					</div>
					<div class="form-group">
						<label>Lien url vers l'information</label>
						<input type="text" name="urlLienActualite" class="form-control" placeholder="Entrer l'url en lien avec l'actualité..."/>
					</div>
					<button class="btn btn-primary" type="submit">Créer cette actualité</button>	
				</form>
			</div>
			<div class="col-lg-offset-1  col-lg-7 sousMenu">
				<h2>Liste des actualites</h2>
				<ul class="list-group listeActualites">
					<?php 
					if(isset($actualites)){
						foreach($actualites as $actualite){?>
					<a href="/ForAdminOnly/Actualites/id=<?php echo $actualite->getIdActualite();?>"><li class="list-group-item"><?php echo $actualite->getTitreActualite();?></li></a>
					<?php }} ?>
				</ul>

			</div>
			
		</div>
	</div>
</div>