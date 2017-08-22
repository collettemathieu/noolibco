<?php
namespace Library;


// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP Managers pour envoyer aux managers le DAO.	  		 	  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe Managers
 * @access: public
 * @version: 1
 */

class Managers
{
	
	/* Définition des attributs */
	protected $api = null,
			  $dao = null,
			  $managers = array();

	/**
	* Constructeur de la classe
	*/
	public function __construct($api, $dao)
	{
		$this->api = $api;
		$this->dao = $dao;
	}

	/**
	* Permet d'obtenir le manager pour le module demandé dans l'URL du client.
	*/
	public function getManagerOf($module)
	{
		if(is_string($module) && !empty($module))
		{
			if(!isset($this->managers[$module]))
			{
				$manager = '\\Library\\Models\\'.$this->getAPI().$module.'Manager';
				$this->managers[$module] = new $manager($this->getDAO());
				return $this->managers[$module];
			}
			else
			{
				return $this->managers[$module];
			}
		}
		else
		{
			throw new \InvalidArgumentException('The specific module is invalid.');
		}
	}

	/* GETTERS*/

	public function getAPI()
	{
		return $this->api;
	}

	public function getDAO()
	{
		return $this->dao;
	}

}