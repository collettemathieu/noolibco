		<div class="container-fluid">
			<div class="row-fluid">
				<div id="noospace" class="col-sm-11 text-center centering noospace fadeIn">

				<?php if(isset($applicationNooSpace) && isset($version)){
				// On récupère la liste des tâches
				$taches = $version->getTaches();
				?>
					<div class="appInDock runIt" id="<?php echo $applicationNooSpace->getIdApplication();?>" idVersion="<?php echo $version->getIdVersion();?>">
						<div class="containerApplication">
							<div class="top">
								<img class="imageApplication" src="data:image/png;charset=utf8;base64,<?php echo base64_encode(file_get_contents($applicationNooSpace->getUrlLogoApplication())); ?>" alt="Logo application"/><div class="linkPlay">&brvbar;</div>
								<div class="playButton"><img src="/Images/play.png"/></div>
							</div>
							<div class="ajaxLoaderApplication"><img src="/Images/waiter.gif"/></div><!--
							--><hr><div class="resultBox">
								<img src="/Images/results.png"/>
								<div class="applicationReports hidden"></div>
							</div>
						</div>
						<div class="tachesApplication"></div>
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
					<div class="modal-body" id="carouselApplicationReport"></div>
				</div>
			</div>
		</div>

		<div class="hidden item" id="templateItemReportApplication">
			<ul class="nav nav-pills">
				<li><a href="#table2D" data-toggle="tab">Table</a></li>
				<li><a href="#results" data-toggle="tab">Results</a></li>
				<li><a href="#comments" data-toggle="tab">Comments</a></li>
				<li><a href="#errors" data-toggle="tab">Errors</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane results table2D" id="table2D"></div>
				<div class="tab-pane results tableOfResults" id="results"></div>
				<div class="tab-pane results commentsResult" id="comments">
					<ul class="list-group"></ul>
				</div>
				<div class="tab-pane results errorsResult" id="errors">
					<ul class="list-group"></ul>
				</div>
			</div>
		</div>

		<div id="overlayDragAndDropData">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="col-lg-10 centering text-center">
						<div class="textDragAndDrop">Drop your file to upload!</div>
					</div>
				</div>
			</div>
		</div>