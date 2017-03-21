	<?php $utilisateur = unserialize($user->getAttribute('userSession'));?>
	<div id="application" class="container-fluid" ng-app="applicationApplicationsManager" ng-controller="mainController" idapplication="<?php echo $app->getIdApplication();?>">
		<div class="row-fluid">
			<div class="col-sm-10 centering sousMenu maxWidth fadeIn">
				<div class="row">
					<div class="col-sm-12 informationApplication">
						<div class="col-sm-12">
							<a ng-click="logoApplicationModal()">
								<img class="infoBulle" data-toggle="tooltip" title="Edit its logo" ng-src="data:image/png;charset=utf8;base64,{{application.urlLogo}}"/>
							</a>
							<a ng-click="nameApplicationModal()">
								<h3 class="infoBulle" data-toggle="tooltip" title="Edit its name">{{application.nom}}</h3>
							</a>
							<?php							
							if($utilisateur->getIdUtilisateur() === $app->getCreateur()->getIdUtilisateur() || $user->getAttribute('isAdmin')){?>
							<button type="button" class="infoBulle pull-right btn btn-danger btn-margin btn-lg" ng-click="deleteApplicationModal()" title="Delete it"><i class="glyphicon glyphicon-trash"></i></button>
							<?php }?>
							<?php 
							if($user->getAttribute('isAdmin')){
								$numStatutApp = (int) $app->getStatut()->getIdStatut(); ?>
								<a class="infoBulle pull-right btn btn-default btn-margin btn-lg" href="/PourAdminSeulement/Applications/PostOnSocialNetworks/app=<?php echo $app->getIdApplication();?>" title="Post on Facebook and Twitter"><i class="glyphicon glyphicon-thumbs-up"></i></a>
								<form class="pull-right" method="post" action="/PourAdminSeulement/Applications/ActiverDesactiverApplication">
									<button type="submit" class="infoBulle btn btn-margin btn-lg <?php if($numStatutApp > 4){echo 'btn-danger';}else{echo 'btn-success';}?>" title="<?php if($numStatutApp > 4){echo 'Deactivate this application';}else{echo 'Activate this application';}?>"><i class="glyphicon <?php if($numStatutApp > 4){echo 'glyphicon-remove';}else{echo 'glyphicon-ok';}?>"></i></button>
									<input type="hidden" name="idApplication" value=<?php echo '"'.$app->getIdApplication().'"';?>/>
								</form>
							<?php } ?>
							<a id="testInNooSpace" class="infoBulle pull-right btn btn-primary btn-margin btn-lg" href="/NooSpace/a=<?php echo $app->getIdApplication();?>v=<?php echo $app->getVersions()[count($app->getVersions())-1]->getIdVersion();?>" target="_blank" title="Test it in the noospace"><i class="glyphicon glyphicon-log-out"></i></a>
							<button type="button" class="infoBulle pull-right btn btn-info btn-margin btn-lg" ng-click="publicationsModal()" title="Manage its publications"><i class="glyphicon glyphicon-education"></i></button>
							<?php							
							if($utilisateur->getIdUtilisateur() === $app->getCreateur()->getIdUtilisateur() || $user->getAttribute('isAdmin')){?>
							<button type="button" class="infoBulle pull-right btn btn-success btn-margin btn-lg" ng-click="authorsApplicationModal()" title="Manage its authors"><i class="glyphicon glyphicon-user"></i></button>
							<?php } ?>
							<button type="button" class="infoBulle pull-right btn btn-default btn-margin btn-lg" ng-click="descriptionApplicationModal()" title="Edit its description, category and keywords"><i class="glyphicon glyphicon-tags"></i></button>
						</div>
					</div>
					<div class="col-lg-12"><hr></div>

					<div class="col-lg-12">
						<div class="col-lg-2">
							<form>
								<span class="label label-primary">Version</span>
								<select class="btn btn-default" type="button" id="selectVersion" name="idVersion">
									<?php 
									$size = count($app->getVersions());
									foreach($app->getVersions() as $key=>$version){?>
									<option value="<?php echo $version->getIdVersion();?>" <?php if($key === $size-1){echo "selected";}?>><?php echo $version->getNumVersion();?></option>
									<?php } ?>
								</select>
							</form>
						</div>
						<div class="dropdown pull-right">
							<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								Action
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu" aria-labelledby="dropdownMenu">
								<li><a data-toggle="modal" href="#formulaireApplication" id="boutonAjouterTache">Add a new task</a></li>
								<li role="separator" class="divider"></li>
								<li><a data-toggle="modal" href="#formulaireNouvelleVersion" id="boutonCreerNewVersion">Create a new version</a></li>
								<?php 
								if($user->getAttribute('isAdmin')){?>
								<li role="separator" class="divider"></li>
								<li><a id="validVersionApplication" href="/PourAdminSeulement/Applications/ActivateVersion/a=<?php echo $app->getIdApplication();?>v=<?php echo $app->getVersions()[count($app->getVersions())-1]->getIdVersion();?>">Valid this version</a></li>
								<?php }?>
							</ul>
						</div>
						<div id="containerTreeApplication" class="containerTreeApplication" idApp="<?php echo $app->getIdApplication();?>"></div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!-- Affichage des formulaires -->
	<div id="formulaireApplication" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content" id="contenuForm"></div>
	  </div>
	</div>

	<div id="formulaireNouvelleVersion" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">x</button>
				<h2 class="modal-title">Create a new version</h2>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row-fluid">
						<div class="col-lg-10 centering">
							<form id="formCreateNewVersion" class="well well-lg">
								<div class="form-group">
									<label for="nameVersionApplication">Enter a new number version</label>
									<input type="text" class="form-control input-lg" name="nameVersionApplication" id="nameVersionApplication" placeholder="1.0.0" maxlength="5"/>
								</div>
								<div class="form-group">
									<label for="descriptionVersionApplication">Add a short description (0-100 chars) presenting the new features of this version</label>
									<textarea class="form-control input-lg" name="descriptionVersionApplication" id="descriptionVersionApplication" placeholder="Short description..." cols="10" maxlength="100"></textarea>
								</div>
								<input type="hidden" name="idApp" value="<?php echo $app->getIdApplication();?>"/>
								<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Loading..." type="submit">Send</button>
							</form>
						</div>
					</div>
				</div>
	      	</div>
	    </div>
	  </div>
	</div>


	<div id="addPublication" class="modal fade" role="dialog">
	  <div class="modal-dialog">

	    <!-- Modal content-->
	    <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">x</button>
				<h2 class="modal-title">Manage publications of <?php echo $app->getNomApplication();?></h2>
			</div>
			<div class="modal-body">

				<ul class="nav nav-pills">
					<li class="active"><a href="#viewPublication" data-toggle="tab"><span class="glyphicon glyphicon-book"></span> Publication(s)</a></li>
					<li><a href="#automatic" data-toggle="tab"><span class="glyphicon glyphicon-list"></span> Automatic entry</a></li>
					<li><a href="#manuel" data-toggle="tab"><span class="glyphicon glyphicon-pencil"></span> Manuel entry</a></li>
				</ul><br>

				<div class="tab-content">
					<div class="tab-pane active" id="viewPublication">
						<div class="container-fluid">
							<div class="row-fluid">
								<div class="col-lg-11 centering">
									<?php if(count($app->getPublications()) != 0){ ?>
									<table class="table table-bordered table-striped table-condensed">
										<thead>
									        <tr>
									            <th>Title</th>
									            <th>First Author</th>
									            <th>Journal</th>
									            <th>Year of publication</th>
									            <th>Type</th>
									            <th>Link</th>
									            <th>Action</th>
									        </tr>
									    </thead>
										<tbody>
								    		<?php foreach($app->getPublications() as $publication){?>
								    		<tr>
												<td><?php echo $publication->getTitrePublication();?></td>
									           	<td><?php
									           	$premierAuteur = $publication->getAuteurs()[0];
									          	echo $premierAuteur->getPrenomAuteur()." ".$premierAuteur->getNomAuteur();
									           	?></td>
												<td><?php echo $publication->getJournalPublication();?></td>
									           	<td><?php echo $publication->getAnneePublication();?></td>
												<td><?php $typePublication = $publication->getTypePublication(); echo $typePublication->getNomTypePublication();?></td>
												<td><?php echo '<a href="'.$publication->getUrlPublication().'" target="_blank">See</a>';?></td>
												<td>
													<form class="formDeletePublication">
														<input type="hidden" name="idPublication" value="<?php echo $publication->getIdPublication(); ?>"/>
														<input type="hidden" name="idApp" value="<?php echo $app->getIdApplication();?>"/>
														<button class="btn btn-primary" type="submit">Delete</button>
													</form>
												</td>
								      		</tr>
								      		<?php } ?>
										</tbody>
									</table>
									<?php }else{?>
									<div class="alert alert-warning"><p><?php echo $app->getNomApplication();?> is not currently validated by scientific publications.</p></div>
									<?php } ?>
								</div>
							</div>
						</div>

					</div>
					<div class="tab-pane" id="automatic">
						<form id="searchPublicationForm" class="well well-lg col-lg-12 centering">
							<div class="form-group has-feedback">
								<input type="text" name="reqPublication" class="form-control input-lg" placeholder="Enter a valid DOI (ex: 10.1000/123456...)"/>
								<span class="glyphicon form-control-feedback"></span>
								<span class="help-block"></span>
							</div>
							<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Loading..." type="submit">Search</button>
						</form>
					</div>
					<div class="tab-pane" id="manuel">
						<form id="addPublicationForm" class="well well-lg col-lg-12 centering">
							<div class="form-group has-feedback">
								<label for="typePubli">Select a type for your publication</label>
								<select name="typePublication" class="form-control">
									
								</select>
								<span class="glyphicon form-control-feedback"></span>
								<span class="help-block"></span>
							</div>
							<div class="form-group has-feedback">
								<input type="text" id="titrePubli" name="titrePublication" maxlength="200" class="form-control" placeholder="Title of the publication" value="<?php if(isset($publicationAAjouter)){ echo $publicationAAjouter->getTitrePublication(); } ?>"/>
								<span class="glyphicon form-control-feedback"></span>
								<span class="help-block"></span>
							</div>
							<div class="form-group has-feedback">
								<input type="text" id="auteursPubli" name="auteursPublication" class="form-control" placeholder="Name1 Surname1, Name2 Surname2, ..." value="<?php if(isset($publicationAAjouter)){ $auteurs = $publicationAAjouter->getAuteurs(); $listeAuteurs=''; foreach($auteurs as $auteur){ $listeAuteurs.= $auteur->getPrenomAuteur().' '.$auteur->getNomAuteur().', ';} echo substr($listeAuteurs,0,-2); } ?>"/>
								<span class="glyphicon form-control-feedback"></span>
								<span class="help-block"></span>
							</div>
							<div class="form-group has-feedback">
								<input type="text" id="anneePubli" name="anneePublication" maxlength="4" class="form-control" placeholder="Enter the year of publication" value="<?php if(isset($publicationAAjouter)){ echo $publicationAAjouter->getAnneePublication(); } ?>"/>
								<span class="glyphicon form-control-feedback"></span>
								<span class="help-block"></span>
							</div>
							<div class="form-group has-feedback">
								<input type="text" id="journalPubli" name="journalPublication" maxlength="200" class="form-control" placeholder="Enter the name of the scientific journal" value="<?php if(isset($publicationAAjouter)){ echo $publicationAAjouter->getJournalPublication(); } ?>"/>
								<span class="glyphicon form-control-feedback"></span>
								<span class="help-block"></span>
							</div>
							<div class="form-group has-feedback">
								<input type="text" id="urlPubli" name="urlPublication" class="form-control" placeholder="Enter a hyperlink to access to the publication (http://)." value="<?php if(isset($publicationAAjouter)){ echo $publicationAAjouter->getUrlPublication(); } ?>"/>
								<span class="glyphicon form-control-feedback"></span>
								<span class="help-block"></span>
							</div>
							<input type="hidden" value="<?php echo $app->getIdApplication(); ?>" name="idApplication"/>
							<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Loading..." type="submit">Add</button>
						</form>
					</div>
				</div>
	      	</div>
	    </div>
	  </div>
	</div>