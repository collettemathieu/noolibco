<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 AboutScience	         				          |
// +----------------------------------------------------------------------+
// | Classe PHP comme controleur d'identification des utilisateurs. 	  |
// | Ce controleur permet d'identifier l'utilisateur sur le site, de 	  |
// | contrôler la désactivation de JS et la bonne version du navigateur.  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@aboutscience.net>	      |
// +----------------------------------------------------------------------+

/**
 * @name: controleur des utilisateurs, de JS et du navigateur
 * @access: public
 * @version: 1
 */	

namespace Applications\LogIn\Modules\LogIn;

class LogInController extends \Library\BackController{
	
	/**
	* Permet de gérer l'affichage lorsque JS n'est pas activé
	*/
	public function executeActivatedJS(){}

	/**
	* Permet de rafraîchir l'affichage lorsque JS a été désactivé
	*/
	public function executeRefreshJS(){
		// On procède à la redirection vers la page principale
		$response = $this->app->getHTTPResponse();
		$response->redirect('/');
	}

	/**
	*	Pour traiter une mise à jour nécessaire du navigateur client
	*/
	public function executeBrowserIsValid($request){

		// On vérifie que l'utilisateur n'est pas déjà connecté
		$user = $this->app->getUser();	

		// On informe que c'est un chargement Ajax
		$user->setAjax(true);

		
		if($user->browserIsValid()){
			$isValid = 1;
		}else{

			// On récupère les variables
			$browserName = $request->getPostData('name');
			$browserVersion = (int) $request->getPostData('version');	

			// On charge le fichier de configuration pour récupérer les versions des navigateurs compatibles NooLib
			$config = $this->getApp()->getConfig();
			$currentVersionBrowsers = $config->getVar('browsers', 'version');

			// Initialisation
			$user->setBrowserIsValid(true);
			$isValid = 1;

			// On vérifie la compatibilité du navigateur du client
			if($browserName == 'ie'){ // Si le navigateur est IE
				$user->setBrowserIsValid(false);
				$isValid = 0;
			}else{
				switch ($browserName){
					case 'chrome':
						if($browserVersion < $currentVersionBrowsers['chrome']){
							$user->setBrowserIsValid(false);
							$isValid = 0;
						}
						break;
					case 'safari':
				       	if($browserVersion < $currentVersionBrowsers['safari']){
							$user->setBrowserIsValid(false);
							$isValid = 0;
						}
				        break;
				    case 'opera':
				    	if($browserVersion < $currentVersionBrowsers['opera']){
							$user->setBrowserIsValid(false);
							$isValid = 0;
						}
				        break;
			        case 'firefox':
				    	if($browserVersion < $currentVersionBrowsers['firefox']){
							$user->setBrowserIsValid(false);
							$isValid = 0;
						}
				        break;
				    case 'edge':
				    	if($browserVersion < $currentVersionBrowsers['edge']){
							$user->setBrowserIsValid(false);
							$isValid = 0;
						}
				        break;
			        default:
						$user->setBrowserIsValid(false);
						$isValid = 0;
				        break;
				}
			}

		}

		// On envoie la liste à la page
		$this->page->addVar('isValid', $isValid);
	}

}
