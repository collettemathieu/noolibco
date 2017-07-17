<?php
header("", true); // supprime les précédents headers
$reponse = array();
if($user->getMessageClient()->hasErreur()){
	$texte = '';
	foreach($user->getMessageClient()->getErreurs() as $erreur){
		$texte .= '<p>'.$erreur.'</p>';
	}
	$reponse['erreurs'] = $texte;
}

if($user->getMessageClient()->hasReussite()){
	
	$resultatsApplication = array();
	
	foreach($user->getMessageClient()->getReussites() as $resultat){
		// Pour supprimer les espaces en début/fin de chaîne ainsi que les retours chariots
		$resultat = trim($resultat);
		$resultat = str_replace( array( '<br>', '<br />', "\n", "\r" ), array( '', '', '', '' ), $resultat );
		// Pour supprimer tout ce qu'il y a avant et après les {} du résultat // Eviter les headers par exemple de php
		$resultat = preg_replace('/^(.*?){/', '{', $resultat); // Avant
		$resultat = preg_replace('/(.*)}.*$/', '$1}', $resultat); // Après
		// Pour un encodage en UTF8
		$resultat = utf8_encode($resultat);
		$resultat = preg_replace('/(\/home\/noolibco\/.+)/', '', $resultat); // Retire les noms des chemin du serveur NooLib
		// Pour les failles de type scripts
		$resultat = htmlspecialchars($resultat, ENT_NOQUOTES);
		array_push($resultatsApplication, $resultat);
	}
	$reponse['resultat'] = $resultatsApplication;
}
$reponse = json_encode($reponse);
// On envoie la réponse au format JSON
// On se protège des failles XSS par htmlspecialchars
print_r($reponse);
?>