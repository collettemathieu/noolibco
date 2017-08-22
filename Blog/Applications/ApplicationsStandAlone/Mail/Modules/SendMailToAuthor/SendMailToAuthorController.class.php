<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour l'envoi d'un mail à un auteur.		  |	  										  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
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
	public function executeSendAMessageForValidatingComment()
	{
		// On récupère l'utilisateur
		$user = $this->app->getUser();

		// On récupère les variables du message
		$variablesArray = $user->getFlash();

		$expediteur = 'contact@scienceapart.com';
		$destinataire = $variablesArray['mailAuteur'];
		$titreMail = $variablesArray['titreMessage'];
		$lienValidation = $variablesArray['lienValidation'];

		// On envoit les variables à la page avec les noms de variables spécifiques
		$this->page->addVar('expediteur', $expediteur);
		$this->page->addVar('destinataires', $destinataire);
		$this->page->addVar('titreMail', $titreMail);
		$this->page->addVar('lienValidation', $lienValidation);
	}


}