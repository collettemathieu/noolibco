<div class="container-fluid backend paddingTopBackend">
	<div class="row-fluid">
		<div class="col-lg-12 centering maxWidth">
			<div class="col-lg-4 sousMenu">
				<form method="post" class="well well-lg" action="/ForAdminOnly/Editos/CreerEdito">
					<legend>Créer un nouvel édito</legend>
					<div class="form-group">
						<label>Texte</label>
						<textarea name="texteEdito" rows="10" class="form-control" placeholder="Entrer le texte de l'édito"></textarea>
					</div>
					<button class="btn btn-primary" type="submit">Créer cet edito</button>	
				</form>
			</div>
			<div class="col-lg-offset-1  col-lg-7 sousMenu">
				<h2>Liste des editos</h2>
				<ul class="list-group listeEditos">
					<?php 
					if(isset($editos)){
						foreach($editos as $edito){?>
					<a href="/ForAdminOnly/Editos/id=<?php echo $edito->getIdEdito();?>"><li class="list-group-item"><?php echo substr($edito->getTexteEdito(), 0, 50).' ...';?></li></a>
					<?php }} ?>
				</ul>

			</div>
			
		</div>
	</div>
</div>