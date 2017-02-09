<?php
namespace Library;

// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP HTTPRequest pour récupérer les données de la requête 	  |
// | du client.     													  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe HTTPRequest
 * @access: public
 * @version: 1
 */

class HTTPRequest extends \Library\ApplicationComponent
{
	/**
	* Définitions des attributs.
	*/

	protected $cookieData = array(),
			  $postData = array(),
			  $getData = array();

	/**
	* Constructeur de la classe.
	*/
	
	public function __construct(Application $app)
	{
		// On appel le constructeur parent.
		parent::__construct($app);

		// Permet de gérer les erreurs fatales avec javascript via l'appel en ajax
		ini_set('display_errors', false);

		// On construit les variables de HTTPRequest avec les variables post, get et les cookies.
		// Suppression des variables globales Get, Post, mais pas Cookie sinon nouvelle section.
		if(isset($_COOKIE)){
			$this->setCookieData($_COOKIE);	
		}
		if(isset($_POST)){
			$this->setPostData($_POST);
			unset($_POST);
		}
		if(isset($_GET)){
			$this->setGetData($_GET);
			//unset($_GET);
		}		
	}

	/* SETTERS */

	public function setCookieData(array $donnees)
	{
		if(isset($donnees) && !empty($donnees))
		{
			foreach ($donnees as $key => $value)
			{
				// On protège les données entrées.
				if(!empty($value)){
					$value = htmlspecialchars($value);
					$this->cookieData[$key] = $value;
				}
			}
		}
	}
	
	public function setPostData(array $donnees)
	{
		if(isset($donnees) && !empty($donnees))
		{
			
			foreach ($donnees as $key => $value)
			{
				// On protège les données entrées.
				$value = trim(htmlspecialchars($value));
				// On supprime les caractères étranges
				$value = preg_replace('\'\"', '', $value);
				$this->postData[$key] = $value;
			}
		}
	}


	public function setGetData(array $donnees)
	{
		if(isset($donnees) && !empty($donnees))
		{
			foreach ($donnees as $key => $value)
			{
				// On protège les données entrées.
				$value = trim(htmlspecialchars($value));
				$this->getData[$key] = $value;
			}
		}
	}
	
	/* GETTERS */
	
	public function getCookieData($value)
	{
		if(isset($this->cookieData[$value])){
			return $this->cookieData[$value];
		}else{
			return null;
		}
	}

	
	public function getPostData($value)
	{
		if(isset($this->postData[$value])){
			return $this->postData[$value];
		}else{
			return null;
		}
	}

	/**
	* Fonction permettant de récupérer toutes les données en POST.
	*/
	public function getPostAllData(){
		if(!empty($this->postData)){
			$postDataTab = array();
			foreach($this->postData as $key => $data){
				$postDataTab[$key] = $data;
			}
			return $postDataTab;
			
		}else{
			return null;
		}
	}


	public function getGetData($value)
	{
		if(isset($this->getData[$value])){
			return $this->getData[$value];
		}else{
			return null;
		}
	}



	/**
	* Permet de récupérer la méthode employée pour envoyer la requête.
	*/
	public function getMethodRequest()
	{
		return $_SERVER['REQUEST_METHOD'];
	}


	/**
	* Permet de récupérer l'URL entrée par le client.
	*/
	public function getURI()
	{
		return $_SERVER['REQUEST_URI'];
	}


	/**
	* Permet de vérifier l'existence du cookie.
	*/
	public function isExistCookie($value)
	{
		return isset($this->cookieData[$value]);
	}


	/**
	* Permet de vérifier l'existence de la variable POST.
	*/
	public function isExistPOST($value)
	{
		return isset($this->postData[$value]);
	}


	/**
	* Permet de vérifier l'existence de la variable GET.
	*/
	public function isExistGET($value)
	{
		return isset($this->getData[$value]);
	}

	/**
	* Permet de vérifier si la requête a été envoyée en Ajax.
	*/
	public function isAjaxRequest(){
		if (array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			// On ajoute un indicateur permettant de savoir si un travail Ajax sur des données est déjà en cours
			if($this->app->getNomApplication() === 'HandleData'){
				// On récupère l'utilisateur système
				$user = $this->app->getUser();
				$currentTime = time();
				if($currentTime > $user->ajaxLastRequestTimer() - 2){ // On autorise une requête toutes les 2 secondes (+2)
					$user->setIsAjaxRequestAlreadyRunning(false);
					$user->ajaxLastRequestTimer($currentTime);
				}else{
					$user->setIsAjaxRequestAlreadyRunning(true);
				}
			}
			return true;
		}else{
			return false;
		}
	}
}
