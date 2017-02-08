<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP comme controleur principale du module backend			  |
// +----------------------------------------------------------------------+
// | Auteurs : Guénaël DEQUEKER <dequekerguenael@noolib.com> 		      |
// | 		   Steve DESPRES    <despressteve@noolib.com> 		     	  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe DefautController
 * @access: public
 * @version: 1
 */	

namespace Applications\Backend\Modules\Defaut;
	
class DefautController extends \Library\BackController{

	use \Library\Traits\MethodeUtilisateurControleur;

	public function executeShow()
	{
		$user = $this->app->getUser();
		
		$utilisateur = unserialize($user->getAttribute('userSession'));
		//si l'utilisateur n'est pas logué en temps qu'admin et si l'utilisateur ne peut pas être administrateur
		if(!$user->getAttribute('isAdmin') && $utilisateur->getPasswordAdminUtilisateur() == '')
		{
			$user->getMessageClient()->addErreur(self::DENY_ACCESS_PAGE);
			$this->page->addVar('admisSurLaPage', false);
			// On procède à la redirection vers la page d'inscription
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
		else
		{
			$this->page->addVar('admisSurLaPage', true);
		}
	}
	
	public function executeSortirDuModeAdmin()
	{
		$user = $this->app->getUser();
		$user->setAttribute('isAdmin', false);
		$response = $this->app->getHTTPResponse();
		$response->redirect('/');
	}
	
	public function executePasserEnAdmin($request)
	{
		$user = $this->app->getUser();
		
		$userSession = unserialize($user->getAttribute('userSession'));
		
		//on récupère le mot de passe envoyé
		$passwordAdmin = $request->getPostData('passwordAdmin');
		//si l'utilisateur ne peut pas être admin
		if($userSession->getPasswordAdminUtilisateur() == '')
		{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
			
			$user->getMessageClient()->addErreur(self::DENY_ACCESS_PAGE);
		}
		else
		{
			//si rien envoyé en post
			if($passwordAdmin == null || $passwordAdmin == '')
			{
				$response = $this->app->getHTTPResponse();
				$user->getMessageClient()->addErreur(self::BACKEND_WRONG_PASSWORD);
				$response->redirect('/PourAdminSeulement/');
			}
			else
			{
				$config = $this->getApp()->getConfig();
				
				//si il a mal tapé son mot de passe administrateur
				if( !password_verify( $passwordAdmin, $userSession->getPasswordAdminUtilisateur() ))
				{
					//on fait s'écrire l'erreur (TOP10 des commentaires de Guénaël !)
					$user->getMessageClient()->addErreur(self::BACKEND_WRONG_PASSWORD);
					
					// si non, on procède à la redirection
					$response = $this->app->getHTTPResponse();
					$response->redirect('/PourAdminSeulement/');
				}
				else
				{
					//on pass l'utilisateur en admin
					$user->setAttribute('isAdmin', true);
					$user->getMessageClient()->addReussite(self::BACKEND_WELCOME_ADMIN);
					
					// si non, on procède à la redirection
					$response = $this->app->getHTTPResponse();
					$response->redirect('/PourAdminSeulement/');
				}
			}
		}
		
	}
}