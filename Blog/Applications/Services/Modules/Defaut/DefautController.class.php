<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 AboutScience			         		          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour l'affichage de la page des services.	  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@aboutscience.com>    	  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe DefautController
 * @access: public
 * @version: 1
 */	


namespace Applications\Services\Modules\Defaut;
	
class DefautController extends \Library\BackController
{
	public function executeShow($request){
		
	}

	public function executeContact($request){
		$user = $this->app->getUser();

		$adresseMail = trim($request->getPostData('adresseMail'));
		$sujet = trim($request->getPostData('sujet'));
		$message = trim($request->getPostData('message'));

		// On charge le fichier de configuration
		$config = $this->getApp()->getConfig();

		if($config->validMail($adresseMail)){

			if(!empty($sujet) && !empty($message)){

				// Manager des utilisateurs
				$managerUser = $this->getManagers()->getManagerOf('Utilisateur');

				// On enregistre l'utilisateur dans la base s'il n'existe pas encore
				$utilisateur = $managerUser->getUtilisateurByMail(trim($request->getPostData('adresseMail')));

				if(!$utilisateur instanceof \Library\Entities\Utilisateur){

					// On vérifie que l'adresse électronique de l'utilisateur n'est pas une adresse jetable
					
					$utilisateur = new \Library\Entities\Utilisateur(array(
						'nomUtilisateur' => trim($request->getPostData('nom')),
						'mailUtilisateur' => $adresseMail,
						'passwordAdminUtilisateur' => '',
						'newsletterUtilisateur' => true,
						'superAdminUtilisateur' => false
					));

					if(sizeof($utilisateur->getErreurs()) != 0){
						$user->getMessageClient()->addErreur($utilisateur->getErreurs());
					}else{
						// Insertion en BDD si l'utilisateur n'existe pas
						if(empty($utilisateur->getIdUtilisateur())){
							$utilisateur = $managerUser->addUtilisateur($utilisateur);
						}
					}
				}

				//On envoi un mail à l'administrateur par l'application Mail en StandAlone
				// On place la variable en Flash pour qu'elle soit récupérée par l'application Mail
				$user->setFlash(array(
					'mailAuteur' => $adresseMail,
					'titreMessage' => $sujet,
					'message' => $message
					));
				$mailApplication = new \Applications\ApplicationsStandAlone\Mail\MailApplication;
				$mailApplication->execute('SendMailToScienceAPart', 'sendAMessage'); // Module = MailInscription ; action = sendAMessageForValidatingComment
				
				$user->getMessageClient()->addReussite($user->getFlash());

			}else{
				$user->getMessageClient()->addErreur('Le sujet ou le message de votre demande ne peut être vide.');
			}
		}else{
			$user->getMessageClient()->addErreur('L\'adresse électronique renseignée ne semble pas être une adresse valide.');
		}
		
		$response = $this->app->getHTTPResponse();
		$response->redirect('/Services/');	
		
	}
}