<?php
namespace Library;
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP de la classe User pour gérer les variables de session 	  |
// | l'utilisateur sur la plateforme NooLib.      						  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe User
 * @access: public
 * @version: 1
 */
/* Démarrage de la session */
// Au moins 5 heures (3600 * 5 = 18000)
ini_set("session.gc_maxlifetime", "18000");
ini_set("session.cookie_lifetime", "0");
session_start();

class User extends ApplicationComponent
{

	/*Définition des attributs*/
	protected $messageClient;

	/*Constructeur de la classe */
	public function __construct(Application $app)
	{
		// On appel le constructeur parent.
		parent::__construct($app);
		$this->messageClient = new MessageClient();
	}

	/**
	* Retourne la valeur de l'attribut du tableau $_SESSION[].
	*/
	public function getAttribute($attribut)
	{
		if(isset($_SESSION[$attribut]))
		{
			return $_SESSION[$attribut];
		}
		else
		{
			return null;
		}
	}

	/**
	* Ajouter un attribut au tableau $_SESSION[].
	*/
	public function setAttribute($attribut, $value)
	{
		if(isset($attribut) && isset($value))
		{
			$_SESSION[$attribut] = $value;
		}
	}

	/**
	* Supprimer un attribut au tableau $_SESSION[].
	*/
	public function delAttribute($attribut){
		if(isset($_SESSION[$attribut])){
			unset($_SESSION[$attribut]);
		}
	}

	/**
	* Supprimer tous les attributs en session
	*/
	public function delAllAttribute(){
		foreach($_SESSION as $cle=>$attribut){
			unset($_SESSION[$cle]);
		}
	}

	public function setFlash($value)
	{
		if(isset($value) && !empty($value))
		{
			$_SESSION['flash'] = $value;
		}
	}

	public function getFlash()
	{
		if(isset($_SESSION['flash'])){
			$flash = $_SESSION['flash'];
			unset($_SESSION['flash']);

			return $flash;
		}else{
			return null;
		}
		
	}

	public function hasFlash()
	{
		return isset($_SESSION['flash']);
	}


	public function getMessageClient(){
		return $this->messageClient;
	}


	public function hasAttribute($attribut)
	{
		return isset($_SESSION[$attribut]);
	}
	
	/**
	* Permet de gérer l'authentification de l'utilisateur sur la plateforme
	*/
	public function isAuthenticated()
	{
		return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
	}

	public function setAuthenticated($authenticated)
	{
		if(is_bool($authenticated))
		{
			$_SESSION['authenticated'] = $authenticated;
		}
		else
		{
			throw new \InvalidArgumentException('The specific value in USER::setAuthenticated must be a boolean');
		}
	}


	/**
	* Permet de gérer la validité du navigateur de l'utilisateur sur la plateforme
	*/
	public function browserIsValid()
	{
		return isset($_SESSION['browserValid']) && $_SESSION['browserValid'] === true;
	}

	public function setBrowserIsValid($browserValid)
	{
		if(is_bool($browserValid))
		{
			$_SESSION['browserValid'] = $browserValid;
		}
		else
		{
			throw new \InvalidArgumentException('The specific value in USER::setBrowserIsValid must be a boolean');
		}
	}

	/**
	* Permet de gérer l'activation de JS du navigateur de l'utilisateur sur la plateforme
	*/
	public function jsIsActivated()
	{
		return isset($_SESSION['jsActivated']) && $_SESSION['jsActivated'] === true;
	}

	public function setJsIsActivated($jsActivated)
	{
		if(is_bool($jsActivated))
		{
			$_SESSION['jsActivated'] = $jsActivated;
		}
		else
		{
			throw new \InvalidArgumentException('The specific value in USER::setJsIsActivated must be a boolean');
		}
	}

	/**
	* Permet de gérer la connexion via des mobiles
	*/
	public function isMobile()
	{
		if(!isset($_SESSION['isMobile'])){
			return false;
		}else{
			return $_SESSION['isMobile'];
		}
		
	}

	public function setIsMobile($isMobile)
	{
		if(is_bool($isMobile))
		{
			$_SESSION['isMobile'] = $isMobile;
		}
		else
		{
			throw new \InvalidArgumentException('The specific value in USER::setIsMobile must be a boolean');
		}
	}

	/**
	* Permet de gérer les chargements en Ajax
	*/
	public function setAjax($value)
	{
		if(isset($value) && is_bool($value))
		{
			$_SESSION['ajax'] = $value;	
		}
	}

	public function getAjax()
	{
		if(isset($_SESSION['ajax'])){
			$flash = $_SESSION['ajax'];
			unset($_SESSION['ajax']);

			return $flash;
		}else{
			return null;
		}
		
	}


	/**
	* Permet de vérifier si une requête Ajax est déjà en cours d'exécution.
	*/
	public function ajaxLastRequestTimer($time = null)
	{
		if(isset($time) && is_int($time)){
			$_SESSION['ajaxLastRequestTimer'] = $time;
		}else{
			if(!isset($_SESSION['ajaxLastRequestTimer'])){
				return time()-31;
			}else{
				return $_SESSION['ajaxLastRequestTimer'];
			}
		}
	}
	public function isAjaxRequestAlreadyRunning()
	{
		if(!isset($_SESSION['isAjaxRequestAlreadyRunning'])){
			return false;
		}else{
			return $_SESSION['isAjaxRequestAlreadyRunning'];
		}
	}
	public function setIsAjaxRequestAlreadyRunning($isRunning)
	{
		if(is_bool($isRunning)){
			$_SESSION['isAjaxRequestAlreadyRunning'] = $isRunning;
		}
		else{
			throw new \InvalidArgumentException('The specific value in USER::setIsAjaxRequestRunning must be a boolean');
		}
	}

}