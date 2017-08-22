<?php

namespace Library;

// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP Route pour gérer les routes URL.			  		 	  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe Route
 * @access: public
 * @version: 1
 */


class Route
{
	/* Définition des attributs */
	protected $action,
			  $module,
			  $url,
			  $varsNames,
			  $vars = array();
	
	/**
	* Constructeur de la classe.
	*/
	public function __construct($url, $module, $action, array $varsNames)
	{
		$this->setUrl($url);
		$this->setModule($module);
		$this->setAction($action);
		$this->setVarsNames($varsNames);
	}


	/**
	* Permet de vérifier si la route possède des variables.
	*/
	public function hasVars()
	{
		return !empty($this->varsNames);
	}


	/**
	* Permet de savoir si la route correspond à l'URL demandée par le client.
	*/
	public function match($url)
	{
		// On contrôle qu'il y a concordance entre une route et l'url demandé par l'utilisateur. 
		// $matches récupère les variables entre () et $matches[0] récupère l'expression complète.
		if (preg_match('#^'.$this->getUrl().'$#', $url, $matches))
		{
			return $matches;
		}
		else
		{
			return false;
		}
	}



	/* SETTERS */


	public function setUrl($url)
	{
		if(is_string($url))
		{
			$this->url = $url;
		}
		else
		{
			$this->url = '';
		}
	}

	public function setModule($module)
	{
		if(is_string($module))
		{
			$this->module = $module;
		}
		else
		{
			$this->module = '';
		}
	}

	public function setAction($action)
	{
		if(is_string($action))
		{
			$this->action = $action;
		}
		else
		{
			$this->action = '';
		}
	}


	public function setVarsNames(array $varsNames)
	{
		$this->varsNames = $varsNames;
	}


	public function setVars(array $vars)
	{
		$this->vars = $vars;
	}


	/* GETTERS */


	public function getUrl()
	{
		return $this->url;
	}

	public function getAction()
	{
		return $this->action;
	}

	public function getModule()
	{
		return $this->module;
	}

	public function getVarsNames()
	{
		return $this->varsNames;
	}

	public function getVars()
	{
		return $this->vars;
	}
}













