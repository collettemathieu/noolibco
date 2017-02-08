<?php 
if(isset($tableOfCategories)){
	$reponse = $tableOfCategories;
}else{
	$reponse = array(
		'erreurs' => 'A system error has occured.'
	);
}

// On envoie la réponse au format JSON
echo json_encode($reponse);

?>