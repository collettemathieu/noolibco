<?php 
if(isset($actualite)){
	$reponse = array(
			'urlBackground' => $actualite->getUrlImageActualite(),
			'titre' => nl2br($actualite->getTitreActualite()),
			'texte' => nl2br($actualite->getTexteActualite()),
			'lien' => $actualite->getUrlLienActualite(),
			'nbreActualites' => $nbreActualites
		);

}else{
	$reponse = array(
		'erreurs' => 'Une erreur est apparue.'
	);
}

// On envoie la réponse au format JSON
echo json_encode($reponse);

?>