<?php
namespace Library;

// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP ApplicationComponent pour les composantes des   |
// | applications constituant l'architecture de la plateforme NooLib.     |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe abstraite ApplicationComponent
 * @access: public
 * @version: 1
 */
		
abstract class ApplicationComponent
{
	
	/* Définition des attributs*/
	protected $app;

	/**
	* Constructeur de la classe ApplicationComponent.
	*/
	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	/**
	* Permet d'obtenir l'attribut de l'application en cours d'execution.
	*/
	public function getApp()
	{
		return $this->app;
	}
}