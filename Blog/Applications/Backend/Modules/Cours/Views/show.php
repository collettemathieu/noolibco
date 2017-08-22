<div class="container-fluid backend paddingTopBackend">
	<div class="row-fluid">
		<div class="col-lg-12 centering maxWidth">
			<div class="col-lg-4 sousMenu">
				<form method="post" class="well well-lg" action="/ForAdminOnly/Cours/CreerCours">
					<legend>Créer un nouveau cours</legend>
					<div class="form-group">
						<label>Titre</label>
						<input type="text" name="titreCours" maxlength="50" class="form-control" placeholder="Entrer le titre du cours"/>
					</div>
					<div class="form-group">
						<label>Description</label>
						<textarea name="descriptionCours" class="form-control" placeholder="Entrer une description"></textarea>
					</div>
					<div class="form-group">
						<label>Mot-clés</label>
						<input type="text" name="motClesCours" class="form-control" placeholder="mot1, mot2, mot3, ..."/>
					</div>
					<div class="form-group">
						<label>Catégorie</label>
						<select name="categorieCours" class="form-control input-sm">
							<?php echo $categoriesAAfficher; ?>
						</select>
					</div>
					<div class="form-group">
						<label>Relié ce cours au cours global </label>
						<select name="idCoursGlobal" class="form-control input-sm">
							<?php echo $coursGlobauxAAfficher; ?>
						</select>
					</div>
					<button class="btn btn-primary" type="submit">Créer ce cours</button>	
				</form>
			</div>
			<div class="col-lg-offset-1  col-lg-7 sousMenu">
				<h2>Liste des cours</h2>
				<ul class="list-group listeCours">
					<?php 
					if(isset($cours)){
						foreach($cours as $cours){?>
					<a href="/ForAdminOnly/Cours/id=<?php echo $cours->getIdCours();?>"><li class="list-group-item"><?php echo $cours->getTitreCours();?></li></a>
					<?php }} ?>
				</ul>

			</div>
			
		</div>
	</div>
</div>