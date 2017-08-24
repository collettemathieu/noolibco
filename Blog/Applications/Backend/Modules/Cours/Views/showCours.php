<div class="container-fluid backend paddingTopBackend">
	<div class="row-fluid">
		<div class="col-lg-12 centering maxWidth">
			<div class="col-lg-1 sousMenu">
				<form class="" method="post" action="/ForAdminOnly/Cours/PublierDepublierCours">
					<button type="submit" class="infoBulle menuCoursBackend btn btn-margin btn-lg <?php if($cours->getEnLigneCours()){echo 'btn-danger';}else{echo 'btn-success';}?>" title="<?php if($cours->getEnLigneCours()){echo 'Retirer ce cours';}else{echo 'Publier ce cours';}?>"><i class="glyphicon <?php if($cours->getEnLigneCours()){echo 'glyphicon-remove';}else{echo 'glyphicon-ok';}?>"></i></button>
					<input type="hidden" name="idCours" value="<?php echo $cours->getIdCours();?>"/>
				</form>
				<button type="button" class="infoBulle menuCoursBackend btn btn-info btn-margin btn-lg" data-toggle="modal" href="#editerCategorie" title="Editer la catégorie du cours"><i class="glyphicon glyphicon-list-alt"></i></button>
				<button type="button" class="infoBulle menuCoursBackend btn btn-warning btn-margin btn-lg" data-toggle="modal" href="#editerDescription" title="Editer la description du cours"><i class="glyphicon glyphicon-align-left"></i></button>
				<button type="button" class="infoBulle menuCoursBackend btn btn-default btn-margin btn-lg" data-toggle="modal" href="#editerMotCles" title="Editer les mots-clés du cours"><i class="glyphicon glyphicon-tags"></i></button>
				<button type="button" class="infoBulle menuCoursBackend btn btn-success btn-margin btn-lg" data-toggle="modal" href="#editerCoursGlobal" title="Changer de cours global"><i class="glyphicon glyphicon-random"></i></button>
				<a class="infoBulle menuCoursBackend btn btn-default btn-margin btn-lg" href="/ForAdminOnly/Cours/PostOnSocialNetworks/idCours=<?php echo $cours->getIdCours();?>" title="Poster sur Facebook et Twitter"><i class="glyphicon glyphicon-thumbs-up"></i></a>	
				<a class="infoBulle menuCoursBackend btn btn-info btn-margin btn-lg" href="/ForAdminOnly/Cours/MAJSommaireFigureCours/id=<?php echo $cours->getIdCours();?>" title="Mettre à jour le sommaire et les figures"><i class="glyphicon glyphicon-blackboard"></i></a>
				<button type="button" class="infoBulle menuCoursBackend btn btn-danger btn-margin btn-lg" data-toggle="modal" href="#supprimerCours" title="Supprimer le cours"><i class="glyphicon glyphicon-trash"></i></button>	
			</div>
			<div class="col-lg-offset-1 col-lg-10 sousMenu">
				<div class="row">
					<a class="col-lg-4" data-toggle="modal" href="#modifierImageCours">
						<img class="infoBulle" data-toggle="tooltip" title="Editer l'image de présentation du cours" src="data:image/png;charset=utf8;base64,<?php echo base64_encode(file_get_contents($cours->getUrlImageMiniatureCours())); ?>"/>
					</a>
					<a class="col-lg-8" data-toggle="modal" href="#editerTitre">
						<h2 class="infoBulle" data-toggle="tooltip" title="Editer le titre du cours"><?php echo $cours->getTitreCours();?></h2>
					</a>
					<h3 class="col-lg-8 cesure"><?php echo $cours->getDescriptionCours();?></h3>
				</div>
			</div>
			<div class="col-lg-offset-2 col-lg-10 sousMenu">
				<form action="/ForAdminOnly/Cours/ModifierTexteCours" method="post" class="well well-lg">
					<legend>Texte du cours</legend>
					<div class="form-group">
						<textarea spellcheck rows="50" name="nouveauTexte" id="nouveauTexte" class="form-control"><?php echo $cours->getTexteCours();?></textarea>
					</div>
					<input type="hidden" name="idCours" id="idCours" value="<?php echo $cours->getIdCours();?>"/>
					<button class="btn btn-primary" type="submit">Modifier</button>	
				</form>
			</div>
			<div class="col-lg-offset-2 col-lg-10 sousMenu">
				<form action="/ForAdminOnly/Cours/ModifierReferencesCours" method="post" class="well well-lg">
					<legend>Références du cours</legend>
					<div class="form-group">
						<textarea rows="10" id="referencesCours" name="nouvellesReferences" class="form-control"><?php echo $cours->getReferencesCours();?></textarea>							
					</div>
					<input type="hidden" name="idCours" value="<?php echo $cours->getIdCours();?>"/>
					<button type="button" class="infoBulleBottom pull-right menuCoursBackend btn btn-info btn-margin btn-lg" data-toggle="modal" href="#addReference" title="Ajouter une référence"><i class="glyphicon glyphicon-plus"></i></button>	
					<button class="btn btn-primary" type="submit">Mettre à jour</button>
					
				</form>
			</div>
		</div>
	</div>
