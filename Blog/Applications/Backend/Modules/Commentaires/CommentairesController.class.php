<?php
// +----------------------------------------------------------------------+
// | PHP Version 7                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2018 NooLib The Blog									  |
// +----------------------------------------------------------------------+
// | Ce controleur permet de créer ou supprimer les commentaires   		  |	  										  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@noolib.com> 			  |
// +----------------------------------------------------------------------+

/**
 * @name: controleur des commentaires
 * @access: public
 * @version: 1
 */	


namespace Applications\Backend\Modules\Commentaires;

class CommentairesController extends \Library\BackController{
	
	// Page principal de la gestion des commentaires
	public function executeShow($request){
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$response = $this->app->getHTTPResponse();
			$response->redirect('/ForAdminOnly/');
		}
	}


	// Page principal de la gestion des commentaires des articles
	public function executeShowArticle($request){
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$response = $this->app->getHTTPResponse();
			$response->redirect('/ForAdminOnly/');
		}
		else{
			
			//On appelle le manager des Commentaires
			$managerCommentaire = $this->getManagers()->getManagerOf('Commentaire');

			// On récupère la liste de tous les commentaires
			$commentaires = $managerCommentaire->getAllCommentaires();

			$commentairesAJour = array();
			foreach($commentaires as $commentaire){
				$commentaire = $managerCommentaire->putArticleInCommentaire($commentaire);
				array_push($commentairesAJour, $commentaire);
			}

			// On envoie la liste à la page
			$this->page->addVar('commentaires', $commentairesAJour);

		}
	}


	// Page principal de la gestion des commentaires des cours
	public function executeShowCours($request){
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$response = $this->app->getHTTPResponse();
			$response->redirect('/ForAdminOnly/');
		}
		else{
			
			//On appelle le manager des Commentaires
			$managerCommentaire = $this->getManagers()->getManagerOf('Commentaire');

			// On récupère la liste de tous les commentaires
			$commentaires = $managerCommentaire->getAllCommentaires();

			$commentairesAJour = array();
			foreach($commentaires as $commentaire){
				$commentaire = $managerCommentaire->putCoursInCommentaire($commentaire);
				array_push($commentairesAJour, $commentaire);
			}

			// On envoie la liste à la page
			$this->page->addVar('commentaires', $commentairesAJour);

		}
	}

	
	// Méthode pour envoyer la validation du commentaire à l'auteur
	public function executeValidationAdminCommentaire($request){
		$user = $this->app->getUser();

		// On informe que c'est un chargement Ajax
		$user->setAjax(true);
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
		}else{
			$idCommentaire = $request->getPostData('idCommentaire');
			//On appelle le manager des Commentaires
			$managerCommentaire = $this->getManagers()->getManagerOf('Commentaire');
			//on recuper le commentaire à administrer
			$commentaire = $managerCommentaire->getCommentaireById($idCommentaire);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($commentaire === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				
			}
			else{
				//On procède à la mise à jour dans la BDD du commentaire
				$commentaire->hydrate(array(
					'enAttenteValidationAuteurCommentaire' => true
					));
				$managerCommentaire->saveCommentaire($commentaire);

				//On envoi un mail pour confirmer l'inscription par l'application Mail en StandAlone
				// On place la variable en Flash pour qu'elle soit récupérée par l'application Mail
				$user->setFlash(array(
					'mailAuteur' => $commentaire->getUtilisateur()->getMailUtilisateur(),
					'titreMessage' => 'Votre commentaire a été accepté',
					'lienValidation' => 'http://blog.noolib.com/Commentaire/ValiderCommentaire/id='.$commentaire->getIdCommentaire()
					));
				$mailApplication = new \Applications\ApplicationsStandAlone\Mail\MailApplication;
				$mailApplication->execute('SendMailToAuthor', 'sendAMessageForValidatingComment'); // Module = MailInscription ; action = sendAMessageForValidatingComment
				
				$user->getMessageClient()->addReussite($user->getFlash());

				// On récupère la liste de tous les commentaires
				$commentaires = $managerCommentaire->getAllCommentaires();

				$commentairesAJour = array();
				$commentaire = $managerCommentaire->putArticleInCommentaire($commentaire);
				$commentaire = $managerCommentaire->putCoursInCommentaire($commentaire);
				if($commentaire->getArticle() instanceof \Library\Entities\Article){
					$isArticle = true;
				}else{
					$isArticle = false;
				}
				foreach($commentaires as $commentaire){
					if($isArticle){
						$commentaire = $managerCommentaire->putArticleInCommentaire($commentaire);
					}else{
						$commentaire = $managerCommentaire->putCoursInCommentaire($commentaire);
					}
					array_push($commentairesAJour, $commentaire);
				}
				
				// On envoie la liste à la page
				$this->page->addVar('commentaires', $commentairesAJour);
				
			}
		}
	}

	// Méthode pour supprimer un commentaire de la base
	public function executeSupprimerCommentaire($request){
		$user = $this->app->getUser();

		// On informe que c'est un chargement Ajax
		$user->setAjax(true);
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
		}else{
			$idCommentaire = $request->getPostData('idCommentaire');
			//On appelle le manager des Commentaires
			$managerCommentaire = $this->getManagers()->getManagerOf('Commentaire');
			//on recuper le commentaire à administrer
			$commentaire = $managerCommentaire->getCommentaireById($idCommentaire);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($commentaire === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				
			}
			else{
				
				//On procède à la suppression dans la BDD de le commentaire
				$commentaire = $managerCommentaire->putArticleInCommentaire($commentaire);
				$commentaire = $managerCommentaire->putCoursInCommentaire($commentaire);
				$managerCommentaire->deleteCommentaire($commentaire);
				$user->getMessageClient()->addReussite('Le commentaire a bien été supprimé.');

				// On récupère la liste de tous les commentaires
				$commentaires = $managerCommentaire->getAllCommentaires();

				$commentairesAJour = array();
				if($commentaire->getArticle() instanceof \Library\Entities\Article){
					$isArticle = true;
				}else{
					$isArticle = false;
				}
				foreach($commentaires as $commentaire){
					if($isArticle){
						$commentaire = $managerCommentaire->putArticleInCommentaire($commentaire);
					}else{
						$commentaire = $managerCommentaire->putCoursInCommentaire($commentaire);
					}
					array_push($commentairesAJour, $commentaire);
				}

				// On envoie la liste à la page
				$this->page->addVar('commentaires', $commentairesAJour);
				
			}
		}
	}
}
