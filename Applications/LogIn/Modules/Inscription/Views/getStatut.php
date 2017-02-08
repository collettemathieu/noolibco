<?php 
if($user->getMessageClient()->hasErreur()){
	$texte = '';
	foreach($user->getMessageClient()->getErreurs() as $erreur){
		$texte .= '<p>'.$erreur.'</p>';
	}
	$reponse = array(
		'erreurs' => $texte
	);
}else if(isset($statutAAfficher)){
	$reponse = array(
		'allStatus' => $statutAAfficher
	);
}else{
	$reponse = array(
		'erreurs' => '[Server] An system error has occurred.'
	);
}

// On envoie la réponse au format JSON
echo json_encode($reponse);
?>