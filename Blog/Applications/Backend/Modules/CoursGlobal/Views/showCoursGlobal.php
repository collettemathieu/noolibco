<div class="container-fluid backend paddingTopBackend">
	<div class="row-fluid">
		<div class="col-lg-12 centering maxWidth">
			<div class="col-lg-1 sousMenu">
				<form class="" method="post" action="/ForAdminOnly/CoursGlobal/PublierDepublierCoursGlobal">
					<button type="submit" class="infoBulle menuCoursGlobalBackend btn btn-margin btn-lg <?php if($coursGlobal->getEnLigneCoursGlobal()){echo 'btn-danger';}else{echo 'btn-success';}?>" title="<?php if($coursGlobal->getEnLigneCoursGlobal()){echo 'Retirer ce cours global';}else{echo 'Publier ce cours global';}?>"><i class="glyphicon <?php if($coursGlobal->getEnLigneCoursGlobal()){echo 'glyphicon-remove';}else{echo 'glyphicon-ok';}?>"></i></button>
					<input type="hidden" name="idCoursGlobal" value="<?php echo $coursGlobal->getIdCoursGlobal();?>"/>
				</form>
				<button type="button" class="infoBulle menuCoursGlobalBackend btn btn-info btn-margin btn-lg" data-toggle="modal" href="#editerCategorie" title="Editer la catégorie du coursGlobal"><i class="glyphicon glyphicon-list-alt"></i></button>
				<button type="button" class="infoBulle menuCoursGlobalBackend btn btn-warning btn-margin btn-lg" data-toggle="modal" href="#editerDescription" title="Editer la description du coursGlobal"><i class="glyphicon glyphicon-align-left"></i></button>
				<button type="button" class="infoBulle menuCoursGlobalBackend btn btn-danger btn-margin btn-lg" data-toggle="modal" href="#supprimerCoursGlobal" title="Supprimer le coursGlobal"><i class="glyphicon glyphicon-trash"></i></button>	
			</div>
			<div class="col-lg-offset-1 col-lg-10 sousMenu">
				<div class="row">
					<a class="col-lg-4" data-toggle="modal" href="#modifierImageCoursGlobal">
						<img class="infoBulle" data-toggle="tooltip" title="Editer l'image de présentation du coursGlobal" src="data:image/png;charset=utf8;base64,<?php echo base64_encode(file_get_contents($coursGlobal->getUrlImageMiniatureCoursGlobal())); ?>"/>
					</a>
					<a class="col-lg-8" data-toggle="modal" href="#editerTitre">
						<h2 class="infoBulle" data-toggle="tooltip" title="Editer le titre du coursGlobal"><?php echo $coursGlobal->getTitreCoursGlobal();?></h2>
					</a>
					<h3 class="col-lg-8 cesure"><?php echo $coursGlobal->getDescriptionCoursGlobal();?></h3>
				</div>
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
			<h2 class="modal-title">Titre du coursGlobal</h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering">
						<form action="/ForAdminOnly/CoursGlobal/ModifierTitreCoursGlobal" method="post" class="well well-lg">
							<legend>Modifier le titre du cours global ?</legend>
							<div class="form-group">
								<label>Titre actuel</label>
								<input type="text" name="nouveauTitre" class="form-control" value="<?php echo $coursGlobal->getTitreCoursGlobal();?>"/>
							</div>
							<input type="hidden" name="idCoursGlobal" value="<?php echo $coursGlobal->getIdCoursGlobal();?>"/>
							<button class="btn btn-primary" type="submit">Modifier</button>	
						</form>
					</div>
				</div>
			</div>
      	</div>
    </div>
  </div>
</div>

<div id="editerCategorie" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2 class="modal-title">Catégorie du coursGlobal</h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering">
						<form action="/ForAdminOnly/CoursGlobal/ModifierCategorieCoursGlobal" method="post" class="well well-lg">
							<legend>Modifier la catégorie du cours global ?</legend>
							<div class="form-group">
								<label>Catégorie actuelle</label>
								<select name="nouvelleCategorieCoursGlobal" class="form-control input-sm">
									<?php echo $categoriesAAfficher; ?>
								</select>
							</div>
							<input type="hidden" name="idCoursGlobal" value="<?php echo $coursGlobal->getIdCoursGlobal();?>"/>
							<button class="btn btn-primary" type="submit">Modifier</button>	
						</form>
					</div>
				</div>
			</div>
      	</div>
    </div>
  </div>
</div>

<div id="modifierImageCoursGlobal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2 class="modal-title">Image de présentation du cours global</h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering">
						<div class="file-picker" id="dropzoneImageCoursGlobal">
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
									<input type="hidden" name="idCoursGlobal" value="<?php echo $coursGlobal->getIdCoursGlobal();?>"/>
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


<div id="editerDescription" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2 class="modal-title">Description du cours global</h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering">
						<form action="/ForAdminOnly/CoursGlobal/ModifierDescriptionCoursGlobal" method="post" class="well well-lg">
							<legend>Modifier la description du cours global ?</legend>
							<div class="form-group">
								<label>Description actuelle</label>
								<textarea rows="5" name="nouvelleDescription" class="form-control"><?php echo $coursGlobal->getDescriptionCoursGlobal();?></textarea>							</div>
							<input type="hidden" name="idCoursGlobal" value="<?php echo $coursGlobal->getIdCoursGlobal();?>"/>
							<button class="btn btn-primary" type="submit">Modifier</button>	
						</form>
					</div>
				</div>
			</div>
      	</div>
    </div>
  </div>
</div>


<div id="supprimerCoursGlobal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2 class="modal-title">Suppression du cours global</h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering">
						<form action="/ForAdminOnly/CoursGlobal/SupprimerCoursGlobal" method="post" class="well well-lg">
							<legend>Etes-vous sûr de vouloir supprimer cet cours global ?</legend>
							<input type="hidden" name="idCoursGlobal" value="<?php echo $coursGlobal->getIdCoursGlobal();?>"/>
							<button class="btn btn-primary" type="submit">Supprimer</button>	
						</form>
					</div>
				</div>
			</div>
      	</div>
    </div>
  </div>
</div>