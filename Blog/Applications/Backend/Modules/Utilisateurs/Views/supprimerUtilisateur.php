<?php 
if($user->getMessageClient()->hasReussite()){
	$texte = '';
	foreach($user->getMessageClient()->getReussites() as $reussite){
		$texte .= '<p>'.$reussite.'</p>';
	}

	if(isset($utilisateurs)){
		$listeUtilisateurs = '';
		foreach($utilisateurs as $utilisateur){
			if($utilisateur->getNewsletterUtilisateur()){$newsletter = 1;}else{$newsletter = 0;}
			$listeUtilisateurs .='<li class="list-group-item utilisateur" idUser="'.$utilisateur->getIdUtilisateur().'" nameUser="'.$utilisateur->getNomUtilisateur().'" mailUser="'.$utilisateur->getMailUtilisateur().'" dateInscriptionUser="'.$utilisateur->getDateInscriptionUtilisateur().'" newsletter="'.$newsletter.'">'.$utilisateur->getMailUtilisateur().'</li>';
		}

		$reponse = array(
			'reussites' => $texte,
			'listeUtilisateurs' => $listeUtilisateurs
		);
	}else{
		$reponse = array(
			'erreurs' => 'A system error has occured.'
		);
	}

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