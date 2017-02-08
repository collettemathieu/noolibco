	<div class="container-fluid">
		<div class="row-fluid">
			<div class="col-sm-8 sousMenu centering maxWidth fadeIn">
				<h2>Manage your own applications</h2>
				<?php if(isset($applicationsOfUser) && count($applicationsOfUser) != 0){
				foreach($applicationsOfUser as $application){
					$nomApplication = $application->getNomApplication();
					if(strlen($nomApplication)>16){
						$nomApplication = substr($application->getNomApplication(), 0, 12).'...';
					}?>
					<a class="caseMenu application text-left" 
						<?php if($application->getStatut()->getNomStatut() === 'Validated' || $application->getStatut()->getNomStatut() == 'Inactive' || $application->getStatut()->getNomStatut() == 'Not validated'){ ?>
						href="/ManagerOfApplications/app=<?php echo $application->getIdApplication();?>"  
						<?php }else{ ?>
						href="/SubmitAnApplication/<?php echo $application->getIdApplication();?>"  
						<?php } ?>
						id="<?php echo $application->getIdApplication();?>">
						<p><?php echo $nomApplication; ?></p>
						<img src="data:image/png;charset=utf8;base64,<?php echo base64_encode(file_get_contents($application->getUrlLogoApplication())); ?>" alt="Logo application"/>
					</a>
				<?php }}else{?>
				<div class="alert alert-warning">
					<p>None application found.</p>
					<p>Do you want to <a href="/SubmitAnApplication/">submit a new application ?</a></p>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>