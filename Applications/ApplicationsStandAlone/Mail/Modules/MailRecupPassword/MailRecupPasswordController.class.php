<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP comme controleur du module d'inscription des utilisateurs.|
// | Ce controleur permet d'inscrire l'utilisateur sur le site et de 	  |
// | contrôler son compte.												  |
// +----------------------------------------------------------------------+
// | Auteur : Steve Despres <despressteve@noolib.com>    			      |
// +----------------------------------------------------------------------+

/**
 * @name: controleur de la rubrique inscription des utilisateurs
 * @access: public
 * @version: 1
 */	
 
namespace Applications\ApplicationsStandAlone\Mail\Modules\MailRecupPassword;
	
class MailRecupPasswordController extends \Library\BackController{

	use \Library\Traits\MethodeUtilisateurControleur;
	
	/**
	* Permet d'envoyer un lien de récuperation du mot de passe
	**/
	
		public function executeShow(){
	
		// On récupère l'utilisateur
		$user = $this->app->getUser();

		// On récupère la variable Flash
		$mailUtilisateur = $user->getFlash();

		$expediteur = 'contact@noolib.com';

		//On définit le titre du mail
		$titre = self::MAIL_RESET_PASSWORD;

		//On créé un jeton avec en paramètre l'email de l'utilisateur
		$jeton = $this->codeJeton($mailUtilisateur);

		if($jeton){

			// On place le jeton en session de l'utilisateur
			$user->setAttribute('jetonUser', $jeton);

			//On définit le lien de validation				
			$lienRecupPassword ='http://noolib.com/LogIn/ResetPassword/j='.$jeton;
			
			// On envoit les variables à la page avec les noms de variables spécifiques
			$this->page->addVar('expediteur', $expediteur);
			$this->page->addVar('destinataires', $mailUtilisateur);
			$this->page->addVar('titreMail', $titre);
			$this->page->addVar('lienValidation', $lienRecupPassword);

		}else{
			
		}
	}

}