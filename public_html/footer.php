		<footer>
			<?php if(!$user->isAuthenticated()){ ?>
			<div class="container">
				<div class="row">
					<div class="col-lg-5 centering" >
						<h5>© 2017 NooLib - All rights reserved - </h5>
						<button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#team">Team</button>
						<button ng-click="contactModal()" class="btn btn-default btn-xs">Contact</button>
						<ul>
							<li><a href="https://twitter.com/NoolibApp" target="_blank"><img src="/Images/Social/twitter.png"/></a></li>
							<li><a href="https://www.facebook.com/Noolib-1648329638748950/" target="_blank"><img src="/Images/Social/facebook.png" alt="lien facebook"/></a></li>
							<li><a href="https://www.linkedin.com/company/noolib" target="_blank"><img src="/Images/Social/linkedin.png" alt="lien linkedin"/></a></li>
						</ul>
					</div>
				</div>
			</div>
			<?php } ?>
		</footer>
		<?php if(!$user->isAuthenticated()){ ?>
		<div id="team" class="modal fade" role="dialog">
		  <div class="modal-dialog modal-lg">

		    <!-- Modal content-->
		    <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">x</button>
					<h2 class="teamTitle modal-title">The NooLib Team</h2>
				</div>
				<div class="modal-body">
					<ul class="list-unstyled">
						<li><h3>Authors (Thanks a lot)</h3>
							<ul class="list-unstyled">
								<li class="teamPicture"><span></span><p class="text-center">Léna Buron (2 months)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Corentin Chevallier (2 months)</p></li>
								<li class="teamPicture"><a href="/Profile/idAuteur=122"><span></span><p class="text-center">Quentin Denis (2 months)</p></a></li>
								<li class="teamPicture"><a href="/Profile/idAuteur=79"><span></span><p class="text-center">Guénaël Dequeker (5 months)</p></a></li>
								<li class="teamPicture"><span></span><p class="text-center">Steve Despres (3 months)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Remi Dugue (2 months)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Antoine Fauchard (2 months)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Kévin Grosbois (1 month)</p></li>
								<li class="teamPicture"><a href="/Profile/idAuteur=97"><span></span><p class="text-center">Naoures Hassine (6 months)</p></a></li>
								<li class="teamPicture"><span></span><p class="text-center">Baptiste Houssais (1 month)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Brian Le Bras (1 month)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Jean Mainguy (1 month)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Baptiste Maudet (2 months)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Yohann Pichois (1 month)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Clément Richard (1 month)</p></li>
							</ul>
						</li>
						<li><h3>Founder & author</h3><ul class="list-unstyled"><li class="teamPicture"><a href="/Profile/idAuteur=58"><div class="founder"></div><p class="text-center">Mathieu Collette</p></a></li></ul></li>
						<li><h3>Partners</h3>
						<ul class="list-inline">
							<li>
								<a href="https://www.chu-angers.fr/" target="_blank"><img src="/Images/Social/chuAngers.png" alt="Centre hospitalier et universitaire d'Angers"/></a>
							</li>
							<li>
								<a href="http://www.uco.fr/ima/" target="_blank"><img src="/Images/Social/ima.jpg" alt="Institut de Mathématiques Appliquées de l'Université Catholique de l'Ouest"/></a>
							</li>
						</ul>
					</li>
				</ul>
		      </div>
		    </div>
		  </div>
		</div>
		<?php } ?>
		<?php if($user->isAuthenticated() && $user->jsIsActivated()) {
			if((!$user->getAttribute('isAdmin') && $_SERVER['REQUEST_URI'] != '/PourAdminSeulement/') || (bool) strstr($_SERVER['REQUEST_URI'],'/NooSpace/a=')) {?>
			<div id="overlayDockApplication">
				<div class="inHeaderDock">
					<div class="titreDock">Applications in dock</div>
					<div id="applicationsInDock">
						<?php 
						// On récupère l'utilisateur en session
						$userSession = unserialize($user->getAttribute('userSession'));
						
						foreach($userSession->getFavoris() as $application){
							$version = $application->getVersions()[count($application->getVersions()) - 1];
							// On récupère la liste des tâches
							$taches = $version->getTaches();
							?>
							<div class="appInDock" draggable="true" id="<?php echo $application->getIdApplication();?>">
								<div class="ajaxLoaderApplication"><img src="/Images/waiter.gif"/></div>
								<div class="dataBox"></div><hr><!--
								--><img class="imageApplication" src="data:image/png;charset=utf8;base64,<?php echo base64_encode(file_get_contents($application->getUrlLogoApplication())); ?>" alt="Logo application"/><!--
								--><hr><div class="resultBox">
										<img src="/Images/results.png"/>
										<div class="applicationReports hidden"></div>
									</div>
								<div class="tachesApplication">
									<ul>
									<?php foreach($taches as $tache){ ?>
										<li id="<?php echo $tache->getNomTache(); ?>"><?php echo $tache->getNomTache(); ?></li>
									<?php }?>
									</ul>
								</div>
								<div class="parametresApplication">
									<div class="container-fluid">
										<div class="row-fluid">
											<?php $tacheAvecParametre = array();
											foreach($taches as $tache){
												$tabParametres = array();
												foreach($tache->getFonctions() as $fonction){
													if(count($fonction->getParametres()) != 0){
														foreach($fonction->getParametres() as $parametre){
															if($parametre->getStatutPublicParametre()){
																array_push($tabParametres, $parametre);
															}
														}
													}
												}
												if(count($tabParametres) !=0){
													$tacheAvecParametre[$tache->getNomTache()] = $tabParametres;
												}
											}

											if(count($tacheAvecParametre) ===0){?>
											<div class="alert alert-warning">Sorry, this application cannot be set.</div>
											<?php }else{?>
											<form class="well col-lg-12 centering" enctype="multipart/form-data" method="post">
											<?php foreach($tacheAvecParametre as $nomTache => $tabParametres){?>
												<ul>
													<li class="parametresTache">
														<div class="nomTacheParametreApplication"><?php echo $nomTache; ?></div>
														<ul>

												<?php foreach($tabParametres as $parametre){ ?>
															<li title="<?php echo $parametre->getDescriptionParametre(); ?>">
																<label for="<?php echo $parametre->getNomParametre(); ?>" class="labelVariable" ><?php echo $parametre->getNomParametre(); ?> :</label>
													 			<input type="text" id="<?php echo $parametre->getNomParametre(); ?>" name="<?php echo $parametre->getIdParametre(); ?>" class="inputVariable valeurDefautParametre" value="<?php echo $parametre->getValeurDefautParametre(); ?>" readonly />
													 			<input type="hidden" class="valeurMinParametre" value="<?php echo $parametre->getValeurMinParametre(); ?>" />
													 			<input type="hidden" class="valeurMaxParametre" value="<?php echo $parametre->getValeurMaxParametre(); ?>" />
													 			<input type="hidden" class="valeurPasParametre" value="<?php echo $parametre->getValeurPasParametre(); ?>" />
													 			<div class="sliderParametreApplication"></div>
												 			</li>
												<?php }?> 
														</ul>
													</li>
												</ul>
											<?php } ?>
											<button class="btn btn-default pull-right" type="submit">Save</button>
											</form>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						<?php }?>
					</div>
				</div>
			</div>

			<?php if(count($userSession->getFavoris()) !=0){?>
			<div id="panelSettingsApplication" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<!-- Modal content-->
				    <div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">x</button>
							<h2 class="modal-title">Settings</h2>
						</div>
						<div class="modal-body"></div>
					</div>	
				</div>
			</div>
			<?php }?>
		<?php }} ?>