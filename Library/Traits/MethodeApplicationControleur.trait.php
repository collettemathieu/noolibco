<?php

namespace Library\Traits;

// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Trait pour les methodes des Applications			                  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@noolib.com>	     	  |
// | Auteurs : Steve DESPRES	<despressteve@noolib.com>	   		  	  |
// +----------------------------------------------------------------------+

/**
 * @access: public
 * @version: 1
 */	

trait MethodeApplicationControleur
{
	/**
	* Permet de mettre à jour le dock des applications
	**/
	
	private function updateDockApplication($application){
		if($application instanceof \Library\Entities\Application){
			// On récupère l'id de l'application
			$idApplication = $application->getIdApplication();
			
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On récupère l'utilisateur en session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On vérifie que l'application est présente dans le dock (cad dans les favoris de l'utilisateur)
			$appIsInDock = false;
			foreach($userSession->getFavoris() as $key => $applicationInDock){
				if($applicationInDock->getIdApplication() === $idApplication){
					$appIsInDock = true;
					$index = $key;
					break;
				}
			}

			// Si l'application est bien présente dans le dock, on la met à jour
			if($appIsInDock){
				$userSession->updateFavori($application, $index);
				// On sauvegarde la session
				$user->setAttribute('userSession', serialize($userSession));
			}

		}
	}

	/**
	* Permet de supprimer une application du dock lorsque l'application est supprimer définitivement
	**/
	private function removeApplicationFromDock($application){
		if($application instanceof \Library\Entities\Application){
			// On récupère l'id de l'application
			$idApplication = $application->getIdApplication();
		
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On récupère l'utilisateur en session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On vérifie que l'application est présente dans le dock (cad dans les favoris de l'utilisateur)
			$appIsInDock = false;
			foreach($userSession->getFavoris() as $key => $applicationInDock){
				if($applicationInDock->getIdApplication() === $idApplication){
					$appIsInDock = true;
					$index = $key;
					break;
				}
			}

			// Si l'application est bien présente dans le dock, on la supprime
			if($appIsInDock){
				$userSession->removeFavori($application, $index);
				// On sauvegarde la session
				$user->setAttribute('userSession', serialize($userSession));
			}

		}
	}
	
	/**
	* Permet de supprimer une application définitivement
	**/
	private function executeDeleteApplication($request){
	
		// On récupère l'utilisateur système
		$user = $this->app->getUser();
		
		//On récupère l'id de la publication en GET ou en POST
		if($request->getGetData('idApplication')){
		
			$idApplication = $request->getGetData('idApplication');
			
		}else{
			//Sinon, si Id en post
			if($request->getPostData('idApplication')){
				// On récupère l'id de la publication que le client veut ajouter au Dock
				$idApplication = (int) $request->getPostData('idApplication');
			}else{
				return false;
			}
		
		}
		
		// On récupère l'id de l'application que le client veut ajouter au Dock
		$idApplication = (int) $request->getPostData('idApplication');

		// On appelle le manager des applications
		$managerApplication = $this->getManagers()->getManagerOf('Application');
		// On récupère l'application en question si elle existe avec tous ses attributs emplis
		$application = $managerApplication->getApplicationByIdWithAllParameters($idApplication);

		if ($application != false){

			// On supprime tous les fichiers sources et images
			$file = $this->getApp()->getFileDelete();

			if($file->deleteApplicationFile($application)){
				// On supprime l'application de la BDD
				$managerApplication->deleteApplication($application);
				$user->getMessageClient()->addReussite('Your application is being removed...');

				// On met à jour le dock des applications
				$this->removeApplicationFromDock($application);

				// On met à jour la session de l'utilisateur
				$utilisateur = unserialize($user->getAttribute('userSession'));
				$utilisateur->removeApplication($application);

				// On sauvegarde la session utilisateur
				$user->setAttribute('userSession', serialize($utilisateur));

				// Execution du script Bash pour supprimer une application
				// On execute l'objet Exec
				$exec = $this->getApp()->getExec();
				$exec->delApplication($application);
				
				return true;
				
			}else{
				// On retourne le tableau d'erreurs
				$user->getMessageClient()->addErreur('An error has occurred during the deleting of the application.');
				return false;
			}
		}else{
			$user->getMessageClient()->addErreur('The application you want to delete does exist.');
			return false;
		}
	
	}

	/**
	* Permet de récupérer les types des publications
	**/
	private function getTypePublications(){
		// On récupère la liste des types de publication
		// On appelle le manager des types de publication
		$managerTypePublication = $this->getManagers()->getManagerOf('TypePublication');
		$typesPublication = $managerTypePublication->getAllTypePublications();
		// On créé le tableau des types de publication
		$typeAAfficher = array();
		foreach($typesPublication as $id=>$type){
			$typePublication = array(
				'id' => $id,
				'nameType' => $type->getNomTypePublication()
				);
			array_push($typeAAfficher, $typePublication);
		}
		// On retourne le résultat
		return $typeAAfficher;
	}


	/**
	* Permet de récupérer les publications d'une application
	**/
	private function getPublications($application){
		if($application instanceof \Library\Entities\Application){
			$publicationsApplication = array();
			foreach($application->getPublications() as $publication){
				$premierAuteur = $publication->getAuteurs()[0];
				array_push($publicationsApplication, array(
					'idPublication' => $publication->getIdPublication(),
					'idApplication' => $application->getIdApplication(),
					'titrePublication' => $publication->getTitrePublication(),
					'auteursPublication' => $premierAuteur->getPrenomAuteur().' '.$premierAuteur->getNomAuteur().' et al',
					'journalPublication' => $publication->getJournalPublication(),
					'anneePublication' => $publication->getAnneePublication(),
					'typePublication' => $publication->getTypePublication()->getNomTypePublication(),
					'urlPublication' => $publication->getUrlPublication()
					));
			}
			return $publicationsApplication;
		}else{
			return null;
		}
	}


	/**
	* Permet de supprimer une publication définitivement
	**/
	private function executeDeletePublication($request){
	
		// On récupère l'utilisateur système
		$user = $this->app->getUser();

		//Si id en Get
		if($request->getGetData('idPublication')){
			$idPublication = $request->getGetData('idPublication');
		}else{
			//Sinon, si Id en post
			if($request->getPostData('idPublication')){
				// On récupère l'id de la publication que le client veut ajouter au Dock
				$idPublication = (int) $request->getPostData('idPublication');
			}else{
				$user->getMessageClient()->addErreur('This link is not valid.');
				return false;
			}
		}

		// On appelle le manager des publications
		$managerPublication = $this->getManagers()->getManagerOf('Publication');
		// On récupère la publication en question 
		$publication = $managerPublication->getPublicationById($idPublication);
		
		//Si la publication existe
		if ($publication){
			// On récupère les applications en lien avec la publication
			$managerPublication->putApplicationsInPublication($publication);

			// On appelle le manager des statuts
			$managerStatut = $this->getManagers()->getManagerOf('StatutApplication');

			// Si l'application ne possède plus de publications la validant après le retrait de celle-ci,
			// on change le statut de l'application
			foreach($publication->getApplications() as $application){
				if(count($application->getPublications()) < 1 && $application->getStatut()->getIdStatut() === 3){
					// On met à jour le statut de l'application
					$application->hydrate(array(
						'statut' => $managerStatut->getStatutByNom('Not validated')
					));
					// On met à jour le statut de l'application
					$managerApplication = $this->getManagers()->getManagerOf('Application');
					$managerApplication->saveStatutApplication($application);
				}
			}

			//On supprime la publication
			$managerPublication->deletePublication($managerPublication->getPublicationById($idPublication));
			$user->getMessageClient()->addReussite('The publication has been well removed.');

			return true;
		}else{
			// On retourne le tableau d'erreurs
			$user->getMessageClient()->addErreur('The publication you want to remove does not exist.');

			return false;
		}
	}
	
	/**
	* Permet d'ajouter une publication définitivement
	**/
	private function executeAjoutPublication($request){
	
	
		// On vérifie que l'utilisateur est bien identifié
		$user = $this->app->getUser();
		//Si id en Get
		if($request->getGetData('idApplication')){
			$idApplication = $request->getGetData('idApplication');
		}else{
			//Sinon, si Id en post
			if($request->getPostData('idApplication')){
				// On récupère l'id de la publication que le client veut ajouter au Dock
				$idApplication = (int) $request->getPostData('idApplication');
			}else{
				$user->getMessageClient()->addErreur('This link is not valid.');
				return false;
			}
		}
		
		// On appelle le manager des applications
		$managerApplication = $this->getManagers()->getManagerOf('Application');
		// On récupère l'application en question si elle existe avec tous ses attributs emplis
		$app = $managerApplication->getApplicationByIdWithAllParameters($idApplication);
		//Si l'application existe
		if($app){
			// On appelle le manager des types de publication
			$managerTypePublication = $this->getManagers()->getManagerOf('TypePublication');
			$typesPublication = $managerTypePublication->getAllTypePublications();
			// On créé la variable d'affichage à insérer dans la page.
			$typeAAfficher = '';
			foreach($typesPublication as $type){
				$typeAAfficher.='<option value="'.$type->getNomTypePublication().'">'.$type->getNomTypePublication().'</option>';
			}
			// On ajoute l'application
			$this->page->addVar('application', $app);
			// On ajoute la variable flèche menu à la page
			$this->page->addVar('typeAAfficher', $typeAAfficher);	
			return true;
		
		}else{
		
			$user->getMessageClient()->addErreur('The application does not exist. You cannot add a publication for it.');
			return false;
		}				
	}
	
	/**
	* Permet de valider l'ajout d'une publication définitivement
	**/
	private function executeValidAjoutPublication($request){
	
		// On vérifie que l'utilisateur est bien identifié
		$user = $this->app->getUser();
		if($request->getPostData('idApplication')){
			// On récupère l'id de l'application
			$idApplication = (int) $request->getPostData('idApplication');
		}else{
			$user->getMessageClient()->addErreur('This link does not exist.');
			return false;
		}
		// On appelle le manager des applications
		$managerApplication = $this->getManagers()->getManagerOf('Application');
		// On récupère l'application en question si elle existe avec tous ses attributs emplis
		$app = $managerApplication->getApplicationByIdWithAllParameters($idApplication);
		//On récupère le createur de l'application
		$createur = $app->getCreateur();
	
		// On charge le fichier de configuration
		$config = $this->getApp()->getConfig();
	
		// On contrôle les auteurs de la publication
		// On appelle la fonction multiexplode pour les auteurs entrés par l'utilisateur
		$auteurs = $request->getPostData('auteursPublication');
		$delimitateursAutorisés = explode('|', $config->getVar('divers', 'divers', 'delimitateurAuteurPublication')); //Tableau des délimiteurs autorisés
		//Permet d'ajouter les auteurs à la publication
		if(isset($auteurs) && !empty($auteurs)){
			$auteurs = $config->multiexplode($delimitateursAutorisés, $auteurs);
			//print_r($auteurs);exit();
			if(isset($auteurs) && !empty($auteurs)){
				$auteursPublication = array();
				foreach($auteurs as $auteur){
					if(!empty($auteur)){
						$tempAuteur = explode(' ', $auteur);
						if(!empty($tempAuteur)){

							$nomAuteur = $tempAuteur[count($tempAuteur)-1];
							$prenomAuteur = $tempAuteur[0];
							for($i=1; $i<count($tempAuteur)-1; ++$i){
								$prenomAuteur .= ' '.$tempAuteur[$i];
							}
							array_push($auteursPublication, new \Library\Entities\Auteur(array(
								'nomAuteur' => $nomAuteur,
								'prenomAuteur' => $prenomAuteur
								)));
						}else{
							array_push($auteursPublication, new \Library\Entities\Auteur(array(
								'nomAuteur' => '???',
								'prenomAuteur' => '???'
								)));
						}
					}
				}
			}else{
				$auteursPublication = null;
			}	
		}else{
			$auteursPublication = null;
		}

		// On appelle le manager des types de publication
		$managerTypePublication = $this->getManagers()->getManagerOf('TypePublication');
		// On créé l'objet Publication avec les données entrées par l'utilisateur
		$newPublication = new \Library\Entities\Publication(array(
			'applications' => array($app),
			'typePublication' => $managerTypePublication->getTypePublicationByName($request->getPostData('typePublication')),
			'titrePublication' => $request->getPostData('titrePublication'),
			'utilisateur' => $createur,
			'utilisateurs' => array($createur),
			'applications' => array($app),
			'auteurs' => $auteursPublication,
			'anneePublication' => (int) $request->getPostData('anneePublication'),
			'journalPublication' => $request->getPostData('journalPublication'),
			'urlPublication' => $request->getPostData('urlPublication')
			));


		if(sizeof($newPublication->getErreurs()) == 0){
		
			// On appelle le manager des publi
			$managerPublication = $this->getManagers()->getManagerOf('Publication');
			
			// Vérifier que la publication n'existe pas déjà dans la base
			if(!$managerPublication->getPublicationByTitre($newPublication->getTitrePublication())){

				// On appelle le manager des auteurs pour les insérer dans la BDD
				$managerAuteur = $this->getManagers()->getManagerOf('Auteur');
				$auteursPublication = array();
				foreach($newPublication->getAuteurs() as $auteur){
					$auteurExist = $managerAuteur->getAuteurByNameAndSurname($auteur->getNomAuteur(), $auteur->getPrenomAuteur());
					if(!$auteurExist){
						$managerAuteur->addAuteur($auteur); // L'ID de l'auteur est mis à jour lors de son insertion dans la BDD
					}else{
						$auteur->setIdAuteur($auteurExist->getIdAuteur());
					}
					array_push($auteursPublication, $auteur);
				}

				// On ajoute la publication à la base de données
				$managerPublication->addPublication($newPublication);

				// Si l'application n'était pas validée avant, on change son statut à valider
				$statutApp = $app->getStatut();
				if($statutApp->getNomStatut() === 'Not validated'){
					// On appelle le manager des statuts
					$managerStatut = $this->getManagers()->getManagerOf('StatutApplication');

					// On met à jour le statut de l'application
					$app->setStatut($managerStatut->getStatutByNom('Validated'));
					
					// On sauvegarde dans la BDD le statut de l'application
					$managerApplication->saveStatutApplication($app);
				}
				
				return true;		
						
			}else{
				// On ajoute la variable d'erreurs à la variable flash de la session
				$user->getMessageClient()->addErreur('The publication entered is already associated with another application.');
			
				return false;
			}
		}else{
			$user->getMessageClient()->addErreur($newPublication->getErreurs());
			$user->setFlash($newPublication);
			return false;
		}
	}


	
	/**
	* Permet de modifier une publication définitivement
	**/
	private function executeModifPublication($request){

		// On vérifie que l'utilisateur est bien identifié
		$user = $this->app->getUser();

		//Si id en Get
		if($request->getGetData('idPublication')){
			$idPublication = $request->getGetData('idPublication');
		}else{
			//Sinon, si Id en post
			if($request->getPostData('idPublication')){
				// On récupère l'id de la publication que le client veut ajouter au Dock
				$idPublication = (int) $request->getPostData('idPublication');
			}else{
				$user->getMessageClient()->addErreur('This link is not valid.');
				return false;
			}
		}
		
		// On appelle le manager des applications
		$managerPublication = $this->getManagers()->getManagerOf('Publication');
		// On récupère la Publication en question si elle existe avec tous ses attributs emplis
		$publication = $managerPublication->getPublicationById($idPublication);
		//Si la Publication existe
		if($publication){
			// On appelle le manager des types de publication
			$managerTypePublication = $this->getManagers()->getManagerOf('TypePublication');
			$typesPublication = $managerTypePublication->getAllTypePublications();
			// On créé la variable d'affichage à insérer dans la page.
			$typeAAfficher = '';
			foreach($typesPublication as $type){
				$typeAAfficher.='<option value="'.$type->getNomTypePublication().'">'.$type->getNomTypePublication().'</option>';
			}

			// On ajoute la variable flèche menu à la page
			$this->page->addVar('typeAAfficher', $typeAAfficher);	

			$this->page->addVar('publication', $publication);	
			return true;
		
		}else{
		
			$user->getMessageClient()->addErreur('The publication you want to edit does not exist.');
			return false;
		}				
	
	
	
	
	}
	
	/**
	* Permet de valider la modification d'une publication définitivement
	**/
	private function executeValidModifPublication($request){
	
		// On vérifie que l'utilisateur est bien identifié
		$user = $this->app->getUser();
		
		//Si id en Get
		if($request->getPostData('idPublication')){
			// On appelle le manager des publications
			$managerPublication = $this->getManagers()->getManagerOf('Publication');
			// On récupère la Publication en question 
			$publication = $managerPublication->getPublicationById($request->getPostData('idPublication'));
		
			if($publication){
				// On charge le fichier de configuration
				$config = $this->getApp()->getConfig();
				// On appelle la fonction multiexplode pour les auteurs entrés par l'utilisateur
				$auteurs = $request->getPostData('auteursPublication');
				$delimitateursAutorisés = explode('|', $config->getVar('divers', 'divers', 'delimitateurAuteurPublication')); //Tableau des délimiteurs autorisés
				//Permet d'ajouter les auteurs à la publication 
				if(isset($auteurs) && !empty($auteurs)){
					$auteurs = $config->multiexplode($delimitateursAutorisés, $auteurs);
					if(isset($auteurs) && !empty($auteurs)){
						$auteursPublication = array();
						foreach($auteurs as $auteur){
							$tempAuteur = explode(' ', $auteur);
							if(isset($tempAuteur) && count($tempAuteur) == 2){
								array_push($auteursPublication, new \Library\Entities\Auteur(array(
									'nomAuteur' => $tempAuteur[1],
									'prenomAuteur' => $tempAuteur[0]
									)));
							}else{
								array_push($auteursPublication, new \Library\Entities\Auteur(array(
									'nomAuteur' => '???',
									'prenomAuteur' => $tempAuteur[0]
									)));
							}
						}
					}else{
						$auteursPublication = null;
					}	
				}else{
					$auteursPublication = null;
				}


				// On appelle le manager des types de publication
				$managerTypePublication = $this->getManagers()->getManagerOf('TypePublication');
				$updatePublication = new \Library\Entities\Publication(array(
					'typePublication' => $managerTypePublication->getTypePublicationByName($request->getPostData('typePublication')),
					'titrePublication' => $request->getPostData('titrePublication'),
					'anneePublication' => $request->getPostData('anneePublication'),
					'auteurs' => $auteursPublication,
					'journalPublication' => $request->getPostData('journalPublication'),
					'urlPublication' => $request->getPostData('urlPublication'),
					'utilisateur' => $publication->getUtilisateur(),
					'idPublication' => $publication->getIdPublication()
					
					));
			
				//SI il n'y a pas d'erreur dans la construction de la publication
				if(sizeof($updatePublication->getErreurs()) == 0){
				
					// On appelle le manager des publi
					$managerPublication = $this->getManagers()->getManagerOf('Publication');
					$titreUpdatePublication = $updatePublication->getTitrePublication();
				
					// Si le titre a changé
					if(!$publication->getTitrePublication()==$titreUpdatePublication){
						//si le nom existe déjà
						if($managerPublication->getPublicationByTitre($titreUpdatePublication)){
							
							// On ajoute la variable d'erreurs à la variable flash de la session
							$user->getMessageClient()->addErreur('The publication entered is already associated with another application.');
							//Renvoie true car meme redirection que si la publication a été créée( même redirection )
							return false;
							
						}
					}
					//Permet de mettre à jour les auteurs
					$managerAuteur = $this->getManagers()->getManagerOf('Auteur');
					$auteursPublication = array();
					foreach($updatePublication->getAuteurs() as $auteur){
						$auteurExist = $managerAuteur->getAuteurByNameAndSurname($auteur->getNomAuteur(), $auteur->getPrenomAuteur());
						if(!$auteurExist){
							$managerAuteur->addAuteur($auteur); // L'ID de l'auteur est mis à jour lors de son insertion dans la BDD
						}else{
							$auteur->setIdAuteur($auteurExist->getIdAuteur());
						}
						array_push($auteursPublication, $auteur);
					}
						$user->getMessageClient()->addReussite('Les modifications ont bien été prises en compte.');
	
					//On sauvegarde la publication
					return $managerPublication->savePublication($updatePublication);
					
			  }else{
					$user->getMessageClient()->addErreur($updatePublication->getErreurs());
					return false;
			  }
		   }else{
				$user->getMessageClient()->addErreur('This publication does not exist.');
				return false;
		}
		}else{
			$user->getMessageClient()->addErreur('This link is not valid.');
			return false;
		}
	}


	/**
	* Permet d'ajouter un auteur à l'application
	**/
	private function executeValidAddAuthor($request){
	
		// On vérifie que l'utilisateur est bien identifié
		$user = $this->app->getUser();
		if($request->getPostData('idApplication')){
			// On récupère l'id de l'application
			$idApplication = (int) $request->getPostData('idApplication');
		}else{
			$user->getMessageClient()->addErreur(self::TRAIT_LINK_NOT_EXIST);
			return false;
		}

		$managerApplication = $this->getManagers()->getManagerOf('Application');
		$application = $managerApplication->getApplicationByIdWithAllParameters($idApplication);

		if($application){

			$prenomAuteur = $request->getPostData('firstname');
			$nomAuteur = $request->getPostData('lastname');
			$mailAuteur = $request->getPostData('mail');

			if(!empty($prenomAuteur) && !empty($nomAuteur) && !empty($mailAuteur)){
				$nouvelAuteur = new \Library\Entities\Auteur(array(
						'prenomAuteur' => $prenomAuteur,
						'nomAuteur' => $nomAuteur,
						'mailAuteur' => $mailAuteur
					));

				if(sizeof($nouvelAuteur->getErreurs()) == 0){
					$mailCreateur = $application->getCreateur()->getMailUtilisateur();
					if($mailAuteur != $mailCreateur){

						// On appelle le manager des auteurs et des application-auteur
						$managerAuteur = $this->getManagers()->getManagerOf('Auteur');
						$managerApplicationAuteur = $this->getManagers()->getManagerOf('ApplicationAuteur');

						// On vérifie que l'auteur n'est pas déjà présent en BDD
						$auteurBDD = $managerAuteur->getAuteurByMail($nouvelAuteur->getMailAuteur());
						if(!$auteurBDD){
							$managerAuteur->addAuteur($nouvelAuteur); // L'ID est automatiquement mis à jour.
						}else{
							$nouvelAuteur = $auteurBDD;
						}

						// On vérifie que l'auteur n'est pas déjà collaborateur de l'application
						$bool = false;
						foreach($application->getAuteurs() as $auteur){
							if($nouvelAuteur->getMailAuteur() === $auteur->getMailAuteur()){
								$bool = true;break;
							}
						}
						
						if(!$bool){
							// On enregistre le lien entre l'auteur et l'application en BDD
							$applicationAuteur = new \Library\Entities\ApplicationAuteur(array(
								'application' => $application,
								'auteur' => $nouvelAuteur
								));
							$managerApplicationAuteur->addApplicationAuteur($applicationAuteur);
							return true;
						}else{
							$user->getMessageClient()->addErreur(self::TRAIT_APPLICATION_FIELD_AUTHOR_IS_ALREADY_REGISTERED);
							return false;
						}
					}else{
						$user->getMessageClient()->addErreur(self::TRAIT_APPLICATION_FIELD_AUTHOR_IS_CREATOR);
						return false;
					}
				}else{
					$user->getMessageClient()->addErreur($nouvelAuteur->getErreurs());
					return false;
				}		
			}else{
				$user->getMessageClient()->addErreur(self::TRAIT_APPLICATION_FIELD_AUTHOR_EMPTY);
				return false;
			}
		}else{
			$user->getMessageClient()->addErreur(self::NO_APPLICATION);
			return false;
		}
	}
}