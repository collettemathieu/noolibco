<?php 
if(isset($stats)){
	$reponse = array(
			'nbreUsers' => $stats['nbreUsers'],
			'nbreArticles' => $stats['nbreArticles'],
			'nbreVues' => $stats['nbreVues'],
			'nbreCommentaires' => $stats['nbreCommentaires']
		);

}else{
	$reponse = array(
		'erreurs' => 'Une erreur est apparue.'
	);
}

// On envoie la réponse au format JSON
echo json_encode($reponse);

?>