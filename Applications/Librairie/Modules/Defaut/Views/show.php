<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-lg-12 centering maxWidth fadeIn">
			<div class="row">
				<form class="well centering col-lg-6" id="formRechercheApplication">
					<legend>Need an application?</legend>
					<div class="form-group">
						<input type="text" class="form-control" name="rechercheApplication" placeholder="Keywords..." autofocus/>
					</div>
					<div class="form-group">
						<label for="categorieRecherche">Search by category:</label>
						<select name="categorie" class="form-control" data-live-search="true">
							<option selected value="0">All categories</option>
							<?php echo($categoriesAAfficher); ?>
						</select>
					</div>
					<button class="btn btn-primary" id="boutonRechercheApplication" data-loading-text="<span class='glyphicon glyphicon-refresh spinning'></span> Loading..." type="submit">Search</button>
				</form>
				<br>
				<div class="col-lg-12" id="informationRecherche"></div>

				<div id="lastApplications" class="col-lg-12 sousMenu">
					<h2>Best new applications</h2>
					<div class="col-lg-12 sautDeLigne"><hr></div>
					<?php if(isset($apps)){
						foreach($apps as $app){
						$nomApp = $app->getNomApplication();
						if(strlen($nomApp)>13){
							$nomApp = substr($app->getNomApplication(), 0, 13).'...';
						}?>
						<div class="col-lg-4 applicationView">
							<div class="col-lg-3">
								<a href ="/Library/app=<?php echo $app->getIdApplication();?>">
									<img src="data:image/png;charset=utf8;base64,<?php echo base64_encode(file_get_contents($app->getUrlLogoApplication())); ?>"/>
								</a>
							</div>
							<div class="col-lg-9">
								<a class="noA" href ="/Library/app=<?php echo $app->getIdApplication();?>">
									<h4><?php echo $nomApp;?></h4>
								</a>
								<p><?php echo $app->getCategorie()->getNomCategorie();?></p>
							</div>
						</div>
					<?php }} ?>
				</div>
				
			</div>
		</div>
	</div>	
</div>