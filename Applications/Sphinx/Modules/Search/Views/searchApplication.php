<?php if(!$rechercheVide) { ?>
	<?php if($absenceResultat) { ?>
		<div class="alert alert-dismissable alert-danger">
			<button type="button" class="close" data-dismiss="alert">×</button>
			No result found !
		</div>
	<?php } else { ?>

		<div class="row">
			<div class="col-lg-12 sousMenu">
				<?php foreach($applications as $app){
				$nomApp = $app->getNomApplication();
				if(strlen($nomApp)>12){
					$nomApp = substr($app->getNomApplication(), 0, 12).'...';
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
				<?php } ?>
			</div>
		</div>
	<?php } ?>
<?php }else{ ?>
<div class="alert alert-dismissable alert-warning">
	<button type="button" class="close" data-dismiss="alert">×</button>
	Please enter at least a keyword for your search !
</div>
<?php } ?>
