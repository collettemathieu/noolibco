<?php
// +----------------------------------------------------------------------+
// | PHP Version 7 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP comme controleur du profil des utilisateurs. 	  		  |
// | Ce controleur permet d'afficher le profil des utilisateurs et de     |
// | permettre leur modification par ces derniers. 		  				  |
// +----------------------------------------------------------------------+
// |           Guénaël DEQUEKER <dequekerguenael@noolib.com>  et 		  | 
// |		   Baptiste MAUDET <maudetbapstiste@noolib.com>			      |
// | 		   Steve DESPRES    <despressteve@noolib.com> 				  |
// |		   Mathieu COLLETTE    <collettemathieu@noolib.com>		   	  |
// +----------------------------------------------------------------------+

/**
 * @name: controleur du profil de l'utilisateur pour le Profile
 * @access: public
 * @version: 1
 */	

namespace Applications\Profile\Modules\Defaut;

class DefautController extends \Library\BackController
{
	use \Library\Traits\MethodeUtilisateurControleur;
	
	public function executeShow($request)
	{
		$user = $this->app->getUser();
		
		//On appelle le manager des Utilisateurs
		$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
		
		//On récupère l'id passé en GET
		$idAuteur = (int) $request->getGetData('idAuteur');
		
		//On test si il y a un id passé en Get
		if(isset($idAuteur) && $idAuteur != 0){
			//On récupère les paramètre de l'utilisateur à afficher gràce à son id
			$utilisateurAAfficher = $managerUtilisateur->getUtilisateurByIdWithAllData($idAuteur);
		}
		
		if(!isset($utilisateurAAfficher) || !$utilisateurAAfficher){
			// On récupère l'utilisateur en session
			$utilisateurAAfficher = unserialize($user->getAttribute('userSession'));
		}
		
		// On récupère les applications de l'utilisateur
		$listeApps = $utilisateurAAfficher->getApplications();
		$managerApplication = $this->getManagers()->getManagerOf('Application');
		//on leur assigne leur version
		foreach($listeApps as $app){
			$managerApplication->putVersionsInApplication($app);
		}
		
		//On ajoute les variable à la page
		$this->page->addVar('utilisateurAAfficher', $utilisateurAAfficher);
		$this->page->addVar('listeApps', $listeApps);
		
		// On appelle le manager des Statut Utilisateur
		// On récupère les différents statut d'utilisateur
		$managerStatutUtilisateur = $this->getManagers()->getManagerOf('StatutUtilisateur');
		$statuts = $managerStatutUtilisateur->getAllStatuts();
		// On créé la variable d'affichage à insérer dans la page.
		$statutAAfficher = '';
		foreach($statuts as $statut)
		{
			if($statut->getIdStatut() != 1)
			{
				$statutAAfficher.='<option value="'.$statut->getNomStatut().'">'.$statut->getNomStatut().'</option>';
			}
		}
		$this->page->addVar('statutAAfficher', $statutAAfficher);
	}
	
	/**
	* Méthode pour récupérer la liste des institutions de recherche
	*/
	public function executeGetInstitutions($request){
		
		// On récupère l'utilisateur système
		$user = $this->app->getUser();

		// On informe que c'est un chargement Ajax
		$user->setAjax(true);

		$listeEtablissementsAAfficher = $this->executeGetAllInstitutions();

		// On ajoute la variable à la page
		$this->page->addVar('listeEtablissementsAAfficher', $listeEtablissementsAAfficher);
		
	}


	/**
	* Méthode pour récupérer la liste des laboratoires en fonction de l'établissement
	*/
	public function executeGetLaboratories($request){
		
		// On récupère l'utilisateur système
		$user = $this->app->getUser();

		// On informe que c'est un chargement Ajax
		$user->setAjax(true);

		// On récupère l'ensemble des laboratoires associés à une institution
		$listeLaboratoiresAAfficher = $this->executeGetAllLaboratories($request);
		
		if($listeLaboratoiresAAfficher){
			// On ajoute la variable à la page
			$this->page->addVar('listeLaboratoiresAAfficher', $listeLaboratoiresAAfficher);
		}
	}

