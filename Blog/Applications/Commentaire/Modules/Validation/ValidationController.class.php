<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Ce controleur permet de valider les commentaires par les auteurs	  |	  										  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: controleur de validation des commentaires par les auteurs
 * @access: public
 * @version: 1
 */	


namespace Applications\Commentaire\Modules\Validation;

class ValidationController extends \Library\BackController{
	
	
	// Méthode pour ajouter un commentaire à un article
	public function executeAjouterCommentaire($request){
		
		//On récupère la requête du client
		$user = $this->app->getUser();

		// On récupère la réponse
		$response = $this->app->getHTTPResponse();
		
		// On informe que c'est un chargement Ajax
		$user->setAjax(true);

		// On charge le fichier de configuration
		$config = $this->getApp()->getConfig();

		// Manager des utilisateurs
		$managerUser = $this->getManagers()->getManagerOf('Utilisateur');

		// On cherche si il y a une connexion par cookies
		$emailUser = $request->getCookieData('emailUser');
		$nameUser = $request->getCookieData('nameUser');

		// On tente de récupérer l'utilisateur si celui-ci existe. Sinon on le crée.
		if(isset($emailUser) && isset($nameUser)){
			$utilisateur = $managerUser->getUtilisateurByMail($emailUser);
		}else{
			$utilisateur = $managerUser->getUtilisateurByMail(trim($request->getPostData('adresseMail')));
		}

		if(!$utilisateur instanceof \Library\Entities\Utilisateur){

			// On vérifie que l'adresse électronique de l'utilisateur n'est pas une adresse jetable
			$adresseMail = trim($request->getPostData('adresseMail'));
			if($config->validMail($adresseMail) == true){
				$mailUtilisateur = trim($adresseMail);
			}else{
				$mailUtilisateur = false;
			}

			$utilisateur = new \Library\Entities\Utilisateur(array(
				'nomUtilisateur' => trim($request->getPostData('nom')),
				'mailUtilisateur' => $mailUtilisateur,
				'passwordAdminUtilisateur' => '',
				'newsletterUtilisateur' => (bool) $request->getPostData('newsletter'),
				'superAdminUtilisateur' => false
			));
		}
		
		if(sizeof($utilisateur->getErreurs()) != 0){
			$user->getMessageClient()->addErreur($utilisateur->getErreurs());
		}else{
			
			if(!isset($emailUser) && !isset($nameUser)){
				// On crée les cookies de connexion automatique
				$response->setCookie('emailUser', $utilisateur->getMailUtilisateur(), time()+365*24*3600);
				$response->setCookie('nameUser', $utilisateur->getNomUtilisateur(), time()+365*24*3600);
			}

			// Insertion en BDD si l'utilisateur n'existe pas
			if(empty($utilisateur->getIdUtilisateur())){
				$utilisateur = $managerUser->addUtilisateur($utilisateur);
			}

			// Manager des articles et des cours
			$managerArticle = $this->getManagers()->getManagerOf('Article');
			$managerCours = $this->getManagers()->getManagerOf('Cours');
			$article = $managerArticle->getArticleById($request->getPostData('idArticle'));
			$cours = $managerCours->getCoursById($request->getPostData('idCours'));

			if($article instanceof \Library\Entities\Article){
				// Contrôle du commentaire
				$newComment = new \Library\Entities\Commentaire(array(
					'texteCommentaire' => trim($request->getPostData('texteCommentaire')),
					'enLigneCommentaire' => false,
					'enAttenteValidationAuteurCommentaire' => false,
					'article' => $article,
					'utilisateur' => $utilisateur
				));

				if(sizeof($newComment->getErreurs()) != 0){
					$user->getMessageClient()->addErreur($newComment->getErreurs());
				}else{
					// Insertion du commentaire en BDD
					// Manager des commentaires
					$managerCommentaire = $this->getManagers()->getManagerOf('Commentaire');
					$managerCommentaire->addCommentaire($newComment);
					$user->getMessageClient()->addReussite('Merci pour votre participation. Un email de confirmation vous sera envoyé lorsque votre commentaire aura été approuvé par un modérateur.');

				}
			}elseif($cours instanceof \Library\Entities\Cours){
				// Contrôle du commentaire
				$newComment = new \Library\Entities\Commentaire(array(
					'texteCommentaire' => trim($request->getPostData('texteCommentaire')),
					'enLigneCommentaire' => false,
					'enAttenteValidationAuteurCommentaire' => false,
					'cours' => $cours,
					'utilisateur' => $utilisateur
				));

				if(sizeof($newComment->getErreurs()) != 0){
					$user->getMessageClient()->addErreur($newComment->getErreurs());
				}else{
					// Insertion du commentaire en BDD
					// Manager des commentaires
					$managerCommentaire = $this->getManagers()->getManagerOf('Commentaire');
					$managerCommentaire->addCommentaire($newComment);
					$user->getMessageClient()->addReussite('Merci pour votre participation. Un email de confirmation vous sera envoyé lorsque votre commentaire aura été approuvé par un modérateur.');
				}
			}else{
				$user->getMessageClient()->addErreur('L\'article ou le cours demandé n\'existe pas.');
			}
		}
	}
	
	// Méthode pour valider un commentaire (via validation par email de l'auteur)
	public function executeValiderCommentaire($request){
		$user = $this->app->getUser();

		$idCommentaire = $request->getGetData('idCommentaire');
		//On appelle le manager des Commentaires
		$managerCommentaire = $this->getManagers()->getManagerOf('Commentaire');
		//on recuper le commentaire à administrer
		$commentaire = $managerCommentaire->getCommentaireById($idCommentaire);
		
		//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
		if($commentaire === false){
			
			$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
		else{

			if($commentaire->getEnAttenteValidationAuteurCommentaire()){

				if(!$commentaire->getEnLigneCommentaire()){
					//On procède à la mise à jour dans la BDD du commentaire
					$commentaire->hydrate(array(
						'enLigneCommentaire' => true
						));
					$managerCommentaire->saveCommentaire($commentaire);
					$user->getMessageClient()->addReussite('Votre commentaire a bien été publié.');

					// On renvoie l'utilisateur vers l'article ou le cours
					$managerCommentaire->putArticleInCommentaire($commentaire);
					$managerCommentaire->putCoursInCommentaire($commentaire);
					$response = $this->app->getHTTPResponse();
					if($commentaire->getArticle() instanceof \Library\Entities\Article){
						$response->redirect('/Blog/'.$commentaire->getArticle()->getUrlTitreArticle());
					}elseif($commentaire->getCours() instanceof \Library\Entities\Cours){
						$response->redirect('/Cours/'.$commentaire->getCours()->getUrlTitreCours());
					}else{
						$response->redirect('/');
					}
					
				}else{
					$managerCommentaire->putArticleInCommentaire($commentaire);
					$managerCommentaire->putCoursInCommentaire($commentaire);
					$user->getMessageClient()->addErreur('Le commentaire a déjà été publié.');
					$response = $this->app->getHTTPResponse();
					if($commentaire->getArticle() instanceof \Library\Entities\Article){
						$response->redirect('/Blog/'.$commentaire->getArticle()->getUrlTitreArticle());
					}elseif($commentaire->getCours() instanceof \Library\Entities\Cours){
						$response->redirect('/Cours/'.$commentaire->getCours()->getUrlTitreCours());
					}else{
						$response->redirect('/');
					}
				}
			}else{
				$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à réaliser cette commande.');
				$response = $this->app->getHTTPResponse();
				$response->redirect('/');
			}
		}
	}
}
