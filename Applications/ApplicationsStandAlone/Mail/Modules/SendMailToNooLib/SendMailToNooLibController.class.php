<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2015 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour l'envoi d'un mail à un NooLib.		  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe SendMailToNooLib
 * @access: private
 * @version: 1
 */


namespace Applications\ApplicationsStandAlone\Mail\Modules\SendMailToNooLib;
	
class SendMailToNooLibController extends \Library\BackController{

	use \Library\Traits\MethodeUtilisateurControleur;

	/**
	* Permet d'envoyer un message à un NooLib
	**/
	public function executeSendAMessage(){
		// On récupère l'utilisateur
		$user = $this->app->getUser();

		// On récupère les variables du message
		$variablesArray = $user->getFlash();

		$expediteur = $variablesArray['mailUtilisateur'];
		$destinataire = 'hostmaster@noolib.com';
		$titreMail = $variablesArray['titreMessage'];

		//On définit le message du mail
		$message = $variablesArray['messageMail'];

		// On envoi les variables à la page avec les noms de variables spécifiques
		$this->page->addVar('expediteur', $expediteur);
		$this->page->addVar('destinataires', $destinataire);
		$this->page->addVar('titreMail', $titreMail);
		$this->page->addVar('message', $message);

	}
}