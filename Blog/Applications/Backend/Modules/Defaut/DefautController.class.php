<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP comme controleur principale du module backend			  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe DefautController
 * @access: public
 * @version: 1
 */	

namespace Applications\Backend\Modules\Defaut;
	
class DefautController extends \Library\BackController{

	use \Library\Traits\MethodeUtilisateurControleur;

	public function executeShow(){	
		$user = $this->app->getUser();
		if($user->getAttribute('isAdmin')) {
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/ForAdminOnly/Articles/');
		}
		
	}
	
	public function executeSortirDuModeAdmin()
	{
		$user = $this->app->getUser();
		$user->setAttribute('isAdmin', false);
		$user->setAttribute('isSuperAdmin', false);
		$response = $this->app->getHTTPResponse();
		$response->redirect('/');
	}
	
	public function executePasserEnAdmin($request)
	{
		$user = $this->app->getUser();
		
		//On récupère le mot de passe et l'email envoyé
		$passwordAdmin = $request->getPostData('passwordAdmin');
		$mailAdmin = $request->getPostData('mailAdmin');
		
		// Si aucune donnée utilisateur envoyé
		if($passwordAdmin == null || $passwordAdmin == '' || $mailAdmin == null || $mailAdmin == ''){

			$response = $this->app->getHTTPResponse();
			$user->getMessageClient()->addErreur(self::BACKEND_WRONG_PASSWORD);
			$response->redirect('/ForAdminOnly/');
		}else{
			//On appelle le manager des Utilisateurs
			$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
			//On récupère l'objet utilisateur s'il existe dans la BDD
			$utilisateur = $managerUtilisateur->getUtilisateurByMail($mailAdmin);
			
			if($utilisateur){
				// Vérification du mot de passe
				if( !password_verify( $passwordAdmin, $utilisateur->getPasswordAdminUtilisateur() ))
				{
					$user->getMessageClient()->addErreur(self::BACKEND_WRONG_PASSWORD);
					
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/');
				}
				else
				{
					//On passe l'utilisateur en admin
					$user->setAttribute('isAdmin', true);
					//Si l'administrateur est un super-admin, on le signale
					if($utilisateur->getSuperAdminUtilisateur()){
						$user->setAttribute('isSuperAdmin', true);
						$user->getMessageClient()->addReussite(self::BACKEND_WELCOME_SUPER_ADMIN);	
					}else{
						$user->setAttribute('isSuperAdmin', false);
						$user->getMessageClient()->addReussite(self::BACKEND_WELCOME_ADMIN);
					}
				
					$response = $this->app->getHTTPResponse();

					// On crée le cookie de connexion admin
					$response->setCookie('admin', true, time()+365*24*3600);
					//On procède à la redirection
					$response->redirect('/ForAdminOnly/Articles/');
				}
			}else{
				$response = $this->app->getHTTPResponse();
				$user->getMessageClient()->addErreur(self::BACKEND_WRONG_PASSWORD);
				$response->redirect('/ForAdminOnly/');
			}		

		}
		
	}
}