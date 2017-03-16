<?php 
if($user->getMessageClient()->hasErreur()){
	$texte = '';
	foreach($user->getMessageClient()->getErreurs() as $erreur){
		$texte .= '<p>'.$erreur.'</p>';
	}
	$reponse = array(
			'erreurs' => $texte
		);

	
}elseif(isset($dataObjet)){
	$reponse = $dataObjet;
}else{
	$reponse = array(
		'erreurs' => '<p>A system error has occurred !</p>'
	);
}
// On envoie la réponse au format JSON
echo json_encode($reponse);
?>