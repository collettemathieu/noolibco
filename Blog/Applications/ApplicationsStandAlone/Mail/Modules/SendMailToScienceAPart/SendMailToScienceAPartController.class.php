<?php
// +----------------------------------------------------------------------+
// | PHP Version 7                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib The Blog									  |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour l'envoi d'un mail à un NooLib.		  |	  										  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@noolib.com> 			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe SendMailToScienceAPart
 * @access: private
 * @version: 1
 */


namespace Applications\ApplicationsStandAlone\Mail\Modules\SendMailToScienceAPart;
	
class SendMailToScienceAPartController extends \Library\BackController{

	
	/**
	* Permet d'envoyer un message à un ScienceAPart
	**/
	public function executeSendAMessage(){
		// On récupère l'utilisateur
		$user = $this->app->getUser();

		// On récupère les variables du message
		$variablesArray = $user->getFlash();

		$expediteur = $variablesArray['mailAuteur'];
		$destinataire = 'contactteam@noolib.com';
		$titreMail = $variablesArray['titreMessage'];

		//On définit le message du mail
		$message = $variablesArray['message'];

		// On envoi les variables à la page avec les noms de variables spécifiques
		$this->page->addVar('expediteur', $expediteur);
		$this->page->addVar('destinataires', $destinataire);
		$this->page->addVar('titreMail', $titreMail);
		$this->page->addVar('message', $message);

	}
}