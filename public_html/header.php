<header>
	<!--ALERT BROSWER-->
    <div id="alertBrowser" class="hidden text-center alert alert-danger"></div>

	<?php
	if($user->isAuthenticated() && $user->jsIsActivated()) { ?>
		<!--Menu boutons-->
		<nav>
			<ul class="list-inline">
				<li><a href="/Profile/"><img src="/Images/profilMini.png" data-html="true" data-toggle="popover" data-content="Edit your profile"/></a></li>
				<li id="helperButton"><a><img src="/Images/aide.png" data-html="true" data-toggle="popover" data-content="May I help you ?"/></a></li>
				<li data-toggle="modal" href="#infoNoolibApplication"><a><img src="/Images/information.png" data-html="true" data-toggle="popover" data-content="About Noolib"/></a></li>
				<?php if($user->getAttribute('isAdmin')) { ?>
				<li><a href="/PourAdminSeulement/SortirDuModeAdmin"><img src="/Images/quitAdminMode.png" data-html="true" data-toggle="popover" data-content="Return to user mode"/></a></li>
				<?php } ?>
				<li><a href="/LogIn/Deconnexion"><img src="/Images/cross.png" data-html="true" data-toggle="popover" data-content="Log out"/></a></li>
			</ul>
		</nav>

		<!--Fil d'Ariane-->
		<div class="breadCrumbHolder module">
			<?php if($_SERVER['REQUEST_URI'] != '/' && $_SERVER['REQUEST_URI'] != '/PourAdminSeulement/') {} ?>
		    <div id="menuFilAriane" class="breadCrumb module">
		        <ul>
		        	<!-- Lien pour la maison -->
		            <?php if($user->getAttribute('isAdmin')) {?>
						 <li>
			                <a href="/PourAdminSeulement/"></a>
			            </li>
					<?php }else{ ?>
						 <li>
			                <a href="/"></a>
			            </li>
					<?php } ?>

					<!-- Lien pour le reste du fil -->
		            <?php

		            	$URI = $_SERVER['REQUEST_URI'];
		            	if($user->getAttribute('isAdmin') && preg_match('#^/ManagerOfApplications/app=[0-9]+$#', $URI)){
		            		$URI = '/PourAdminSeulement/Applications/';
		            	}

						$repertoires = explode('/', $URI);
						
						$tailleTableau = count($repertoires);
						foreach($repertoires as $indice => $rep) {
							if($rep != '' && $rep != 'PourAdminSeulement'){
								// Pour la NooSpace on affiche jamais le numéro de l'application
								if($rep == 'NooSpace'){
									$repAAfficher = ucfirst(strtolower(trim(preg_replace('#([A-Z])#', ' $1',$rep))));?>
									<li>
										<a href="<?php echo preg_replace('#(.+'.$rep.'/)(.+)?#', '$1', $URI); ?>">
											<?php echo $repAAfficher; ?>
										</a>
									</li>
									<?php break; // On quitte la boucle foreach

								}else{
									$repAAfficher = ucfirst(strtolower(trim(preg_replace('#([A-Z])#', ' $1',$rep))));

									if($indice == $tailleTableau - 1){
										// Pour afficher le nom de l'application
										if(isset($app)){

											$repAAfficher = preg_replace('#(app=[0-9]+)#',$app->getNomApplication(),$rep);
										}
										// Pour afficher le nom de l'utilisateur plutôt que son ID
										if(isset($utilisateurAAfficher)){
											$repAAfficher = preg_replace('#(idAuteur=[0-9]+)#', $utilisateurAAfficher->getPrenomUtilisateur().' '.$utilisateurAAfficher->getNomUtilisateur(),$rep);
										}
									}
									if($indice < $tailleTableau - 1){
									?>
									<li>
										<a href="<?php echo preg_replace('#(.+'.$rep.'/)(.+)?#', '$1', $URI); ?>">
											<?php echo $repAAfficher; ?>
										</a>
									</li>
									<?php }else{
									?>
									<li>
										<?php echo $repAAfficher; ?>
									</li>
									<?php }
								}
							}
						}
					 ?>
		        </ul>
		    </div>

		</div>

		<?php if((bool) strstr($_SERVER['REQUEST_URI'],'/NooSpace/')){ ?>
		<span id="boutonFullScreen" class="glyphicon glyphicon-fullscreen fullScreen infoBulleRight" title="Full screen mode"></span>
		<?php } ?>

		<div id="infoNoolibApplication" class="modal fade" role="dialog">
		  <div class="modal-dialog modal-lg">

		    <!-- Modal content-->
		    <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">x</button>
					<h2 class="modal-title">NooLib Web Application</h2>
				</div>
				<div class="modal-body">
					<ul class="list-unstyled">
						<li><h3>Authors (Thanks a lot)</h3>
							<ul class="list-unstyled">
								<li class="teamPicture"><span></span><p class="text-center">Léna Buron (2 months)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Corentin Chevallier (2 months)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Guénaël Dequeker (5 months)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Steve Despres (3 months)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Remi Dugue (2 months)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Antoine Fauchard (2 months)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Kévin Grosbois (1 month)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Baptiste Houssais (1 month)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Brian Le Bras (1 month)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Jean Mainguy (1 month)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Baptiste Maudet (2 months)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Yohann Pichois (1 month)</p></li>
								<li class="teamPicture"><span></span><p class="text-center">Clément Richard (1 month)</p></li>
							</ul>
						</li>
						<li><h3>Founder & author</h3><ul class="list-unstyled"><li class="teamPicture"><a href="/Profile/idAuteur=58"><div class="founder"></div><p class="text-center">Mathieu Collette</p></a></li></ul></li>
						<li><h3>Settings</h3>
							<ul class="list-unstyled">
								<li>Status: in development</li>
								<li>Version: prototype</li>
								<li>Category: sciences</li>
								<li>KeyWords: application, research, science</li>
							</ul>
						</li>
						<li><h3>Partners</h3>
						<ul class="list-inline">
							<li>
								<a href="http://www.chu-angers.fr/" target="_blank"><img src="/Images/Social/chuAngers.png" alt="Centre hospitalier et universitaire d'Angers"/></a>
							</li>
						</ul>
					</li>
				</ul>
		      </div>
		    </div>
		  </div>
		</div>

		<div id="helperApplication" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">x</button>
					<h2 class="modal-title">Help center</h2>
				</div>
				<div class="modal-body"></div>
		    </div>
		  </div>
		</div>

	<?php } ?>
</header>

<!-- Deleted and removed to nooSpace/show.php by Naoures -->