<?php 
if($user->getMessageClient()->hasReussite()){
	$reponse = array(
			'statusApp' => $user->getMessageClient()->getReussites()
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