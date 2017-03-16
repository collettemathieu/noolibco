<?php 
if($user->getMessageClient()->hasErreur()){
	$texte = '';
	foreach($user->getMessageClient()->getErreurs() as $erreur){
		$texte .= '<p>'.$erreur.'</p>';
	}
	$reponse = array(
			'erreurs' => $texte
		);
}else if($user->getMessageClient()->hasReussite()){
	$texte = '';
	foreach($user->getMessageClient()->getReussites() as $reussite){
		$texte .= '<p>'.$reussite.'</p>';
	}
	$reponse = array(
			'reussites' => $texte
		);
}else{
	$reponse = array(
			'erreurs' => 'A system error has occurred.'
		);
}

// On envoie la réponse au format JSON
echo json_encode($reponse);
?>