<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP de l'application en lecture seule Mail.			  		  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+


/**
 * @name:  Classe Mail
 * @access: private
 * @version: 1
 */

namespace Applications\ApplicationsStandAlone\Mail;

class MailApplication extends \Library\Application
{
	
	/* Appel du constructeur parent */
	public function __construct()
	{
		parent::__construct();
		$this->nomApplication = 'Mail';
		$this->standAlone = true; // On spécifie Mail comme une application StandAlone
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

		// On crée la page associée
		$this->HTTPResponse->setPage($controleur->getPage());

		// On envoie la réponse au client sous forme de Mail.
		$this->HTTPResponse->sendByMail();
	}
}