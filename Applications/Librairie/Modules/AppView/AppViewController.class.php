<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour afficher le profil des applications    |
// | présents sur la plateforme.							  			  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe AppViewController
 * @access: public
 * @version: 1
 */

namespace Applications\Librairie\Modules\AppView;
	
class AppViewController extends \Library\BackController
{
	public function executeShow($request)
	{
		// On récupère l'utilisateur système
		$user = $this->app->getUser();

		// On récupère l'id de l'application que le client veut visualiser
		$idApp = (int) $request->getGetData('app');

		// On récupère les applications des utilisateurs
		// On appelle les managers
		$managerApp = $this->getManagers()->getManagerOf('Application');
		// On récupère l'application en question si elle existe avec tous ses attributs emplis
		$app = $managerApp->getApplicationByIdWithAllParameters($idApp);
		
		if (!$app) {
			// On procède à la redirection vers la librairie
			$response = $this->app->getHTTPResponse();
			$response->redirect('/Library/');
		}else{
			$statut = (int) $app->getStatut()->getIdStatut();
			if($statut <= 4){
				// On procède à la redirection vers la librairie
				$response = $this->app->getHTTPResponse();
				$response->redirect('/Library/');
			}else{
				// On vérifie que l'application n'est pas déjà présente dans le dock
				$appIsInDock = false;
				$userSession = unserialize($user->getAttribute('userSession'));		
				foreach($userSession->getFavoris() as $applicationInDock){
					if($applicationInDock->getIdApplication() === $app->getIdApplication()){
						$appIsInDock = true;
					}
				}

				// On récupère les auteurs de l'application et on les affiche avec un lien s'ils sont
				// enregistrés sur NooLib
				$otherAuthors = '';
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				foreach($app->getAuteurs() as $auteur){
					$utilisateurBDD = $managerUtilisateur->getUtilisateurByMail($auteur->getMailAuteur());
					if($utilisateurBDD){
						$otherAuthors .= '<li>';
						$otherAuthors .='<a href="/Profile/idAuteur='.$utilisateurBDD->getIdUtilisateur().'">';
						$otherAuthors .= $utilisateurBDD->getNomUtilisateur().' '.$utilisateurBDD->getPrenomUtilisateur().'</a>';		
						$otherAuthors .='</li>';			
					}else{
						$otherAuthors .= '<li>'.$auteur->getNomAuteur().' '.$auteur->getPrenomAuteur().'</li>';
					}
				}

				$this->page->addVar('otherAuthors', $otherAuthors);

				// On ajoute la variable de présence dans le dock à la page
				$this->page->addVar('appIsInDock', $appIsInDock);
				
				// On ajoute l'application à la page
				$this->page->addVar('app', $app);
			}
		}
		
	}


	/**
	* Méthode pour contacter un auteur à propos de son application
	*/
	public function executeContactAuthor($request)
	{
		$response = $this->app->getHTTPResponse();
		$user = $this->app->getUser();

		// On appelle le manager des utilisateurs
		$idAuteur = (int) $request->getPostData('idAuteur');
		$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
		$auteurUtilisateur = $managerUtilisateur->getUtilisateurById($idAuteur);


		if($auteurUtilisateur){
				
			//On verifie les variables
			$headerMessageMail = $request->getPostData('headerMessageMail');
			$bodyMessageMail = $request->getPostData('bodyMessageMail');
			$nomApplication = $request->getPostData('nomApplication');
			$idApplication = (int) $request->getPostData('idApplication');
			
			if(!empty($headerMessageMail) && !empty($bodyMessageMail) && !empty($nomApplication)){
				
					$variablesArray = array(
						'mailAuteur' => $auteurUtilisateur->getMailUtilisateur(),
						'titreMessage' => $headerMessageMail,
						'messageMail' => $bodyMessageMail,
						'nomApplication' => $nomApplication
						);

					// On envoi un mail à l'auteur
					// On place la variable en Flash pour qu'elle soit récupérée par l'application Mail
					$user->setFlash($variablesArray);
					$mailApplication = new \Applications\ApplicationsStandAlone\Mail\MailApplication;
					$mailApplication->execute('SendMailToAuthor', 'sendAMessageForApplication'); // Module = SendMailToAuthor ; action = sendAMessageForApplication

					// Opération réussie
					$user->getMessageClient()->addReussite(self::MAIL_MESSAGE_SENT);
			}
			else{
				// On envoie une erreur
				$user->getMessageClient()->addErreur(self::ALL_FIELDS_REQUIRED);
			}
			
			$response->redirect('/Library/app='.$idApplication);
		}else{
			// On envoie une erreur
			$user->getMessageClient()->addErreur(self::LIBRARY_NO_AUTHOR);
			$response->redirect('/Library/');
		}
	}
}