<?php

namespace Library;

// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP Manager pour les managers PDO.    		 	  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe Manager
 * @access: public
 * @version: 1
 */

abstract class Manager
{
	/* Définition des attributs */
	protected $dao;

	/* Constructeur de la classe */
	public function __construct($dao)
	{
		if(isset($dao))
		{
			$this->dao = $dao;
		}
	}

	public function getDAO(){
		return $this->dao;
	}

}
