<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Ce controleur permet d'afficher, moditier ou crée de nouveaux		  |
// | utilisateurs                                                         |
// +----------------------------------------------------------------------+
// | Auteurs : Guénaël DEQUEKER <dequekerguenael@noolib.com> 		      |
// | 		   Steve DESPRES    <despressteve@noolib.com> 		     	  |
// | 		   Mathieu COLLETTE    <collettemathieu@noolib.com>      	  |
// +----------------------------------------------------------------------+

/**
 * @name: controleur des options de l'utilisateur pour le Frontend
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
		
		if(!$user->getAttribute('isAdmin'))
		{
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			// On récupère la requête utilisateur
			$request = $this->getApp()->getHTTPRequest();
			
			//On récupère le mail passé en POST
			$mailUtilisateur = $request->getPostData('mailUtilisateur');
			//ou l'id passé en get
			$idUtilisateur = $request->getGetData('idUtilisateur');
			
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
					$statutAAfficher.='<option value="'.$statut->getIdStatut().'">'.$statut->getNomStatut().'</option>';
				}
			}
			$this->page->addVar('statutAAfficher', $statutAAfficher);
			
			//On appelle le manager des Utilisateurs
			$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
			
			$this->page->addVar('utilisateurs', $managerUtilisateur->getAllUtilisateurs());
			
			if($mailUtilisateur != null || $idUtilisateur != null)
			{
				//on recupere l'utilisateur avec toutes ses données
				$utilisateurAAdministrer = $managerUtilisateur->getUtilisateurByMail($mailUtilisateur);
				
				//is l'utilisateur a été passé en post avec son mail
				if($utilisateurAAdministrer)
				{
					//on passe l'id en post et on redirige
					$response = $this->app->getHTTPResponse();
					$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$utilisateurAAdministrer->getIdUtilisateur());
				}
				else
				{
					$utilisateurAAdministrer = $managerUtilisateur->getUtilisateurByIdWithAllData($idUtilisateur);
					//on vérifie si on a bien récupéré un utilisateur
					if(!$utilisateurAAdministrer)
					{
						//on fait s'écrire l'erreur
						$user->getMessageClient()->addErreur('L\'utilisateur demandé n\'existe pas.');
						
						// si non, on procède à la redirection
						$response = $this->app->getHTTPResponse();
						$response->redirect('/PourAdminSeulement/Utilisateurs/');
					}
					else
					{
						$userSession = unserialize($user->getAttribute('userSession'));
						//si l'admin tente de s'auto-administrer
						if($utilisateurAAdministrer->getIdUtilisateur() == $userSession->getIdUtilisateur())
						{
							//on fait s'écrire l'erreur
							$user->getMessageClient()->addErreur('Vous ne pouvez pas vous administrer vous-même.');
							
							// si non, on procède à la redirection
							$response = $this->app->getHTTPResponse();
							$response->redirect('/PourAdminSeulement/Utilisateurs/');
						}
						else{
							//on ajoute les variables nécéssaire à la page
							$this->page->addVar('utilisateurAAdministrer', $utilisateurAAdministrer);
						}
					}
				}
			}
		}
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
		$listeEquipesAAfficher = $this->executeGetAllTeams($request)
		
		if($listeEquipesAAfficher){
			// On ajoute la variable à la page
			$this->page->addVar('listeEquipesAAfficher', $listeEquipesAAfficher);
		}
	}

	
	public function executeCreerUtilisateur($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$motDePasseFormulaire = $request->getPostData('motDePasseFormulaire');
			$motDePasseConfirme = $request->getPostData('motDePasseConfirme');
			
			if($motDePasseFormulaire == null || $motDePasseFormulaire != $motDePasseConfirme)
			{
				$user->getMessageClient()->addErreur('Le mot de passe a mal été confirmé.');
				$response->redirect('/PourAdminSeulement/Utilisateurs/');
			}
			else
			{
				// On protège le mot de passe
				$config = $this->getApp()->getConfig();
				if( $this->validPassword($motDePasseFormulaire)){
					$passwordUser=$this->protectPassword($motDePasseFormulaire);
								
	   		    }
								
				$managerStatutUtilisateur = $this->getManagers()->getManagerOf('StatutUtilisateur');
				
				$newUser = new \Library\Entities\Utilisateur(array(
					'nomUtilisateur' => trim($request->getPostData('nom')),
					'prenomUtilisateur' => trim($request->getPostData('prenom')),
					'mailUtilisateur' => trim($request->getPostData('adresseMail')),
					'passwordUtilisateur' => $passwordUser,
					'statut' => $managerStatutUtilisateur->getStatutById($request->getPostData('newStatut')),
					'urlPhotoUtilisateur' => $config->getVar('divers', 'divers', 'photoProfilDefault'),
					'descriptionUtilisateur' => '',
					'lienPagePersoUtilisateur' => '',
					'passwordAdminUtilisateur' => '', 
					'etatBanniUtilisateur' => false, 
					'urlBackgroundUtilisateur' => $this->getApp()->getConfig()->getVar('divers', 'divers', 'defaultUserBackground'),
					'utilisateurActive' => false
				));
				
				if(sizeof($newUser->getErreurs()) != 0)
				{
					$user->getMessageClient()->addErreur($newUser->getErreurs());
					$response->redirect('/PourAdminSeulement/Utilisateurs/');
				}
				else
				{

					// On créé la variable fixe de l'utilisateur basée sur son mail
					// On créé un nombre aléatoire
					$nombre = rand(0,10000000);
					$variableFixeUtilisateur = $this->cleanFileName($newUser->getMailUtilisateur()).$nombre;
					$variableFixeUtilisateur = substr($variableFixeUtilisateur, 0, 31); // Pour pouvoir créer des utilisateurs linux
					// On charge le fichier de configuration
					$repertoireDestination = $config->getVar('divers','divers','safeWorkSpace');
					$newUser->hydrate(array(
						'variableFixeUtilisateur' => $variableFixeUtilisateur,
						'workSpaceFolderUtilisateur' => $repertoireDestination.$variableFixeUtilisateur
					));

					if(sizeof($newUser->getErreurs()) == 0) {

						$managerUser = $this->getManagers()->getManagerOf('Utilisateur');
						
						if($managerUser->getUtilisateurByMail($newUser->getMailUtilisateur()) instanceof \Library\Entities\Utilisateur){
							$user->getMessageClient()->addErreur('Cette adresse électronique est déjà prise par un autre utilisateur.');
							$response->redirect('/PourAdminSeulement/Utilisateurs/');
						}
						else{
							$managerUser->addUtilisateur($newUser);
							$user->getMessageClient()->addReussite('L\'utilisateur a bien été créé.');

							// Execution du script Bash pour créer un utilisateur linux
							// On execute l'objet Exec
							$exec = $this->getApp()->getExec();
							$exec->createUser($newUser);
							
							$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$managerUser->getUtilisateurByMail($newUser->getMailUtilisateur())->getIdUtilisateur());

						}
					}else{
						// On ajoute la variable d'erreurs à la page
						$user->getMessageClient()->addErreur($newUser->getErreurs());
					}
				}
			}
		}
	}
	
	public function executeUtilisateurEnAdministrateur($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		// On vérifie que l'utilisateur est connecté
		if(!$user->getAttribute('isAdmin'))
		{
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$newPassword1 = $request->getPostData('futurPasswordAdmin1');
			$newPassword2 = $request->getPostData('futurPasswordAdmin2');
			
			$idUtilisateurAAdministrer = $request->getPostData('idUtilisateur');
			//On appelle le manager des Utilisateurs
			$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
			//on recuper l'utilisateur à administrer
			$utilisateurAAdministrer = $managerUtilisateur->getUtilisateurByIdWithAllData($idUtilisateurAAdministrer);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($newPassword1 === null || $newPassword2 === null || $utilisateurAAdministrer === false)
			{
				// si non, on procède à la redirection
				$response->redirect('/PourAdminSeulement/');
			}
			else
			{
				$userSession = unserialize($user->getAttribute('userSession'));
				//si l'admin tente de s'auto-administrer
				if($utilisateurAAdministrer->getIdUtilisateur() == $userSession->getIdUtilisateur())
				{
					//on fait s'écrire l'erreur
					$user->getMessageClient()->addErreur('Vous ne pouvez pas vous administrer vous-même.');
					
					// si non, on procède à la redirection
					$response = $this->app->getHTTPResponse();
					$response->redirect('/PourAdminSeulement/Utilisateurs/');
				}
				else
				{
					//si la confirmation a échouée
					if($newPassword1 != $newPassword2)
					{
						$user->getMessageClient()->addErreur('Le mot de passe a mal été confirmé.');
					}
					else
					{
						// On charge le fichier de configuration
						$config = $this->getApp()->getConfig();
						
						//si le nouveau mot de passe admin est le même que le mot de passe utilisateur					
						if( password_verify( $newPassword1, $utilisateurAAdministrer->getPasswordUtilisateur())) 
						{
							$user->getMessageClient()->addErreur('Le mot de passe administrateur doit être différent du mot de passe utilisateur.');
						}
						else
						{		
							//Si le mot de passe à le bon format (regex)
							if( $this->validPassword($newPassword1)) {
								//on protege le mot de passe
								$newPassword = $this->protectPassword($newPassword1);
								//on assigne le mot de pass admin à l'utilisateur
								$utilisateurAAdministrer->setPasswordAdminUtilisateur($newPassword);

								$erreurs = $utilisateurAAdministrer->getErreurs();
								//si il y a une ou des erreurs
								if(count($erreurs) != 0)
								{
									$user->getMessageClient()->addErreur($erreurs);
								}
								else
								{
									//on met la base à jour
									$managerUtilisateur->saveUtilisateur($utilisateurAAdministrer);
									$user->getMessageClient()->addReussite('L\'utilisateur est à présent administrateur.');
								}

								} else {
									$user->getMessageClient()->addErreur('Votre mot de passe doit contenir au moins 8 caractères, 1 majuscule, 1 minuscule et 1 chiffre.');
								}
						}
					}
				}
				//on redirige vers la page d'administration de l'utilisateur
				$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$utilisateurAAdministrer->getIdUtilisateur());
			}
		}
	}
	
	public function executeBannirUtilisateur($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		// On vérifie que l'utilisateur est connecté
		if(!$user->getAttribute('isAdmin'))
		{
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idUtilisateurAAdministrer = $request->getPostData('idUtilisateur');
			//On appelle le manager des Utilisateurs
			$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
			//on recuper l'utilisateur à administrer
			$utilisateurAAdministrer = $managerUtilisateur->getUtilisateurByIdWithAllData($idUtilisateurAAdministrer);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($utilisateurAAdministrer === false)
			{
				// si non, on procède à la redirection
				$response->redirect('/PourAdminSeulement/');
			}
			else
			{
				$userSession = unserialize($user->getAttribute('userSession'));
				//si l'admin tente de s'auto-administrer
				if($utilisateurAAdministrer->getIdUtilisateur() == $userSession->getIdUtilisateur())
				{
					//on fait s'écrire l'erreur
					$user->getMessageClient()->addErreur('Vous ne pouvez pas vous administrer vous-même.');
					
					// si non, on procède à la redirection
					$response = $this->app->getHTTPResponse();
					$response->redirect('/PourAdminSeulement/Utilisateurs/');
				}
				else
				{
					//on banni l'utilisateur
					$utilisateurAAdministrer->setEtatBanniUtilisateur(true);
					
					$erreurs = $utilisateurAAdministrer->getErreurs();
					//si il y a une ou des erreurs
					if(count($erreurs) != 0)
					{
						$user->getMessageClient()->addErreur($erreurs);
					}
					else
					{
						//on met la base à jour
						$managerUtilisateur->saveUtilisateur($utilisateurAAdministrer);
						$user->getMessageClient()->addReussite('L\'utilisateur est à présent banni.');
					}
					//on redirige vers la page d'administration de l'utilisateur
					$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$utilisateurAAdministrer->getIdUtilisateur());
				}
			}
		}
	}
	
	public function executeGracierUtilisateur($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idUtilisateurAAdministrer = $request->getPostData('idUtilisateur');
			//On appelle le manager des Utilisateurs
			$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
			//on recuper l'utilisateur à administrer
			$utilisateurAAdministrer = $managerUtilisateur->getUtilisateurByIdWithAllData($idUtilisateurAAdministrer);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($utilisateurAAdministrer === false)
			{
				// si non, on procède à la redirection
				$response->redirect('/PourAdminSeulement/');
			}
			else
			{
				$userSession = unserialize($user->getAttribute('userSession'));
				//si l'admin tente de s'auto-administrer
				if($utilisateurAAdministrer->getIdUtilisateur() == $userSession->getIdUtilisateur())
				{
					//on fait s'écrire l'erreur
					$user->getMessageClient()->addErreur('Vous ne pouvez pas vous administrer vous-même.');
					
					// si non, on procède à la redirection
					$response = $this->app->getHTTPResponse();
					$response->redirect('/PourAdminSeulement/Utilisateurs/');
				}
				else
				{
					//on banni l'utilisateur
					$utilisateurAAdministrer->setEtatBanniUtilisateur(false);
					
					$erreurs = $utilisateurAAdministrer->getErreurs();
					//si il y a une ou des erreurs
					if(count($erreurs) != 0)
					{
						$user->getMessageClient()->addErreur($erreurs);
					}
					else
					{
						//on met la base à jour
						$managerUtilisateur->saveUtilisateur($utilisateurAAdministrer);
						$user->getMessageClient()->addReussite('L\'utilisateur est à présent grâcié.');
					}
					//on redirige vers la page d'administration de l'utilisateur
					$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$utilisateurAAdministrer->getIdUtilisateur());
				}
			}
		}
	}
	
	public function executeSupprimerUtilisateur($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idUtilisateurAAdministrer = $request->getPostData('idUtilisateur');
			//On appelle le manager des Utilisateurs
			$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
			//on recuper l'utilisateur à administrer
			$utilisateurAAdministrer = $managerUtilisateur->getUtilisateurByIdWithAllData($idUtilisateurAAdministrer);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($utilisateurAAdministrer === false)
			{
				// si non, on procède à la redirection
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				$response->redirect('/PourAdminSeulement/');
			}
			else
			{
				$reponse = $this->supprimerUtilisateur($utilisateurAAdministrer);
				if($reponse === true){
					$user->getMessageClient()->addReussite('L\'utilisateur a bien été supprimé.');

					// Execution du script Bash pour supprimer un utilisateur linux
					// On execute l'objet Exec
					$exec = $this->getApp()->getExec();
					$exec->delUser($utilisateurAAdministrer);
				}else{
					$user->getMessageClient()->addErreur($reponse);
				}
				$response->redirect('/PourAdminSeulement/Utilisateurs/');
			}
		}
	}
	
	public function executeChangerPhotoProfil($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idUtilisateurAAdministrer = $request->getPostData('idUtilisateur');
			//On appelle le manager des Utilisateurs
			$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
			//on recuper l'utilisateur à administrer
			$utilisateurAAdministrer = $managerUtilisateur->getUtilisateurByIdWithAllData($idUtilisateurAAdministrer);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($utilisateurAAdministrer === false)
			{
				// si non, on procède à la redirection
				$response->redirect('/PourAdminSeulement/');
			}
			else
			{
				$this->changerPhotoProfil($utilisateurAAdministrer);
				
				//on redirige vers la page d'administration de l'utilisateur
				$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$utilisateurAAdministrer->getIdUtilisateur());
			}
		}
	}
	
	public function executeAjouterEquipe($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idUtilisateurAAdministrer = $request->getPostData('idUtilisateur');
			//on recuper l'utilisateur à administrer
			$utilisateurAAdministrer = $this->getManagers()->getManagerOf('Utilisateur')->getUtilisateurByIdWithAllData($idUtilisateurAAdministrer);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($utilisateurAAdministrer === false)
			{
				// si non, on procède à la redirection
				$response->redirect('/PourAdminSeulement/');
			}
			else
			{
				$idEquipe = $request->getPostData('selectedTeam');
				
				$this->ajouterEquipe($utilisateurAAdministrer, $idEquipe);
				
				//on redirige vers la page d'administration de l'utilisateur
				$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$utilisateurAAdministrer->getIdUtilisateur());
			}
		}
	}
	
	public function executeRemoveEquipe($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idUtilisateurAAdministrer = $request->getPostData('idUtilisateur');
			//on recuper l'utilisateur à administrer
			$utilisateurAAdministrer = $this->getManagers()->getManagerOf('Utilisateur')->getUtilisateurByIdWithAllData($idUtilisateurAAdministrer);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($utilisateurAAdministrer === false)
			{
				// si non, on procède à la redirection
				$response->redirect('/PourAdminSeulement/');
			}
			else
			{
				$idEquipe = $request->getPostData('idEquipe');
				
				$this->removeEquipe($utilisateurAAdministrer, $idEquipe);
				
				//on redirige vers la page d'administration de l'utilisateur
				$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$utilisateurAAdministrer->getIdUtilisateur());
			}
		}
	}
	
	public function executeChangerPrenom($request)
	{
		$response = $this->app->getHTTPResponse();
		$user = $this->app->getUser();
		$idUtilisateurAAdministrer = $request->getPostData('idUtilisateur');
		//on recuper l'utilisateur à administrer
		$utilisateurAAdministrer = $this->getManagers()->getManagerOf('Utilisateur')->getUtilisateurByIdWithAllData($idUtilisateurAAdministrer);
				
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			$prenom = $request->getPostData('prenom');
			if($prenom === null)
			{
				// si non, on procède à la redirection
				$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$utilisateurAAdministrer->getIdUtilisateur());
			}
			else
			{
				$utilisateurAAdministrer->setPrenomUtilisateur($prenom);
				
				//on vérifie qu'il n'y a pas d'erreur
				$erreurs = $utilisateurAAdministrer->getErreurs();
				if(count($erreurs) == 0)
				{
					//on met la bdd à jour
					$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
					$managerUtilisateur->saveUtilisateur($utilisateurAAdministrer);
					$user->getMessageClient()->addReussite('Le prénom a bien été modifié.');
				}
				else
				{
					//on met les erreur dans le flash
					$user->getMessageClient()->addErreur($erreurs);
				}
			}
		}
		
		$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$utilisateurAAdministrer->getIdUtilisateur());
	}
	
	public function executeChangerNom($request)
	{
		$response = $this->app->getHTTPResponse();
		$user = $this->app->getUser();
		$idUtilisateurAAdministrer = $request->getPostData('idUtilisateur');
		//on recuper l'utilisateur à administrer
		$utilisateurAAdministrer = $this->getManagers()->getManagerOf('Utilisateur')->getUtilisateurByIdWithAllData($idUtilisateurAAdministrer);
				
		if(!$user->getAttribute('isAdmin'))
		{
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			$nom = $request->getPostData('nom');
			if($nom === null)
			{
				// si non, on procède à la redirection
				$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$utilisateurAAdministrer->getIdUtilisateur());
			}
			else
			{
				$utilisateurAAdministrer->setNomUtilisateur($nom);
				
				// Vérifier qu'il n'y a pas d'erreur
				$erreurs = $utilisateurAAdministrer->getErreurs();
				if(count($erreurs) == 0)
				{
					$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
					$managerUtilisateur->saveUtilisateur($utilisateurAAdministrer);
					$user->getMessageClient()->addReussite('Le nom a bien été modifié.');
				}
				else
				{
					$user->getMessageClient()->addErreur($erreurs);
				}
			}
		}
		
		$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$utilisateurAAdministrer->getIdUtilisateur());
	}
	
	
	
	public function executeChangerEmail($request)
	{
		$response = $this->app->getHTTPResponse();
		$user = $this->app->getUser();
		$idUtilisateurAAdministrer = $request->getPostData('idUtilisateur');
		//on recuper l'utilisateur à administrer
		$utilisateurAAdministrer = $this->getManagers()->getManagerOf('Utilisateur')->getUtilisateurByIdWithAllData($idUtilisateurAAdministrer);
				
		if(!$user->getAttribute('isAdmin'))
		{
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			$email = $request->getPostData('email');
			if($email === null)
			{
				// si non, on procède à la redirection
				$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$utilisateurAAdministrer->getIdUtilisateur());
			}
			else
			{
				$utilisateurAAdministrer->setMailUtilisateur($email);
				
				// Vérifier qu'il n'y a pas d'erreur
				$erreurs = $utilisateurAAdministrer->getErreurs();
				if(count($erreurs) == 0)
				{
					$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
					$managerUtilisateur->saveUtilisateur($utilisateurAAdministrer);
					$user->getMessageClient()->addReussite('L\'adresse électronique a bien été modifiée.');
				}
				else
				{
					$user->getMessageClient()->addErreur($erreurs);
				}
			}
		}
		
		$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$utilisateurAAdministrer->getIdUtilisateur());
	}
	
	
	public function executeChangerPagePerso($request)
	{
		$response = $this->app->getHTTPResponse();
		$user = $this->app->getUser();
		$idUtilisateurAAdministrer = $request->getPostData('idUtilisateur');
		//on recuper l'utilisateur à administrer
		$utilisateurAAdministrer = $this->getManagers()->getManagerOf('Utilisateur')->getUtilisateurByIdWithAllData($idUtilisateurAAdministrer);
				
		if(!$user->getAttribute('isAdmin'))
		{
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			$pagePerso = $request->getPostData('pagePerso');
			if($pagePerso === null)
			{
				// si non, on procède à la redirection
				$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$utilisateurAAdministrer->getIdUtilisateur());
			}
			else
			{
				$utilisateurAAdministrer->setLienPagePersoUtilisateur($pagePerso);
				
				// Vérifier qu'il n'y a pas d'erreur
				$erreurs = $utilisateurAAdministrer->getErreurs();
				if(count($erreurs) == 0)
				{
					$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
					$managerUtilisateur->saveUtilisateur($utilisateurAAdministrer);
					$user->getMessageClient()->addReussite('La page personnelle de l\'utilisateur a bien été modifiée.');
				}
				else
				{
					$user->getMessageClient()->addErreur($erreurs);
				}
			}
		}
		
		$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$utilisateurAAdministrer->getIdUtilisateur());
	}
	
	
	
	public function executeChangerDescription($request)
	{
		$response = $this->app->getHTTPResponse();
		$user = $this->app->getUser();
				
		if(!$user->getAttribute('isAdmin'))
		{
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idUtilisateurAAdministrer = $request->getPostData('idUtilisateur');
			//on recuper l'utilisateur à administrer
			$utilisateurAAdministrer = $this->getManagers()->getManagerOf('Utilisateur')->getUtilisateurByIdWithAllData($idUtilisateurAAdministrer);
			
			if(!$utilisateurAAdministrer)
			{
				$response->redirect('/PourAdminSeulement/Utilisateurs/');
			}
			else
			{
				//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
				$description = $request->getPostData('description');
				if($description === null)
				{
					// si non, on procède à la redirection
					$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$utilisateurAAdministrer->getIdUtilisateur());
				}
				else
				{
					$utilisateurAAdministrer->setDescriptionUtilisateur($description);
					
					// Vérifier qu'il n'y a pas d'erreur
					$erreurs = $utilisateurAAdministrer->getErreurs();
					if(count($erreurs) == 0)
					{
						$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
						$managerUtilisateur->saveUtilisateur($utilisateurAAdministrer);
						$user->getMessageClient()->addReussite('La description de l\'utilisateur a bien été modifiée.');
					}
					else
					{
						$user->getMessageClient()->addErreur($erreurs);
					}
				}
			}
		}
		
		$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$utilisateurAAdministrer->getIdUtilisateur());
	}
	
	public function executeChangerStatut($request)
	{
		$response = $this->app->getHTTPResponse();
		$user = $this->app->getUser();
				
		if(!$user->getAttribute('isAdmin'))
		{
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idUtilisateurAAdministrer = $request->getPostData('idUtilisateur');
			//on recuper l'utilisateur à administrer
			$utilisateurAAdministrer = $this->getManagers()->getManagerOf('Utilisateur')->getUtilisateurByIdWithAllData($idUtilisateurAAdministrer);
			
			if(!$utilisateurAAdministrer)
			{
				$response->redirect('/PourAdminSeulement/Utilisateurs/');
			}
			else
			{
				$newStatut = $request->getPostData('newStatut');
				// !=1 car correspond au statu 'Aucun'
				if(isset($newStatut) && $newStatut != 1)
				{
					$managerStatut = $this->getManagers()->getManagerOf('StatutUtilisateur');
					$statut = $managerStatut->getStatutById($newStatut);
					
					$utilisateurAAdministrer->setStatut($statut);
					
					if(count($utilisateurAAdministrer->getErreurs()) ==0)
					{
						$this->getManagers()->getManagerOf('Utilisateur')->saveUtilisateur($utilisateurAAdministrer);
						$user->getMessageClient()->addReussite('Le statut utilisateur a bien été modifié.');
					}else{
						$user->getMessageClient()->addErreur($utilisateurAAdministrer->getErreurs());
					}
				}
				$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$utilisateurAAdministrer->getIdUtilisateur());
			}
		}
	}
	
	public function executeChangerPasswod($request)
	{
		$response = $this->app->getHTTPResponse();
		$user = $this->app->getUser();
				
		if(!$user->getAttribute('isAdmin'))
		{
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idUtilisateurAAdministrer = $request->getPostData('idUtilisateur');
			//on recuper l'utilisateur à administrer
			$utilisateurAAdministrer = $this->getManagers()->getManagerOf('Utilisateur')->getUtilisateurByIdWithAllData($idUtilisateurAAdministrer);
			
			if(!$utilisateurAAdministrer)
			{
				$response->redirect('/PourAdminSeulement/Utilisateurs/');
			}
			else
			{
				$newPassword1 = $request->getPostData('newPassword1');
				$newPassword2 = $request->getPostData('newPassword2');
				if($newPassword1 != null && $newPassword2 != null)
				{
					if($newPassword1 != $newPassword2)
					{
						$user->getMessageClient()->addErreur('Le mot de passe a mal été confirmé.');
					}
					else
					{
						//Si la forme du mot de passe est correcte
						if($this->validPassword($newPassword1)){ 
							//on protege le mot de passe
							$newProtectedPassword = $this->protectPassword($newPassword1);
							//on assigne le mot de passe à l'utilisateur
							$utilisateurAAdministrer->setPasswordUtilisateur($newProtectedPassword);
							$erreurs = $utilisateurAAdministrer->getErreurs();
							if(count($erreurs) != 0)
							{
								$user->getMessageClient()->addErreur($erreurs);
							}
							else
							{
								$this->getManagers()->getManagerOf('Utilisateur')->saveUtilisateur($utilisateurAAdministrer);
								$user->getMessageClient()->addReussite('Le mot de passe a bien été modifié.');

							}

						} else { 
							$user->getMessageClient()->addErreur('Votre mot de passe doit contenir au moins 8 caractères, 1 majuscule, 1 minuscule et 1 chiffre.');
						}	
					}
				}else{
					$user->getMessageClient()->addErreur('Le nouveau mot de passe ne peut pas être vide.');
				}
				$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$utilisateurAAdministrer->getIdUtilisateur());
			}
		}
	}
	
	

	public function executeActiveUtilisateur($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		// On vérifie que l'utilisateur est connecté
		if(!$user->getAttribute('isAdmin'))
		{
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idUtilisateurAAdministrer = $request->getPostData('idUtilisateur');
			//On appelle le manager des Utilisateurs
			$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
			//on recuper l'utilisateur à administrer
			$utilisateurAAdministrer = $managerUtilisateur->getUtilisateurByIdWithAllData($idUtilisateurAAdministrer);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($utilisateurAAdministrer === false)
			{
				// si non, on procède à la redirection
				$response->redirect('/PourAdminSeulement/');
			}
			else
			{
				$userSession = unserialize($user->getAttribute('userSession'));
				//si l'admin tente de s'auto-administrer
				if($utilisateurAAdministrer->getIdUtilisateur() == $userSession->getIdUtilisateur())
				{
					//on fait s'écrire l'erreur
					$user->getMessageClient()->addErreur('Vous ne pouvez pas vous administrer vous-même.');
					
					// si non, on procède à la redirection
					$response = $this->app->getHTTPResponse();
					$response->redirect('/PourAdminSeulement/Utilisateurs/');
				}
				else
				{
					//on active l'utilisateur
					$utilisateurAAdministrer->setUtilisateurActive(true);
					
					$erreurs = $utilisateurAAdministrer->getErreurs();
					//si il y a une ou des erreurs
					if(count($erreurs) != 0)
					{
						$user->getMessageClient()->addErreur($erreurs);
					}
					else
					{
						//on met la base à jour
						$managerUtilisateur->saveUtilisateur($utilisateurAAdministrer);
						$user->getMessageClient()->addReussite('L\'utilisateur est à présent activé.');

						// Execution du script Bash pour créer un utilisateur linux
						// On execute l'objet Exec
						$exec = $this->getApp()->getExec();
						$exec->createUser($utilisateurAAdministrer);
					}
					//on redirige vers la page d'administration de l'utilisateur
					$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$utilisateurAAdministrer->getIdUtilisateur());
				}
			}
		}
	}
	
	public function executeDesactiveUtilisateur($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idUtilisateurAAdministrer = $request->getPostData('idUtilisateur');
			//On appelle le manager des Utilisateurs
			$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
			//on recuper l'utilisateur à administrer
			$utilisateurAAdministrer = $managerUtilisateur->getUtilisateurByIdWithAllData($idUtilisateurAAdministrer);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($utilisateurAAdministrer === false)
			{
				// si non, on procède à la redirection
				$response->redirect('/PourAdminSeulement/');
			}
			else
			{
				$userSession = unserialize($user->getAttribute('userSession'));
				//si l'admin tente de s'auto-administrer
				if($utilisateurAAdministrer->getIdUtilisateur() == $userSession->getIdUtilisateur())
				{
					//on fait s'écrire l'erreur
					$user->getMessageClient()->addErreur('Vous ne pouvez pas vous administrer vous-même.');
					
					// si non, on procède à la redirection
					$response = $this->app->getHTTPResponse();
					$response->redirect('/PourAdminSeulement/Utilisateurs/');
				}
				else
				{
					//on banni l'utilisateur
					$utilisateurAAdministrer->setUtilisateurActive(false);
					
					$erreurs = $utilisateurAAdministrer->getErreurs();
					//si il y a une ou des erreurs
					if(count($erreurs) != 0)
					{
						$user->getMessageClient()->addErreur($erreurs);
					}
					else
					{
						//on met la base à jour
						$managerUtilisateur->saveUtilisateur($utilisateurAAdministrer);
						$user->getMessageClient()->addReussite('L\'utilisateur est à présent désactivé.');
					}
					//on redirige vers la page d'administration de l'utilisateur
					$response->redirect('/PourAdminSeulement/Utilisateurs/idUtilisateur='.$utilisateurAAdministrer->getIdUtilisateur());
				}
			}
		}
	}
	

	
}
