<?php
if($user->getMessageClient()->hasErreur()){
	$texte = '';
	foreach($user->getMessageClient()->getErreurs() as $erreur){
		$texte .= '<p>'.$erreur.'</p>';
	}
	$reponse = array(
				'erreurs' => $texte
			);

	$reponse = json_encode($reponse);

}elseif(isset($listeTacheAAfficher)){
	$reponse = array(
				'listeTache' => '<span class="label label-primary">Select a new task</span>
								<select name="tache0" class="listeTache form-control input-sm">'.$listeTacheAAfficher.'</select>',
				'listeTypeDonnee' => $listeTypeDonnee,
				'listeParams' =>  $listeParams
			);

	$reponse = json_encode($reponse);

}else{
	$reponse = array(
				'erreurs' => '<p>A system error has occurred while loading the mule.</p>'
			);

	$reponse = json_encode($reponse);
	
}
// On envoie la réponse au format JSON
// On se protège des failles XSS par htmlspecialchars
echo $reponse;


?>