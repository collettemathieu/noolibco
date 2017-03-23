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