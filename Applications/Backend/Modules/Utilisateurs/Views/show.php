<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-lg-11 centering maxWidth">
			<div class="col-lg-4 sousMenu">
				<form class="well well-lg" method="POST" action="/PourAdminSeulement/Utilisateurs/">
					<?php if(isset($utilisateurAAdministrer)){ ?>
						<legend class="nomAction">Display a profile user</legend>
					<?php } else { ?>
						<legend class="nomAction">Modify a profile user</legend>
					<?php } ?>
					<div class="form-group">
						<select name="mailUtilisateur" class="form-control input-sm">
							<?php foreach($utilisateurs as $utili) { ?>
								<option value="<?php echo $utili->getMailUtilisateur() ?>">
									<?php echo $utili->getMailUtilisateur() ?> (<?php echo $utili->getNomUtilisateur() ?> <?php echo $utili->getPrenomUtilisateur() ?>)
								</option>
							<?php } ?>
						</select>
					</div>
					<button class="btn btn-primary" type="submit">Valid</button>
				</form>

				<form method="post" class="well well-lg" action="/PourAdminSeulement/Utilisateurs/CreerUtilisateur">
					<legend>Create a new user</legend>
					<div class="form-group">
						<label>Name</label>
						<input type="text" name="nom" class="form-control" placeholder="Enter a name for the new user"/>
					</div>

					<div class="form-group">
						<label>Surname</label>
						<input type="text" name="prenom" class="form-control" placeholder="Enter a surname for the new user"/>
					</div>

					<div class="form-group">
						<label>Electronic address</label>
						<input type="email" name="adresseMail" class="form-control" placeholder="Enter a valid electronic address"/>
					</div>
					<div class="form-group">
						<label>Select a status</label>
						<select name="newStatut" class="form-control input-sm">
							<?php echo($statutAAfficher); ?>
						</select>
					</div>
					<div class="form-group">
						<label>Password user</label>
						<input type="password" name="motDePasseFormulaire" class="form-control" placeholder="Enter a valid password"/>
					</div>
					<div class="form-group">
						<label>Confirm password user</label>
						<input type="password" name="motDePasseConfirme" class="form-control" placeholder="Enter a valid password"/>
					</div>
					<button class="btn btn-primary" type="submit">Create</button>	
				</form>
			</div>
		
		
			
			<?php if(isset($utilisateurAAdministrer)) { ?>
			<div class="col-lg-7 col-lg-offset-1 sousMenu infoProfil">

				<div class="col-lg-12">
					<a data-toggle="modal" href="#changePhotoUser">
						<img  class="infoBulle" data-toggle="tooltip" href="#" title="Edit this photo" src="data:image/png;charset=utf8;base64,<?php echo base64_encode(file_get_contents($utilisateurAAdministrer->getUrlPhotoUtilisateur())); ?>" />
					</a>
				</div>
				<div class="panel panel-default col-lg-12">
					<div class="panel-heading">
						<a class="accordion-toggle" href="#item1" data-toggle="collapse">Surname: <?php echo($utilisateurAAdministrer->getPrenomUtilisateur()); ?></a>
					</div>
					<div id="item1" class="panel-collapse collapse out">
						<div class="panel-body">
							Change his surname:
							<form method="post" action="/PourAdminSeulement/Utilisateurs/ChangerPrenom">
								<input type="hidden" name="idUtilisateur" value="<?php echo($utilisateurAAdministrer->getIdUtilisateur()) ?>"/>
								<div class="form-group">
									<input type="text" name="prenom" class="form-control"/>
								</div>
								<button class="btn btn-primary" type="submit">Send</button>
							</form>
						</div>
					</div> 
				</div>

				<div class="panel panel-default col-lg-12">
					<div class="panel-heading">
						<a class="accordion-toggle" href="#item2" data-toggle="collapse">Name: <?php echo($utilisateurAAdministrer->getNomUtilisateur()); ?></a>
					</div>
					<div id="item2" class="panel-collapse collapse out">
						<div class="panel-body">
							Change his name:
							<form method="post" action="/PourAdminSeulement/Utilisateurs/ChangerNom">
								<input type="hidden" name="idUtilisateur" value="<?php echo($utilisateurAAdministrer->getIdUtilisateur()) ?>"/>
								<div class="form-group">
									<input type="text" name="nom" class="form-control"/>
								</div>
								<button class="btn btn-primary" type="submit">Send</button>
							</form>
						</div>
					</div> 
				</div>

				<div class="panel panel-default col-lg-12">
					<div class="panel-heading">
						<a class="accordion-toggle" href="#item3" data-toggle="collapse">Email address: <?php echo($utilisateurAAdministrer->getMailUtilisateur()); ?></a>
					</div>
					<div id="item3" class="panel-collapse collapse out">
						<div class="panel-body">
							Change his(her) email address:
							<form method="post" action="/PourAdminSeulement/Utilisateurs/ChangerEmail">
								<input type="hidden" name="idUtilisateur" value="<?php echo($utilisateurAAdministrer->getIdUtilisateur()) ?>"/>
								<div class="form-group">
									<input type="mail" name="email" class="form-control"/>
								</div>
								<button class="btn btn-primary" type="submit">Send</button>
							</form>
						</div>
					</div> 
				</div>

				<div class="panel panel-default col-lg-12">
					<div class="panel-heading">
						<a class="accordion-toggle" href="#item4" data-toggle="collapse"><?php if($utilisateurAAdministrer->getStatut()->getIdStatut() != 1){ ?>
							Status: <?php echo($utilisateurAAdministrer->getStatut()->getNomStatut()); ?>
						<?php } ?></a>
					</div>
					<div id="item4" class="panel-collapse collapse out">
						<div class="panel-body">
							Change his status:
							<form method="post" action="/PourAdminSeulement/Utilisateurs/ChangerStatut">
								<div class="form-group">
									<select name="newStatut" class="form-control input-sm">
										<?php echo($statutAAfficher); ?>
									</select>
								</div>
								<input type="hidden" name="idUtilisateur" value="<?php echo($utilisateurAAdministrer->getIdUtilisateur()) ?>"/>
								<button class="btn btn-primary" type="submit">Send</button>
							</form>
						</div>
					</div> 
				</div>

				<div class="panel panel-default col-lg-12">
					<div class="panel-heading">
						<a class="accordion-toggle" href="#item5" data-toggle="collapse">Personal page: <a href="<?php echo($utilisateurAAdministrer->getLienPagePersoUtilisateur()); ?>"><?php echo($utilisateurAAdministrer->getLienPagePersoUtilisateur()); ?></a></a>
					</div>
					<div id="item5" class="panel-collapse collapse out">
						<div class="panel-body">
							Change his personal page:
							<form method="post" action="/PourAdminSeulement/Utilisateurs/ChangerPagePerso">
								<div class="form-group">
									<input type="text" name="pagePerso" class="form-control"/>
								</div>
								<input type="hidden" name="idUtilisateur" value="<?php echo($utilisateurAAdministrer->getIdUtilisateur()) ?>"/>
								<button class="btn btn-primary" type="submit">Send</button>
							</form>
						</div>
					</div> 
				</div>

				<div class="panel panel-default col-lg-12">
					<div class="panel-heading">
						<a class="accordion-toggle" href="#item6" data-toggle="collapse">About this user: <?php echo($utilisateurAAdministrer->getDescriptionUtilisateur()); ?></a>
					</div>
					<div id="item6" class="panel-collapse collapse out">
						<div class="panel-body">
							Change his background profile:
							<form method="post" action="/PourAdminSeulement/Utilisateurs/ChangerDescription">
								<input type="hidden" name="idUtilisateur" value="<?php echo($utilisateurAAdministrer->getIdUtilisateur()) ?>"/>
								<div class="form-group">
									<textarea class="col-lg-12 form-control" rows="5" name="description"><?php echo($utilisateurAAdministrer->getDescriptionUtilisateur()); ?></textarea>
								</div>
								<button class="btn btn-primary" type="submit">Send</button>
							</form>
						</div>
					</div> 
				</div>

				<div class="panel panel-default col-lg-12">
					<div class="panel-heading">
						<a class="accordion-toggle" href="#item7" data-toggle="collapse">Change his(her) password</a>
					</div>
					<div id="item7" class="panel-collapse collapse out">
						<div class="panel-body">
							<form method="post" action="/PourAdminSeulement/Utilisateurs/ChangerPasswod">
								<div class="form-group">
									<span class="label label-primary">New password</span>
									<input type="password" class="form-control input-sm" id="newPassword" name="newPassword1"/>
								</div>
								<div class="form-group">
									<span class="label label-primary">Confirm the new password</span>
									<input type="password" class="form-control input-sm" id="confirmPassword" name="newPassword2"/>
								</div>
								<input type="hidden" name="idUtilisateur" value="<?php echo($utilisateurAAdministrer->getIdUtilisateur()) ?>"/>
								<button class="btn btn-primary" type="submit">Send</button>
							</form>
						</div>
					</div> 
				</div>
			
				<div class="panel panel-default col-lg-12">
					<div class="panel-heading">
						<a class="accordion-toggle" href="#item9" data-toggle="collapse">Institution(s)</a>
					</div>
					<div id="item9" class="panel-collapse collapse out">
						<div class="panel-body">
							<div>
								<table class="table table-bordered table-striped table-condensed">
									<?php foreach($utilisateurAAdministrer->getEquipes() as $equipe)
									{ ?>
										<tr>
											<form method="post" action="/PourAdminSeulement/Utilisateurs/RemoveEquipe">
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
												<input type="hidden" name="idUtilisateur" value="<?php echo($utilisateurAAdministrer->getIdUtilisateur()) ?>"/>
												<button class="btn btn-primary" type="submit">Remove</button>
											</td>
											</form>
										</tr>
									<?php } ?>
								</table>
							</div>
							<div>
								<table class="table table-bordered table-striped table-condensed">
									<caption>Add a team</caption>
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
										<form method="post" action="/PourAdminSeulement/Utilisateurs/idUtilisateur=<?php echo $utilisateurAAdministrer->getIdUtilisateur(); ?>">
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
											<form method="post" action="/PourAdminSeulement/Utilisateurs/idUtilisateur=<?php echo $utilisateurAAdministrer->getIdUtilisateur(); ?>">
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
											<form method="post" action="/PourAdminSeulement/Utilisateurs/AjouterEquipe">
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
													<input type="hidden" name="idUtilisateur" value="<?php echo($utilisateurAAdministrer->getIdUtilisateur()) ?>"/>
													<button class="btn btn-primary" type="submit">Add</button>
												</td>
											</form>
										<?php } ?>
									</tr>
								</table>
							</div>
						</div> 
					</div>
				</div>

				<div class="panel panel-default col-lg-12">
					<div class="panel-heading">
						<a class="accordion-toggle" href="#item10" data-toggle="collapse">Last visit: <?php echo(preg_replace('#(.+)-(.+)-(.+)#', '$3/$2/$1', $utilisateurAAdministrer->getDateDerniereConnexionUtilisateur())); ?></a>
					</div>
					<div id="item10" class="panel-collapse collapse out">
						<div class="panel-body">
							Date of subscription: <?php echo(preg_replace('#(.+)-(.+)-(.+)#', '$3/$2/$1', $utilisateurAAdministrer->getDateInscriptionUtilisateur())); ?>
						</div>
					</div> 
				</div>
				
				
				<?php if($utilisateurAAdministrer->getEtatBanniUtilisateur()){ ?>
				<div class="panel panel-default col-lg-12">
					<div class="panel-heading">
						<a class="accordion-toggle" href="#item11" data-toggle="collapse">This user is currently bannished</a>
					</div>
					<div id="item11" class="panel-collapse collapse out">
						<div class="panel-body">
							<form method="POST" action="/PourAdminSeulement/Utilisateurs/GracierUtilisateur">
								<input type="hidden" name="idUtilisateur" value="<?php echo($utilisateurAAdministrer->getIdUtilisateur()) ?>"/>
								<button class="btn btn-primary" type="submit">Pardon</button>
							</form>
						</div>
					</div> 
				</div>
					
				<?php } else { ?>

				<div class="panel panel-default col-lg-12">
					<div class="panel-heading">
						<a class="accordion-toggle" href="#item11" data-toggle="collapse">Bannish this user</a>
					</div>
					<div id="item11" class="panel-collapse collapse out">
						<div class="panel-body">
							<form method="POST" action="/PourAdminSeulement/Utilisateurs/BannirUtilisateur">
								<input type="hidden" name="idUtilisateur" value="<?php echo($utilisateurAAdministrer->getIdUtilisateur()) ?>"/>
								<button class="btn btn-primary" type="submit">Bannish</button>
							</form>
						</div>
					</div> 
				</div>
				<?php } ?>
				
				
				<?php if($utilisateurAAdministrer->getUtilisateurActive()){ ?>
				<div class="panel panel-default col-lg-12">
					<div class="panel-heading">
						<a class="accordion-toggle" href="#item12" data-toggle="collapse">This user is currently activated</a>
					</div>
					<div id="item12" class="panel-collapse collapse out">
						<div class="panel-body">
							<form method="POST" action="/PourAdminSeulement/Utilisateurs/DesactiveUtilisateur">
								<input type="hidden" name="idUtilisateur" value="<?php echo($utilisateurAAdministrer->getIdUtilisateur()) ?>"/>
								<button class="btn btn-primary" type="submit">Desactivate</button>
							</form>
						</div>
					</div> 
				</div>
				<?php } else { ?>
				<div class="panel panel-default col-lg-12">
					<div class="panel-heading">
						<a class="accordion-toggle" href="#item12" data-toggle="collapse">This user is currently desactivated</a>
					</div>
					<div id="item12" class="panel-collapse collapse out">
						<div class="panel-body">
							<form method="POST" action="/PourAdminSeulement/Utilisateurs/ActiveUtilisateur">
								<input type="hidden" name="idUtilisateur" value="<?php echo($utilisateurAAdministrer->getIdUtilisateur()) ?>"/>
								<button class="btn btn-primary" type="submit">Activate</button>
							</form>
						</div>
					</div> 
				</div>
				<?php } ?>
				

				<div class="panel panel-default col-lg-12">
					<div class="panel-heading">
						<a class="accordion-toggle" href="#item13" data-toggle="collapse">Delete this user</a>
					</div>
					<div id="item13" class="panel-collapse collapse out">
						<div class="panel-body">
							<form method="POST" action="/PourAdminSeulement/Utilisateurs/SupprimerUtilisateur">
								<input type="hidden" name="idUtilisateur" value="<?php echo($utilisateurAAdministrer->getIdUtilisateur()) ?>"/>
								<button class="btn btn-primary" type="submit">Delete</button>
							</form>
						</div>
					</div> 
				</div>
				
				<?php if(!$utilisateurAAdministrer->getEtatBanniUtilisateur()){ ?>
					
				<div class="panel panel-default col-lg-12">
					<div class="panel-heading">
						<a class="accordion-toggle" href="#item14" data-toggle="collapse">Change (or create) his administrator password</a>
					</div>
					<div id="item14" class="panel-collapse collapse out">
						<div class="panel-body">
							<form method="POST" action="/PourAdminSeulement/Utilisateurs/UtilisateurEnAdministrateur">
								<div class="form-group">
									<span class="label label-primary">New administrator password</span>
									<input type="password" class="form-control input-sm" id="newPassword" name="futurPasswordAdmin1"/>
								</div>
								<div class="form-group">
									<span class="label label-primary">Confirm the new administrator password</span>
									<input type="password" class="form-control input-sm" id="confirmPassword" name="futurPasswordAdmin2"/>
								</div>
								<input type="hidden" name="idUtilisateur" value="<?php echo($utilisateurAAdministrer->getIdUtilisateur()) ?>"/>
								<button class="btn btn-primary" type="submit">Send</button>
							</form>
						</div>
					</div> 
				</div>
				<?php } ?>

			</div>
			<?php } ?>
		</div>
	</div>
</div>


<div id="changePhotoUser" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2 class="modal-title">Edit the photo</h2>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering">
						<form method="post" class="well well-lg" action="/PourAdminSeulement/Utilisateurs/ChangerPhotoProfil" enctype="multipart/form-data">
							<div class="form-group">
								<label for="photo" for="submit">Select a picture</label>
								<input type="hidden" name="idUtilisateur" value="<?php if(isset($utilisateurAAdministrer)){echo($utilisateurAAdministrer->getIdUtilisateur());} ?>"/>
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