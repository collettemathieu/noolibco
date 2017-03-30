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

<!--Data manager-->
<?php if($user->isAuthenticated() && $user->jsIsActivated()) {
	if((bool) strstr($_SERVER['REQUEST_URI'],'/NooSpace/')) {?>
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
					<div class="titreGestionnaire">Data manager</div>
					<div id="progressBarGestionnaireDonneeUtilisateur">
						<div><strong id="progressionPourcent"></strong> occupied space</div>
						<progress id="progressBarEspaceDisponibleData" value="" min="0" max=""></progress>
					</div>
				</div>
			</div>
			<div id="inSectionGestionnaireDonnees">
				<div id="boiteAOutils">
					<img id="boutonImporter" class="infoBulleDataManager" data-toggle="modal" href="#overlayFormulaireAjoutDonneeLocale" src="/Images/addData.png" title="Load a new data item">
					<img id="boutonSaveDataOnServer" class="infoBulleDataManager" src="/Images/save.png" title="Drag and drop a data item to save it on NooLib for 30 days">
					<img id="poubelleDonneeUtilisateur" class="infoBulleDataManager" src="/Images/trash.png" title="Drag and drop a data item to delete it permanently">	
				</div>
				<div id="listeDonneesUser">
					<div id="inListeDonneesUser">
						<div class="image-dataManager-waiter"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading data...</div>
					</div>
				</div>

			</div>

		</div>
		<div id="laMule">
			<div class="headerGestionnaireDonnees">
				<div class="inHeaderMule">
					<div class="titreGestionnaire"></div>
				</div><br>
				<form id="formMule" class="col-lg-12 centering">
					<div class="task">
						<div class="form-group"></div>
						<div class="form-group"></div>
						<hr>
					</div>
					<br><br><br>
				</form>
			</div>
		</div>
	</div>

	<div id="overlayAfficherDonnee" class="modal fade" role="dialog">
	  <div class="modal-dialog">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">x</button>
			<div class="image-upload">
			    <label for="" id="labelSubmitUpdateData">
			        <img src="/Images/upload.png" data-html="true" data-toggle="popover"/>
			    </label>
				<form id="formUpdateData">
				    <input type="submit" name="submit" id="submitUpdateData"/>
				    <input type="hidden" name="idDonneeUtilisateur" id="idDonneeUtilisateur" value="">
				    <input type="hidden" name="debutDonnee" id="debutDonnee" value="">
				    <input type="hidden" name="finDonnee" id="finDonnee" value="">
				    <input type="hidden" name="rowSelected" id="rowSelected" value="">
				</form>
			</div>
			<div class="image-waiter" id="image-waiter"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading data...</div>
	      </div>
	      <div class="modal-body">
	      	<div class="row hidden">
		      	<div class="item col-lg-12">
					<ul class="nav nav-pills">
						<li class="active"><a href="#tableResult" data-toggle="tab">Table</a></li>
						<li><a href="#graphResult" data-toggle="tab">Graph</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active tableResult table-responsive" id="tableResult"><br/>
							<table class="table table-bordered table-striped table-condensed"></table>
						</div>
						<div class="tab-pane graphResult centering" id="graphResult"></div>
					</div>
				</div>
			</div>
			<div class="row text-center"></div>
	      </div>
	    </div>
	  </div>
	</div>
	

	<div id="overlayFormulaireAjoutDonneeLocale" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">x</button>
			<div class="image-upload">
			    <label for="fileLocalData" id="labelFileLocalData">
			        <img src="/Images/addData.png" data-html="true" data-toggle="popover" data-content=""/>
			    </label>
			    <label id="labelSubmitLocalData">
			        <img src="/Images/upload.png" data-html="true" data-toggle="popover" data-content=""/>
			    </label>
			    <input id="fileLocalData" name="urlLocalData" type="file" class="withoutBootstrap"/>
				<form method="POST" enctype="multipart/form-data" id="formUploadLocalData">
				    <input type="submit" name="submit" id="submitLocalData"/>
				    <input type="hidden" value="" name="ext" id="extensionFichierData"/>
				    <input type="hidden" value="" name="nomFichier" id="nomFichierData"/>
				    <input type="hidden" value="" name="donneeUtilisateur" id="localData"/>
				    <input type="hidden" value="" name="sampleRateDonneeUtilisateur" id="sampleRateDonnee"/>
				    <input type="hidden" value="" name="tailleDonneeUtilisateur" id="tailleDonnee"/>
				    <input type="hidden" value="" name="tempsMinimumDonneeUtilisateur" id="tempsMinimumDonneeUtilisateur"/>
				</form>
			</div>
			<div class="image-waiter" id="image-local-waiter"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading data...</div>
	      </div>
	      <div class="modal-body">
	      	<div class="row hidden">
		      	<div class="item col-lg-12">
					<ul class="nav nav-pills">
						<li class="active"><a href="#tableLocalResult" data-toggle="tab">Table</a></li>
						<li><a href="#graphLocalResult" data-toggle="tab">Graph</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active tableResult table-responsive" id="tableLocalResult"><br/>
							<table class="table table-bordered table-striped table-condensed"></table>
						</div>
						<div class="tab-pane graphResult centering" id="graphLocalResult"></div>
					</div>
				</div>
			</div>
			<div class="row text-center"></div>
	      </div>
	    </div>

	  </div>
	</div>
<?php } } ?>