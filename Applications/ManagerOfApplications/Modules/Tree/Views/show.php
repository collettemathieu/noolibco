	<?php $utilisateur = unserialize($user->getAttribute('userSession'));?>
	<div id="application" class="container-fluid" ng-app="applicationApplicationsManager" ng-controller="mainController" idapplication="<?php echo $app->getIdApplication();?>">
		<div class="row-fluid">
			<div class="col-sm-10 centering sousMenu maxWidth fadeIn">
				<div class="row">
					<div class="col-sm-12 informationApplication">
						<div class="col-sm-12">
							<a id="logoTour" ng-click="logoApplicationModal()">
								<img class="infoBulle" data-toggle="tooltip" title="Edit its logo" ng-src="data:image/png;charset=utf8;base64,{{application.urlLogo}}"/>
							</a>
							<a id="nameTour" ng-click="nameApplicationModal()">
								<h3 class="infoBulle" data-toggle="tooltip" title="Edit its name" ng-bind="application.nom"></h3>
							</a>
							<?php							
							if($utilisateur->getIdUtilisateur() === $app->getCreateur()->getIdUtilisateur() || $user->getAttribute('isAdmin')){?>
							<button id="deleteTour" type="button" class="infoBulle pull-right btn btn-danger btn-margin btn-lg" ng-click="deleteApplicationModal()" title="Delete it"><i class="glyphicon glyphicon-trash"></i></button>
							<?php }?>
							<?php 
							if($user->getAttribute('isAdmin')){
								$numStatutApp = (int) $app->getStatut()->getIdStatut(); ?>
								<a class="infoBulle pull-right btn btn-default btn-margin btn-lg" ng-href="/PourAdminSeulement/Applications/PostOnSocialNetworks/app={{application.id}}" title="Post on Facebook and Twitter"><i class="glyphicon glyphicon-thumbs-up"></i></a>
								<form class="pull-right" method="post" action="/PourAdminSeulement/Applications/ActiverDesactiverApplication">
									<button type="submit" class="infoBulle btn btn-margin btn-lg <?php if($numStatutApp > 4){echo 'btn-danger';}else{echo 'btn-success';}?>" title="<?php if($numStatutApp > 4){echo 'Deactivate this application';}else{echo 'Activate this application';}?>"><i class="glyphicon <?php if($numStatutApp > 4){echo 'glyphicon-remove';}else{echo 'glyphicon-ok';}?>"></i></button>
									<input type="hidden" name="idApplication" value=<?php echo '"'.$app->getIdApplication().'"';?>/>
								</form>
							<?php } ?>
							<a id="testInNooSpace" class="infoBulle pull-right btn btn-primary btn-margin btn-lg" ng-href="/NooSpace/a={{application.id}}v={{idVersion}}" target="_blank" title="Test it"><i class="glyphicon glyphicon-log-out"></i></a>
							<button id="publicationTour" type="button" class="infoBulle pull-right btn btn-info btn-margin btn-lg" ng-click="publicationsModal()" title="Manage its publications"><i class="glyphicon glyphicon-education"></i></button>
							<?php							
							if($utilisateur->getIdUtilisateur() === $app->getCreateur()->getIdUtilisateur() || $user->getAttribute('isAdmin')){?>
							<button id="authorsTour" type="button" class="infoBulle pull-right btn btn-success btn-margin btn-lg" ng-click="authorsApplicationModal()" title="Manage its authors"><i class="glyphicon glyphicon-user"></i></button>
							<?php } ?>
							<button id="editTour" type="button" class="infoBulle pull-right btn btn-default btn-margin btn-lg" ng-click="descriptionApplicationModal()" title="Edit its description, category and keywords"><i class="glyphicon glyphicon-tags"></i></button>
						</div>
					</div>
					<div class="col-lg-12"><hr></div>

					<div class="col-lg-12">
						<span id="versionTour">Version {{numVersion}}</span>
						<!-- Split button -->
						<div class="btn-group pull-right">
						  <button id="taskTour" type="button" ng-click="createTaskModal()" class="btn btn-primary">New task</button>
						  <button id="taskOptionTour" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    <span class="caret"></span>
						    <span class="sr-only">Toggle Dropdown</span>
						  </button>
						  <ul class="dropdown-menu">
						    <li><a ng-click="createVersionModal()">Create a new version</a></li>
							<?php 
							if($user->getAttribute('isAdmin')){?>
							<li><a id="validVersionApplication" ng-href="/PourAdminSeulement/Applications/ActivateVersion/a={{application.id}}v={{idVersion}}">Valid this version</a></li>
							<?php }?>
						    <li role="separator" class="divider"></li>
						    <li>
						    	<a href="">Version(s) <span class="caret"></span></a>
						    	<div select-version></div>
						    </li>

						  </ul>
						</div>
						<div id="treeTour" tree-application></div>
					</div>
				</div>
			</div>
		</div>
	</div>

<div id="formulaireApplication" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" id="contenuForm"></div>
  </div>
</div>
