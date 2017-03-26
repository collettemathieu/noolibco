<?php 
if(isset($application)){

	$reponse = array(
		'id' => $application->getIdApplication(),
		'nom' => $application->getNomApplication(),
		'description' => $application->getDescriptionApplication(),
		'urlLogo' => base64_encode(file_get_contents($application->getUrlLogoApplication())),
		'motCles' => implode(', ', $application->getMotCles()),
		'categorie' => $application->getCategorie()->getNomCategorie(),
		'contributeurs' => $contributeurs,
		'versions' => $versions
	);
}elseif($user->getMessageClient()->hasErreur()){
	$texte = '';
	foreach($user->getMessageClient()->getErreurs() as $erreur){
		$texte .= '<p>'.$erreur.'</p>';
	}
	$reponse = array(
			'erreurs' => $texte
		);
}else{
	$reponse = array(
		'erreurs' => 'A system error has occured.'
	);
}

// On envoie la réponse au format JSON

echo json_encode($reponse);

?>