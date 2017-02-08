<?php
// +----------------------------------------------------------------------+
// | PHP Version 7 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Ce controleur permet d'activer/désactiver une application ou de 	  |
// | la diffuser sur les réseaux sociaux                                  |
// +----------------------------------------------------------------------+
// | Auteurs : Steve Despres  <stevedespres@noolib.com>					  |
// |			Mathieu COLLETTE <collettemathieu@noolib.com>		      |
// |			Yohann Pichois (FaceBook), Antoine Fauchard (FaceBook)	  |
// +----------------------------------------------------------------------+

/**
 * @name: controleur des applications pour le backend
 * @access: public
 * @version: 1
 */	


namespace Applications\Backend\Modules\Applications;

use Library\Entities\Utilisateur;

class ApplicationsController extends \Library\BackController
{
	use \Library\Traits\MethodeApplicationControleur;
		
	/**
	* Permet de récupérer toutes les applications présentes
	*/
	public function executeShow(){
	
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin')){
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');
		}else{
		
			
			// On récupère la requête utilisateur
			$request = $this->getApp()->getHTTPRequest();
			
			//On appelle le manager des Applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');
			
			$this->page->addVar('applications', $managerApplication->getAllApplications());
			
			
		}
	}
	

	/**
	* Permet d'activer une application sur la plateforme
	**/
	public function executeActiverDesactiverApplication($request){
	
		$user = $this->app->getUser();
		//Si l'utilisateur n'est pas authentifié comme administrateur
		if(!$user->getAttribute('isAdmin')){
		
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');

		}else{
			
			//Si l'id de l'application est présent en post
			if($request->getPostData('idApplication')){
			
				//On appelle le manager des Applications
				$managerApplication = $this->getManagers()->getManagerOf('Application');

				//On récupère l'id de l'application
				$idApplication = $request->getPostData('idApplication');
				
				$application = $managerApplication->getApplicationByIdWithAllParameters($idApplication);

				//Si l'application existe
				if($application){
					
					$numeroStatutActuel = (int) $application->getStatut()->getIdStatut();

					// Il faut terminer le processus de soumission avant de pouvoir l'activer
					if($numeroStatutActuel <= 4){

						if(count($application->getPublications()) != 0){
							$newStatut = 'Validated';
						}else{
							$newStatut = 'Not validated';
						}
					}elseif($numeroStatutActuel > 4){
						$newStatut = 'Inactive';
					}

					// Au moins une version doit être validée pour pouvoir activer l'application
					$canBeActivated = false;
					foreach($application->getVersions() as $version){
						if($version->getActiveVersion()){
							$canBeActivated = true;
							break;
						}
					}

					if($canBeActivated){

						// On appelle le manager des statuts
						$managerStatut = $this->getManagers()->getManagerOf('StatutApplication');

						// On met à jour le statut de l'application
						$application->hydrate(array(
							'statut' => $managerStatut->getStatutByNom($newStatut)
						));

						if(count($application->getErreurs()) == 0){
							$managerApplication->saveStatutApplication($application);

							$user->getMessageClient()->addReussite('Le satut de l\'application est maintenant : '.$newStatut);
							
							// On procède à la redirection
							$response = $this->app->getHTTPResponse();
							$response->redirect('/ManagerOfApplications/app='.$application->getIdApplication());
						}else{
							$user->getMessageClient()->addErreur('Le satut de l\'application n\'a pas pu être modifié.');
							// On procède à la redirection
							$response = $this->app->getHTTPResponse();
							$response->redirect('/ManagerOfApplications/app='.$application->getIdApplication());
						}
					}else{
						$user->getMessageClient()->addErreur(self::BACKEND_APPLICATION_CANNOT_BE_ACTIVATED_WITHOUT_VALID_VERSION);
						// On procède à la redirection
						$response = $this->app->getHTTPResponse();
						$response->redirect('/ManagerOfApplications/app='.$application->getIdApplication());
					}
				}else{
					$user->getMessageClient()->addErreur('Cette application n\'existe pas.');
					// On procède à la redirection
					$response = $this->app->getHTTPResponse();
					$response->redirect('/PourAdminSeulement/Applications/');
				}
				
			}else{
				$user->getMessageClient()->addErreur('Aucun identifiant d\'application a été mentionné.');
				// On procède à la redirection
				$response = $this->app->getHTTPResponse();
				$response->redirect('/');
			}
			
		}
	}


	/**
	* Permet de valider une version d'une application
	**/
	public function executeActiverVersionApplication($request){
	
		$user = $this->app->getUser();
		//Si l'utilisateur n'est pas authentifié comme administrateur
		if(!$user->getAttribute('isAdmin')){
		
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');

		}else{
			
			//Si l'id de l'application est présent en post
			if($request->getGetData('idApplication')){
			
				//On appelle le manager des Applications
				$managerApplication = $this->getManagers()->getManagerOf('Application');

				//On récupère l'id de l'application
				$idApplication = $request->getGetData('idApplication');
				
				$application = $managerApplication->getApplicationByIdWithAllParameters($idApplication);

				//Si l'application existe
				if($application){
					
					// On récupère l'id de la version
					$idVersion = (int) $request->getGetData('idVersion');

					// Au vérifie que l'id de la version existe bien dans l'application
					foreach($application->getVersions() as $versionApp){
						if($versionApp->getIdVersion() === $idVersion){
							$version = $versionApp;
							break;
						}
					}

					if(isset($version)){

						// On appelle le manager des versions
						$managerVersion = $this->getManagers()->getManagerOf('Version');

						// On met à jour le statut de la version
						$version->hydrate(array(
							'activeVersion' => true
						));

						if(count($version->getErreurs()) == 0){
							$managerVersion->saveVersion($version);

							$user->getMessageClient()->addReussite('Version '.$version->getNumVersion().' is now activated.');
							
							// On procède à la redirection
							$response = $this->app->getHTTPResponse();
							$response->redirect('/ManagerOfApplications/app='.$application->getIdApplication());
						}else{
							$user->getMessageClient()->addErreur($version->getErreurs());
							// On procède à la redirection
							$response = $this->app->getHTTPResponse();
							$response->redirect('/ManagerOfApplications/app='.$application->getIdApplication());
						}
					}else{
						$user->getMessageClient()->addErreur(self::BACKEND_VERSION_NOT_FOUND);
						// On procède à la redirection
						$response = $this->app->getHTTPResponse();
						$response->redirect('/ManagerOfApplications/app='.$application->getIdApplication());
					}
				}else{
					$user->getMessageClient()->addErreur('Cette application n\'existe pas.');
					// On procède à la redirection
					$response = $this->app->getHTTPResponse();
					$response->redirect('/PourAdminSeulement/Applications/');
				}
				
			}else{
				$user->getMessageClient()->addErreur('Aucun identifiant d\'application a été mentionné.');
				// On procède à la redirection
				$response = $this->app->getHTTPResponse();
				$response->redirect('/');
			}
			
		}
	}


	/**
    * Permet de poster sur les réseaux sociaux.
    **/
    public function executePostOnSocialNetworks($request){
        
        $user = $this->app->getUser();
		//Si l'utilisateur n'est pas authentifié comme administrateur
		if(!$user->getAttribute('isAdmin')){
		
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');

		}else{

			//On récupère l'id de l'application
			$idApplication = $request->getGetData('idApplication');
			
			//On appelle le manager des Applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');

			$application = $managerApplication->getApplicationByIdWithAllParameters($idApplication);
			//Si l'application existe
			if($application){

				$numeroStatut = (int) $application->getStatut()->getIdStatut();

				if($numeroStatut <= 4){
					// Message d'erreur : aucune publication sur les réseaux sociaux si l'application n'est pas activée
					$user->getMessageClient()->addErreur('Vous ne pouvez pas publier une application qui n\'est pas activée.');

					// On procède à la redirection
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ManagerOfApplications/app='.$application->getIdApplication());
				}else{
					$user->setFlash($application);

		            $socialMediaApplication = new \Applications\ApplicationsStandAlone\SocialMedia\SocialMediaApplication;
		            $socialMediaApplication->execute('AutoPost', 'login'); // Module = AutoPost ; action = login
		        }
	        }
        }
    }

	/**
    * Permet d'annoncer l'activation de l'application sur Facebook.
    **/
    public function executeProcessFacebookApplication($request){
        
        $user = $this->app->getUser();
		//Si l'utilisateur n'est pas authentifié comme administrateur
		if(!$user->getAttribute('isAdmin')){
		
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');

		}else{
            $socialMediaApplication = new \Applications\ApplicationsStandAlone\SocialMedia\SocialMediaApplication;
            $socialMediaApplication->execute('AutoPost', 'process');
        }
    }
}
