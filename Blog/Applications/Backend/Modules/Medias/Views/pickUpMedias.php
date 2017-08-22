<?php 
if(isset($medias)){
	$listeMedias = '';

	foreach($medias as $media){
		$listeMedias .= '<div class="col-lg-4 listeMedias">
			<img class="media" src="data:image/jpg;charset=utf8;base64,'.base64_encode(file_get_contents($media->getUrlMediaMiniature())).'"/>
			<textarea class="hidden">{I}'.$media->getUrlMedia().'{/I}
{C}Figure 0. Legende{/C}</textarea>
		</div>';
	}
	

	$reponse = array(
			'listeMedias' => $listeMedias
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