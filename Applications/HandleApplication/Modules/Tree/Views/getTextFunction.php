<?php 
if(isset($texteSource) && isset($fonction)){
	$reponse = array(
		'text' => $texteSource,
		'ext' => $fonction->getExtensionFonction(),
		'lang' => $fonction->getLanguageFonction(),
		'versionLang' => $fonction->getVersionLangFonction()
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

// On envoie la réponse au format text
echo json_encode($reponse);

?>