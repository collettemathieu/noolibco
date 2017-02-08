<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP de l'application pour poster sur les réseaux sociaux.	  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe SocialMedia
 * @access: private
 * @version: 1
 */

namespace Applications\ApplicationsStandAlone\SocialMedia;  

class SocialMediaApplication extends \Library\Application
{
	
	/* Appel du constructeur parent */
	public function __construct()
	{
		parent::__construct();
		$this->nomApplication = 'SocialMedia';
		$this->standAlone = true; // On spécifie SocialMedia comme une application StandAlone
	}

	public function run(){}

	/**
	* Permet exécuter l'application Mail en interne
	*/
	public function execute($module, $action)
	{
		// On récupére le contrôleur
		$controleur = $this->getControleurForApplicationStandAlone($module, $action);

		// On exécute le contrôleur demandé
		$controleur->execute();
	}
}