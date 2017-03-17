<?php
// +----------------------------------------------------------------------+
// | PHP Version 7 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour la gestion par Ajax de l'arbre des 	  |
// | applications.				  										  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// | Auteurs : Steve Despres  <stevedespres@noolib.com>				      |
// +----------------------------------------------------------------------+

/**
 * @name: Classe TreeController
 * @access: public
 * @version: 1
 */	


namespace Applications\HandleApplication\Modules\Tree;
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


class TreeController extends \Library\BackController
{
	
	use \Library\Traits\MethodeApplicationControleur;


	/**
	* Méthode pour récupérer une application
	*/
	public function executeGetApplication($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApp');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

			// On oriente l'utilisateur selon le statut de dépôt de l'application.
			if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
				
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute le créateur comme ID autorisé
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				foreach($application->getAuteurs() as $auteur){
					$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
					if($utilisateur){
						array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
					}
				}

				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
					// On retourne l'application à la page
					$this->page->addVar('application', $application);
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
			}else{
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}


	/**
	* Méthode pour récupérer les données de l'arbre de l'application
	*/
	public function executeDataApplication($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApp');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

			// On oriente l'utilisateur selon le statut de dépôt de l'application.
			if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
				
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute le créateur comme ID autorisé
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				foreach($application->getAuteurs() as $auteur){
					$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
					if($utilisateur){
						array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
					}
				}

				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
					// On récupère la version de l'application demandée sinon la dernière version
					$idVersion = (int) $request->getPostData('idVersion');
					if($idVersion != 0){
						foreach($application->getVersions() as $item){
							if($item->getIdVersion() === $idVersion){
								$version = $item;
								break;
							}
						}
					}else{
						$version = $application->getVersions()[count($application->getVersions())-1];
					}
					
					$tasks = array();
					$data = array();
					$numTasks = count($version->getTaches());

					foreach($version->getTaches() as $tache){
						array_push($tasks, $tache->getNomTache());
						$drilldown = array();
						$id = array();
						$fonction = array();
						$numFonction = count($tache->getFonctions());
						$dataFonctions = array();

						$drillparameter = array();
						foreach($tache->getFonctions() as $function){
							array_push($id, $function->getIdFonction());
							array_push($fonction, $function->getNomFonction());
							array_push($dataFonctions, 100/($numFonction+1));


							$numParameter = count($function->getParametres());
							$idParametre = array();
							$nomParametre = array();
							$dataParametre = array();
							
							foreach ($function->getParametres() as $parameter) {
								array_push($idParametre, $parameter->getIdParametre());
								array_push($nomParametre, $parameter->getNomParametre());
								array_push($dataParametre, ((100/($numTasks))/($numFonction+1))/($numParameter+1));
							}
							
							// On ajoute la possibilité d'ajouter un paramètre 
							array_push($idParametre, '-1');
							array_push($nomParametre, 'Add parameter');
							array_push($dataParametre, ((100/($numTasks))/($numFonction+1))/($numParameter+1));

							$parametreFonction = array(
								'id' => $idParametre,
								'parameter' => $nomParametre,
								'data' => $dataParametre,
								'fonctionID' => $function->getIdFonction()
								);
							array_push($drillparameter, $parametreFonction);
						}

						// On ajoute la possibilité d'ajouter une fonction
						array_push($id, '-1');
						array_push($fonction, 'Add function');
						array_push($dataFonctions, 100/($numFonction+1));
						if($numFonction !=0){
							$pourcentage = (100/($numTasks))/($numFonction+1);
						}else{
							$pourcentage = 100/($numTasks);
						}
						$addParametreFonction = array(
							'id' => array('-1'),
							'parameter' => array('Add parameter'),
							'data' => array($pourcentage),
							'fonctionID' => '-1'
						);

						array_push($drillparameter, $addParametreFonction);
						
						
						$drilldown = array(
							'id' => $id,
							'fonction' => $fonction,
							'data' => $dataFonctions,
							'drillparameter' => $drillparameter,
							'tacheID' => $tache->getIdTache()
							);

						$M = array(
								'y' => 100/$numTasks,
								'id' => $tache->getIdTache(),
								'drilldown' => $drilldown
							);
						array_push($data, $M);
					}

					// On envoie la réponse au format JSON
					$reponse = array(
						'task' => $tasks,
						'data' => $data
						);

					// On ajoute la réponse à la page
					$this->page->addVar('reponse', $reponse);
				}else{
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
			}else{
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::ERROR_LOADING_APPLICATION);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}


	/**
	* Méthode pour modifier le logo de l'application
	*/
	public function executeChangePictureApplication($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApp');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

			// On oriente l'utilisateur selon le statut de dépôt de l'application.
			if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
				
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute le créateur comme ID autorisé
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				foreach($application->getAuteurs() as $auteur){
					$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
					if($utilisateur){
						array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
					}
				}

				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
				
					/***************************/
					/* CONTROLE DU LOGO        */
					/***************************/
					// On charge l'objet File avec la configuration du logo de l'application
					
					$tagName= array( 'categorie' => 'application', 'sousCategorie' => 'logo');
					$file = $this->getApp()->getFileUpload('logoApp', $tagName);
					
					if(count($file->getErreurs()) == 0){
						
						// En paramètre on renseigne l'utilisateur et le sous-dossier du nom de l'application
						$file->validFileUpload($application->getCreateur(), $application->getVariableFixeApplication());

						if(count($file->getErreurs()) == 0){

							// On supprime le précédent logo de l'application
							$fileDelete = $this->getApp()->getFileDelete();
							$fileDelete->deleteFile($application->getUrlLogoApplication());

							if(count($fileDelete->getErreurs()) == 0){

								// On met à jour l'objet App avec le nouveau logo de l'application
								$application->hydrate(array(
									'urlLogoApplication' => $file->getFilePath()
									));
								
								// On contrôle qu'il n'y a pas eu d'erreurs
								if(sizeof($application->getErreurs()) == 0){
									
									// S'il n'y a pas d'erreur, on enregistre les fichiers sources sur le serveur
									if($file->depositFileUpload()){
									
										/**************************************************/
										/* GESTION DU LOGO pour une réduction de taille   */
										/**************************************************/
										
										/*On crée une image miniature 128*128px de l'image icône*/
										$source = imagecreatefrompng($file->getFilePath());
										$destination = imagecreatetruecolor(128,128);
										
										$largeur_source = imagesx($source);
										$hauteur_source = imagesy($source);
										
										$largeur_destination = imagesx($destination);
										$hauteur_destination = imagesy($destination);
										
										/*On préserve la transparence*/
										imagealphablending($destination, false);
										imagesavealpha($destination, true);

										/*On crée la miniature de 128*128*/
										imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);
										
										/*On écrase l'image précédente par la miniature de 128*128*/
										imagepng($destination, $file->getFilePath());
									
										// On sauvegarde dans la BDD
										$managerApplication = $this->getManagers()->getManagerOf('Application');
										$managerApplication->saveStep2DepositApplication($application);
										
										// On met à jour le dock des applications
										$this->updateDockApplication($application);

										// On retourne un message de confirmation
										$user->getMessageClient()->addReussite(self::TREE_PICTURE_APPLICATION_EDITED);

										// On retourne l'application à la page
										$this->page->addVar('application', $application);

									}else{
										// On ajoute la variable d'erreurs
										$user->getMessageClient()->addErreur($file->getErreurs());
									}
								}else{
									// On ajoute la variable d'erreurs
									$user->getMessageClient()->addErreur($application->getErreurs());
								}
							}else{
								// On ajoute la variable d'erreurs
								$user->getMessageClient()->addErreur($fileDelete->getErreurs());
							}
						}else{
							// On ajoute la variable d'erreurs
							$user->getMessageClient()->addErreur($file->getErreurs());
						}
					}else{
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur($file->getErreurs());
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
			}else{
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}


	/**
	* Méthode pour modifier le nom de l'application
	*/
	public function executeChangeNameApplication($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApp');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

			// On oriente l'utilisateur selon le statut de dépôt de l'application.
			if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
				
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute le créateur comme ID autorisé
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				foreach($application->getAuteurs() as $auteur){
					$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
					if($utilisateur){
						array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
					}
				}

				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
					// On met à jour l'objet App avec le nouveau logo de l'application
					$application->hydrate(array(
						'nomApplication' => $request->getPostData('nomApp')
						));

					// On contrôle qu'il n'y a pas eu d'erreurs
					if(sizeof($application->getErreurs()) == 0){

						// On appelle le manager des Apps
						$managerApplication = $this->getManagers()->getManagerOf('Application');
						// On vérifie que le nom de l'application n'existe pas déjà dans la BDD
						$applicationBDD = $managerApplication->getApplicationByName($application->getNomApplication());

						if(!$applicationBDD){
							// On sauvegarde dans la BDD
							$managerApplication->saveNameApplication($application);
							
							// On met à jour le dock des applications
							$this->updateDockApplication($application);

							// On retourne un message de confirmation
							$user->getMessageClient()->addReussite(self::TREE_NAME_CHANGED_SUCCESSFULLY);

							// On retourne l'application à la page
							$this->page->addVar('application', $application);
						}else{
							// On ajoute la variable d'erreurs
							$user->getMessageClient()->addErreur(self::TREE_CHANGE_NAME);
						}
					}else{
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur($application->getErreurs());
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
			}else{
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}


	/**
	* Méthode pour la description (et +) de l'application
	*/
	public function executeChangeDescriptionApplication($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On charge le fichier de configuration
			$config = $this->getApp()->getConfig();

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApp');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

			// On oriente l'utilisateur selon le statut de dépôt de l'application.
			if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
				
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute le créateur comme ID autorisé
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				foreach($application->getAuteurs() as $auteur){
					$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
					if($utilisateur){
						array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
					}
				}

				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
					/***************************/
					/* CONTROLE DES MOTS-CLES   */
					/***************************/
					// On contrôle les mots-clés entrés par l'utilisateur			
					// On appelle la fonction multiexplode pour les mots-clés entrés par l'utilisateur
					$delimitateursRecherches = explode('|', $config->getVar('divers', 'divers', 'delimitateurMotsCles')); //Tableau des délimiteurs autorisés
					$motsClesEntreUtilisateur = $config->multiexplode($delimitateursRecherches,$request->getPostData('motsClesApp'));

					/***************************/
					/* GESTION DES CATEGORIES  */
					/***************************/

					// On crée l'objet Categorie à partir de la base de données. Si celui-ci n'existe pas, cela retournera une erreur.
					$managerCategorie = $this->getManagers()->getManagerOf('Categorie');
					$categorie = $managerCategorie->getCategorieByNom($request->getPostData('categorieApp'));

					/*****************************/
					/* NOUVEL OBJET APPLICATION  */
					/*****************************/

					// On sauvegarde les précédents mots-clés de l'application
					$motCleSaved = $application->getMotCles();

					// On met à jour l'objet App avec le nouveau logo de l'application
					$application->hydrate(array(
						'descriptionApplication' => $request->getPostData('descriptionApp'),
						'categorie' => $categorie,
						'motCles' => $motsClesEntreUtilisateur
						));

					// On contrôle qu'il n'y a pas eu d'erreurs
					if(sizeof($application->getErreurs()) == 0){

						/***************************/
						/* GESTION DES MOTS-CLES   */
						/***************************/

						// On appelle les managers
						$managerMotCle = $this->getManagers()->getManagerOf('MotCle');
						$managerApplicationMotCle = $this->getManagers()->getManagerOf('ApplicationMotCle');
						//Creation des mots clés que l'on insère dans la BDD s'il n'existe pas encore
						foreach($motsClesEntreUtilisateur as $motcle){
							//tentative de récuperation du mot cle dans la base par son Nom
							$motCleBDD = $managerMotCle->getMotCleByName($motcle);
							//s'il n'existe pas, creation d'un nouveau mot cle que l'on ajoute ensuite directement dans la BDD
							if (!$motCleBDD){
								$motCleBDD = new MotCle(array('nomMotCle' => $motcle));
								$managerMotCle->addMotCle($motCleBDD);
							}

							// On associe ensuite le mot-clé à l'application s'il n'est pas déjà présent dans la BDD
							if(!$managerApplicationMotCle->getApplicationMotCleById($application->getIdApplication(), $motCleBDD->getIdMotCle())){
								$ApplicationMotCle = new ApplicationMotCle(array(
									'motCle' => $motCleBDD,
									'application' => $application
									));
								$managerApplicationMotCle->addApplicationMotCle($ApplicationMotCle);
							}
						}

						// On supprime les mots-clés qui ne sont plus en lien avec l'application
						foreach($motCleSaved as $motcle){
							if(!in_array($motcle, $motsClesEntreUtilisateur)){
								$motCleBDD = $managerMotCle->getMotCleByName($motcle);
								$ApplicationMotCle = new ApplicationMotCle(array(
									'motCle' => $motCleBDD,
									'application' => $application
									));
								$managerApplicationMotCle->deleteApplicationMotCle($ApplicationMotCle);
							}
						}

						// On sauvegarde dans la BDD les nouveaux attributs de l'application
						$managerApplication = $this->getManagers()->getManagerOf('Application');
						$managerApplication->saveDescriptionAndCategorieApplication($application);
						
						// On met à jour le dock des applications
						$this->updateDockApplication($application);

						// On retourne un message de confirmation
						$user->getMessageClient()->addReussite(self::TREE_APPLICATION_EDITED_SUCCESSFULLY);

						// On retourne l'application à la page
						$this->page->addVar('application', $application);
					}else{
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur($application->getErreurs());
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
			}else{
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}

	/**
	* Méthode pour ajouter une publication à l'application
	*/
	public function executeAddPublication($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApplication');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

			// On vérifie que l'application existe
			if($application){
				
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute le créateur comme ID autorisé
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				foreach($application->getAuteurs() as $auteur){
					$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
					if($utilisateur){
						array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
					}
				}

				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
					//On execute la methode du trait
					$etatAjoutPublication = $this->executeValidAjoutPublication($request);
					if($etatAjoutPublication){
						// On retourne un message de confirmation
						$user->getMessageClient()->addReussite(self::TREE_PUBLICATION_EDITED_SUCCESSFULLY);
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
			}else{
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}

	/**
	* Méthode pour rechercher une publication
	*/
	public function executeRequestPublication($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère la recherche
			$reqPublication = $request->getPostData('reqPublication');
			
			if(is_string($reqPublication) && preg_match('#([0-9]{2}.[0-9]{4,5}/.+)#', $reqPublication, $matches)){
				
				$reqPublication = $matches[1];
				// Création d'une requête externe avec la librairie CURL -> activer "allow_url_fopen = On;" et extension=php_curl.dll dans php.ini 
				// On utilise le site crossref.org afin de récupérer les informations.
				$url = 'http://doi.crossref.org/search/doi?pid=collettemathieu%40noolib.com&format=unixsd&doi='.$reqPublication;
				$options = array(
			        CURLOPT_RETURNTRANSFER => true,     // return web page
			        CURLOPT_HEADER         => false,    // don't return headers
			        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
			        CURLOPT_ENCODING       => "",       // handle all encodings
			        CURLOPT_USERAGENT      => "NooLib", // who am i
			        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
			        CURLOPT_CONNECTTIMEOUT => 10,      // timeout on connect
			        CURLOPT_TIMEOUT        => 10,      // timeout on response
			        CURLOPT_MAXREDIRS      => 3,       // stop after 10 redirects
			        CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
		        );

				// On initialise la connexion
				$curl = curl_init($url);
				// On initialise les options de connexion
				curl_setopt_array($curl, $options);
				// On récupère les résultats
				$results = curl_exec($curl);
				// On ferme la connexion
				curl_close($curl);
				// On traite les résultats via le DOM XML
				$dom = new \DomDocument();
				$dom->loadXML($results);

				$query = $dom->getElementsByTagName('query');
				if($query->item(0)->hasAttribute('status')){
					if($query->item(0)->getAttribute('status') === 'resolved'){
						$titleJournal = $dom->getElementsByTagName('full_title')->item(0)->firstChild->nodeValue;
						$titleArticle = $dom->getElementsByTagName('title')->item(0)->firstChild->nodeValue;
						$auteurs = $dom->getElementsByTagName('person_name');
						$yearPublication = $dom->getElementsByTagName('publication_date')->item(0)->getElementsByTagName('year')->item(0)->firstChild->nodeValue;
						$urlRessource = $dom->getElementsByTagName('resource')->item(0)->firstChild->nodeValue;
						$listeAuteurs = '';
						
						foreach($auteurs as $auteur){
							$nameAuteur = $auteur->getElementsByTagName('given_name')->item(0);
							$surnameAuteur = $auteur->getElementsByTagName('surname')->item(0);
							$listeAuteurs .= $nameAuteur->firstChild->nodeValue.' '.$surnameAuteur->firstChild->nodeValue.', ';
						}

						$results = array(
							'titleArticle' => $titleArticle,
							'listeAuteurs' => $listeAuteurs,
							'yearPublication' => $yearPublication,
							'titleJournal' => $titleJournal,
							'urlRessource' => $urlRessource
							);
						
						// On ajoute la variable results à la page
						$this->page->addVar('results', $results);

					}else{
						$user->getMessageClient()->addErreur(self::TREE_DOI_NOT_FOUND);
					}
				}else{
					$user->getMessageClient()->addErreur(self::TREE_DOI_NOT_FOUND);
				}
			}else{
				$user->getMessageClient()->addErreur(self::TREE_ADD_PUBLICATION_ARG_EMPTY);
			}	
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}


	/**
	* Méthode pour supprimer une publication à l'application
	*/
	public function executeDeletePublicationFromApplication($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApp');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

			// On contrôle si l'application existe
			if($application){
				
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute le créateur comme ID autorisé
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				foreach($application->getAuteurs() as $auteur){
					$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
					if($utilisateur){
						array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
					}
				}

				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
					//On execute la methode du trait
					$etatSupprimerPublication = $this->executeDeletePublication($request);
					if(!$etatSupprimerPublication){
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur(self::ERROR_DELETING_PUBLICATION);
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
			}else{
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}


	/**
	* Méthode pour ajouter un auteur à l'application
	*/
	public function executeAddAuthor($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApplication');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);
			
			// On oriente l'utilisateur selon le statut de dépôt de l'application.
			if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
				
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute seulement le créateur comme ID autorisé et non les autres auteurs
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				
				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
					if($user->getAttribute('isAdmin') || $userSession->getMailUtilisateur() != $request->getPostData('mail')){
						//On execute la méthode du trait
						$etatAjoutAuthor = $this->executeValidAddAuthor($request);
						if($etatAjoutAuthor){
							// On retourne un message de confirmation
							$user->getMessageClient()->addReussite(self::TREE_AUTHOR_ADDED);
						}
					}else{
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur(self::TREE_AUTHOR_NOT_AUTHORIZED);	
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_AUTHORS_APPLICATION);
				}
			}else{
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}


	/**
	* Méthode pour supprimer un auteur à l'application
	*/
	public function executeRemoveAuthor($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApplication');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

			// On oriente l'utilisateur selon le statut de dépôt de l'application.
			if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
				
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute seulement le créateur comme ID autorisé et non les autres auteurs
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());

				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
					// On récupère le manager des auteurs et des applicationAuteur
					$managerAuteur = $this->getManagers()->getManagerOf('Auteur');
					$managerApplicationAuteur = $this->getManagers()->getManagerOf('ApplicationAuteur');
					
					// On récupère l'auteur via son id
					$auteur = $managerAuteur->getAuteurById((int) $request->getPostData('idAuteur'));
					
					if($auteur){
						$applicationAuteur = new ApplicationAuteur(array(
							'application' => $application,
							'auteur' => $auteur
							));

						// On supprime le lien entre l'application et l'auteur
						$managerApplicationAuteur->deleteApplicationAuteur($applicationAuteur);

						// On retourne un message de confirmation
						$user->getMessageClient()->addReussite(self::TREE_AUTHOR_REMOVED);
					}else{
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur(self::TREE_AUTHOR_NOT_EXIST);
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_AUTHORS_APPLICATION);
				}
			}else{
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}



	/**
	* Méthode pour afficher le formulaire d'ajout d'une tâche à l'application
	*/
	public function executeShowFormTache($request)
	{
		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApp');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);
			
			// On oriente l'utilisateur selon le statut de dépôt de l'application.
			if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
				
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute le créateur comme ID autorisé
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				foreach($application->getAuteurs() as $auteur){
					$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
					if($utilisateur){
						array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
					}
				}


				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
					/* Pour la création des variables liste de formulaires */

					// On récupère la liste des types de parametre des tâches
					// On appelle le manager des types de parametre
					$managerTypeDonneeUtilisateur = $this->getManagers()->getManagerOf('TypeDonneeUtilisateur');
					$typesDonneeUtilisateur = $managerTypeDonneeUtilisateur->getAllTypeDonneeUtilisateurs();
					// On créé la variable d'affichage à insérer dans la page.
					$typeDonneeUtilisateurAAfficher = '';
					foreach($typesDonneeUtilisateur as $type){
						$typeDonneeUtilisateurAAfficher.='<option value="'.$type->getNomTypeDonneeUtilisateur().'">'.$type->getNomTypeDonneeUtilisateur().'</option>';
					}

					// On ajoute la variable typeparametreAAfficher à la page
					$this->page->addVar('typeDonneeUtilisateurAAfficher', $typeDonneeUtilisateurAAfficher);


					// On récupère la liste des unités de parametre
					// On appelle le manager des unité de parametre
					$managerUniteDonneeUtilisateur = $this->getManagers()->getManagerOf('UniteDonneeUtilisateur');
					$uniteDonneeUtilisateurs = $managerUniteDonneeUtilisateur->getAllUniteDonneeUtilisateurs();
					// On créé la variable d'affichage à insérer dans la page.
					$uniteDonneeUtilisateurAAfficher = '';
					foreach($uniteDonneeUtilisateurs as $unite){
						$uniteDonneeUtilisateurAAfficher.='<option value="'.$unite->getNomUniteDonneeUtilisateur().'">'.$unite->getNomUniteDonneeUtilisateur().' ('.$unite->getSymboleUniteDonneeUtilisateur().')'.'</option>';
					}

					// On ajoute la variable uniteDonneeUtilisateurAAfficher à la page
					$this->page->addVar('uniteDonneeUtilisateurAAfficher', $uniteDonneeUtilisateurAAfficher);

					// On ajoute la variable application à la page
					$this->page->addVar('application', $application);
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
			}else{
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}



	/**
	* Méthode pour afficher le formulaire d'ajout d'une fonction à une tâche
	*/
	public function executeShowFormFonction($request)
	{
		
		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApp');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

			// On oriente l'utilisateur selon le statut de dépôt de l'application.
			if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
				
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute le créateur comme ID autorisé
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				foreach($application->getAuteurs() as $auteur){
					$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
					if($utilisateur){
						array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
					}
				}

				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
					
					// On vérifie que la tâche appartient bien à l'application et à la bonne version
					$idTache = $request->getPostData('idTache');
					// On récupère la version de l'application demandée 
					$idVersion = (int) $request->getPostData('idVersion');
					if($idVersion != 0){
						foreach($application->getVersions() as $item){
							if($item->getIdVersion() === $idVersion){
								$version = $item;
								break;
							}
						}
						if(isset($version)){
							$tabIdTache = array();
							foreach($version->getTaches() as $tache){
								array_push($tabIdTache, $tache->getIdTache());
							}

							if(in_array($idTache, $tabIdTache)){
								// On récupère l'id de la tâche et on l'envoie à la page
								$this->page->addVar('idTache', $idTache);

								// On ajoute la variable application à la page
								$this->page->addVar('app', $application);
							}else{
								// On ajoute la variable d'erreurs
								$user->getMessageClient()->addErreur(self::DENY_HANDLE_TASK);
							}
						}else{
							// On ajoute la variable d'erreurs
							$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
						}
					}else{
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
			}else{
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}


	/**
	* Méthode pour afficher le formulaire d'ajout d'un paramètre à une fonction
	*/
	public function executeShowFormParametre($request)
	{
		
		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApp');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

			// On oriente l'utilisateur selon le statut de dépôt de l'application.
			if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
				
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute le créateur comme ID autorisé
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				foreach($application->getAuteurs() as $auteur){
					$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
					if($utilisateur){
						array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
					}
				}

				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
					
					// On vérifie que la fonction appartient bien à l'application et à la bonne version
					$idFonction = $request->getPostData('idFonction');
					// On récupère la version de l'application demandée 
					$idVersion = (int) $request->getPostData('idVersion');
					if($idVersion != 0){
						foreach($application->getVersions() as $item){
							if($item->getIdVersion() === $idVersion){
								$version = $item;
								break;
							}
						}
						if(isset($version)){
							$tabIdFonction = array();
							foreach($version->getTaches() as $tache){
								foreach($tache->getFonctions() as $fonction){
									array_push($tabIdFonction, $fonction->getIdFonction());
								}
							}

							if(in_array($idFonction, $tabIdFonction)){

								// On récupère la liste des types d'afficahge des parametres des fonctions
								// On appelle le manager des types d'affichage des paramètres
								$managerTypeAffichageParametre = $this->getManagers()->getManagerOf('TypeAffichageParametre');
								$typesAffichageParametre = $managerTypeAffichageParametre->getAllTypeAffichageParametres();
								// On créé la variable d'affichage à insérer dans la page.
								$lesTypeAffichageParametre = '';
								foreach($typesAffichageParametre as $type){
									$lesTypeAffichageParametre.='<option value="'.$type->getNomTypeAffichageParametre().'">'.$type->getNomTypeAffichageParametre().'</option>';
								}

								// On l'envoie à la page
								$this->page->addVar('lesTypeAffichageParametre', $lesTypeAffichageParametre);

								// On récupère l'id de la tâche et on l'envoie à la page
								$this->page->addVar('idFonction', $idFonction);

								// On ajoute la variable application à la page
								$this->page->addVar('app', $application);

							}else{
								// On ajoute la variable d'erreurs
								$user->getMessageClient()->addErreur(self::DENY_HANDLE_FUNCTION);
							}
						}else{
							// On ajoute la variable d'erreurs
							$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
						}
					}else{
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
			}else{
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}



	/**
	* Méthode pour valider le formulaire d'ajout de nouvelle tâche à l'application
	*/
	public function executeValidFormTache($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApp');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

			// On vérifie que le bon contrôleur est appelé
			if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
				
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute le créateur comme ID autorisé
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				foreach($application->getAuteurs() as $auteur){
					$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
					if($utilisateur){
						array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
					}
				}

				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
					
					// On crée une nouvelle tâche
					$nouvelleTache = new Tache(array(
						'nomTache' => $request->getPostData('nomTache'),
						'descriptionTache' => $request->getPostData('descriptionTache')
						));
			
					if(sizeof($nouvelleTache->getErreurs()) == 0){

						// On appelle le manager des types de parametre et des unités
						$managerTypeDonneeUtilisateur = $this->getManagers()->getManagerOf('TypeDonneeUtilisateur');
						$managerUniteDonneeUtilisateur = $this->getManagers()->getManagerOf('UniteDonneeUtilisateur');

						// On contrôle les paramètres d'entrée de la tâche
						$k = 0; 
						$tabTypeDonneeUtilisateurs = array(); 
						$tabDescriptionParametre = array();
						$tabUniteDonneeUtilisateur = array();
						$noError=true;
						while($noError && $request->isExistPOST('typeDonneeUtilisateur'.$k) && $request->isExistPOST('description'.$k)){
							$nomTypeDonneeUtilisateur = $request->getPostData('typeDonneeUtilisateur'.$k);
							$descriptionParametre = $request->getPostData('description'.$k);
							$typeDonneeUtilisateur = $managerTypeDonneeUtilisateur->getTypeDonneeUtilisateurByNom($nomTypeDonneeUtilisateur);

							if($request->isExistPOST('uniteDonneeUtilisateur'.$k)){
								$nomUniteDonneeUtilisateur = $request->getPostData('uniteDonneeUtilisateur'.$k);
							}else{
								$nomUniteDonneeUtilisateur = 'Dimensionless quantity';
							}
							$uniteDonneeUtilisateur = $managerUniteDonneeUtilisateur->getUniteDonneeUtilisateurByNom($nomUniteDonneeUtilisateur);
							if($typeDonneeUtilisateur && $uniteDonneeUtilisateur && strlen($descriptionParametre)>1){
								array_push($tabTypeDonneeUtilisateurs, $typeDonneeUtilisateur);
								array_push($tabUniteDonneeUtilisateur, $uniteDonneeUtilisateur);
								array_push($tabDescriptionParametre, $descriptionParametre);
								++$k;
							}else{
								$noError = false;
								break;
							}
						}
						
						if($noError && !empty($tabTypeDonneeUtilisateurs) && !empty($tabDescriptionParametre) && !empty($tabUniteDonneeUtilisateur)){
							// On appelle le manager des tâches
							$managerTache = $this->getManagers()->getManagerOf('Tache');
							// On appelle le manager des versions-tâches
							$managerVersionTache = $this->getManagers()->getManagerOf('VersionTache');
							// On appelle le manager des taches-typeDonneeUtilisateur
							$managerTacheTypeDonneeUtilisateur = $this->getManagers()->getManagerOf('TacheTypeDonneeUtilisateur');

							// On insère dans la BDD la nouvelle tâche de l'application
							$managerTache->addTache($nouvelleTache);

							// On crée l'objet VersionTache
							// On récupère la version de l'application demandée
							$idVersion = (int) $request->getPostData('idVersion');
							if($idVersion != 0){
								foreach($application->getVersions() as $item){
									if($item->getIdVersion() === $idVersion){
										$version = $item;
										break;
									}
								}
								if(isset($version)){
									$versionTache = new VersionTache(array(
									'version' => $version,
									'tache' => $nouvelleTache
									));
								
									// On met à la jour la Version-Tache de la BDD
									$managerVersionTache->addVersionTache($versionTache);

									// On créé l'objet TacheTypeDonneeUtilisateur pour chaque paramètre entré
									// et on le rentre en BDD
									foreach($tabTypeDonneeUtilisateurs as $ordre => $typeDonneeUtilisateur){
										$tacheTypeDonneeUtilisateur = new TacheTypeDonneeUtilisateur(array(
											'tache' => $nouvelleTache,
											'typeDonneeUtilisateur' => $typeDonneeUtilisateur,
											'ordre' => $ordre+1,
											'description' => $tabDescriptionParametre[$ordre],
											'uniteDonneeUtilisateur' => $tabUniteDonneeUtilisateur[$ordre]
										));

										$managerTacheTypeDonneeUtilisateur->addTacheTypeDonneeUtilisateur($tacheTypeDonneeUtilisateur);
									}

									// On met à jour l'application en session
									$managerApplication = $this->getManagers()->getManagerOf('Application');
									$applicationUpdated = $managerApplication->getApplicationByIdWithAllParameters($application->getIdApplication());
									
									// On rend la version inactive et si aucune autre version active, on rend l'application inactive
									// On appelle le manager des versions
									$managerVersion = $this->getManagers()->getManagerOf('Version');

									$version->hydrate(array(
										'activeVersion' => false
										));
									$managerVersion->saveVersion($version);

									$otherActiveVersion = false;
									foreach($application->getVersions() as $versionApp){
										if($versionApp->getIdVersion() != $version->getIdVersion()){
											if($versionApp->getActiveVersion()){
												$otherActiveVersion = true;
											}
										}
									}

									if(!$otherActiveVersion){
										// On appelle le manager des statuts
										$managerStatut = $this->getManagers()->getManagerOf('StatutApplication');
										// On met à jour le statut de l'application
										$applicationUpdated->hydrate(array(
											'statut' => $managerStatut->getStatutByNom('Inactive')
										));
										// On met à jour le statut de l'application
										$managerApplication->saveStatutApplication($applicationUpdated);
									}

									// On met à jour le dock des applications
									$this->updateDockApplication($applicationUpdated);

									// On retourne un message de confirmation
									$user->getMessageClient()->addReussite(self::TREE_TASK_ADDED);
								}else{
									// On ajoute la variable d'erreurs
									$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
								}
							}else{
								// On ajoute la variable d'erreurs
								$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
							}
						}else{
							// On ajoute la variable d'erreurs
							$user->getMessageClient()->addErreur(self::TREE_NO_TYPE_PARAMETER);
						}
					}else{
						
						// On ajoute la variable d'erreurs à la page
						$user->getMessageClient()->addErreur($nouvelleTache->getErreurs());
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
			}else{
				
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}




	/**
	* Méthode pour valider le formulaire d'ajout de nouvelle fonction d'une tâche
	*/
	public function executeValidFormFonction($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApp');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

			// On vérifie que le bon contrôleur est appelé
			if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
				
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute le créateur comme ID autorisé
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				foreach($application->getAuteurs() as $auteur){
					$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
					if($utilisateur){
						array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
					}
				}

				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
					
					// On vérifie que la tâche appartient bien à l'application et à la bonne version
					$idTache = $request->getPostData('id');
					// On récupère la version de l'application demandée
					$idVersion = (int) $request->getPostData('idVersion');
					if($idVersion != 0){
						foreach($application->getVersions() as $item){
							if($item->getIdVersion() === $idVersion){
								$version = $item;
								break;
							}
						}
						if(isset($version)){
							$tabIdTache = array();
							foreach($version->getTaches() as $tache){
								array_push($tabIdTache, $tache->getIdTache());
							}

							if(in_array($idTache, $tabIdTache)){

								$tagName = array( 'categorie' => 'application', 'sousCategorie' => 'source');
								// On charge l'objet File avec la configuration du fichier source de l'application
								$file = $this->getApp()->getFileUpload('urlFonction', $tagName);

								if(count($file->getErreurs()) == 0){
									// En paramètre on renseigne l'utilisateur, le sous-dossier de l'application et le sous-sous-dossier du numéro de version
									$file->validFileUpload($application->getCreateur(), $application->getVariableFixeApplication(), $version->getNumVersion());

									if(count($file->getErreurs()) == 0){

										// On crée une nouvelle fonction
										$nouvelleFonction = new Fonction(array(
											'nomFonction' => 'Function xx',
											'urlFonction' => $file->getFilePath(),
											'extensionFonction' => $file->getFileExtension()
											));
										
										
										if(sizeof($nouvelleFonction->getErreurs()) == 0){
											
											// S'il n'y a pas d'erreur, on enregistre le fichier source sur le serveur
											if($file->depositFileUpload()){
												
												// On appelle le manager des tâches
												$managerFonction = $this->getManagers()->getManagerOf('Fonction');
												// On appelle le manager des versions-tâches
												$managerTacheFonction = $this->getManagers()->getManagerOf('TacheFonction');
												// On appelle le manager des tâches
												$managerTache = $this->getManagers()->getManagerOf('Tache');

												// On récupère la tâche si elle existe
												$tache = $managerTache->getTacheById($idTache);

												// On crée l'objet TacheFonction s'il existe
												if($tache){
													$numberFonction = count($tache->getFonctions())+1;
													$nouvelleFonction->hydrate(array(
														'nomFonction' => 'Function '.$numberFonction
														));

													// On insère dans la BDD la nouvelle fonction de la tâche, l'Id de la nouvelle fonction est mis à jour.
													$managerFonction->addFonction($nouvelleFonction);

													$tacheFonction = new TacheFonction(array(
														'tache' => $tache,
														'fonction' => $nouvelleFonction,
														'ordre' => $managerTacheFonction->getLastOrdreOfFonctions($tache->getIdTache()) + 1
														));

													if(sizeof($tacheFonction->getErreurs()) == 0){
														// On met à la jour la Tache-Fonction de la BDD
														$managerTacheFonction->addTacheFonction($tacheFonction);

														// On met à jour l'application en session
														$managerApplication = $this->getManagers()->getManagerOf('Application');
														$applicationUpdated = $managerApplication->getApplicationByIdWithAllParameters($application->getIdApplication());
												
														// On rend la version inactive et si aucune autre version active, on rend l'application inactive
														// On appelle le manager des versions
														$managerVersion = $this->getManagers()->getManagerOf('Version');

														$version->hydrate(array(
															'activeVersion' => false
															));
														$managerVersion->saveVersion($version);

														$otherActiveVersion = false;
														foreach($application->getVersions() as $versionApp){
															if($versionApp->getIdVersion() != $version->getIdVersion()){
																if($versionApp->getActiveVersion()){
																	$otherActiveVersion = true;
																}
															}
														}

														if(!$otherActiveVersion){
															// On appelle le manager des statuts
															$managerStatut = $this->getManagers()->getManagerOf('StatutApplication');
															// On met à jour le statut de l'application
															$applicationUpdated->hydrate(array(
																'statut' => $managerStatut->getStatutByNom('Inactive')
															));
															// On met à jour le statut de l'application
															$managerApplication->saveStatutApplication($applicationUpdated);
														}

														// On met à jour le dock des applications
														$this->updateDockApplication($applicationUpdated);

														// On retourne un message de confirmation
														$user->getMessageClient()->addReussite(self::TREE_FUNCTION_ADDED);
													}else{
														
														// On ajoute la variable d'erreurs à la page
														$user->getMessageClient()->addErreur($tacheFonction->getErreurs());
													}
												}
												else{
													// On ajoute la variable d'erreurs à la page
													$user->getMessageClient()->addErreur(self::NO_TASK);
												}
											}
											else{
												// On ajoute la variable d'erreurs à la page
												$user->getMessageClient()->addErreur($file->getErreurs());
											}
										}else{
											
											// On ajoute la variable d'erreurs à la page
											$user->getMessageClient()->addErreur($nouvelleFonction->getErreurs());
										}
									}else{
										// On ajoute la variable d'erreurs à la page
										$user->getMessageClient()->addErreur($file->getErreurs());
									}
								}else{
									// On ajoute la variable d'erreurs à la page
									$user->getMessageClient()->addErreur($file->getErreurs());
								}
							}else{
								// On ajoute la variable d'erreurs
								$user->getMessageClient()->addErreur(self::DENY_HANDLE_TASK);
							}
						}else{
							// On ajoute la variable d'erreurs
							$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
						}
					}else{
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
			}else{
				
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}




	/**
	* Méthode pour valider le formulaire d'ajout d'un nouveau paramètre à la fonction d'une tâche
	*/
	public function executeValidFormParametre($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApp');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

			// On vérifie que le bon contrôleur est appelé
			if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
				
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute le créateur comme ID autorisé
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				foreach($application->getAuteurs() as $auteur){
					$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
					if($utilisateur){
						array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
					}
				}

				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){

					// On vérifie que la fonction appartient bien à l'application et à la bonne version
					$idFonction = $request->getPostData('idFonction');
					// On récupère la version de l'application demandée
					$idVersion = (int) $request->getPostData('idVersion');
					if($idVersion != 0){
						foreach($application->getVersions() as $item){
							if($item->getIdVersion() === $idVersion){
								$version = $item;
								break;
							}
						}
						if(isset($version)){
							$tabIdFonction = array();
							foreach($version->getTaches() as $tache){
								foreach($tache->getFonctions() as $fonction){
									array_push($tabIdFonction, $fonction->getIdFonction());
								}
							}

							if(in_array($idFonction, $tabIdFonction)){

								//On récupère les valeurs du minimum, maximum, par défaut et le pas
								$valeurDefautParametre = $request->getPostData('valeurDefautParametre');
								$valeurMinParametre = $request->getPostData('valeurMinParametre');
								$valeurMaxParametre = $request->getPostData('valeurMaxParametre');
								$valeurPasParametre = $request->getPostData('valeurPasParametre');
											
								if($valeurMinParametre<$valeurMaxParametre){
									if($valeurPasParametre < ($valeurMaxParametre-$valeurMinParametre)){
										if($valeurDefautParametre<=$valeurMaxParametre && $valeurDefautParametre>=$valeurMinParametre){
										
											// On appelle le manager des typeAffichageParametre
											$managerTypeAffichageParametre = $this->getManagers()->getManagerOf('TypeAffichageParametre');

											// On récupère le type d'affichage du paramètre
											$typeAffichageParametre = $managerTypeAffichageParametre->getTypeAffichageParametreByNom($request->getPostData('typeAffichageParametre'));

											if($typeAffichageParametre){
												// On crée une nouveau paramètre
												$nouveauParametre = new Parametre(array(
													'nomParametre' => $request->getPostData('nomParametre'),
													'descriptionParametre' => $request->getPostData('descriptionParametre'),
													'statutPublicParametre' => (bool) $request->getPostData('statutPublicParametre'),
													'valeurDefautParametre' => (float) $request->getPostData('valeurDefautParametre'),
													'typeAffichageParametre' => $typeAffichageParametre,
													'valeurMinParametre' => (float) $request->getPostData('valeurMinParametre'),
													'valeurMaxParametre' => (float) $request->getPostData('valeurMaxParametre'),
													'valeurPasParametre' => (float) $request->getPostData('valeurPasParametre')
												));
												
												if(sizeof($nouveauParametre->getErreurs()) == 0){
										
													// On appelle le manager des paramètres
													$managerParametre = $this->getManagers()->getManagerOf('Parametre');
													// On appelle le manager des fonctions-paramètres
													$managerFonctionParametre = $this->getManagers()->getManagerOf('FonctionParametre');

													// On appelle le manager des fonctions
													$managerFonction = $this->getManagers()->getManagerOf('Fonction');
													
													// On récupère la fonction si elle existe
													$fonction = $managerFonction->getFonctionById($idFonction);

													// On crée l'objet FonctionParametre si la fonction existe
													if($fonction){

														$fonctionParametre = new FonctionParametre(array(
															'parametre' => $nouveauParametre,
															'fonction' => $fonction,
															'ordre' => $managerFonctionParametre->getLastOrdreOfParametres($fonction->getIdFonction()) + 1
														));
													

														if(sizeof($fonctionParametre->getErreurs()) == 0){

															// On insère dans la BDD le nouveau paramètre de la fonction, l'Id du nouveau paramètre est mis à jour.
															$managerParametre->addParametre($nouveauParametre);
															
															// On met à la jour la Fonction-Parametre de la BDD
															$managerFonctionParametre->addFonctionParametre($fonctionParametre);

															// On met à jour l'application en session
															$managerApplication = $this->getManagers()->getManagerOf('Application');
															$applicationUpdated = $managerApplication->getApplicationByIdWithAllParameters($application->getIdApplication());
															
															// On rend la version inactive et si aucune autre version active, on rend l'application inactive
															// On appelle le manager des versions
															$managerVersion = $this->getManagers()->getManagerOf('Version');

															$version->hydrate(array(
																'activeVersion' => false
																));
															$managerVersion->saveVersion($version);

															$otherActiveVersion = false;
															foreach($application->getVersions() as $versionApp){
																if($versionApp->getIdVersion() != $version->getIdVersion()){
																	if($versionApp->getActiveVersion()){
																		$otherActiveVersion = true;
																	}
																}
															}

															if(!$otherActiveVersion){
																// On appelle le manager des statuts
																$managerStatut = $this->getManagers()->getManagerOf('StatutApplication');
																// On met à jour le statut de l'application
																$applicationUpdated->hydrate(array(
																	'statut' => $managerStatut->getStatutByNom('Inactive')
																));
																// On met à jour le statut de l'application
																$managerApplication->saveStatutApplication($applicationUpdated);
															}

															// On met à jour le dock des applications
															$this->updateDockApplication($applicationUpdated);

															// On retourne un message de confirmation
															$user->getMessageClient()->addReussite(self::TREE_PARAMETER_ADDED);

														}else{
															
															// On ajoute la variable d'erreurs à la page
															$user->getMessageClient()->addErreur($fonctionParametre->getErreurs());
														}
													}else{
														// On ajoute la variable d'erreurs à la page
														$user->getMessageClient()->addErreur(self::NO_FUNCTION);
													}
												
												}else{
												
													// On ajoute la variable d'erreurs à la page
													$user->getMessageClient()->addErreur($nouveauParametre->getErreurs());
												}
											}else{
												// On ajoute la variable d'erreurs à la page
												$user->getMessageClient()->addErreur(self::TREE_TYPE_DISPLAY_PARAMETER_NOT_FOUND);
											}
										}else{
											$user->getMessageClient()->addErreur(self::TREE_DEFAULT_PARAMETER);
										}			
									}else{
										$user->getMessageClient()->addErreur(self::TREE_STEP_PARAMETER);
									}	
								}else{
									$user->getMessageClient()->addErreur(self::TREE_MINIMAL_VALUE_PARAMETER);
								}
							}else{
								// On ajoute la variable d'erreurs
								$user->getMessageClient()->addErreur(self::DENY_HANDLE_FUNCTION);
							}
						}else{
							// On ajoute la variable d'erreurs
							$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
						}
					}else{
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
				
			}else{
				
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}

	/**
	* Méthode pour supprimer une tâche de l'application
	*/
	public function executeDeleteTache($request){
		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApp');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

			// On vérifie que le bon contrôleur est appelé
			if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
				
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute le créateur comme ID autorisé
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				foreach($application->getAuteurs() as $auteur){
					$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
					if($utilisateur){
						array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
					}
				}

				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){

					// On vérifie que la tâche appartient bien à l'application et à la bonne version
					$idTache = $request->getPostData('idTache');
					// On récupère la version de l'application demandée 
					$idVersion = (int) $request->getPostData('idVersion');
					if($idVersion != 0){
						foreach($application->getVersions() as $item){
							if($item->getIdVersion() === $idVersion){
								$version = $item;
								break;
							}
						}
						if(isset($version)){
							$tabIdTache = array();
							foreach($version->getTaches() as $tache){
								array_push($tabIdTache, $tache->getIdTache());
							}

							if(in_array($idTache, $tabIdTache)){

								// On appelle le manager des tâches
								$managerTache = $this->getManagers()->getManagerOf('Tache');
								
								// On récupère la tâche si elle existe
								$tache = $managerTache->getTacheById($idTache);

								// On supprime la tâche si elle existe
								if($tache){

									// On supprime tous les fichiers sources
									$file = $this->getApp()->getFileDelete();

									$fonctions = $tache->getFonctions();
									if(!empty($fonctions)){
										if($file->deleteFonctionApplicationFile($fonctions)){
											
											// On supprime dans la BDD la tâche de l'application.
											$managerTache->deleteTache($tache);

											// On retourne un message de confirmation
											$user->getMessageClient()->addReussite(self::TREE_TASK_DELETED);
										
										}else{
											// On retourne le tableau d'erreurs
											$user->getMessageClient()->addErreur($file->getErreurs());
										}
									}else{
										// On supprime dans la BDD la tâche de l'application.
										$managerTache->deleteTache($tache);

										// On met à jour l'application en session
										$managerApplication = $this->getManagers()->getManagerOf('Application');
										
										// On retourne un message de confirmation
										$user->getMessageClient()->addReussite(self::TREE_TASK_DELETED);
									}

									// On met à jour l'application en session
									$managerApplication = $this->getManagers()->getManagerOf('Application');
									$applicationUpdated = $managerApplication->getApplicationByIdWithAllParameters($application->getIdApplication());
									$user->setAttribute('application', serialize($applicationUpdated));

									// On rend la version inactive et si aucune autre version active, on rend l'application inactive
									// On appelle le manager des versions
									$managerVersion = $this->getManagers()->getManagerOf('Version');

									$version->hydrate(array(
										'activeVersion' => false
										));
									$managerVersion->saveVersion($version);

									$otherActiveVersion = false;
									foreach($application->getVersions() as $versionApp){
										if($versionApp->getIdVersion() != $version->getIdVersion()){
											if($versionApp->getActiveVersion()){
												$otherActiveVersion = true;
											}
										}
									}

									if(!$otherActiveVersion){
										// On appelle le manager des statuts
										$managerStatut = $this->getManagers()->getManagerOf('StatutApplication');
										// On met à jour le statut de l'application
										$applicationUpdated->hydrate(array(
											'statut' => $managerStatut->getStatutByNom('Inactive')
										));
										// On met à jour le statut de l'application
										$managerApplication->saveStatutApplication($applicationUpdated);
									}

									// On met à jour le dock des applications
									$this->updateDockApplication($applicationUpdated);

								}else{
									// On ajoute la variable d'erreurs à la page
									$user->getMessageClient()->addErreur(self::NO_TASK);
								}
							}else{
								// On ajoute la variable d'erreurs
								$user->getMessageClient()->addErreur(self::DENY_HANDLE_TASK);
							}
						}else{
							// On ajoute la variable d'erreurs
							$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
						}
					}else{
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
				
			}else{
				
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}

	/**
	* Méthode pour supprimer une fonction de l'application
	*/
	public function executeDeleteFonction($request){
		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApp');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

			// On vérifie que le bon contrôleur est appelé
			if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
					
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute le créateur comme ID autorisé
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				foreach($application->getAuteurs() as $auteur){
					$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
					if($utilisateur){
						array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
					}
				}


				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
					
					// On vérifie que la fonction appartient bien à l'application et à la bonne version
					$idFonction = $request->getPostData('idFonction');
					// On récupère la version de l'application demandée 
					$idVersion = (int) $request->getPostData('idVersion');
					if($idVersion != 0){
						foreach($application->getVersions() as $item){
							if($item->getIdVersion() === $idVersion){
								$version = $item;
								break;
							}
						}
						if(isset($version)){
							$tabIdFonction = array();
							foreach($version->getTaches() as $tache){
								foreach($tache->getFonctions() as $fonction){
									array_push($tabIdFonction, $fonction->getIdFonction());
								}
							}

							if(in_array($idFonction, $tabIdFonction)){

								// On appelle le manager des fonctions et des tâches
								$managerFonction = $this->getManagers()->getManagerOf('Fonction');
								$managerTache = $this->getManagers()->getManagerOf('Tache');
								
								// On récupère la fonction si elle existe
								$fonction = $managerFonction->getFonctionById($idFonction);

								// On supprime la tâche si elle existe
								if($fonction){

									// On supprime tous les fichiers sources
									$file = $this->getApp()->getFileDelete();

									if($file->deleteFonctionApplicationFile($fonction)){
											
										// On supprime dans la BDD la fonction de l'application.
										$managerFonction->deleteFonction($fonction);

										// On met à jour le nom des autres fonctions de la tâche
										$k = 1;
										$tache = $managerTache->getTacheById($fonction->getTaches()[0]->getIdTache());
										foreach($tache->getFonctions() as $fonctionAModifier){
											if($fonctionAModifier->getIdFonction() != $fonction->getIdFonction()){
												$fonctionAModifier->hydrate(array(
													'nomFonction' => 'Fonction '.$k
													));
												// On sauve dans la BDD la fonction.
												$managerFonction->saveFonction($fonctionAModifier);
												++$k;
											}
										}

										// On met à jour l'application en session
										$managerApplication = $this->getManagers()->getManagerOf('Application');
										$applicationUpdated = $managerApplication->getApplicationByIdWithAllParameters($application->getIdApplication());
										
										// On rend la version inactive et si aucune autre version, on rend l'application inactive
										// On appelle le manager des versions
										$managerVersion = $this->getManagers()->getManagerOf('Version');

										$version->hydrate(array(
											'activeVersion' => false
											));
										$managerVersion->saveVersion($version);

										$otherActiveVersion = false;
										foreach($application->getVersions() as $versionApp){
											if($versionApp->getIdVersion() != $version->getIdVersion()){
												if($versionApp->getActiveVersion()){
													$otherActiveVersion = true;
												}
											}
										}

										if(!$otherActiveVersion){
											// On appelle le manager des statuts
											$managerStatut = $this->getManagers()->getManagerOf('StatutApplication');
											// On met à jour le statut de l'application
											$applicationUpdated->hydrate(array(
												'statut' => $managerStatut->getStatutByNom('Inactive')
											));
											// On met à jour le statut de l'application
											$managerApplication->saveStatutApplication($applicationUpdated);
										}

										// On met à jour le dock des applications
										$this->updateDockApplication($applicationUpdated);

										// On retourne un message de confirmation
										$user->getMessageClient()->addReussite(self::TREE_FUNCTION_DELETED);
									
									}else{
										// On retourne le tableau d'erreurs
										$user->getMessageClient()->addErreur($file->getErreurs());
									}

								}else{
									// On ajoute la variable d'erreurs à la page
									$user->getMessageClient()->addErreur(self::NO_FUNCTION);
								}
							}else{
								// On ajoute la variable d'erreurs
								$user->getMessageClient()->addErreur(self::DENY_HANDLE_FUNCTION);
							}
						}else{
							// On ajoute la variable d'erreurs
							$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
						}
					}else{
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
				
			}else{
				
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}

	/**
	* Méthode pour supprimer un paramètre d'une fonction
	*/
	public function executeDeleteParametre($request){
		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApp');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

			// On vérifie que le bon contrôleur est appelé
			if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
				
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute le créateur comme ID autorisé
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				foreach($application->getAuteurs() as $auteur){
					$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
					if($utilisateur){
						array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
					}
				}

				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
					
					// On vérifie que la fonction appartient bien à l'application et à la bonne version
					$idParametre = $request->getPostData('idParametre');
					// On récupère la version de l'application demandée 
					$idVersion = (int) $request->getPostData('idVersion');
					if($idVersion != 0){
						foreach($application->getVersions() as $item){
							if($item->getIdVersion() === $idVersion){
								$version = $item;
								break;
							}
						}
						if(isset($version)){
							$tabIdParametre = array();
							foreach($version->getTaches() as $tache){
								foreach($tache->getFonctions() as $fonction){
									foreach($fonction->getParametres() as $parametre){
										array_push($tabIdParametre, $parametre->getIdParametre());
									}
								}
							}

							if(in_array($idParametre, $tabIdParametre)){

								// On appelle le manager des paramètres
								$managerParametre = $this->getManagers()->getManagerOf('Parametre');
								
								// On récupère le paramètre si il existe
								$parametre = $managerParametre->getParametreById($idParametre);

								// On supprime la tâche si elle existe
								if($parametre){

									// On supprime dans la BDD la fonction de l'application.
									$managerParametre->deleteParametre($parametre);

									// On met à jour l'application en session
									$managerApplication = $this->getManagers()->getManagerOf('Application');
									$applicationUpdated = $managerApplication->getApplicationByIdWithAllParameters($application->getIdApplication());
									
									// On rend la version inactive et si aucune autre version active, on rend l'application inactive
									// On appelle le manager des versions
									$managerVersion = $this->getManagers()->getManagerOf('Version');

									$version->hydrate(array(
										'activeVersion' => false
										));
									$managerVersion->saveVersion($version);

									$otherActiveVersion = false;
									foreach($application->getVersions() as $versionApp){
										if($versionApp->getIdVersion() != $version->getIdVersion()){
											if($versionApp->getActiveVersion()){
												$otherActiveVersion = true;
											}
										}
									}

									if(!$otherActiveVersion){
										// On appelle le manager des statuts
										$managerStatut = $this->getManagers()->getManagerOf('StatutApplication');
										// On met à jour le statut de l'application
										$applicationUpdated->hydrate(array(
											'statut' => $managerStatut->getStatutByNom('Inactive')
										));
										// On met à jour le statut de l'application
										$managerApplication->saveStatutApplication($applicationUpdated);
									}

									// On met à jour le dock des applications
									$this->updateDockApplication($applicationUpdated);

									// On retourne un message de confirmation
									$user->getMessageClient()->addReussite(self::TREE_PARAMETER_DELETED);

								}else{
									// On ajoute la variable d'erreurs à la page
									$user->getMessageClient()->addErreur(self::NO_PARAMETER);
								}
							}else{
								// On ajoute la variable d'erreurs
								$user->getMessageClient()->addErreur(self::DENY_HANDLE_PARAMETER);
							}
						}else{
							// On ajoute la variable d'erreurs
							$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
						}
					}else{
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
				
			}else{
				
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}

	/**
	* Permet de supprimer une application définitivement
	**/
	public function executeDelete($request){
		
		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			//On récupère l'utilisateur
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);
			
			// On récupère l'id de l'application que le client veut ajouter au Dock
			if($request->isExistPOST('idApplication')){
				$idApplication = (int) $request->getPostData('idApplication');
			}else{
				$idApplication = -1;
			}

			// On appelle le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');
			// On récupère l'application en question si elle existe avec tous ses attributs emplis
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApplication);

			// On récupère l'utilisateur en session
			$utilisateur = unserialize($user->getAttribute('userSession'));

			// On vérifie que l'utilisateur supprime bien une de ses applications
			if(!empty($application) && ($utilisateur->getIdUtilisateur() === $application->getCreateur()->getIdUtilisateur() || $user->getAttribute('idAdmin'))){
				//On appelle la fonction Delete du trait pour supprimer l'application
				$this->executeDeleteApplication($request);
			}else{
				$user->getMessageClient()->addErreur(self::DENY_DELETE_APPLICATION);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}

	
	/**
	* Méthode pour modifier une tâche de l'application
	*/
	public function executeModifTache($request)
	{
		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			if($request->isExistPOST('idTache')){

				// On récupère l'utilisateur de session
				$userSession = unserialize($user->getAttribute('userSession'));

				// On récupère l'ID de l'application à mettre en cache
				$idApp = (int) $request->getPostData('idApp');

				// On récupère le manager des applications
				$managerApplication = $this->getManagers()->getManagerOf('Application');

				// On récupère l'application via son ID
				$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);
				
				// On oriente l'utilisateur selon le statut de dépôt de l'application.
				if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
						
					// On charge les utilisateurs autorisés 
					$idAuteursAutorises = array();
					// On récupère le manager des Utilisateurs
					$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
					// On ajoute le créateur comme ID autorisé
					array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
					foreach($application->getAuteurs() as $auteur){
						$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
						if($utilisateur){
							array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
						}
					}
					if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
						
						// On vérifie que la tâche appartient bien à l'application et à la bonne version
						$idTache = $request->getPostData('idTache');
						// On récupère la version de l'application demandée 
						$idVersion = (int) $request->getPostData('idVersion');
						if($idVersion != 0){
							foreach($application->getVersions() as $item){
								if($item->getIdVersion() === $idVersion){
									$version = $item;
									break;
								}
							}
							if(isset($version)){
								$tabIdTache = array();
								foreach($version->getTaches() as $tache){
									array_push($tabIdTache, $tache->getIdTache());
								}

								if(in_array($idTache, $tabIdTache)){

									$managerTache = $this->getManagers()->getManagerOf('Tache');
									$tacheAModifier = $managerTache->getTacheById($idTache);

									/* Pour la création des variables liste de formulaires */

									// On récupère la liste des types de parametre des tâches
									// On appelle le manager des types de parametre
									$managerTypeDonneeUtilisateur = $this->getManagers()->getManagerOf('TypeDonneeUtilisateur');
									$typesDonneeUtilisateur = $managerTypeDonneeUtilisateur->getAllTypeDonneeUtilisateurs();

									// On récupère la liste des unités de parametre
									// On appelle le manager des unité de parametre
									$managerUniteDonneeUtilisateur = $this->getManagers()->getManagerOf('UniteDonneeUtilisateur');
									$uniteDonneeUtilisateurs = $managerUniteDonneeUtilisateur->getAllUniteDonneeUtilisateurs();

									// On ajoute la variable uniteDonneeUtilisateurs à la page
									$this->page->addVar('uniteDonneeUtilisateurs', $uniteDonneeUtilisateurs);
									
									// On ajoute les variables à la page
									$this->page->addVar('tacheAModifier', $tacheAModifier);
									$this->page->addVar('app', $application);
									$this->page->addVar('typesDonneeUtilisateur', $typesDonneeUtilisateur);
								}else{
									// On ajoute la variable d'erreurs
									$user->getMessageClient()->addErreur(self::DENY_HANDLE_TASK);	
								}
							}else{
								// On ajoute la variable d'erreurs
								$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
							}
						}else{
							// On ajoute la variable d'erreurs
							$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
						}
					}else{
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
					}	
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
			
			}else{
				$user->getMessageClient()->addErreur(self::ERROR_REQUEST_NOT_VALID);
			}
				
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}



	/**
	* Méthode pour modifier une fonction d'une tâche
	*/
	public function executeModifFonction($request)
	{
		
		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			if($request->getPostData('idFonction')){
				
				// On récupère l'utilisateur de session
				$userSession = unserialize($user->getAttribute('userSession'));

				// On récupère l'ID de l'application à mettre en cache
				$idApp = (int) $request->getPostData('idApp');

				// On récupère le manager des applications
				$managerApplication = $this->getManagers()->getManagerOf('Application');

				// On récupère l'application via son ID
				$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

				// On oriente l'utilisateur selon le statut de dépôt de l'application.
				if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
					
					// On charge les utilisateurs autorisés 
					$idAuteursAutorises = array();
					// On récupère le manager des Utilisateurs
					$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
					// On ajoute le créateur comme ID autorisé
					array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
					foreach($application->getAuteurs() as $auteur){
						$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
						if($utilisateur){
							array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
						}
					}

					if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
			
						// On vérifie que la fonction appartient bien à l'application et à la bonne version
						$idFonction = $request->getPostData('idFonction');
						// On récupère la version de l'application demandée 
						$idVersion = (int) $request->getPostData('idVersion');
						if($idVersion != 0){
							foreach($application->getVersions() as $item){
								if($item->getIdVersion() === $idVersion){
									$version = $item;
									break;
								}
							}
							if(isset($version)){
								$tabIdFonction = array();
								foreach($version->getTaches() as $tache){
									foreach($tache->getFonctions() as $fonction){
										array_push($tabIdFonction, $fonction->getIdFonction());
									}
								}

								if(in_array($idFonction, $tabIdFonction)){

									$managerFonction = $this->getManagers()->getManagerOf('Fonction');
									$fonctionAModifier = $managerFonction->getFonctionById($idFonction);

									// On affiche les premières lignes du fichier source
									if($fichierSource = fopen($fonctionAModifier->getUrlFonction(), 'r')){
										$texte = '';
										$nbreLigne = 0;
										while($nbreLigne < 10){
											$line = fgets($fichierSource);
											$texte .= $line.'<br>';
											++$nbreLigne;
										}
										$texte.='...';
										// On ferme le fichier de données
										fclose($fichierSource);
									}

									$this->page->addVar('texteSource', $texte);
									$this->page->addVar('fonctionAModifier', $fonctionAModifier);
									$this->page->addVar('app', $application);
								}else{
									// On ajoute la variable d'erreurs
									$user->getMessageClient()->addErreur(self::DENY_HANDLE_FUNCTION);
								}
							}else{
								// On ajoute la variable d'erreurs
								$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
							}
						}else{
							// On ajoute la variable d'erreurs
							$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
						}
					}else{
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
			}else{
				$user->getMessageClient()->addErreur(self::ERROR_REQUEST_NOT_VALID);
			}
				
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}


	/**
	* Méthode pour modifier un paramètre d'une fonction
	*/
	public function executeModifParametre($request)
	{
		
		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApp');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);


			// On oriente l'utilisateur selon le statut de dépôt de l'application.
			if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
				
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute le créateur comme ID autorisé
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				foreach($application->getAuteurs() as $auteur){
					$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
					if($utilisateur){
						array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
					}
				}

				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
					
					// On vérifie que la fonction appartient bien à l'application et à la bonne version
					$idParametre = $request->getPostData('idParametre');
					// On récupère la version de l'application demandée 
					$idVersion = (int) $request->getPostData('idVersion');
					if($idVersion != 0){
						foreach($application->getVersions() as $item){
							if($item->getIdVersion() === $idVersion){
								$version = $item;
								break;
							}
						}
						if(isset($version)){
							$tabIdParametre = array();
							foreach($version->getTaches() as $tache){
								foreach($tache->getFonctions() as $fonction){
									foreach($fonction->getParametres() as $parametre){
										array_push($tabIdParametre, $parametre->getIdParametre());
									}
								}
							}

							if(in_array($idParametre, $tabIdParametre)){
							
								$idParametre = $request->getPostData('idParametre');
								$managerParametre = $this->getManagers()->getManagerOf('Parametre');
								$parametreAModifier = $managerParametre->getParametreById($idParametre);
							
								// On récupère l'id de la tâche et on l'envoie à la page
								$this->page->addVar('parametreAModifier', $parametreAModifier);

								// On ajoute la variable application à la page
								$this->page->addVar('app', $application);

								// On récupère la liste des types d'affichage du parametre
								$managerTypeAffichageParametre = $this->getManagers()->getManagerOf('TypeAffichageParametre');
								$listeTypeAffichageParametre = $managerTypeAffichageParametre->getAllTypeAffichageParametres();
								// On ajoute la variable listeTypeAffichageParametre à la page
								$this->page->addVar('listeTypeAffichageParametre', $listeTypeAffichageParametre);
							}else{
								$user->getMessageClient()->addErreur(self::DENY_HANDLE_PARAMETER);
							}
						}else{
							// On ajoute la variable d'erreurs
							$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
						}
					}else{
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
			}else{
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}
	
	
	

	/**
	* Méthode pour valider le formulaire d'ajout de nouvelle tâche à l'application
	*/
	public function executeValidModifTache($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApp');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

			// On vérifie que le bon contrôleur est appelé
			if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
				
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute le créateur comme ID autorisé
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				foreach($application->getAuteurs() as $auteur){
					$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
					if($utilisateur){
						array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
					}
				}

				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
					
					// On vérifie que la tâche appartient bien à l'application et à la bonne version
					$idTache = $request->getPostData('idTache');
					// On récupère la version de l'application demandée 
					$idVersion = (int) $request->getPostData('idVersion');
					if($idVersion != 0){
						foreach($application->getVersions() as $item){
							if($item->getIdVersion() === $idVersion){
								$version = $item;
								break;
							}
						}
						if(isset($version)){
							$tabIdTache = array();
							foreach($version->getTaches() as $tache){
								array_push($tabIdTache, $tache->getIdTache());
							}

							if(in_array($idTache, $tabIdTache)){
								// On appelle le manager des types de parametre
								$managerTache = $this->getManagers()->getManagerOf('Tache');
								$tacheAModifier = $managerTache->getTacheById($request->getPostData('idTache'));
							
								$nomTache = $request->getPostData('nomTache');
								$descriptionTache = $request->getPostData('descriptionTache');
								$typeDonneeUtilisateur = $request->getPostData('typeDonneeUtilisateur');
								
								// On appelle le manager des types de parametre et des unités
								$managerTypeDonneeUtilisateur = $this->getManagers()->getManagerOf('TypeDonneeUtilisateur');
								$managerUniteDonneeUtilisateur = $this->getManagers()->getManagerOf('UniteDonneeUtilisateur');

								$tacheAModifier->hydrate(array(
									'nomTache' => $nomTache,
									'descriptionTache' => $descriptionTache
									));

								if(count($tacheAModifier->getErreurs()) == 0){

									// On contrôle les paramètres d'entrée de la tâche
									$k = 0; 
									$tabTypeDonneeUtilisateurs = array(); 
									$tabDescriptionParametre = array();
									$tabUniteDonneeUtilisateur = array();
									$noError=true;
									while($noError && $request->isExistPOST('typeDonneeUtilisateur'.$k) && $request->isExistPOST('description'.$k)){
										$nomTypeDonneeUtilisateur = $request->getPostData('typeDonneeUtilisateur'.$k);
										$descriptionParametre = $request->getPostData('description'.$k);
										
										if($request->isExistPOST('uniteDonneeUtilisateur'.$k)){
											$nomUniteDonneeUtilisateur = $request->getPostData('uniteDonneeUtilisateur'.$k);
										}else{
											$nomUniteDonneeUtilisateur = 'Dimensionless quantity';
										}
										$typeDonneeUtilisateur = $managerTypeDonneeUtilisateur->getTypeDonneeUtilisateurByNom($nomTypeDonneeUtilisateur);
										$uniteDonneeUtilisateur = $managerUniteDonneeUtilisateur->getUniteDonneeUtilisateurByNom($nomUniteDonneeUtilisateur);
										if($typeDonneeUtilisateur && $uniteDonneeUtilisateur && strlen($descriptionParametre)>1){
											array_push($tabTypeDonneeUtilisateurs, $typeDonneeUtilisateur);
											array_push($tabUniteDonneeUtilisateur, $uniteDonneeUtilisateur);
											array_push($tabDescriptionParametre, $descriptionParametre);
											++$k;
										}else{
											$noError = false;
											break;
										}
									}
									
									if($noError && !empty($tabTypeDonneeUtilisateurs) && !empty($tabDescriptionParametre) && !empty($tabUniteDonneeUtilisateur)){
										// On supprime tous les paramètres d'entrée à la tâche en BDD
										$managerTache->deleteLinkbetweenTacheTypeDonneeUtilisateur($tacheAModifier);

										// On appelle le manager des taches-typeDonneeUtilisateur
										$managerTacheTypeDonneeUtilisateur = $this->getManagers()->getManagerOf('TacheTypeDonneeUtilisateur');

										// On créé l'objet TacheTypeDonneeUtilisateur pour chaque paramètre entré
										// et on le rentre en BDD
										$tabTacheTypeDonneeUtilisateur = array();
										foreach($tabTypeDonneeUtilisateurs as $ordre => $typeDonneeUtilisateur){
											$tacheTypeDonneeUtilisateur = new TacheTypeDonneeUtilisateur(array(
												'tache' => $tacheAModifier,
												'typeDonneeUtilisateur' => $typeDonneeUtilisateur,
												'ordre' => $ordre+1,
												'description' => $tabDescriptionParametre[$ordre],
												'uniteDonneeUtilisateur' => $tabUniteDonneeUtilisateur[$ordre]
											));

											$managerTacheTypeDonneeUtilisateur->addTacheTypeDonneeUtilisateur($tacheTypeDonneeUtilisateur);
											array_push($tabTacheTypeDonneeUtilisateur, $tacheTypeDonneeUtilisateur);
										}

										// On met à jour la tâche à modifier
										$tacheAModifier->setTacheTypeDonneeUtilisateurs($tabTacheTypeDonneeUtilisateur);
									
										$managerTache->saveTache($tacheAModifier);
										// On met à jour l'application en session
										$managerApplication = $this->getManagers()->getManagerOf('Application');
										$applicationUpdated = $managerApplication->getApplicationByIdWithAllParameters($application->getIdApplication());
										
										// On rend la version inactive et si aucune autre version active, on rend l'application inactive
										// On appelle le manager des versions
										$managerVersion = $this->getManagers()->getManagerOf('Version');

										$version->hydrate(array(
											'activeVersion' => false
											));
										$managerVersion->saveVersion($version);

										$otherActiveVersion = false;
										foreach($application->getVersions() as $versionApp){
											if($versionApp->getIdVersion() != $version->getIdVersion()){
												if($versionApp->getActiveVersion()){
													$otherActiveVersion = true;
												}
											}
										}

										if(!$otherActiveVersion){
											// On appelle le manager des statuts
											$managerStatut = $this->getManagers()->getManagerOf('StatutApplication');
											// On met à jour le statut de l'application
											$applicationUpdated->hydrate(array(
												'statut' => $managerStatut->getStatutByNom('Inactive')
											));
											// On met à jour le statut de l'application
											$managerApplication->saveStatutApplication($applicationUpdated);
										}

										// On met à jour le dock des applications
										$this->updateDockApplication($applicationUpdated);
										
										$user->getMessageClient()->addReussite(self::TREE_TASK_EDITED);
									}else{
										// On ajoute la variable d'erreurs
										$user->getMessageClient()->addErreur(self::TREE_NO_TYPE_PARAMETER);
									}
								}else{
									// On ajoute la variable d'erreurs à la page
									$user->getMessageClient()->addErreur($tacheAModifier->getErreurs());
								}
							}else{
								// On ajoute la variable d'erreurs
								$user->getMessageClient()->addErreur(self::DENY_HANDLE_TASK);
							}
						}else{
							// On ajoute la variable d'erreurs
							$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
						}
					}else{
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
			}else{
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}




	/**
	* Méthode pour valider le formulaire d'ajout de nouvelle fonction d'une tâche
	*/
	public function executeValidModifFonction($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApp');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

			// On vérifie que le bon contrôleur est appelé
			if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
				
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute le créateur comme ID autorisé
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				foreach($application->getAuteurs() as $auteur){
					$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
					if($utilisateur){
						array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
					}
				}

				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
					// On vérifie que la fonction appartient bien à l'application et à la bonne version
					$idFonction = $request->getPostData('id');
					// On récupère la version de l'application demandée 
					$idVersion = (int) $request->getPostData('idVersion');
					if($idVersion != 0){
						foreach($application->getVersions() as $item){
							if($item->getIdVersion() === $idVersion){
								$version = $item;
								break;
							}
						}
						if(isset($version)){
							$tabIdFonction = array();
							foreach($version->getTaches() as $tache){
								foreach($tache->getFonctions() as $fonction){
									array_push($tabIdFonction, $fonction->getIdFonction());
								}
							}

							if(in_array($idFonction, $tabIdFonction)){

								$tagName = array( 'categorie' => 'application', 'sousCategorie' => 'source');
								// On charge l'objet File avec la configuration du fichier source de l'application
								$file = $this->getApp()->getFileUpload('urlFonction', $tagName);

								if(count($file->getErreurs()) == 0){
									
									// En paramètre on renseigne l'utilisateur, le sous-dossier de l'application et le sous-sous-dossier du numéro de version
									$file->validFileUpload($application->getCreateur(), $application->getVariableFixeApplication(), $version->getNumVersion());


									if(count($file->getErreurs()) == 0){

										$managerFonction = $this->getManagers()->getManagerOf('Fonction');	
										$fonctionAModifier = $managerFonction->getFonctionById($idFonction);
										
										// On appel l'objet pour supprimer les données	
										$delete = $this->getApp()->getFileDelete();
										
										// On supprime le fichier source précédent de la fonction
										$delete->deleteFile($fonctionAModifier->getUrlFonction());
								
										$fonctionAModifier->hydrate(array(
											'urlFonction' => $file->getFilePath(),
											'extensionFonction' => $file->getFileExtension()
											));
											
										if(sizeof($fonctionAModifier->getErreurs()) == 0){
										
											// S'il n'y a pas d'erreur, on enregistre le fichier source sur le serveur
											if($file->depositFileUpload()){
											
												$managerFonction->saveFonction($fonctionAModifier);			
															
												// On met à jour l'application en session
												$managerApplication = $this->getManagers()->getManagerOf('Application');
												$applicationUpdated = $managerApplication->getApplicationByIdWithAllParameters($application->getIdApplication());
												
												// On rend la version inactive et si aucune autre version, on rend l'application inactive
												// On appelle le manager des versions
												$managerVersion = $this->getManagers()->getManagerOf('Version');

												$version->hydrate(array(
													'activeVersion' => false
													));
												$managerVersion->saveVersion($version);

												$otherActiveVersion = false;
												foreach($application->getVersions() as $versionApp){
													if($versionApp->getIdVersion() != $version->getIdVersion()){
														if($versionApp->getActiveVersion()){
															$otherActiveVersion = true;
														}
													}
												}

												if(!$otherActiveVersion){
													// On appelle le manager des statuts
													$managerStatut = $this->getManagers()->getManagerOf('StatutApplication');
													// On met à jour le statut de l'application
													$applicationUpdated->hydrate(array(
														'statut' => $managerStatut->getStatutByNom('Inactive')
													));
													// On met à jour le statut de l'application
													$managerApplication->saveStatutApplication($applicationUpdated);
												}

												// On met à jour le dock des applications
												$this->updateDockApplication($applicationUpdated);
												
												$user->getMessageClient()->addReussite(self::TREE_FUNCTION_EDITED);	
												
											}else{
												// On ajoute la variable d'erreurs à la page
												$user->getMessageClient()->addErreur($file->getErreurs());
											}
											
										}else{
											// On ajoute la variable d'erreurs à la page
											$user->getMessageClient()->addErreur($nouvelleFonction->getErreurs());
										}	
														
									}else{
										// On ajoute la variable d'erreurs à la page
										$user->getMessageClient()->addErreur($file->getErreurs());
									}
								}else{
									// On ajoute la variable d'erreurs à la page
									$user->getMessageClient()->addErreur($file->getErreurs());
								}
							}else{
								// On ajoute la variable d'erreurs
								$user->getMessageClient()->addErreur(self::DENY_HANDLE_FUNCTION);
							}
						}else{
							// On ajoute la variable d'erreurs
							$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
						}
					}else{
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
			}else{
				
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}




	/**
	* Méthode pour valider le formulaire d'ajout d'un nouveau paramètre à la fonction d'une tâche
	*/
	public function executeValidModifParametre($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApp');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

			// On vérifie que le bon contrôleur est appelé
			if($application && ($application->getStatut()->getNomStatut()==='Inactive' || $application->getStatut()->getNomStatut()==='Validated' || $application->getStatut()->getNomStatut()==='Not validated')){
				
				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute le créateur comme ID autorisé
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				foreach($application->getAuteurs() as $auteur){
					$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
					if($utilisateur){
						array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
					}
				}

				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){
					
					// On vérifie que la fonction appartient bien à l'application et à la bonne version
					$idParametre = $request->getPostData('idParametre');
					// On récupère la version de l'application demandée 
					$idVersion = (int) $request->getPostData('idVersion');
					if($idVersion != 0){
						foreach($application->getVersions() as $item){
							if($item->getIdVersion() === $idVersion){
								$version = $item;
								break;
							}
						}
						if(isset($version)){
							$tabIdParametre = array();
							foreach($version->getTaches() as $tache){
								foreach($tache->getFonctions() as $fonction){
									foreach($fonction->getParametres() as $parametre){
										array_push($tabIdParametre, $parametre->getIdParametre());
									}
								}
							}

							if(in_array($idParametre, $tabIdParametre)){
								//On récupère les valeurs du minimum, maximum, par défaut et le pas
								$nomParametre = $request->getPostData('nomParametre');
								$descriptionParametre = $request->getPostData('descriptionParametre');
								$valeurDefautParametre = floatval($request->getPostData('valeurDefautParametre'));
								$valeurMinParametre = floatval($request->getPostData('valeurMinParametre'));
								$valeurMaxParametre = floatval($request->getPostData('valeurMaxParametre'));
								$valeurPasParametre = floatval($request->getPostData('valeurPasParametre'));
																										
								if($valeurMinParametre<$valeurMaxParametre){
									if($valeurPasParametre < ($valeurMaxParametre-$valeurMinParametre)){
										if($valeurDefautParametre<=$valeurMaxParametre && $valeurDefautParametre>=$valeurMinParametre){
											
											// On appelle le manager des typeAffichageParametre
											$managerTypeAffichageParametre = $this->getManagers()->getManagerOf('TypeAffichageParametre');

											// On récupère le type d'affichage du paramètre
											$typeAffichageParametre = $managerTypeAffichageParametre->getTypeAffichageParametreByNom($request->getPostData('typeAffichageParametre'));

											if($typeAffichageParametre){

												// On appelle le manager des types parametre et unité de paramètre
												$managerParametre = $this->getManagers()->getManagerOf('Parametre');
												
												$parametreAModifier = $managerParametre->getParametreById($idParametre);

												$parametreAModifier->hydrate(array(
													'nomParametre' => $nomParametre,
													'descriptionParametre' => $descriptionParametre,
													'statutPublicParametre' => (bool) $request->getPostData('statutPublicParametre'),
													'valeurDefautParametre' => $valeurDefautParametre,
													'typeAffichageParametre' => $typeAffichageParametre,
													'valeurMinParametre' => $valeurMinParametre,
													'valeurMaxParametre' => $valeurMaxParametre,
													'valeurPasParametre' => $valeurPasParametre
													));

												if(count($parametreAModifier->getErreurs()) == 0){
													$managerParametre->saveParametre($parametreAModifier);
													
													// On met à jour l'application en session
													$managerApplication = $this->getManagers()->getManagerOf('Application');
													$applicationUpdated = $managerApplication->getApplicationByIdWithAllParameters($application->getIdApplication());
													
													// On rend la version inactive et si aucune autre version active, on rend l'application inactive
													// On appelle le manager des versions
													$managerVersion = $this->getManagers()->getManagerOf('Version');

													$version->hydrate(array(
														'activeVersion' => false
														));
													$managerVersion->saveVersion($version);

													$otherActiveVersion = false;
													foreach($application->getVersions() as $versionApp){
														if($versionApp->getIdVersion() != $version->getIdVersion()){
															if($versionApp->getActiveVersion()){
																$otherActiveVersion = true;
															}
														}
													}

													if(!$otherActiveVersion){
														// On appelle le manager des statuts
														$managerStatut = $this->getManagers()->getManagerOf('StatutApplication');
														// On met à jour le statut de l'application
														$applicationUpdated->hydrate(array(
															'statut' => $managerStatut->getStatutByNom('Inactive')
														));
														// On met à jour le statut de l'application
														$managerApplication->saveStatutApplication($applicationUpdated);
													}
													
													// On met à jour le dock des applications
													$this->updateDockApplication($applicationUpdated);
													
													$user->getMessageClient()->addReussite(self::TREE_PARAMETER_EDITED);		
												}else{
													$user->getMessageClient()->addErreur($parametreAModifier->getErreurs());
												}
											}else{
												$user->getMessageClient()->addErreur(self::TREE_TYPE_DISPLAY_PARAMETER_NOT_FOUND);
											}
										}else{
											$user->getMessageClient()->addErreur(self::TREE_DEFAULT_PARAMETER);
										}			
									}else{
										$user->getMessageClient()->addErreur(self::TREE_STEP_PARAMETER);
									}	
								}else{
									$user->getMessageClient()->addErreur(self::TREE_MINIMAL_VALUE_PARAMETER);
								}						
							}else{
								// On ajoute la variable d'erreurs
								$user->getMessageClient()->addErreur(self::DENY_HANDLE_PARAMETER);
							}
						}else{
							// On ajoute la variable d'erreurs
							$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
						}
					}else{
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_FOUND);
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
			}else{
				
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}

	/**
	* Méthode pour créer une nouvelle version de l'application à partir de sa précédente
	*/
	public function executeCreateNewVersionApplication($request){
		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur de session
			$userSession = unserialize($user->getAttribute('userSession'));

			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getPostData('idApp');

			// On récupère le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			// On récupère l'application via son ID
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

			// On vérifie que la bonne application est appelée
			if($application && $application->getStatut()->getIdStatut() >= 4){

				// On charge les utilisateurs autorisés 
				$idAuteursAutorises = array();
				// On récupère le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				// On ajoute le créateur comme ID autorisé
				array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
				foreach($application->getAuteurs() as $auteur){
					$utilisateur = $managerUtilisateur->getUtilisateurById($auteur->getIdAuteur());
					if($utilisateur){
						array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
					}
				}

				if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises) || $user->getAttribute('isAdmin')){

					// On récupère le numéro de la nouvelle version
					$nameVersion = $request->getPostData('nameVersionApplication');
					$versionInvalid = false;
					$pattern = '/^[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,2}$/';
					if(preg_match($pattern, $nameVersion)){
						foreach($application->getVersions() as $version){
							if($version->getNumVersion() === $nameVersion){
								$versionInvalid = true;
								break;
							}
						}
						if(!$versionInvalid){
							
							/***********/
							/* VERSION */
							/***********/
							// On récupère la dernière version
							$lastVersion = $application->getVersions()[count($application->getVersions())-1];

							// On ajoute la nouvelle version de l'application à la BDD
							// On crée automatiquement la 1ère version de l'application
							$newVersion = new \Library\Entities\Version(array(
								'numVersion' => $nameVersion,
								'activeVersion' => false,
								'noteMajVersion' => $request->getPostData('descriptionVersionApplication'),
								'application' => $application
							));

							if(sizeof($newVersion->getErreurs()) == 0){
								
								// On copie physiquement la dernière version de l'application dans la nouvelle
								$fileCopy = $this->getApp()->getFileCopy();
								$paths = $fileCopy->copyLastVersionApplication($application, $nameVersion);

								// On appelle le manager des versions et on place la version en BDD
								$managerVersion = $this->getManagers()->getManagerOf('Version');
								$newVersion = $managerVersion->addVersion($newVersion); // L'Id de la version est automatiquement mis à jour.

								// On ajoute la nouvelle ID de la version à la page
								$this->page->addVar('newIdVersion', $newVersion->getIdVersion());

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
								foreach($lastVersion->getTaches() as $tache){
									$tache = $managerTache->addTache($tache);

									// On crée l'objet VersionTache
									$versionTache = new VersionTache(array(
										'version' => $newVersion,
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

										// On crée une nouvelle fonction
										$nouvelleFonction = new Fonction(array(
											'nomFonction' => $fonction->getNomFonction(),
											'urlFonction' => preg_replace('/([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,2})/', $nameVersion, $fonction->getUrlFonction()),
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
															
															// On ajoute la variable d'erreurs à la page
															$user->getMessageClient()->addErreur($fonctionParametre->getErreurs());
														}
													
													}else{
													
														// On ajoute la variable d'erreurs à la page
														$user->getMessageClient()->addErreur($nouveauParametre->getErreurs());
													}
												}
											}else{
												
												// On ajoute la variable d'erreurs à la page
												$user->getMessageClient()->addErreur($tacheFonction->getErreurs());
											}
										}else{
											
											// On ajoute la variable d'erreurs à la page
											$user->getMessageClient()->addErreur($nouvelleFonction->getErreurs());
										}
									}
								}
							}else{
								// On ajoute la variable d'erreurs à la variable flash de la session
								$user->getMessageClient()->addErreur($newVersion->getErreurs());
							}
						}else{
							// On ajoute la variable d'erreurs
							$user->getMessageClient()->addErreur(self::TREE_VERSION_ALREADY_EXIST);
						}
					}else{
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur(self::TREE_VERSION_WRONG);
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				}
			}else{
				// On ajoute la variable d'erreurs
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}

}