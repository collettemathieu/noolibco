<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour l'envoi d'un mail à un auteur.		  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe SendMailToAuthorController
 * @access: private
 * @version: 1
 */


namespace Applications\ApplicationsStandAlone\Mail\Modules\SendMailToAuthor;
	
class SendMailToAuthorController extends \Library\BackController
{

	use \Library\Traits\MethodeUtilisateurControleur;

	/**
	* Permet d'envoyer un message à un auteur via son profil
	**/
	public function executeSendAMessage()
	{
		// On récupère l'utilisateur
		$user = $this->app->getUser();

		// On récupère l'utilisateur en session
		$utilisateur = unserialize($user->getAttribute('userSession'));

		// On récupère les variables du message
		$variablesArray = $user->getFlash();

		$expediteur = $utilisateur->getMailUtilisateur();
		$destinataire = $variablesArray['mailAuteur'];
		$titreMail = $variablesArray['titreMessage'];

		//On définit le message du mail
		$message = $variablesArray['messageMail'];

		// On envoit les variables à la page avec les noms de variables spécifiques
		$this->page->addVar('expediteur', $expediteur);
		$this->page->addVar('destinataires', $destinataire);
		$this->page->addVar('titreMail', $titreMail);
		$this->page->addVar('message', $message);

	}


	/**
	* Permet d'envoyer un message à un auteur via son application
	**/
	public function executeSendAMessageForApplication()
	{
		// On récupère l'utilisateur
		$user = $this->app->getUser();

		// On récupère l'utilisateur en session
		$utilisateur = unserialize($user->getAttribute('userSession'));

		// On récupère les variables du message
		$variablesArray = $user->getFlash();

		$expediteur = $utilisateur->getMailUtilisateur();
		$destinataire = $variablesArray['mailAuteur'];
		$titreMail = $variablesArray['titreMessage'];
		$nomApplication = $variablesArray['nomApplication'];

		//On définit le message du mail
		$message = $variablesArray['messageMail'];

		// On envoit les variables à la page avec les noms de variables spécifiques
		$this->page->addVar('expediteur', $expediteur);
		$this->page->addVar('destinataires', $destinataire);
		$this->page->addVar('titreMail', $titreMail);
		$this->page->addVar('message', $message);
		$this->page->addVar('nomApplication', $nomApplication);

	}
}