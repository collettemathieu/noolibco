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

use Library\Entities\Application;
use Library\Entities\Utilisateur;
use Library\Entities\Categorie;
use Library\Entities\Tache;
use Library\Entities\VersionTache;
use Library\Entities\TacheTypeDonneeUtilisateur;
use Library\Entities\TypeAffichageParametre;
use Library\Entities\Fonction;
use Library\Entities\TacheFonction;
use Library\Entities\Parametre;
use Library\Entities\TypeDonneeUtilisateur;
use Library\Entities\UniteDonneeUtilisateur;
use Library\Entities\FonctionParametre;
use Library\Entities\MotCle;
use Library\Entities\ApplicationMotCle;
use Library\Entities\ApplicationAuteur;


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
				// On récupère l'id
				$idApplication = (int) $request->getPostData('idApplication');
			}else{
				// On retourne le tableau d'erreurs
				$user->getMessageClient()->addErreur('No id passed to the function.');
				return false;
			}
		
		}

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


	/**
	* Permet de créer une application démo pour l'utilisateur
	**/
	private function createDemoApplication($newUser){
		if($newUser instanceof Utilisateur){
			
			// On récupère l'application example
			$managerApplication = $this->getManagers()->getManagerOf('Application');
			$application = $managerApplication->getApplicationByIdWithAllParameters(68);
			// Valeur de contrôle d'erreur
			$hasError = false;
			if($application instanceof Application){
			
				// On créé la variable fixe de l'application basée sur son nom
				$nombre = rand(0,10000000);
				$variableFixeApplication = $newUser->getNomUtilisateur().'_'.$application->getNomApplication().'_'.$nombre;

				// On copie les fichiers physiques de l'application
				$fileCopy = $this->getApp()->getFileCopy();
				$paths = $fileCopy->copyApplication($application, $newUser, $variableFixeApplication);


				// On modifie la variable fixe de l'application
				$application->setVariableFixeApplication($variableFixeApplication);
				// On modifie son nom
				//$application->setNomApplication('Example1');
				// On modifie son auteur
				$application->setCreateur($newUser);
				// On modifie le chemin du logo
				$name = substr($application->getUrlLogoApplication(), -(strlen($application->getUrlLogoApplication())-strrpos($application->getUrlLogoApplication(),'/'))+1);
				$application->setUrlLogoApplication($paths['newLogoPath'].$name);

				// On sauvegarde l'application en BDD
				$application = $managerApplication->addApplicationWithAllParameters($application);
				// On enregistre la dernière version en BDD
				$version = $application->getVersions()[count($application->getVersions())-1];
				$version->setApplication($application);
				$managerVersion = $this->getManagers()->getManagerOf('Version');
				$version = $managerVersion->addVersion($version);

				/***********/
				/* TACHES  */
				/***********/
				// On ajoute les nouvelles tâches associées à la nouvelle version
				// On appelle le manager des tâches
				$managerTache = $this->getManagers()->getManagerOf('Tache');
				// On appelle le manager des versions-tâches
				$managerVersionTache = $this->getManagers()->getManagerOf('VersionTache');
				// On appelle le manager des taches-typeDonneeUtilisateur
				$managerTacheTypeDonneeUtilisateur = $this->getManagers()->getManagerOf('TacheTypeDonneeUtilisateur');
				
				// On insère dans la BDD les tâches de la nouvelle version de l'application
				foreach($version->getTaches() as $tache){
					$tache = $managerTache->addTache($tache);

					// On crée l'objet VersionTache
					$versionTache = new VersionTache(array(
						'version' => $version,
						'tache' => $tache
					));
				
					// On met à la jour la Version-Tache de la BDD
					$managerVersionTache->addVersionTache($versionTache);

					// On créé l'objet TacheTypeDonneeUtilisateur pour chaque paramètre entré
					// et on le rentre en BDD
					foreach($tache->getTacheTypeDonneeUtilisateurs() as $tacheTypeDonneeUtilisateur){
						$newTacheTypeDonneeUtilisateur = new TacheTypeDonneeUtilisateur(array(
							'tache' => $tache,
							'typeDonneeUtilisateur' => $tacheTypeDonneeUtilisateur->getTypeDonneeUtilisateur(),
							'ordre' => $tacheTypeDonneeUtilisateur->getOrdre(),
							'description' => $tacheTypeDonneeUtilisateur->getDescription(),
							'uniteDonneeUtilisateur' => $tacheTypeDonneeUtilisateur->getUniteDonneeUtilisateur()
						));

						$managerTacheTypeDonneeUtilisateur->addTacheTypeDonneeUtilisateur($newTacheTypeDonneeUtilisateur);
					}

					/**************/
					/* FONCTIONS  */
					/**************/

					foreach($tache->getFonctions() as $fonction){

						// On crée une nouvelle fonction avec la nouvelle url
						$newUrl = $paths['newVersionPath'].substr($fonction->getUrlFonction(), -(strlen($fonction->getUrlFonction())-strrpos($fonction->getUrlFonction(),'/'))+1);

						$nouvelleFonction = new Fonction(array(
							'nomFonction' => $fonction->getNomFonction(),
							'urlFonction' => $newUrl,
							'extensionFonction' => $fonction->getExtensionFonction()
							));
						
						
						if(sizeof($nouvelleFonction->getErreurs()) == 0){
							
							// On appelle le manager des fonctions
							$managerFonction = $this->getManagers()->getManagerOf('Fonction');
							// On appelle le manager des versions-tâches
							$managerTacheFonction = $this->getManagers()->getManagerOf('TacheFonction');

							// On insère dans la BDD la nouvelle fonction de la tâche, l'Id de la nouvelle fonction est mis à jour.
							$nouvelleFonction = $managerFonction->addFonction($nouvelleFonction);

							$tacheFonction = new TacheFonction(array(
								'tache' => $tache,
								'fonction' => $nouvelleFonction,
								'ordre' => $managerTacheFonction->getLastOrdreOfFonctions($tache->getIdTache()) + 1
								));

							if(sizeof($tacheFonction->getErreurs()) == 0){
								// On met à la jour la Tache-Fonction de la BDD
								$managerTacheFonction->addTacheFonction($tacheFonction);

								/***************/
								/* PARAMETRES  */
								/***************/

								foreach($fonction->getParametres() as $parametre){
									// On crée une nouveau paramètre
									$nouveauParametre = new Parametre(array(
										'nomParametre' => $parametre->getNomParametre(),
										'descriptionParametre' => $parametre->getDescriptionParametre(),
										'statutPublicParametre' => (bool) $parametre->getStatutPublicParametre(),
										'valeurDefautParametre' => (float) $parametre->getValeurDefautParametre(),
										'typeAffichageParametre' => $parametre->getTypeAffichageParametre(),
										'valeurMinParametre' => (float) $parametre->getValeurMinParametre(),
										'valeurMaxParametre' => (float) $parametre->getValeurMaxParametre(),
										'valeurPasParametre' => (float) $parametre->getValeurPasParametre()
									));
									
									if(sizeof($nouveauParametre->getErreurs()) == 0){
							
										// On appelle le manager des paramètres
										$managerParametre = $this->getManagers()->getManagerOf('Parametre');
										// On appelle le manager des fonctions-paramètres
										$managerFonctionParametre = $this->getManagers()->getManagerOf('FonctionParametre');
										
										$fonctionParametre = new FonctionParametre(array(
											'parametre' => $nouveauParametre,
											'fonction' => $nouvelleFonction,
											'ordre' => $managerFonctionParametre->getLastOrdreOfParametres($nouvelleFonction->getIdFonction()) + 1
										));
									

										if(sizeof($fonctionParametre->getErreurs()) == 0){

											// On insère dans la BDD le nouveau paramètre de la fonction, l'Id du nouveau paramètre est mis à jour.
											$managerParametre->addParametre($nouveauParametre);
											
											// On met à la jour la Fonction-Parametre de la BDD
											$managerFonctionParametre->addFonctionParametre($fonctionParametre);
										}else{
											$hasError = true;
											// On ajoute la variable d'erreurs à la page
											$user->getMessageClient()->addErreur($fonctionParametre->getErreurs());
											break;
										}
									
									}else{
										$hasError = true;
										// On ajoute la variable d'erreurs à la page
										$user->getMessageClient()->addErreur($nouveauParametre->getErreurs());
										break;
									}
								}
							}else{
								$hasError = true;
								// On ajoute la variable d'erreurs à la page
								$user->getMessageClient()->addErreur($tacheFonction->getErreurs());
								break;
							}
						}else{
							$hasError = true;
							// On ajoute la variable d'erreurs à la page
							$user->getMessageClient()->addErreur($nouvelleFonction->getErreurs());
							break;
						}
					}
				}
				
			}else{
				$hasError = true;
			}
			return $hasError;
		}
	}
}