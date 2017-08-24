<div class="container-fluid backend paddingTopBackend">
	<div class="row-fluid">
		<div class="col-lg-12 centering maxWidth">
			<div class="col-lg-1 sousMenu">
				<form class="" method="post" action="/ForAdminOnly/Actualites/PublierDepublierActualite">
					<button type="submit" class="infoBulle menuActualiteBackend btn btn-margin btn-lg <?php if($actualite->getEnLigneActualite()){echo 'btn-danger';}else{echo 'btn-success';}?>" title="<?php if($actualite->getEnLigneActualite()){echo 'Retirer cette actualité';}else{echo 'Publier cette actualité';}?>"><i class="glyphicon <?php if($actualite->getEnLigneActualite()){echo 'glyphicon-remove';}else{echo 'glyphicon-ok';}?>"></i></button>
					<input type="hidden" name="idActualite" value="<?php echo $actualite->getIdActualite();?>"/>
				</form>
				<button type="button" class="infoBulle menuActualiteBackend btn btn-warning btn-margin btn-lg" data-toggle="modal" href="#editerUrlLienActualite" title="Editer le lien URL de l'actualité"><i class="glyphicon glyphicon-align-left"></i></button>
				<button type="button" class="infoBulle menuActualiteBackend btn btn-danger btn-margin btn-lg" data-toggle="modal" href="#supprimerActualite" title="Supprimer l'actualité"><i class="glyphicon glyphicon-trash"></i></button>	
			</div>
			<div class="col-lg-offset-1 col-lg-10 sousMenu">
				<div class="row">
					<a class="col-lg-4" data-toggle="modal" href="#modifierImageActualite">
						<img class="infoBulle imageActualite" data-toggle="tooltip" title="Editer l'image de présentation de l'actualité" src="data:image/png;charset=utf8;base64,<?php echo base64_encode(file_get_contents('../public_html'.$actualite->getUrlImageActualite())); ?>"/>
					</a>
					<a class="col-lg-8" data-toggle="modal" href="#editerTitre">
						<h2 class="infoBulle" data-toggle="tooltip" title="Editer le titre de l'actualité"><?php echo $actualite->getTitreActualite();?></h2>
					</a>
				</div>
			</div>
			<div class="col-lg-offset-2 col-lg-10 sousMenu">
				<form action="/ForAdminOnly/Actualites/ModifierTexteActualite" method="post" class="well well-lg">
					<div class="form-group">
						<textarea required maxlength="450" rows="8" name="nouveauTexte" class="form-control"><?php echo $actualite->getTexteActualite();?></textarea>							
					</div>
					<input type="hidden" name="idActualite" value="<?php echo $actualite->getIdActualite();?>"/>
					<button class="btn btn-primary" type="submit">Modifier</button>	
				</form>
			</div>
		</div>
	</div>
</div>


<div id="editerTitre" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2 class="modal-title">Titre de l'actualité</h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering">
						<form action="/ForAdminOnly/Actualites/ModifierTitreActualite" method="post" class="well well-lg">
							<legend>Modifier le titre de l'actualité ?</legend>
							<div class="form-group">
								<label>Titre actuel</label>
								<input type="text" name="nouveauTitre" class="form-control" value="<?php echo $actualite->getTitreActualite();?>"/>
							</div>
							<input type="hidden" name="idActualite" value="<?php echo $actualite->getIdActualite();?>"/>
							<button class="btn btn-primary" type="submit">Modifier</button>	
						</form>
					</div>
				</div>
			</div>
      	</div>
    </div>
  </div>
</div>



<div id="editerUrlLienActualite" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2 class="modal-title">Lien URL de l'actualité</h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering">
						<form action="/ForAdminOnly/Actualites/ModifierUrlLienActualite" method="post" class="well well-lg">
							<legend>Modifier le lien URL de l'actualité ?</legend>
							<div class="form-group">
								<label>Lien URL actuel</label>
								<input type="text" name="nouveauLienUrl" class="form-control" value="<?php echo $actualite->getUrlLienActualite();?>"/>
							</div>
							<input type="hidden" name="idActualite" value="<?php echo $actualite->getIdActualite();?>"/>
							<button class="btn btn-primary" type="submit">Modifier</button>	
						</form>
					</div>
				</div>
			</div>
      	</div>
    </div>
  </div>
