<?php
// +----------------------------------------------------------------------+
// | PHP Version 7                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib The Blog									  |
// +----------------------------------------------------------------------+
// | Classe PHP de l'application pour poster sur les réseaux sociaux.	  |		   			  										  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@noolib.com> 			  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe SocialMedia
 * @access: public
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