<?php 
if($user->getMessageClient()->hasReussite() && isset($application)){
	$texte = '';
	foreach($user->getMessageClient()->getReussites() as $reussite){
		$texte .= '<p>'.$reussite.'</p>';
	}

	$reponse = array(
			'reussites' => $texte,
			'description' => $application->getDescriptionApplication(),
			'motCles' => implode(', ', $application->getMotCles()),
			'idCategorie' => $application->getCategorie()->getIdCategorie()
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