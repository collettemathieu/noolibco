<?php 
if(isset($listeCours) && $titreCours){
	$coursAAfficher = '';
	foreach($listeCours as $cours){
		$date = new DateTime($cours->getDateCreationCours());
		$coursAAfficher .= '<a class="caseCours" href="/Cours/'.$cours->getUrlTitreCours().'"><!--
			--><img class="infoBulleBottom" title="'.$cours->getTitreCours().'" src="data:image/png;charset=utf8;base64,'.base64_encode(file_get_contents($cours->getUrlImageMiniatureCours())).'"/><!--
			--><div class="mainText">
				<h4>'.nl2br($cours->getTitreCours()).'</h4>
				<div class="informationCours"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> Publié le '.$date->format('d-m-Y').'</div>
				<div class="informationCours"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Par '.$cours->getAuteur()->getNomUtilisateur().'</div>
				<div class="informationCours"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Lu '.$cours->getNbreVueCours().' fois</div>
				<p class="cesure">'.nl2br($cours->getDescriptionCours()).'</p>
			</div>
		</a>';
	}

	$reponse = array(
			'coursAAfficher' => $coursAAfficher,
			'titreCours' => $titreCours
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

// On envoie la réponse au format JSON
echo json_encode($reponse);

?>