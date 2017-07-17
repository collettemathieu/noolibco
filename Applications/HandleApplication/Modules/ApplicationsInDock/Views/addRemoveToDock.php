<?php 
// On récupère l'utilisateur en session
$userSession = unserialize($user->getAttribute('userSession'));
foreach($userSession->getFavoris() as $application){?>
	<div class="appInDock" draggable="true" id="<?php echo $application->getIdApplication();?>">
		<div class="dataBox"></div><hr><!--
		-->
		<img class="imageApplication" src="data:image/png;charset=utf8;base64,<?php echo base64_encode(file_get_contents($application->getUrlLogoApplication())); ?>" alt="Logo application"/><!--
		--><hr><div class="resultBox"></div>	
	</div>
<?php }

?>