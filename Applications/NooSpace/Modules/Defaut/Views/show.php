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
										
													<li title="<?php echo $parametre->getDescriptionParametre(); ?>">
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
				<li><a href="#fileResult" data-toggle="tab">File</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active results imageResult centering" id="imageResult"></div>
				<div class="tab-pane results tableResult" id="tableResult">
					<table class="table table-bordered table-striped table-condensed"></table>
				</div>
				<div class="tab-pane graphResult centering" id="graphResult"></div>
				<div class="tab-pane results tableOfResults" id="tableOfResults"></div>
				<div class="tab-pane results commentairesResult" id="commentairesResult"></div>
				<div class="tab-pane results fileResult" id="fileResult"></div>
			</div>
		</div>