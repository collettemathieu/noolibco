<?php 
if(isset($texteSource)){
	$reponse = array(
		'text' => $texteSource,
		'ext' => $ext
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

// On envoie la réponse au format text
echo json_encode($reponse);

?>