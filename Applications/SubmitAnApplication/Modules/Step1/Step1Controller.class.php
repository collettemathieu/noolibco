<?php
// +----------------------------------------------------------------------+
// | PHP Version 7 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour le dépôt des applications. Le dépôt se |
// | réalise en plusieurs étapes avant d'être validé.					  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe Step1Controller
 * @access: public
 * @version: 1
 */	


namespace Applications\SubmitAnApplication\Modules\Step1;
	
use Library\Entities\Categorie;
use Library\Entities\Auteur;
use Library\Entities\ApplicationAuteur;

class Step1Controller extends \Library\BackController{

	use \Library\Traits\FonctionsUniverselles;

	/**
	* Méthode pour récupérer toutes les catégories d'une application
	*/
	public function executeGetAllCategories($request){
		
		if ($request->isAjaxRequest()) {

			// On vérifie que l'utilisateur est bien identifié
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère les différentes catégories des applications
			// On appelle le manager des Catégories
			$managerCategorie = $this->getManagers()->getManagerOf('Categorie');
			$categories = $managerCategorie->getAllCategories();
			// On créé la variable d'affichage à insérer dans la page.

			$tableOfCategories = array();
			
			foreach($categories as $categorie){

				$categorieTemp = array(
					'id' => $categorie->getIdCategorie(),
					'nameCategory' => $categorie->getNomCategorie(),
					'onCategory' => $categorie->getSurcategorie()->getNomSurcategorie(),
					'descriptionCategory' => $categorie->getDescriptionCategorie()
					);

				array_push($tableOfCategories, $categorieTemp);
			}

			// On ajoute la variable flèche menu à la page
			$this->page->addVar('tableOfCategories', $tableOfCategories);
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}

	/**
	* Méthode pour récupérer le status de l'application et la déposer en session
	*/
	public function executeGetStatusApplication($request){
		
		if ($request->isAjaxRequest()) {

			// On vérifie que l'utilisateur est bien identifié
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);
			
			// On récupère l'ID de l'application à mettre en cache
			$idApp = (int) $request->getGetData('idApp');

			if(isset($idApp)){
				// On récupère le manager des applications
				$managerApplication = $this->getManagers()->getManagerOf('Application');

				// On récupère l'application via son ID
				$newApp = $managerApplication->getApplicationByIdWithAllParameters($idApp);
				
				// Si celle-ci existe...
				if($newApp){
					// On place l'application en session
					$user->setAttribute('newApp', serialize($newApp));

					// On ajoute un message de réussite avec le numéro du status de l'application
					$user->getMessageClient()->addReussite($newApp->getStatut()->getIdStatut());
				}else{
					// On ajoute la variable de réussites
					$user->getMessageClient()->addErreur(self::NO_APPLICATION);
				}
			}else{
				// On ajoute la variable de réussites
				$user->getMessageClient()->addErreur(self::ERROR_REQUEST_NOT_VALID);
			}	
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}

	/**
	* Méthode pour valider le nom de l'application
	*/
	public function executeValidNameApplication($request){
		
		if ($request->isAjaxRequest()) {

			if($request->isExistPOST('nomApp')){
				
				// On récupère l'objet User
				$user = $this->app->getUser();

				// On informe que c'est un chargement Ajax
				$user->setAjax(true);

				// On appelle le manager des Apps
				$managerApp = $this->getManagers()->getManagerOf('Application');

				$applicationBDD = $managerApp->getApplicationByName($request->getPostData('nomApp'));
			
				// Vérifier que l'application n'existe pas déjà dans la base
				if(!$applicationBDD){
					// On ajoute la variable de réussites
					$user->getMessageClient()->addReussite(true);
				}else{
					// On ajoute la variable de réussites
					$user->getMessageClient()->addErreur(false);
				}
			}else{
				// On ajoute la variable de réussites
				$user->getMessageClient()->addErreur(self::ERROR_REQUEST_NOT_VALID);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}



	/**
	* Méthode pour valider le dépôt d'une application de l'étape 1 et accéder à l'étape 2
	*/
	public function executeValidStep1($request){
		
		if ($request->isAjaxRequest()) {

			// On récupère l'objet User
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur connecté
			$utilisateur = unserialize($user->getAttribute('userSession'));

			// On charge le fichier de configuration
			$config = $this->getApp()->getConfig();

			// On crée l'objet Categorie à partir de la base de données.	
			$managerCategorie = $this->getManagers()->getManagerOf('Categorie');
			$categorie = $managerCategorie->getCategorieByNom($request->getPostData('categorieApp'));

			// On crée le statut par défaut de l'application : non déposée.
			$managerStatut = $this->getManagers()->getManagerOf('StatutApplication');
			$statut = $managerStatut->getStatutByNom('Step1Deposit');

			// On créé la variable fixe de l'utilisateur basée sur son mail
			// On créé un nombre aléatoire
			$nombre = rand(0,10000000);
			$variableFixeApplication = $this->cleanFileName($request->getPostData('nomApp')).$nombre;

			// On créé l'objet Application avec les données entrées par l'utilisateur
			//modified by Naoures
			$newApp = new \Library\Entities\Application(array(
				'createur' => $utilisateur,
				'nomApplication' => $request->getPostData('nomApp'),
				'variableFixeApplication' => $variableFixeApplication,
				'descriptionApplication' => $request->getPostData('descriptionApp'),
				'lienApplication' => $request->getPostData('lienApp'),
				'categorie' => $categorie,
				'statut' => $statut,
				'urlLogoApplication' => $config->getVar('divers', 'divers', 'urlLogoApplicationDefault')
				));

				
			if(sizeof($newApp->getErreurs()) == 0){
				// S'il n'y a pas d'erreurs dans les données entrées
				// On appelle le manager des Apps
				$managerApp = $this->getManagers()->getManagerOf('Application');

				$applicationBDD = $managerApp->getApplicationByName($newApp->getNomApplication());
			
				// Vérifier que l'application n'existe pas déjà dans la base
				if(!$applicationBDD){

					// On insère l'objet application dans la base de données. Cela permettra de créer l'ID de l'application.
					if($managerApp->addApplication($newApp)){ // L'ID de l'application est automatiquement mis à jour dans $newApp

						// On crée automatiquement la 1ère version de l'application
						$premiereVersion = new \Library\Entities\Version(array(
							'numVersion' => '1.0.0',
							'activeVersion' => false,
							'noteMajVersion' => 'Version generated by NooLib.',
							'application' => $newApp
						));

						if(sizeof($premiereVersion->getErreurs()) == 0){
							
							// On appelle le manager des versions et on place la version en BDD
							$managerVersion = $this->getManagers()->getManagerOf('Version');
							$managerVersion->addVersion($premiereVersion); // L'Id de la version est automatiquement mis à jour.
							
							// On met à jour l'application
							$newApp->addVersion($premiereVersion);

							// On met à jour le statut de l'application à l'étape 2
							$newApp->setStatut($managerStatut->getStatutByNom('Step2Deposit'));
							// On sauvegarde dans la BDD
							$managerApp->saveStatutApplication($newApp);

							// On place l'objet newApp en Session 
							$user->setAttribute('newApp', serialize($newApp));

							// On met à jour la session de l'utilisateur
							$utilisateur->addApplication($newApp);
							$user->setAttribute('userSession', serialize($utilisateur));

							// On ajoute la variable de réussites
							$user->getMessageClient()->addReussite(true);

						}else{
							// On ajoute la variable d'erreurs à la variable flash de la session
							$user->getMessageClient()->addErreur($premiereVersion->getErreurs());
						}
					}else{
						// On ajoute la variable d'erreurs à la variable flash de la session
						$user->getMessageClient()->addErreur(self::SUBMITAPPLICATION_ERROR_REGISTRATION);
					}
				}else{
					// On ajoute la variable d'erreurs à la variable flash de la session
					$user->getMessageClient()->addErreur(self::SUBMITAPPLICATION_CHANGE_NAME_APPLICATION);
				}
			}else{
				
				// On ajoute la variable d'erreurs à la variable flash de la session
				$user->getMessageClient()->addErreur($newApp->getErreurs());
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
		
	}
}