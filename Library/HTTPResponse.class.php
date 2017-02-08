<?php
namespace Library;

// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP HTTPResponse pour envoyer la réponse au client.    	 	  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe HTTPResponse
 * @access: public
 * @version: 1
 */

class HTTPResponse extends \Library\ApplicationComponent
{
	
	/* Définition des attributs*/
	protected $page;

	/**
	* Permet d'ajouter un header spécifique à la page
	*/
	public function addHeader($header)
	{
		if (isset($header) && !empty($header))
		{
			$header = (string) $header;
			header($header);
		}
	}


	/**
	* Permet de rédiriger le client.
	*/
	public function redirect($location)
	{
		if (isset($location) && !empty($location))
		{
			$location = (string) $location;
			header('Location: '.$location);
			exit;
		}
	}



	/**
	* Permet d'ajouter un cookie.
	*/
	public function setCookie($name, $value = '', $expire = 0, $path = '/', $domain = null, $secure = false, $httpOnly = true)
	{
		setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
		
	}


	/**
	* Permet de générer la page et de l'envoyer au client. La fonction Exit affichera ce qui
	* se trouve dans sa parenthèse.
	*/
	public function send()
	{
		if($this->getApp()->isStandAlone()){
			return $this->getPage()->getGeneratedPage();
		}else{
			exit($this->getPage()->getGeneratedPage());
		}
	}

	/**
	* Permet de générer la page d'envoyer la réponse par email au client.
	*/
	public function sendByMail()
	{
		$this->getPage()->getGeneratedPageByMail();
	}


	/**
	* Permet d'obtenir la page de la réponse
	*/
	public function getPage()
	{
		return $this->page;
	}


	/**
	* Permet d'assigner une page à la réponse
	*/
	public function setPage(Page $page)
	{
		$this->page = $page;
	}

}






