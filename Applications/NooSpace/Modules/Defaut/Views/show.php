		<div ng-app="NooSpace"  ng-controller="nooSpaceController" style="height:100%;">
		<div class="container-fluid">
			<div class="row-fluid">
				<div id="noospace" class="col-sm-11 text-center centering noospace fadeIn">
				<?php if(isset($applicationNooSpace) && isset($version)){
				// On récupère la liste des tâches
				$taches = $version->getTaches();
				?>
					<div class="appInDock runIt" id="<?php echo $applicationNooSpace->getIdApplication();?>" idVersion="<?php echo $version->getIdVersion();?>">
						<div class="ajaxLoaderApplication"><img src="/Images/waiter.gif"/></div>
						<div class="dataBox"></div><hr><!--
						--><img class="imageApplication" src="data:image/png;charset=utf8;base64,<?php echo base64_encode(file_get_contents($applicationNooSpace->getUrlLogoApplication())); ?>"/><!--
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
										<?php foreach($tabParametres as $parametre){
											if(count($fonction->getParametres()) != 0){?>
										
													<li>
														<label for="<?php echo $parametre->getNomParametre(); ?>" class="labelVariable"><?php echo $parametre->getNomParametre(); ?> :</label>
											 			<input type="text" id="<?php echo $parametre->getNomParametre(); ?>" name="<?php echo $parametre->getIdParametre(); ?>" class="inputVariable valeurDefautParametre" value="<?php echo $parametre->getValeurDefautParametre(); ?>" readonly />
											 			<input type="hidden" class="valeurMinParametre" value="<?php echo $parametre->getValeurMinParametre(); ?>" />
											 			<input type="hidden" class="valeurMaxParametre" value="<?php echo $parametre->getValeurMaxParametre(); ?>" />
											 			<input type="hidden" class="valeurPasParametre" value="<?php echo $parametre->getValeurPasParametre(); ?>" />
											 			<div class="sliderParametreApplication"></div>
										 			</li>
												
												
										<?php }}?> 
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

		<div id="resultReportApplication" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
			    <div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">x</button>
						<div class="image-upload">
						    <label for="submitSaveResult" id="labelSubmitSaveResult">
						        <img src="/Images/upload.png" data-html="true" data-toggle="popover" data-content="Load all results in your data manager"/>
						    </label>
							<form method="POST" enctype="multipart/form-data" id="formSaveResult">
							    <input type="submit" name="submit" id="submitSaveResult"/>
							    <input type="hidden" value="" name="ext" id="extensionDataResult"/>
							    <input type="hidden" value="" name="nomFichier" id="nomDataResult"/>
							    <input type="hidden" value="" name="donneeUtilisateur" id="dataResult"/>
							    <input type="hidden" value="" name="sampleRateDonneeUtilisateur" id="sampleRateDataResult"/>
							    <input type="hidden" value="" name="tailleDonneeUtilisateur" id="tailleDataResult"/>
							    <input type="hidden" value="" name="tempsMinimumDonneeUtilisateur" id="tempsMinimumDataResult"/>
							</form>
						</div>
						<div class="image-waiter" id="image-result-waiter"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading data...</div>
					</div>
					<div class="modal-body">
						<div id="carouselApplicationReport" class="carousel slide">
							<ol class="carousel-indicators"></ol>
							<div class="carousel-inner"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="hidden item" id="templateItemReportApplication">
			<ul class="nav nav-pills">
				<li class="active"><a href="#imageResult" data-toggle="tab">Image</a></li>
				<li><a href="#tableResult" data-toggle="tab">Table</a></li>
				<li><a href="#graphResult" data-toggle="tab">Graph</a></li>
				<li><a href="#tableOfResults" data-toggle="tab">Table of results</a></li>
				<li><a href="#commentairesResult" data-toggle="tab">Comments</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active results imageResult centering" id="imageResult"></div>
				<div class="tab-pane results tableResult" id="tableResult">
					<table class="table table-bordered table-striped table-condensed"></table>
				</div>
				<div class="tab-pane graphResult centering" id="graphResult"></div>
				<div class="tab-pane results tableOfResults" id="tableOfResults"></div>
				<div class="tab-pane results commentairesResult" id="commentairesResult"></div>
			</div>
		</div>

		<!-- Romoved by Naoures from header.php -->
		<!-- ******Button full screen****** -->
		<!-- <span id="boutonFullScreen" class="glyphicon glyphicon-fullscreen fullScreen infoBulleRight" title="Full screen mode"></span>-->
		<span id="boutonFullScreen" ng-click="screenChange()" ng-class="glyphicon-fullscreen" title="{{attribut}}"></span>
		<!-- ************ --> 
		<!--**** Gestionnaire de données *****-->
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
		<!-- ******** -->
		<!--********-->