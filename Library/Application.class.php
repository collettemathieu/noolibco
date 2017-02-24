<?php
namespace Library;

// +----------------------------------------------------------------------+
// | PHP Version 7                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP Application pour les applications permettant    |
// | de créer l'architecture de la plateforme NooLib.                  	  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe abstraite Application
 * @access: public
 * @version: 1
 */


abstract class Application{

	/* Utilisation des traits*/
	use \Library\Traits\EnCache;

	/* Définition des attributs*/
	protected $standAlone = false,
			  $HTTPRequest, 
			  $HTTPResponse, 
			  $user,
			  $config,
			  $nomApplication,
			  $file,
			  $fileUpload,
			  $fileUploadLocalData,
			  $fileDelete,
			  $fileCopy,
			  $exec;
	
	/**
	* Contructeur de la classe application.
	*/
	public function __construct()
	{	
		$this->nomApplication = '';
		$this->user = new User($this);
		$this->config = new Config($this);
		$this->HTTPResponse = new HTTPResponse($this);
		$this->HTTPRequest = new HTTPRequest($this);
		$this->file = new \Library\File($this);
		$this->fileUpload = new \Library\TypeFile\FileUpload($this);
		$this->fileUploadLocalData = new \Library\TypeFile\FileUploadLocalData($this);
		$this->fileDelete = new \Library\TypeFile\FileDelete($this);
		$this->fileCopy = new \Library\TypeFile\FileCopy($this);
		$this->exec = new Exec($this);
	}

	/**
	* Permet d'excuter l'application système
	*/
	abstract public function run();

	/**
	* Permet de savoir si une application est standAlone (cad s'exécutant uniquement par demande de la plateforme et non du client)
	*/
	public function isStandAlone()
	{
		return $this->standAlone;
	}

	/**
	* Permet d'obtenir la réponse à envoyer au client.
	*/
	public function getHTTPResponse()
	{
		return $this->HTTPResponse;
	}

	
	/**
	* Permet d'obtenir la requête du client.
	*/
	public function getHTTPRequest()
	{
		return $this->HTTPRequest;
	}



	/**
	* Permet d'obtenir le nom de l'application.
	*/
	public function getNomApplication()
	{
		return $this->nomApplication;
	}

	/**
	* Permet d'obtenir l'utilisateur.
	*/
	public function getUser()
	{
		return $this->user;
	}

	/**
	* Permet d'obtenir l'objet CONFIG pour gérer le fichier de configuration de la plateforme
	*/
	public function getConfig(){
		return $this->config;
	}

	/**
	* Permet de récupérer l'objet FileUpload pour gérer l'upload de fichiers
	*/
	public function getFile(){

		return $this->file;
	}

	/**
	* Permet de récupérer l'objet FileUpload pour gérer l'upload de fichiers
	*/
	public function getFileUpload($variableServeur = '', $tagName = ''){

		$this->fileUpload->setTableServerFile($variableServeur);
		$this->fileUpload->setRestraintsFile($tagName);
		return $this->fileUpload;
	}

	/**
	* Permet de récupérer l'objet FileUploadLocalData pour gérer l'upload de fichiers temporaires
	*/
	public function getFileUploadLocalData(){

		$this->fileUploadLocalData->setRestraintsFile();
		return $this->fileUploadLocalData;
	}

	/**
	* Permet de récupérer l'objet FileCopy pour gérer la gestion de copie de fichiers
	*/
	public function getFileCopy(){

		return $this->fileCopy;
	}

	/**
	* Permet de récupérer l'objet FileDelete pour gérer la suppression de fichiers
	*/
	public function getFileDelete(){

		return $this->fileDelete;
	}

	/**
	* Permet de récupérer l'objet Eexec pour executer des commandes Bash
	*/
	public function getExec(){

		return $this->exec;
	}


