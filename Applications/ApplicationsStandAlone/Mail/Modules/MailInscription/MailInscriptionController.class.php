<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour l'envoi par mail d'une validation      |
// | d'inscription de l'utilisateur.	  								  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe MailInscriptionController
 * @access: private
 * @version: 1
 */


namespace Applications\ApplicationsStandAlone\Mail\Modules\MailInscription;
	
class MailInscriptionController extends \Library\BackController
{

	use \Library\Traits\MethodeUtilisateurControleur;

	/**
	* Permet d'envoyer un lien de validation pour l'inscription
	**/
	public function executeShow()
	{
		// On récupère l'utilisateur
		$user = $this->app->getUser();

		// On récupère la variable Flash
		$mailUtilisateur = $user->getFlash();

		$expediteur = 'contact@noolib.com';

		//On définit le titre du mail
		$titre = self::MAIL_SUBSCRIPTION;

		//On créé un jeton avec en paramètre l'email de l'utilisateur
		$jeton = $this->codeJeton($mailUtilisateur);

		if($jeton){
			//On définit le lien de validation				
			$lienValidation ='https://www.noolib.com/LogIn/ValidationMail/j='.$jeton;
			
			// On envoit les variables à la page avec les noms de variables spécifiques
			$this->page->addVar('expediteur', $expediteur);
			$this->page->addVar('destinataires', $mailUtilisateur);
			$this->page->addVar('titreMail', $titre);
			$this->page->addVar('lienValidation', $lienValidation);

		}else{
			
		}
	}
}