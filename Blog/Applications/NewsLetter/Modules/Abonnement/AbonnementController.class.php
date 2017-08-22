<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Ce controleur permet de gérer les abonnements à la newsletter 	 	  |	  										  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: controleur de gestion des abonnements à la newsletter
 * @access: public
 * @version: 1
 */	


namespace Applications\NewsLetter\Modules\Abonnement;

class AbonnementController extends \Library\BackController{
	
	
	// Méthode pour ajouter un utilisateur comme abonné à la newsletter
	public function executeAjouterUtilisateur($request){
		
		//On récupère la requête du client
		$user = $this->app->getUser();

		// On charge le fichier de configuration
		$config = $this->getApp()->getConfig();

		// Manager des utilisateurs
		$managerUser = $this->getManagers()->getManagerOf('Utilisateur');

		// On tente de récupérer l'utilisateur si celui-ci existe. Sinon on le crée.
		$utilisateur = $managerUser->getUtilisateurByMail(trim($request->getPostData('adresseMail')));
		
		if(!$utilisateur instanceof \Library\Entities\Utilisateur){

			// On vérifie que l'adresse électronique de l'utilisateur n'est pas une adresse jetable
			$adresseMail = trim($request->getPostData('adresseMail'));
			if($config->validMail($adresseMail) == true){
				$mailUtilisateur = trim($adresseMail);
			}else{
				$mailUtilisateur = false;
			}

			$utilisateur = new \Library\Entities\Utilisateur(array(
				'nomUtilisateur' => trim($request->getPostData('nom')),
				'mailUtilisateur' => $mailUtilisateur,
				'passwordAdminUtilisateur' => '',
				'newsletterUtilisateur' => true,
				'superAdminUtilisateur' => false
			));
		}else{
			$utilisateur->hydrate(array(
				'newsletterUtilisateur' => true
				));
		}
		
		if(sizeof($utilisateur->getErreurs()) != 0){
			$user->getMessageClient()->addErreur($utilisateur->getErreurs());
		}else{
			
			// Insertion en BDD si l'utilisateur n'existe pas sinon on le sauve
			if(empty($utilisateur->getIdUtilisateur())){
				$utilisateur = $managerUser->addUtilisateur($utilisateur);
			}else{
				$managerUser->saveUtilisateur($utilisateur);
			}

			$user->getMessageClient()->addReussite('Merci. Vous êtes à présent abonné à notre newsletter.');
		}

		$response = $this->app->getHTTPResponse();
		$response->redirect('/Services/');
	}
	

	// Méthode pour désabonner un utilisateur de la newsletter
	public function executeDesabonnerUtilisateur($request){
		//On récupère la requête du client
		$user = $this->app->getUser();

		// On charge le fichier de configuration
		$config = $this->getApp()->getConfig();

		// Manager des utilisateurs
		$managerUser = $this->getManagers()->getManagerOf('Utilisateur');

		// On tente de récupérer l'utilisateur si celui-ci existe. Sinon on le crée.
		$utilisateur = $managerUser->getUtilisateurByMail(trim($request->getGetData('adresseMail')));
		
		if($utilisateur instanceof \Library\Entities\Utilisateur){

			$utilisateur->hydrate(array(
				'newsletterUtilisateur' => false
				));

			if(sizeof($utilisateur->getErreurs()) != 0){
				$user->getMessageClient()->addErreur($utilisateur->getErreurs());
			}else{
				
				// Sauvegarde en BDD
				$managerUser->saveUtilisateur($utilisateur);
				
				$user->getMessageClient()->addReussite('Merci. Vous êtes à présent désabonné de notre newsletter.');
			}
		}else{
			$user->getMessageClient()->addErreur('Cette adresse email ne semble pas présente dans notre base de données.');
		}
		
		$response = $this->app->getHTTPResponse();
		$response->redirect('/');
	}
}
