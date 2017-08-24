		<div class="container-fluid">
			<div class="row-fluid">
				<div id="noospace" class="col-sm-11 text-center centering noospace fadeIn">

				<?php if(isset($applicationNooSpace) && isset($version)){
				// On récupère la liste des tâches
				$taches = $version->getTaches();
				?>
					<div class="appInDock runIt" id="<?php echo $applicationNooSpace->getIdApplication();?>" idVersion="<?php echo $version->getIdVersion();?>">
								<div class="containerApplication" style="display:inline-flex;">
									<!--hr--><!--
									-->
									<button class='play'style='position:absolute;top:-35px;right:25px;visibility:hidden'>play</button>
									<div class="ajaxLoaderApplication"><img src="/Images/waiter.gif"/></div>
									<img class="imageApplication" src="data:image/png;charset=utf8;base64,<?php echo base64_encode(file_get_contents($applicationNooSpace->getUrlLogoApplication())); ?>"/>
									<hr><div class="resultBox">
											<img src="/Images/results.png"/>
											<div class="applicationReports hidden"></div>
										</div>
								</div>
									<div class="tachesApplication">
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