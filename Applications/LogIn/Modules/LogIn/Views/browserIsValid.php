<?php 
if(isset($isValid)){
	$reponse = array(
			'isValid' => $isValid
		);

}else{
	$reponse = array(
		'erreurs' => 'A system error has occured.'
	);
}

// On envoie la réponse au format JSON
echo json_encode($reponse);

?>