</div>


<div id="addReference" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2 class="modal-title">Ajouter une référence</h2>
		</div>
		<div class="modal-body">

			<ul class="nav nav-pills">
				<li class="active"><a href="#automatic" data-toggle="tab"><span class="glyphicon glyphicon-list"></span> Par DOI</a></li>
				<li><a href="#manuel" data-toggle="tab"><span class="glyphicon glyphicon-pencil"></span> Manuellement</a></li>
			</ul><br>

			<div class="tab-content">
				<div class="tab-pane active" id="automatic">
					<form id="searchPublicationForm" class="well well-lg col-lg-12 centering">
						<div class="form-group has-feedback">
							<input type="text" name="reqPublication" class="form-control input-lg" placeholder="Entrer une DOI valide (ex: 10.1000/123456...)"/>
							<span class="glyphicon form-control-feedback"></span>
							<span class="help-block"></span>
						</div>
						<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Traitement..." type="submit">Rechercher</button>
					</form>
				</div>
				<div class="tab-pane" id="manuel">
					<form id="addPublicationForm" class="well well-lg col-lg-12 centering">
						<div class="form-group has-feedback">
							<input type="text" id="titrePubli" name="titrePublication" maxlength="200" class="form-control" placeholder="Titre de la publication" value=""/>
							<span class="glyphicon form-control-feedback"></span>
							<span class="help-block"></span>
						</div>
						<div class="form-group has-feedback">
							<input type="text" id="auteursPubli" name="auteursPublication" class="form-control" placeholder="Collette M, Collette D, ..." value=""/>
							<span class="glyphicon form-control-feedback"></span>
							<span class="help-block"></span>
						</div>
						<div class="form-group has-feedback">
							<input type="text" id="anneePubli" name="anneePublication" maxlength="4" class="form-control" placeholder="Entrer l'année de publication" value=""/>
							<span class="glyphicon form-control-feedback"></span>
							<span class="help-block"></span>
						</div>
						<div class="form-group has-feedback">
							<input type="text" id="journalPubli" name="journalPublication" maxlength="200" class="form-control" placeholder="Entrer le nom du journal scientifique" value=""/>
							<span class="glyphicon form-control-feedback"></span>
							<span class="help-block"></span>
						</div>
						<div class="form-group has-feedback">
							<input type="text" id="urlPubli" name="urlPublication" class="form-control" placeholder="Entrer un lien hypertexte vers la référence (http://)." value=""/>
							<span class="glyphicon form-control-feedback"></span>
							<span class="help-block"></span>
						</div>
						<button class="btn btn-primary" type="submit">Ajouter</button>
					</form>
				</div>
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
			<h2 class="modal-title">Titre du cours</h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering">
						<form action="/ForAdminOnly/Cours/ModifierTitreCours" method="post" class="well well-lg">
							<legend>Modifier le titre du cours ?</legend>
							<div class="form-group">
								<label>Titre actuel</label>
								<input type="text" name="nouveauTitre" maxlength="50" class="form-control" value="<?php echo $cours->getTitreCours();?>"/>
							</div>
							<input type="hidden" name="idCours" value="<?php echo $cours->getIdCours();?>"/>
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
			<h2 class="modal-title">Catégorie du cours</h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering">
						<form action="/ForAdminOnly/Cours/ModifierCategorieCours" method="post" class="well well-lg">
							<legend>Modifier la catégorie du cours ?</legend>
							<div class="form-group">
								<label>Catégorie actuelle</label>
								<select name="nouvelleCategorieCours" class="form-control input-sm">
									<?php echo $categoriesAAfficher; ?>
								</select>
							</div>
							<input type="hidden" name="idCours" value="<?php echo $cours->getIdCours();?>"/>
							<button class="btn btn-primary" type="submit">Modifier</button>	
						</form>
					</div>
				</div>
			</div>
      	</div>
    </div>
  </div>
