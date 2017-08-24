<?php 
if($user->getMessageClient()->hasReussite()){
	$texte = '';
	foreach($user->getMessageClient()->getReussites() as $reussite){
		$texte .= '<p>'.$reussite.'</p>';
	}

	if(isset($commentaires)){
		$listeCommentairesEnAttente = '';
		$listeCommentairesEnAttenteValidationAuteur = '';
		$listeCommentairesEnLigne = '';
		foreach($commentaires as $commentaire){
			if(!$commentaire->getEnAttenteValidationAuteurCommentaire()){
				if($commentaire->getArticle() instanceof \Library\Entities\Article){
					$listeCommentairesEnAttente.= '<li class="list-group-item commentaire" enLigneComment="'.(int)$commentaire->getEnLigneCommentaire().'" titreArticle = "'.$commentaire->getArticle()->getTitreArticle().'" texteComment = "'.$commentaire->getTexteCommentaire().'" auteurComment = "'.$commentaire->getUtilisateur()->getNomUtilisateur().'" idComment = "'.$commentaire->getIdCommentaire().'">'.$commentaire->getArticle()->getTitreArticle().' | de '.$commentaire->getUtilisateur()->getNomUtilisateur().' | '.substr($commentaire->getTexteCommentaire(), 0, 20).'</li>';
				}elseif($commentaire->getCours() instanceof \Library\Entities\Cours){
					$listeCommentairesEnAttente.= '<li class="list-group-item commentaire" enLigneComment="'.(int)$commentaire->getEnLigneCommentaire().'" titreCours = "'.$commentaire->getCours()->getTitreCours().'" texteComment = "'.$commentaire->getTexteCommentaire().'" auteurComment = "'.$commentaire->getUtilisateur()->getNomUtilisateur().'" idComment = "'.$commentaire->getIdCommentaire().'">'.$commentaire->getCours()->getTitreCours().' | de '.$commentaire->getUtilisateur()->getNomUtilisateur().' | '.substr($commentaire->getTexteCommentaire(), 0, 20).'</li>';
				}
			}elseif(!$commentaire->getEnLigneCommentaire() && $commentaire->getEnAttenteValidationAuteurCommentaire()){
				if($commentaire->getArticle() instanceof \Library\Entities\Article){
					$listeCommentairesEnAttenteValidationAuteur.= '<li class="list-group-item commentaire" enLigneComment="'.(int)$commentaire->getEnLigneCommentaire().'" titreArticle = "'.$commentaire->getArticle()->getTitreArticle().'" texteComment = "'.$commentaire->getTexteCommentaire().'" auteurComment = "'.$commentaire->getUtilisateur()->getNomUtilisateur().'" idComment = "'.$commentaire->getIdCommentaire().'">'.$commentaire->getArticle()->getTitreArticle().' | de '.$commentaire->getUtilisateur()->getNomUtilisateur().' | '.substr($commentaire->getTexteCommentaire(), 0, 20).'</li>';
				}elseif($commentaire->getCours() instanceof \Library\Entities\Cours){
					$listeCommentairesEnAttenteValidationAuteur.= '<li class="list-group-item commentaire" enLigneComment="'.(int)$commentaire->getEnLigneCommentaire().'" titreCours = "'.$commentaire->getCours()->getTitreCours().'" texteComment = "'.$commentaire->getTexteCommentaire().'" auteurComment = "'.$commentaire->getUtilisateur()->getNomUtilisateur().'" idComment = "'.$commentaire->getIdCommentaire().'">'.$commentaire->getCours()->getTitreCours().' | de '.$commentaire->getUtilisateur()->getNomUtilisateur().' | '.substr($commentaire->getTexteCommentaire(), 0, 20).'</li>';
				}
			}else{
				if($commentaire->getArticle() instanceof \Library\Entities\Article){
					$listeCommentairesEnLigne.= '<li class="list-group-item commentaire" enLigneComment="'.(int)$commentaire->getEnLigneCommentaire().'" titreArticle = "'.$commentaire->getArticle()->getTitreArticle().'" texteComment = "'.$commentaire->getTexteCommentaire().'" auteurComment = "'.$commentaire->getUtilisateur()->getNomUtilisateur().'" idComment = "'.$commentaire->getIdCommentaire().'">'.$commentaire->getArticle()->getTitreArticle().' | de '.$commentaire->getUtilisateur()->getNomUtilisateur().' | '.substr($commentaire->getTexteCommentaire(), 0, 20).'</li>';
				}elseif($commentaire->getCours() instanceof \Library\Entities\Cours){
					$listeCommentairesEnLigne.= '<li class="list-group-item commentaire" enLigneComment="'.(int)$commentaire->getEnLigneCommentaire().'" titreCours = "'.$commentaire->getCours()->getTitreCours().'" texteComment = "'.$commentaire->getTexteCommentaire().'" auteurComment = "'.$commentaire->getUtilisateur()->getNomUtilisateur().'" idComment = "'.$commentaire->getIdCommentaire().'">'.$commentaire->getCours()->getTitreCours().' | de '.$commentaire->getUtilisateur()->getNomUtilisateur().' | '.substr($commentaire->getTexteCommentaire(), 0, 20).'</li>';
				}
			}
		}

		$reponse = array(
			'reussites' => $texte,
			'listeCommentairesEnAttente' => $listeCommentairesEnAttente,
			'listeCommentairesEnAttenteValidationAuteur' => $listeCommentairesEnAttenteValidationAuteur,
			'listeCommentairesEnLigne' => $listeCommentairesEnLigne
		);
	}else{
		$reponse = array(
			'erreurs' => 'Une erreur système est apparue.'
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
		'erreurs' => 'Une erreur système est apparue.'
	);
}

// On envoie la réponse au format JSON
echo json_encode($reponse);

?>