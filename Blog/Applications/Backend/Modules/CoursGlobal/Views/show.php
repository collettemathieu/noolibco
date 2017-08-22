<div class="container-fluid backend paddingTopBackend">
	<div class="row-fluid">
		<div class="col-lg-12 centering maxWidth">
			<div class="col-lg-4 sousMenu">
				<form method="post" class="well well-lg" action="/ForAdminOnly/CoursGlobal/CreerCoursGlobal">
					<legend>Créer un nouveau cours global</legend>
					<div class="form-group">
						<label>Titre</label>
						<input type="text" name="titreCoursGlobal" class="form-control" placeholder="Entrer le titre du cours"/>
					</div>
					<div class="form-group">
						<label>Description</label>
						<textarea name="descriptionCoursGlobal" class="form-control" placeholder="Entrer une description"></textarea>
					</div>
					<div class="form-group">
						<label>Catégorie</label>
						<select name="categorieCoursGlobal" class="form-control input-sm">
							<?php echo $categoriesAAfficher; ?>
						</select>
					</div>
					<button class="btn btn-primary" type="submit">Créer ce cours global</button>	
				</form>
			</div>
			<div class="col-lg-offset-1  col-lg-7 sousMenu">
				<h2>Liste des cours globaux</h2>
				<ul class="list-group listeCoursGlobal">
					<?php 
					if(isset($coursGlobal)){
						foreach($coursGlobal as $coursGlobal){?>
					<a href="/ForAdminOnly/CoursGlobal/id=<?php echo $coursGlobal->getIdCoursGlobal();?>"><li class="list-group-item"><?php echo $coursGlobal->getTitreCoursGlobal();?></li></a>
					<?php }} ?>
				</ul>

			</div>
			
		</div>
	</div>
</div>