	/**
	* Méthode pour récupérer la liste des équipes en fonction du laboratoire
	*/
	public function executeGetTeams($request){
		
		// On récupère l'utilisateur système
		$user = $this->app->getUser();

		// On informe que c'est un chargement Ajax
		$user->setAjax(true);

		// On récupère l'ensemble des équipes associées à un laboratoire
		$listeEquipesAAfficher = $this->executeGetAllTeams($request);
		
		if($listeEquipesAAfficher){
			// On ajoute la variable à la page
			$this->page->addVar('listeEquipesAAfficher', $listeEquipesAAfficher);
		}
	}


	/**
	* Méthode pour contacter l'auteur via son profil 
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
			
			if(!empty($headerMessageMail) && !empty($bodyMessageMail)){
				
					$variablesArray = array(
						'mailAuteur' => $auteurUtilisateur->getMailUtilisateur(),
						'titreMessage' => $headerMessageMail,
						'messageMail' => $bodyMessageMail
						);

					// On envoi un mail à l'auteur
					// On place la variable en Flash pour qu'elle soit récupérée par l'application Mail
					$user->setFlash($variablesArray);
					$mailApplication = new \Applications\ApplicationsStandAlone\Mail\MailApplication;
					$mailApplication->execute('SendMailToAuthor', 'sendAMessage'); // Module = SendMailToAuthor ; action = sendAMessage

					// Opération réussie
					$user->getMessageClient()->addReussite(self::PROFILE_MESSAGE_SENT);
			}
			else{
				// On envoie une erreur
				$user->getMessageClient()->addErreur(self::ALL_FIELDS_REQUIRED);
			}
			
			$response->redirect('/Profile/idAuteur='.$auteurUtilisateur->getIdUtilisateur());
		}else{
			// On envoie une erreur
			$user->getMessageClient()->addErreur(self::PROFILE_CONTACT_NOT_EXIST);
			$response->redirect('/Profile/');
		}
	}


	public function executeChangerPhotoProfil()
	{
		$response = $this->app->getHTTPResponse();
		$user = $this->app->getUser();
		$utilisateur = unserialize($user->getAttribute('userSession'));
		
		//On verifie si l'utilisateur n'a pas accédé à la methode via l'url
		if($this->changerPhotoProfil($utilisateur)){
			//On met la session à jour
			$user->setAttribute('userSession', serialize($utilisateur));
		}
		
		$response->redirect('/Profile/');
	}
	
	public function executeAjouterEquipe($request)
	{
		$response = $this->app->getHTTPResponse();
		$user = $this->app->getUser();
		$utilisateur = unserialize($user->getAttribute('userSession'));
		
		$idEquipe = $request->getPostData('selectedTeam');
		
		$this->ajouterEquipe($utilisateur, $idEquipe);
		//on met à jour la variable session
		$utilisateur->addEquipe($this->getManagers()->getManagerOf('Equipe')->getEquipeById($idEquipe));
		$user->setAttribute('userSession', serialize($utilisateur));

		$response->redirect('/Profile/');
	}
	
	public function executeRemoveEquipe($request)
	{
		$response = $this->app->getHTTPResponse();
		$user = $this->app->getUser();
		$userSession = unserialize($user->getAttribute('userSession'));
		
		//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
		$idEquipe = $request->getPostData('idEquipe');
		$this->removeEquipe($userSession, $idEquipe);
		
		//on met à jour la variable session
		$userSession->removeEquipe($this->getManagers()->getManagerOf('Equipe')->getEquipeById($idEquipe));
		$user->setAttribute('userSession', serialize($userSession));

		$response->redirect('/Profile/');
	}
	
	public function executeChangerPrenom($request)
	{
		$response = $this->app->getHTTPResponse();
		$user = $this->app->getUser();
		$userSession = unserialize($user->getAttribute('userSession'));
				
		//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
		$prenom = $request->getPostData('prenom');
		if($prenom === null)
		{
			// si non, on procède à la redirection
			$response->redirect('/Profile/');
		}
		else
		{
			$userSession->setPrenomUtilisateur($prenom);
			
			//on vérifie qu'il n'y a pas d'erreur
			$erreurs = $userSession->getErreurs();
			if(count($erreurs) == 0)
			{
				//on met la bdd à jour
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				$managerUtilisateur->saveUtilisateur($userSession);
				//on met la session à jour
				$user->setAttribute('userSession', serialize($userSession));
				//opération réussie mentionnée en flash
				$user->getMessageClient()->addReussite(self::PROFILE_NAME_CHANGED);
			}
			else
			{
				//on place les erreurs en flash
				$user->getMessageClient()->addErreur($erreurs);
			}
		}
		
		$response->redirect('/Profile/');
	}
	
	public function executeChangerNom($request)
	{
		$response = $this->app->getHTTPResponse();
		$user = $this->app->getUser();
		$userSession = unserialize($user->getAttribute('userSession'));
				
		//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
		$nom = $request->getPostData('nom');
		if($nom === null)
		{
			// si non, on procède à la redirection
			$response->redirect('/Profile/');
		}
		else
		{
			$userSession->setNomUtilisateur($nom);
			
			// Vérifier qu'il n'y a pas d'erreur
			$erreurs = $userSession->getErreurs();
			if(count($erreurs) == 0)
			{
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				$managerUtilisateur->saveUtilisateur($userSession);

				//opération réussie mentionnée en flash
				$user->getMessageClient()->addReussite(self::PROFILE_SURNAME_CHANGED);
				
				$user->setAttribute('userSession', serialize($userSession));
			}
			else
			{
				//on place les erreurs en flash
				$user->getMessageClient()->addErreur($erreurs);
			}
		}
		
		$response->redirect('/Profile/');
	}
	
	
	
	public function executeChangerEmail($request)
	{
		$response = $this->app->getHTTPResponse();
		$user = $this->app->getUser();
		$userSession = unserialize($user->getAttribute('userSession'));
				
		//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
		$email = $request->getPostData('email');
		if($email === null)
		{
			// si non, on procède à la redirection
			$response->redirect('/Profile/');
		}
		else
		{
			$userSession->setMailUtilisateur($email);
			
			// Vérifier qu'il n'y a pas d'erreur
			$erreurs = $userSession->getErreurs();
			if(count($erreurs) == 0)
			{
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				$managerUtilisateur->saveUtilisateur($userSession);

				//opération réussie mentionnée en flash
				$user->getMessageClient()->addReussite(self::PROFILE_EMAIL_CHANGED);
				
				$user->setAttribute('userSession', serialize($userSession));
			}
			else
			{
				//on place les erreurs en flash
				$user->getMessageClient()->addErreur($erreurs);
			}
		}
		
		$response->redirect('/Profile/');
	}
	
	
	public function executeChangerPagePerso($request)
	{
		$response = $this->app->getHTTPResponse();
		$user = $this->app->getUser();
		$userSession = unserialize($user->getAttribute('userSession'));
				
		//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
		$pagePerso = $request->getPostData('pagePerso');
		if($pagePerso === null)
		{
			// si non, on procède à la redirection
			$response->redirect('/Profile/');
		}
		else
		{
			$userSession->setLienPagePersoUtilisateur($pagePerso);
			
			// Vérifier qu'il n'y a pas d'erreur
			$erreurs = $userSession->getErreurs();
			if(count($erreurs) == 0)
			{
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				$managerUtilisateur->saveUtilisateur($userSession);

				//opération réussie mentionnée en flash
				$user->getMessageClient()->addReussite(self::PROFILE_PAGE_CHANGED);
				
				$user->setAttribute('userSession', serialize($userSession));
			}
			else
			{
				//on place les erreurs en flash
				$user->getMessageClient()->addErreur($erreurs);
			}
		}
		
		$response->redirect('/Profile/');
	}
	
	
	
	public function executeChangerDescription($request)
	{
		$response = $this->app->getHTTPResponse();
		$user = $this->app->getUser();
		$userSession = unserialize($user->getAttribute('userSession'));
				
		//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
		$description = $request->getPostData('description');
		if($description === null)
		{
			// si non, on procède à la redirection
			$response->redirect('/Profile/');
		}
		else
		{
			$userSession->setDescriptionUtilisateur($description);
			
			// Vérifier qu'il n'y a pas d'erreur
			$erreurs = $userSession->getErreurs();
			if(count($erreurs) == 0)
			{
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				$managerUtilisateur->saveUtilisateur($userSession);

				//opération réussie mentionnée en flash
				$user->getMessageClient()->addReussite(self::PROFILE_DESCRIPTION_CHANGED);
				
				$user->setAttribute('userSession', serialize($userSession));
			}
			else
			{
				//on place les erreurs en flash
				$user->getMessageClient()->addErreur($erreurs);
			}
		}
		
		$response->redirect('/Profile/');
	}
	
	public function executeChangerStatut($request){

		$response = $this->app->getHTTPResponse();
		$user = $this->app->getUser();
		$userSession = unserialize($user->getAttribute('userSession'));
		
		//on verifie si l'utilisateur n'a pas accede a la methode via l'url
		$newStatut = $request->getPostData('newStatut');
		if($newStatut === null)
		{
			// si non, on procède à la redirection
			$response->redirect('/Profile/');
		}
		else
		{
			$managerStatut = $this->getManagers()->getManagerOf('StatutUtilisateur');
			$statuts = $managerStatut->getAllStatuts();
			foreach ($statuts as $statut)
			{
				if($statut->getIdStatut() != 1)
				{
					$statutsValides[] = $statut->getNomStatut();
				}
			}
			if ( ! in_array($newStatut, $statutsValides) )
			{
				$user->getMessageClient()->addErreur(self::PROFILE_STATUS_NOT_EXIST);
			}
			else
			{
				foreach ($statuts as $statut)
				{
					if($newStatut == $statut->getNomStatut())
					{
						$userSession->setStatut($statut);
					}
				}
				
				// Verifier qu'il n'y a pas d'erreur
				$erreurs = $userSession->getErreurs();
				if(count($erreurs) == 0)
				{
					$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
					$managerUtilisateur->saveUtilisateur($userSession);

					//opération réussie mentionnée en flash
					$user->getMessageClient()->addReussite(self::PROFILE_STATUS_CHANGED);
				
					$user->setAttribute('userSession', serialize($userSession));
				}
				else
				{
					//on place les erreurs en flash
					$user->getMessageClient()->addErreur($erreurs);
				}
			}
		}
		
		$response->redirect('/Profile/');
	}
	
	public function executeChangerPassword($request)
	{
		$user = $this->app->getUser();
		$userSession = unserialize($user->getAttribute('userSession'));
		$response = $this->app->getHTTPResponse();
		
		//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
		$actualPassword = $request->getPostData('actualPassword');
		$newPassword1 = $request->getPostData('newPassword1');
		$newPassword2 = $request->getPostData('newPassword2');
		if($actualPassword === null || $newPassword1 === null || $newPassword2 === null)
		{
			// si non, on procède à la redirection
			$response->redirect('/Profile/');
		}
		else
		{
			//si la confirmation a réussit
			if($newPassword1 != $newPassword2)
			{
				$user->getMessageClient()->addErreur(self::PASSWORDS_NOT_MATCH);
			}
			else
			{
				// On charge le fichier de configuration
				$config = $this->getApp()->getConfig();
				
				//si le mot de passe actuel a mal été entré				
				if( !password_verify( $actualPassword, $userSession->getPasswordUtilisateur() ))
				{
					$user->getMessageClient()->addErreur(self::PROFILE_OLD_PASSWORD_INCORRECT);
				}
				else
				{					
						//Si le mot de passe utilisateur est different de celui administrateur
						if(!password_verify($newPassword1, $userSession->getPasswordAdminUtilisateur())) {							
							if( $this->validPassword($newPassword1)) {
								//on protege le mot de passe
								$newPassword = $this->protectPassword($newPassword1);
								//on assigne le mot de pass admin à l'utilisateur
								$userSession->setPasswordUtilisateur($newPassword);
							 }else { 
								$user->getMessageClient()->addErreur(self::PASSWORD_NOT_VALID);
							 }	
						}else{ 
							$user->getMessageClient()->addErreur(self::PASSWORD_DIFFERENT_ADMIN);
						}
						
															
					$erreurs = $userSession->getErreurs();
					if(count($erreurs) != 0)
					{
						//on place les erreurs en flash
						$user->getMessageClient()->addErreur($erreurs);
					}
					else
					{
						$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
						$managerUtilisateur->saveUtilisateur($userSession);

						//opération réussie mentionnée en flash
						$user->getMessageClient()->addReussite(self::PROFILE_PASSWORD_WELL_EDITED);
						
						$user->setAttribute('userSession', serialize($userSession));
					}
				}
			}
		}
		
		$response->redirect('/Profile/');
	}

	public function executeChangerPasswordAdmin($request)
	{
		$user = $this->app->getUser();
		$userSession = unserialize($user->getAttribute('userSession'));
		$response = $this->app->getHTTPResponse();

		// Si l'utilisateur n'est pas un administrateur
		if($userSession->getPasswordAdminUtilisateur() != ''){
		
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			$actualPassword = $request->getPostData('actualPasswordAdmin');
			$newPassword1 = $request->getPostData('newPasswordAdmin1');
			$newPassword2 = $request->getPostData('newPasswordAdmin2');
			if(!($actualPassword === null || $newPassword1 === null || $newPassword2 === null))
			{
				//si la confirmation a échouée
				if($newPassword1 != $newPassword2)
				{
					$user->getMessageClient()->addErreur(self::PASSWORDS_NOT_MATCH);
				}
				else
				{
					// On charge le fichier de configuration
					$config = $this->getApp()->getConfig();
				
					//si le mot de passe actuel a mal été entré					
					if( !password_verify($actualPassword, $userSession->getPasswordAdminUtilisateur() ))
					{
						$user->getMessageClient()->addErreur(self::PROFILE_OLD_ADMIN_PASSWORD_INCORRECT);
					}
					else
					{				
						//si le nouveau mot de passe admin est le même que le mot de passe utilisateur
						if( !password_verify( $newPassword1, $userSession->getPasswordUtilisateur()))
						{
							if( $this->validPassword($newPassword1)){
								$newPassword=$this->protectPassword($newPassword1);
								$userSession->setPasswordAdminUtilisateur($newPassword);
							} else {
								$user->getMessageClient()->addErreur(self::PASSWORD_NOT_VALID);
							}
						}else{
							$user->getMessageClient()->addErreur(self::PROFILE_ADMIN_PASSWORD_DIFFERENT_FROM_USER);
							$response->redirect('/Profile/');
						}
							
							$erreurs = $userSession->getErreurs();
							if(count($erreurs) != 0)
							{
								$user->getMessageClient()->addErreur($erreurs);
							}
							else
							{
								$user->getMessageClient()->addReussite(self::PROFILE_CHANGE_ADMIN_PASSWORD);

								$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
								$managerUtilisateur->saveUtilisateur($userSession);
								
								$user->setAttribute('userSession', serialize($userSession));
							}
					}
				}
			}
			$response->redirect('/Profile/');
		}else{
			$user->getMessageClient()->addErreur(self::DENY_ACCESS_PAGE);
			$response->redirect('/Profile/');
		}		
	}
}