	/**
	* Permet de créer le routeur et de lui assigner les différentes routes présentes dans le
	* fichier routes.xml.
	*/
	public function getControleur(\Library\Routeur $routeur)
	{
		// Récupération du contrôleur, du module et de l'action en fonction de l'URL demandée par
		// le client.
		
		// Récupération de l'URL demandée par le client.
		$request = $this->getHTTPRequest();
		$urlDemandee = $request->getURI();

		// Si le client a demandé une connexion automatique par cookies, on dirige 
		// dans un 1er temps le client vers le contrôleur de connexion pour une connexion automatique
		// Si le client n'est pas identifié du tout, on le dirige vers le contrôleur de connexion
		
		// On vérifie dans un premier temps que l'utilisateur n'est pas sur mobile
		if(!$this->user->isMobile() && !$this->user->isAuthenticated() && !$this->user->jsIsActivated()){
			
			// On récupère les informations du navigateur client
			$br = new \Library\Browscap('../Applications/LogIn/Cache');
			$br->doAutoUpdate = false;
			//$br->updateCache();

			// Get information about the current browser's user agent
			$current_browser = $br->getBrowser(null, true);
			$isMobile = (bool) $current_browser['isMobileDevice'];
			
			if($isMobile){

				// L'utilisateur est sur un mobile
				$this->user->setIsMobile(true);

				// On procède à la redirection vers la page pour mobile (qui proposera de télécharger l'application)
				$response = $this->getHTTPResponse();
				$response->redirect('/Mobile/');
			}elseif($request->isExistCookie('mail') && $request->isExistCookie('cle')){

				// On place en session flash l'url demandée par le client
				$this->user->setFlash($urlDemandee);

				// On définit manuellement le contrôleur LogIn
				$controllerClass = 'Applications\\LogIn\\Modules\\LogIn\\LogInController';
				$controller = new $controllerClass($this, 'LogIn', 'connexionAutomatique');

				return $controller;

			}elseif($this->nomApplication != 'LogIn'){
				// On place en session flash l'url demandée par le client
				$this->user->setFlash($urlDemandee);

				// On procède à la redirection vers la page de connexion
				$response = $this->getHTTPResponse();
				$response->redirect('/LogIn/');
			}
		}elseif($this->user->isMobile() && $this->nomApplication != 'Mobile'){
			// On procède à la redirection vers la page pour mobile (qui proposera de télécharger l'application)
			$response = $this->getHTTPResponse();
			$response->redirect('/Mobile/');
		}elseif(!$this->user->isMobile() && $this->nomApplication === 'Mobile'){
			// On procède à la redirection vers la page pour mobile (qui proposera de télécharger l'application)
			//$response = $this->getHTTPResponse();
			//$response->redirect('/');
		}elseif(!$this->user->jsIsActivated()){
			// On réactive JS pour l'utilisateur
			$this->user->setJsIsActivated(true);
		}

		// On recherche le contrôleur de la route demandée
		$matchedRoute = $routeur->getRoute($urlDemandee);

		if($matchedRoute === false){
			// Sinon aucune route n'a été trouvée en correspondance avec
			// l'URL demandée par le client. Nous renvoyons donc le client vers 
			// la route par défaut (soit l'acceuil).
			$matchedRoute = $routeur->getRouteDefault();

			if($this->nomApplication === 'Frontend'){
				// On place en session flash l'url demandée par le client pour traiter
				// si par exemple un nom d'application a été mentionnée
				$this->user->setFlash($urlDemandee);
			}
		}

		
		// On ajoute les variables de l'URL à la variables getData de HTTPRequest.
		$this->HTTPRequest->setGetData($matchedRoute->getVars());

		// On instancie le contrôleur.
		$controllerClass = 'Applications\\'.$this->getNomApplication().'\\Modules\\'.$matchedRoute->getModule().'\\'.$matchedRoute->getModule().'Controller';

		// On retourne le contrôleur.
		$controller = new $controllerClass($this, $matchedRoute->getModule(), $matchedRoute->getAction());
	
		return $controller;
	}


	/**
	* Permet de créer le contrôleur pour les applications StandAlone (sans requête HTTP).
	*/
	public function getControleurForApplicationStandAlone($module, $action)
	{

		// On instancie le contrôleur.
		$controllerClass = 'Applications\\ApplicationsStandAlone\\'.$this->getNomApplication().'\\Modules\\'.$module.'\\'.$module.'Controller';

		// On retourne le contrôleur.
		$controller = new $controllerClass($this, $module, $action);
	
		return $controller;
	}

}

