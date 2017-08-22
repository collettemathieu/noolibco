<?php 
if(isset($isValid)){
	$reponse = array(
			'isValid' => $isValid
		);

}else{
	$reponse = array(
		'erreurs' => 'Une erreur est apparue.'
	);
}

// On envoie la réponse au format JSON
echo json_encode($reponse);

?>