</div>

<div id="editerCoursGlobal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2 class="modal-title">Cours global du cours</h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering">
						<form action="/ForAdminOnly/Cours/ModifierCoursGlobalCours" method="post" class="well well-lg">
							<legend>Modifier le rattachement à un cours global ?</legend>
							<div class="form-group">
								<label>Cours global actuel</label>
								<select name="nouveauCoursGlobal" class="form-control input-sm">
									<?php echo $coursGlobauxAAfficher; ?>
								</select>
							</div>
							<input type="hidden" name="idCours" value="<?php echo $cours->getIdCours();?>"/>
							<button class="btn btn-primary" type="submit">Modifier</button>	
						</form>
					</div>
				</div>
			</div>
      	</div>
    </div>
  </div>
</div>

<div id="modifierImageCours" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2 class="modal-title">Image de présentation du cours</h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering">
						<div class="file-picker" id="dropzoneImageCours">
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
									<input type="hidden" name="idCours" value="<?php echo $cours->getIdCours();?>"/>
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
			<h2 class="modal-title">Description du cours</h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering">
						<form action="/ForAdminOnly/Cours/ModifierDescriptionCours" method="post" class="well well-lg">
							<legend>Modifier la description du cours ?</legend>
							<div class="form-group">
								<label>Description actuelle</label>
								<textarea rows="5" name="nouvelleDescription" class="form-control"><?php echo $cours->getDescriptionCours();?></textarea>							</div>
							<input type="hidden" name="idCours" value="<?php echo $cours->getIdCours();?>"/>
							<button class="btn btn-primary" type="submit">Modifier</button>	
						</form>
					</div>
				</div>
			</div>
      	</div>
    </div>
  </div>
</div>

<div id="editerMotCles" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2 class="modal-title">Mots-clés du cours</h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering">
						<form action="/ForAdminOnly/Cours/ModifierMotClesCours" method="post" class="well well-lg">
							<legend>Modifier les mots-clés du cours ?</legend>
							<div class="form-group">
								<label>Mots-clés actuels</label>
								<?php 
								$listeMotsCles = '';
								foreach($cours->getMotCles() as $motCle){
									$listeMotsCles .= $motCle->getNomMotCle().', ';
								}
								$listeMotsCles = substr($listeMotsCles, 0, -2);
								?>
								<input type="text" name="nouveauxMotsCles" class="form-control" value="<?php echo $listeMotsCles;?>"/>
							</div>
							<input type="hidden" name="idCours" value="<?php echo $cours->getIdCours();?>"/>
							<button class="btn btn-primary" type="submit">Modifier</button>	
						</form>
					</div>
				</div>
			</div>
      	</div>
    </div>
  </div>
</div>


<div id="supprimerCours" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2 class="modal-title">Suppression du cours</h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering">
						<form action="/ForAdminOnly/Cours/SupprimerCours" method="post" class="well well-lg">
							<legend>Etes-vous sûr de vouloir supprimer cet cours ?</legend>
							<input type="hidden" name="idCours" value="<?php echo $cours->getIdCours();?>"/>
							<button class="btn btn-primary" type="submit">Supprimer</button>	
						</form>
					</div>
				</div>
			</div>
      	</div>
    </div>
  </div>
</div>

<div id="boutonShowGestionnaireDonnees">
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="centering center">
				<img src="/Images/dataButtonWhite.png" alt="Your data"/>
			</div>
		</div>
	</div>
</div>

<div id="overlayGestionnaireDonnees">
	<div id="sectionGestionnaireDonnees">
		<div class="headerGestionnaireDonnees">
			<div class="inHeaderGestionnaireDonnees">
				<div class="titreGestionnaire">Bibliothèque multimédia</div>
			</div>
		</div>
		<div class="image-dataManager-waiter"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Chargement...</div>
		<div id="inSectionGestionnaireDonnees"></div>
	</div>
</div>