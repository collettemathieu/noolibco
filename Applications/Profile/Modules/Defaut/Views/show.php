<?php $userSession = unserialize($user->getAttribute('userSession'));?>		
<div class="container-fluid" ng-app="ProfileUser" ng-controller="profileUserController" ng-strict-di><!-- ng-strict-di for throwing an exception when minify operation errors appeared -->
	<div class="row-fluid">

		<div class="col-lg-12 sousMenu centering maxWidth">
			<div class="row">
				<div class="col-lg-2 col-lg-offset-1 sousMenu profilUserName text-center" <?php if($utilisateurAAfficher->getIdUtilisateur() == $userSession->getIdUtilisateur()){ ?>data-intro="Edit this picture"<?php }else{ ?>data-intro="Picture of <?php echo $utilisateurAAfficher->getPrenomUtilisateur(); ?>"<?php } ?> data-position="bottom">
					<p>
						<?php echo($utilisateurAAfficher->getPrenomUtilisateur() . ' ' . $utilisateurAAfficher->getNomUtilisateur());?>
					</p>
					
					<?php if($utilisateurAAfficher->getIdUtilisateur() == $userSession->getIdUtilisateur())
					{ ?>
						<a data-toggle="modal" href="#changePhotoUser">
							<img  class="infoBulle" data-toggle="tooltip" href="#" title="Edit your profile picture" src="data:image/png;charset=utf8;base64,<?php echo base64_encode(file_get_contents($utilisateurAAfficher->getUrlPhotoUtilisateur())); ?>" />
						</a>
					<?php }
					else
					{ ?>
						<img src="data:image/png;charset=utf8;base64,<?php echo base64_encode(file_get_contents($utilisateurAAfficher->getUrlPhotoUtilisateur())); ?>"/>
						
					<?php } ?>
				</div>
			
				<div class="col-lg-7 sousMenu infoProfil" <?php if($utilisateurAAfficher->getIdUtilisateur() == $userSession->getIdUtilisateur()){ ?>data-intro="Change informations about you"<?php }else{ ?>data-intro="Profile of <?php echo $utilisateurAAfficher->getPrenomUtilisateur(); ?>"<?php } ?> data-position="top">
					
					<?php if($utilisateurAAfficher->getIdUtilisateur() != $userSession->getIdUtilisateur())
					{
						if($utilisateurAAfficher->getMailUtilisateur() != null)
						{ ?>
							
							<div class="panel panel-default col-lg-12">
								<div class="panel-heading">
									<a class="accordion-toggle" href="#item1" data-toggle="collapse">Send an email to <?php echo($utilisateurAAfficher->getPrenomUtilisateur() . ' ' . $utilisateurAAfficher->getNomUtilisateur());?></a>
								</div>
								<div id="item1" class="panel-collapse collapse out">
									<div class="panel-body">
										<div class="alert alert-warning">By sending an email to <?php echo($utilisateurAAfficher->getPrenomUtilisateur() . ' ' . $utilisateurAAfficher->getNomUtilisateur());?>, your email address will be known by this person.</div>
										<form class="well well-lg" action="/Profile/ContactAuthor" method="post">
											<div class="form-group has-feedback">
												<input type="text" class="form-control" name="headerMessageMail" placeholder="Your title..."/>
												<span class="glyphicon form-control-feedback"></span>
												<span class="help-block"></span>
											</div>
											<div class="form-group has-feedback">
												<textarea class="form-control" name="bodyMessageMail" placeholder="Your message..."></textarea>
												<span class="glyphicon form-control-feedback"></span>
												<span class="help-block"></span>
											</div>
											<input type="hidden" name="idAuteur" value="<?php echo $utilisateurAAfficher->getIdUtilisateur(); ?>">
											<button class="btn btn-primary" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Loading..." type="submit">
										    Send
										 	</button>
										</form>
									</div>
								</div> 
							</div>
						<?php } ?>
						<?php if($utilisateurAAfficher->getStatut()->getIdStatut() != 1){ ?>
							<div class="panel panel-default col-lg-12">
								<div class="panel-heading">
									<a class="accordion-toggle" href="#item2" data-toggle="collapse">Status</a>
								</div>
								<div id="item2" class="panel-collapse collapse out">
									<div class="panel-body">
										<?php echo($utilisateurAAfficher->getStatut()->getNomStatut()); ?>
									</div>
								</div> 
							</div>

						<?php } ?>
						<?php if($utilisateurAAfficher->getLienPagePersoUtilisateur() != '')
						{ ?>
							<div class="panel panel-default col-lg-12">
								<div class="panel-heading">
									<a class="accordion-toggle" href="#item3" data-toggle="collapse">Personal page</a>
								</div>
								<div id="item3" class="panel-collapse collapse out">
									<div class="panel-body">
										<a href="<?php echo($utilisateurAAfficher->getLienPagePersoUtilisateur()); ?>"><?php echo($utilisateurAAfficher->getLienPagePersoUtilisateur()); ?></a>
									</div>
								</div> 
							</div>
						<?php }
						if($utilisateurAAfficher->getDescriptionUtilisateur() != null)
						{ ?>
							<div>
								 
							</div>
							<div class="panel panel-default col-lg-12">
								<div class="panel-heading">
									<a class="accordion-toggle" href="#item4" data-toggle="collapse">About <?php echo($utilisateurAAfficher->getPrenomUtilisateur()); ?></a>
								</div>
								<div id="item4" class="panel-collapse collapse out">
									<div class="panel-body">
										<?php echo($utilisateurAAfficher->getDescriptionUtilisateur()); ?>
									</div>
								</div> 
							</div>
						<?php } ?>
					<?php }
					else
					{ ?>
						<div class="panel panel-default col-lg-12">
							<div class="panel-heading">
								<a class="accordion-toggle" href="#item1" data-toggle="collapse">First name: <?php echo($utilisateurAAfficher->getPrenomUtilisateur()); ?></a>
							</div>
							<div id="item1" class="panel-collapse collapse out">
								<div class="panel-body">
									Change your first name:
									<form method="post" action="/Profile/ChangerPrenom">
										<input type="text" name="prenom"/>
										<input type="submit" name="submit" value="Send">
									</form>
								</div>
							</div> 
						</div>

						<div class="panel panel-default col-lg-12">
							<div class="panel-heading">
								<a class="accordion-toggle" href="#item2" data-toggle="collapse">Last name: <?php echo($utilisateurAAfficher->getNomUtilisateur()); ?></a>
							</div>
							<div id="item2" class="panel-collapse collapse out">
								<div class="panel-body">
									Change your last name:
									<form method="post" action="/Profile/ChangerNom">
										<input type="text" name="nom"/>
										<input type="submit" name="submit" value="Send">
									</form>
								</div>
							</div> 
						</div>

						<div class="panel panel-default col-lg-12">
							<div class="panel-heading">
								<a class="accordion-toggle" href="#item3" data-toggle="collapse">Email address: <?php echo($utilisateurAAfficher->getMailUtilisateur()); ?></a>
							</div>
							<div id="item3" class="panel-collapse collapse out">
								<div class="panel-body">
									Change your email address:
									<div class="alert alert-warning">This option is not available yet. Please stay tune !</div>
									<!--<form method="post" action="/Profile/ChangerEmail">
										<input type="text" name="email"/>
										<input type="submit" name="submit" value="Send">
									</form>-->
								</div>
							</div> 
						</div>

						<div class="panel panel-default col-lg-12">
							<div class="panel-heading">
								<a class="accordion-toggle" href="#item4" data-toggle="collapse"><?php if($utilisateurAAfficher->getStatut()->getIdStatut() != 1){ ?>
									Status: <?php echo($utilisateurAAfficher->getStatut()->getNomStatut()); ?>
								<?php } ?></a>
							</div>
							<div id="item4" class="panel-collapse collapse out">
								<div class="panel-body">
									Change your status:
									<form method="post" action="/Profile/ChangerStatut">
										<select name="newStatut">
											<?php echo($statutAAfficher); ?>
										</select>
										<input type="submit" name="submit" value="Send">
									</form>
								</div>
							</div> 
						</div>

						<div class="panel panel-default col-lg-12">
							<div class="panel-heading">
								<a class="accordion-toggle" href="#item5" data-toggle="collapse">Personal page: <a href="<?php echo($utilisateurAAfficher->getLienPagePersoUtilisateur()); ?>"><?php echo($utilisateurAAfficher->getLienPagePersoUtilisateur()); ?></a></a>
							</div>
							<div id="item5" class="panel-collapse collapse out">
								<div class="panel-body">
									Change your personal page:
									<form method="post" action="/Profile/ChangerPagePerso">
										<input type="text" name="pagePerso"/>
										<input type="submit" name="submit" value="Send">
									</form>
								</div>
							</div> 
						</div>
						
						<div class="panel panel-default col-lg-12">
							<div class="panel-heading">
								<a class="accordion-toggle" href="#item6" data-toggle="collapse">About you: <?php echo($utilisateurAAfficher->getDescriptionUtilisateur()); ?></a>
							</div>
							<div id="item6" class="panel-collapse collapse out">
								<div class="panel-body">
									Change your background profile:
									<form method="post" action="/Profile/ChangerDescription">
										<textarea name="description"><?php echo($utilisateurAAfficher->getDescriptionUtilisateur()); ?></textarea>
										<input type="submit" name="submit" value="Send">
									</form>
								</div>
							</div> 
						</div>
						

						<div class="panel panel-default col-lg-12">
							<div class="panel-heading">
								<a class="accordion-toggle" href="#item7" data-toggle="collapse">Change your password</a>
							</div>
							<div id="item7" class="panel-collapse collapse out">
								<div class="panel-body">
									<form method="post" action="/Profile/ChangerPassword">
										<div class="form-group">
											<label for="oldPassword">Old password</label>
											<input type="password" class="form-control input-sm" id="oldPassword" name="actualPassword"/>
										</div>
										<div class="form-group">
											<label for="newPassword">New password</label>
											<input type="password" class="form-control input-sm" id="newPassword" name="newPassword1"/>
										</div>
										<div class="form-group">
											<label for="confirmPassword">Confirm the new password</label>
											<input type="password" class="form-control input-sm" id="confirmPassword" name="newPassword2"/>
										</div>
										<input type="submit" name="submit" value="Send">
									</form>
								</div>
							</div> 
						</div>
						<?php if($userSession->getPasswordAdminUtilisateur() != ''){?>
						<div class="panel panel-default col-lg-12">
							<div class="panel-heading">
								<a class="accordion-toggle" href="#item8" data-toggle="collapse">Change your administrator password</a>
							</div>
							<div id="item8" class="panel-collapse collapse out">
								<div class="panel-body">
									<form method="post" action="/Profile/ChangerPasswordAdmin">
										<div class="form-group">
											<label for="oldPassword">Old password</label>
											<input type="password" class="form-control input-sm" id="oldPassword" name="actualPasswordAdmin"/>
										</div>
										<div class="form-group">
											<label for="newPassword">New password</label>
											<input type="password" class="form-control input-sm" id="newPassword" name="newPasswordAdmin1"/>
										</div>
										<div class="form-group">
											<label for="confirmPassword">Confirm the new password</label>
											<input type="password" class="form-control input-sm" id="confirmPassword" name="newPasswordAdmin2"/>
										</div>
										<input type="submit" name="submit" value="Send">
									</form>
								</div>
							</div> 
						</div>
						
					<?php }} ?>
					
					<div class="panel panel-default col-lg-12">
						<div class="panel-heading">
							<a class="accordion-toggle" href="#item9" data-toggle="collapse">Institution(s)</a>
						</div>
						<div id="item9" class="panel-collapse collapse out">
							<div class="panel-body">
								<?php if(count($utilisateurAAfficher->getEquipes()) != 0 || $utilisateurAAfficher->getIdUtilisateur() == $userSession->getIdUtilisateur()){  
						if($utilisateurAAfficher->getIdUtilisateur() == $userSession->getIdUtilisateur()){ ?>
							<div>
								<table class="table table-bordered table-striped table-condensed">
									<?php foreach($utilisateurAAfficher->getEquipes() as $equipe)
									{ ?>
										<tr>
											<form method="post" action="/Profile/RemoveEquipe">
												<td><?php echo( $equipe->getNomEquipe() . 
																' (' . 
																'<a href="' . 
																$equipe->getLaboratoire()->getUrlLaboratoire() . 
																'" target="_blank">' . 
																$equipe->getLaboratoire()->getNomLaboratoire() . 
																'</a>' . 
																', ' . 
																$equipe->getLaboratoire()->getEtablissement()->getNomEtablissement() . 
																')' ); ?></td>
												<td><input type="hidden" name="idEquipe" value="<?php echo $equipe->getIdEquipe() ?>">
												<input type="submit" name="submit" value="Remove"><br/></td>
											</form>
										</tr>
									<?php } ?>
								</table>
							</div>
							<div>
								<table class="table table-bordered table-striped table-condensed">
									<caption>Add yourself to a team</caption>
									<tr>
										<td>
											Institution:
										</td>
										<td></td>
										<td>
											<?php if(isset($idEtablissement)) { ?>
												Laboratory:
											<?php } ?>
										</td>
										<td></td>
										<td>
											<?php if(isset($idLaboratoire)) { ?>
												Team:
											<?php } ?>
										</td>
										<td></td>
									</tr>
									<tr>
										<select ng-options="institution.idEtablissement for institution.name in institutions" name="selectedInstitution" ng-model="selectedInstitution"></select>
										<form method="post" action="/Profile/">
											<td>
												<select name="idEtablissement">
													<?php foreach($listeEtablissement as $etablissement)
													{
														$echo = '<option value="' . $etablissement->getIdEtablissement() . '"';
														if(isset($idEtablissement) && $etablissement->getIdEtablissement() == $idEtablissement)
														{
															$echo .= ' selected';
														}
														$echo .= '>' . $etablissement->getNomEtablissement() . '</option>';
														echo($echo);
													} ?>
												</select>
											</td>
											<td>
												<input type="submit" value="→"/>
											</td>
										</form>
										<?php if(isset($idEtablissement)) { ?>
											<form method="post" action="/Profile/">
												<td>
													<input type="hidden" name="idEtablissement" value="<?php echo $idEtablissement ?>"/>
													<select name="idLaboratoire">
														<?php foreach($listeLaboratoire as $laboratoire)
														{
															$echo = '<option value="' . $laboratoire->getIdLaboratoire() . '"';
															if(isset($idLaboratoire) && $laboratoire->getIdLaboratoire() == $idLaboratoire)
															{
																$echo .= ' selected';
															}
															$echo .= '>' . $laboratoire->getNomLaboratoire() . '</option>';
															echo($echo);
														} ?>
													</select>
												</td>
												<td>
													<input type="submit" value="→"/>
												</td>
											</form>
										<?php } ?>
										<?php if(isset($idLaboratoire)) { ?>
											<form method="post" action="/Profile/AjouterEquipe">
												<td>
													<select name="idEquipe">
														<?php foreach($listeEquipe as $equipe)
														{
															$echo = '<option value="' . $equipe->getIdEquipe() . '"';
															$echo .= '>' . $equipe->getNomEquipe() . '</option>';
															echo($echo);
														} ?>
													</select>
												</td>
												<td>
													<input type="submit" value="Add">
												</td>
											</form>
										<?php } ?>
									</tr>
								</table>
							</div>
					<?php }
					else
					{ ?>
							<div>
								<table>
									<?php foreach($utilisateurAAfficher->getEquipes() as $equipe)
									{ ?>
										<tr>
											<td><?php echo( $equipe->getNomEquipe() . 
												' (' . 
												'<a href="' . 
												$equipe->getLaboratoire()->getUrlLaboratoire() . 
												'" target="_blank">' . 
												$equipe->getLaboratoire()->getNomLaboratoire() . 
												'</a>' . 
												', ' . 
												$equipe->getLaboratoire()->getEtablissement()->getNomEtablissement() . 
												')' ); ?></td>
										</tr>
									<?php } ?>
								</table>
							</div>
						<?php } ?>
					<?php } ?>
							</div>
						</div> 
					</div>

					<div class="panel panel-default col-lg-12">
						<div class="panel-heading">
							<a class="accordion-toggle" href="#item10" data-toggle="collapse">Last visit: <?php echo(preg_replace('#(.+)-(.+)-(.+)#', '$3/$2/$1', $utilisateurAAfficher->getDateDerniereConnexionUtilisateur())); ?></a>
						</div>
						<div id="item10" class="panel-collapse collapse out">
							<div class="panel-body">
								Date of subscription: <?php echo(preg_replace('#(.+)-(.+)-(.+)#', '$3/$2/$1', $utilisateurAAfficher->getDateInscriptionUtilisateur())); ?>
							</div>
						</div> 
					</div>
					
				</div>
			</div>

			<div class="row"><br>
				<div class="col-lg-7 infoApplicationUser sousMenu" <?php if($utilisateurAAfficher->getIdUtilisateur() == $userSession->getIdUtilisateur()){ ?>data-intro="Your activated applications on NooLib"<?php }else{ ?>data-intro="Applications of <?php echo $utilisateurAAfficher->getPrenomUtilisateur(); ?>"<?php } ?> data-position="left">
					<?php if($utilisateurAAfficher->getIdUtilisateur() == $userSession->getIdUtilisateur()){ ?>
						<h4>My applications <a href="/ManagerOfApplications/">Manage my applications</a></h4>
						<?php }
						else{ ?>
						<h4>Applications of <?php echo $utilisateurAAfficher->getPrenomUtilisateur().' '.$utilisateurAAfficher->getNomUtilisateur(); ?></h4>
						<?php } ?>
					<?php 
					foreach($listeApps as $app){ 
						if($utilisateurAAfficher->getIdUtilisateur() == $userSession->getIdUtilisateur() || ($utilisateurAAfficher->getIdUtilisateur() != $userSession->getIdUtilisateur() && $app->getStatut()->getIdStatut()>4)){
						?>
						
					<table class="table table-bordered table-striped table-condensed">
				 		<caption class="captionTableApplicationProfil">
				 			<a href ="/Library/app=<?php echo $app->getIdApplication(); ?>">
			 					<img src="data:image/png;charset=utf8;base64,<?php echo base64_encode(file_get_contents($app->getUrlLogoApplication())) ?>"/>
			 				</a>
				 		</caption>
				 		<thead>
				 		</thead>
				 		<tbody>
				 			<tr>
				 				<td>Name</td>
				 				<td><?php echo $app->getNomApplication() ?></td>
				 			</tr>
							<tr>
								<td>Version</td>
								<?php 
								foreach($app->getVersions() as $version)
								{
									if(!isset($derniereVersion) || $version->getDatePublicationVersion() > $derniereVersion)
									{
										$derniereVersion = $version->getDatePublicationVersion();
										$numVersion = $version->getNumVersion();
									}
								} ?>
								<td>v. <?php echo $numVersion ?></td>
							</tr>
							<tr>
								<td>Category</td>
								<td><?php echo $app->getCategorie()->getNomCategorie() ?></td>
							</tr>
							<tr>
								<td>Status</td>
								<td><?php echo $app->getStatut()->getNomStatut() ?></td>
							</tr>
						</tbody>
					</table>
					<?php }} ?>
				</div>
			</div>
		</div>
	</div>
</div>


<div id="changePhotoUser" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2 class="modal-title">Edit your profile picture</h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering">
						<form method="post" class="well well-lg" action="/Profile/ChangerPhotoProfil" enctype="multipart/form-data">
							<div class="form-group">
								<label for="photo" for="submit">Select a picture</label>
								<input type="file" class="form-control input-sm" name="photo" id="photo" />
							</div>
							<button class="btn btn-primary" type="submit">Send</button>
						</form>
					</div>
				</div>
			</div>
      	</div>
    </div>
  </div>
</div>
