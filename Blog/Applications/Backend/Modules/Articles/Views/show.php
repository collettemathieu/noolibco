<div class="container-fluid backend paddingTopBackend">
	<div class="row-fluid">
		<div class="col-lg-12 centering maxWidth">
			<div class="col-lg-4 sousMenu">
				<form method="post" class="well well-lg" action="/ForAdminOnly/Articles/CreerArticle">
					<legend>Créer un nouvel article</legend>
					<div class="form-group">
						<label>Titre</label>
						<input type="text" name="titreArticle" maxlength="50" class="form-control" placeholder="Entrer le titre de l'article"/>
					</div>
					<div class="form-group">
						<label>Description</label>
						<textarea name="descriptionArticle" class="form-control" placeholder="Entrer une description"></textarea>
					</div>
					<div class="form-group">
						<label>Mot-clés</label>
						<input type="text" name="motClesArticle" class="form-control" placeholder="mot1, mot2, mot3, ..."/>
					</div>
					<div class="form-group">
						<label>Catégorie</label>
						<select name="categorieArticle" class="form-control input-sm">
							<?php echo $categoriesAAfficher; ?>
						</select>
					</div>
					<button class="btn btn-primary" type="submit">Créer cet article</button>	
				</form>
			</div>
			<div class="col-lg-offset-1  col-lg-7 sousMenu">
				<h2>Liste des articles</h2>
				<ul class="list-group listeArticles">
					<?php 
					if(isset($articles)){
						foreach($articles as $article){?>
					<a href="/ForAdminOnly/Articles/id=<?php echo $article->getIdArticle();?>"><li class="list-group-item"><?php echo $article->getTitreArticle();?></li></a>
					<?php }} ?>
				</ul>

			</div>
			
		</div>
	</div>
</div>