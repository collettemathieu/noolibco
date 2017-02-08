	<?php 

	$applicationNotActivated = '';
	$applicationActivated = '';
	$applicationUnderSubmission = '';

	if($applications){
		foreach($applications as $app){
			$nomApplication = $app->getNomApplication();
			if(strlen($nomApplication)>12){
				$nomApplication = substr($app->getNomApplication(), 0, 12).'...';
			}
			if((int) $app->getStatut()->getIdStatut() > 4){
				$applicationActivated.='<a class="caseMenu application" href="/ManagerOfApplications/app='.$app->getIdApplication().'"  id="'.$app->getIdApplication().'">
					<p>'.$nomApplication.'</p>
					<img src="data:image/png;charset=utf8;base64,'.base64_encode(file_get_contents($app->getUrlLogoApplication())).'" alt="Logo application"/>
				</a>';
			}elseif((int) $app->getStatut()->getIdStatut() == 4){
				$applicationNotActivated.='<a class="caseMenu application" href="/ManagerOfApplications/app='.$app->getIdApplication().'"  id="'.$app->getIdApplication().'">
					<p>'.$nomApplication.'</p>
					<img src="data:image/png;charset=utf8;base64,'.base64_encode(file_get_contents($app->getUrlLogoApplication())).'" alt="Logo application"/>
				</a>';
			}else{
				$applicationUnderSubmission.='<a class="caseMenu application" href="/ManagerOfApplications/app='.$app->getIdApplication().'"  id="'.$app->getIdApplication().'">
					<p>'.$nomApplication.'</p>
					<img src="data:image/png;charset=utf8;base64,'.base64_encode(file_get_contents($app->getUrlLogoApplication())).'" alt="Logo application"/>
				</a>';
			}
		}
	}

	if(empty($applicationNotActivated)){$applicationNotActivated = '<p>None application found.</p>';}
	if(empty($applicationActivated)){$applicationActivated = '<p>None application found.</p>';}
	if(empty($applicationUnderSubmission)){$applicationUnderSubmission = '<p>None application found.</p>';}
	?>

	<div class="container-fluid">
		<div class="row-fluid">
			<div class="col-lg-10 sousMenu centering fadeIn">
				<div class="row">
					<div class="col-lg-12">
						<h3>Applications pending activation</h3><hr>
						<?php echo $applicationNotActivated; ?>
					</div>
					<div class="col-lg-12">
						<h3>Applications under submission</h3><hr>
						<?php echo $applicationUnderSubmission; ?>
					</div>
					<div class="col-lg-12">
						<h3>Applications currently activated</h3><hr>
						<?php echo $applicationActivated; ?>
					</div>
				</div>
			</div>
		</div>
	</div>