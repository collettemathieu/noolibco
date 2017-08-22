<?php 
if(isset($edito)){			
	$date = new DateTime($edito->getDateEdito());
	$reponse = array(
			'texte' => nl2br($edito->getTexteEdito()),
			'date' => $date->format('d-m-Y'),
			'nbreEditos' => $nbreEditos
		);

}else{
	$reponse = array(
		'erreurs' => 'Une erreur est apparue.'
	);
}

// On envoie la réponse au format JSON
echo json_encode($reponse);

?>