<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Ce controleur permet d'afficher, modifier ou créer de nouveaux		  |
// | utilisateurs   			  										  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: controleur des utilisateurs
 * @access: public
 * @version: 1
 */	


namespace Applications\Backend\Modules\Utilisateurs;

class UtilisateursController extends \Library\BackController
{
	use \Library\Traits\MethodeUtilisateurControleur;
	use \Library\Traits\FonctionsUniverselles;
	
	public function executeShow($request)
	{
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$response = $this->app->getHTTPResponse();
			$response->redirect('/ForAdminOnly/');
		}
		else{
			
			//On appelle le manager des Utilisateurs
			$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');

			// On récupère la liste de tous les utilisateurs
			$utilisateurs = $managerUtilisateur->getAllUtilisateurs();

			// On envoie la liste à la page
			$this->page->addVar('utilisateurs', $utilisateurs);
		}
	}
	
	// Méthode pour créer un utilisateur
	public function executeCreerUtilisateur($request){
		
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		//On récupère la réponse
		$response = $this->app->getHTTPResponse();

		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$response->redirect('/ForAdminOnly/');
		}else{
			
			$motDePasseAdmin = $request->getPostData('motDePasseAdmin');
			$motDePasseAdminConfirme = $request->getPostData('motDePasseAdminConfirme');
			
			if($motDePasseAdmin != $motDePasseAdminConfirme){
				
				$user->getMessageClient()->addErreur('Le mot de passe est vide ou a mal été confirmé.');
				$response->redirect('/ForAdminOnly/Utilisateurs/');
			}else{
				if(empty($motDePasseAdmin)){
					$passwordUser = '';
					$superAdmin = false;
				}else{
					// On protège le mot de passe
					if($this->validPassword($motDePasseAdmin)){
						$passwordUser=$this->protectPassword($motDePasseAdmin);
						$superAdmin = (bool) $request->getPostData('superAdmin');
		   		    }else{
		   		    	$user->getMessageClient()->addErreur(self::PASSWORD_NOT_VALID);
						$response->redirect('/ForAdminOnly/Utilisateurs/');
		   		    }
				}

				
				$newUser = new \Library\Entities\Utilisateur(array(
					'nomUtilisateur' => trim($request->getPostData('nom')),
					'mailUtilisateur' => trim($request->getPostData('adresseMail')),
					'passwordAdminUtilisateur' => $passwordUser,
					'newsletterUtilisateur' => (bool) $request->getPostData('newsletter'),
					'superAdminUtilisateur' => $superAdmin
				));
				
				if(sizeof($newUser->getErreurs()) != 0){
					$user->getMessageClient()->addErreur($newUser->getErreurs());
					$response->redirect('/ForAdminOnly/Utilisateurs/');
				}else{
					
					$managerUser = $this->getManagers()->getManagerOf('Utilisateur');
					
					if($managerUser->getUtilisateurByMail($newUser->getMailUtilisateur()) instanceof \Library\Entities\Utilisateur){
						$user->getMessageClient()->addErreur('Cette adresse électronique est déjà prise par un autre utilisateur.');
						$response->redirect('/ForAdminOnly/Utilisateurs/');
					}else{
						$managerUser->addUtilisateur($newUser);
						$user->getMessageClient()->addReussite('L\'utilisateur a bien été créé.');
						
						$response->redirect('/ForAdminOnly/Utilisateurs/');

					}
				}
			}
		}
	}

	// Méthode pour modifier un utilisateur
	public function executeModifierUtilisateur($request){
		
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On informe que c'est un chargement Ajax
		$user->setAjax(true);

		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
		}else{

			$modifyUser = new \Library\Entities\Utilisateur(array(
				'nomUtilisateur' => trim($request->getPostData('nom')),
				'mailUtilisateur' => trim($request->getPostData('adresseMail')),
				'newsletterUtilisateur' => (bool) $request->getPostData('newsletter')
			));
			
			if(sizeof($modifyUser->getErreurs()) != 0){
				$user->getMessageClient()->addErreur($modifyUser->getErreurs());
			}else{
				
				$managerUser = $this->getManagers()->getManagerOf('Utilisateur');
				
				$utilisateur = $managerUser->getUtilisateurByMail(trim($request->getPostData('ancienMailUtilisateur')));

				if($utilisateur instanceof \Library\Entities\Utilisateur){
					
					$modifyUser->hydrate(array(
						'idUtilisateur' => $utilisateur->getIdUtilisateur()
						));
					$managerUser->saveUtilisateur($modifyUser);
					$user->getMessageClient()->addReussite('L\'utilisateur a bien été modifié.');
					
					// On récupère la liste de tous les utilisateurs
					$utilisateurs = $managerUser->getAllUtilisateurs();

					// On envoie la liste à la page
					$this->page->addVar('utilisateurs', $utilisateurs);
					
				}else{
					$user->getMessageClient()->addErreur('L\'utilisateur que vous souhaitez modifier n\'existe pas.');
				}
			}
			
		}
	}
	
	// Méthode pour supprimer un utilisateur de la base
	public function executeSupprimerUtilisateur($request){
		$user = $this->app->getUser();

		// On informe que c'est un chargement Ajax
		$user->setAjax(true);
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
		}else{
			$idUtilisateurAAdministrer = $request->getPostData('idUtilisateur');
			//On appelle le manager des Utilisateurs
			$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
			//on recuper l'utilisateur à administrer
			$utilisateurAAdministrer = $managerUtilisateur->getUtilisateurByIdWithAllData($idUtilisateurAAdministrer);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($utilisateurAAdministrer === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				
			}
			else{
				//On procède à la suppression dans la BDD de l'utilisateur
				$managerUtilisateur->deleteUtilisateur($utilisateurAAdministrer);
				$user->getMessageClient()->addReussite('L\'utilisateur a bien été supprimé.');

				// On récupère la liste de tous les utilisateurs
				$utilisateurs = $managerUtilisateur->getAllUtilisateurs();

				// On envoie la liste à la page
				$this->page->addVar('utilisateurs', $utilisateurs);
				
			}
		}
	}
	
	
	

	
}