</div>

<div id="modifierImageActualite" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2 class="modal-title">Modifier l'image de présentation de l'actualité</h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering">
						<div class="file-picker" id="dropzoneImageActualite">
							<svg class="file-picker__icon" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
								<g>
									<polygon points="41.3,57.7 42.1,58.5 49.5,51.1 49.5,74.8 50.5,74.8 50.5,51.1 57.9,58.5 58.7,57.7 50,49    "/>
									<path d="M71.3,38.2c-0.1,0-0.2,0-0.3,0c-1.1-7.5-7.4-13-15.1-13c-6.5,0-12.3,4.2-14.4,10.3c-1.1-0.3-2.3-0.5-3.4-0.5
									c-6,0-11.1,4.4-12,10.3c-0.3,0-0.6-0.1-0.9-0.1c-4.7,0-8.6,3.8-8.6,8.6c0,4.7,3.8,8.6,8.6,8.6h10.8v-1.1H25.2
									c-4.1,0-7.5-3.4-7.5-7.5c0-4.1,3.3-7.5,7.5-7.5c0.4,0,0.8,0,1.3,0.1l0.6,0.1l0.1-0.6c0.6-5.6,5.3-9.9,10.9-9.9
									c1.2,0,2.4,0.2,3.6,0.6l0.5,0.2l0.2-0.5c1.8-5.9,7.3-10.1,13.5-10.1c7.2,0,13.3,5.4,14,12.6l0.1,0.5l0.5,0c0.2,0,0.5,0,0.7,0
									c6.1,0,11,4.9,11,11c0,6.1-4.9,11-11,11h-7v1.1h7c6.7,0,12.1-5.4,12.1-12.1C83.3,43.6,77.9,38.2,71.3,38.2z"/>
								</g>
							</svg>

							<svg class="file-picker__icon--cancel" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
								<path d="M51,50l14.9-15c0.3-0.3,0.3-0.7,0-1c-0.3-0.3-0.7-0.3-1,0L50,49L35.1,34.1c-0.3-0.3-0.7-0.3-1,0c-0.3,0.3-0.3,0.7,0,1L49,50
								L34.1,65c-0.3,0.3-0.3,0.7,0,1c0.1,0.1,0.3,0.2,0.5,0.2c0.2,0,0.4-0.1,0.5-0.2L50,51l14.9,14.9c0.1,0.1,0.3,0.2,0.5,0.2
								c0.2,0,0.4-0.1,0.5-0.2c0.3-0.3,0.3-0.7,0-1L51,50z"/>
							</svg>

							<div class="file-picker__progress" id="file-picker__progress"></div>

							<form class="file-picker__form dropzone" id="dropzone" method="POST" enctype="multipart/form-data">
								<div class="dropzone__fallback hidden">
									<input name="file" type="file" />
									<input type="hidden" name="idActualite" value="<?php echo $actualite->getIdActualite();?>"/>
									<input type="submit" name="submit" value="Upload file" />
								</div>
								<div class="hidden" id="dropzone__hidden"></div>
							</form>
							<div class="file-picker__overlay"></div>
						</div>

					</div>
				</div>
			</div>
      	</div>
    </div>
  </div>
</div>


<div id="supprimerActualite" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2 class="modal-title">Suppression de l'actualité</h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering">
						<form action="/ForAdminOnly/Actualites/SupprimerActualite" method="post" class="well well-lg">
							<legend>Etes-vous sûr de vouloir supprimer cette actualité ?</legend>
							<input type="hidden" name="idActualite" value="<?php echo $actualite->getIdActualite();?>"/>
							<button class="btn btn-primary" type="submit">Supprimer</button>	
						</form>
					</div>
				</div>
			</div>
      	</div>
    </div>
  </div>
</div>