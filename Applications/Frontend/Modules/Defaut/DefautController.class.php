<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour l'affichage de la page par défaut.	  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe DefautController
 * @access: public
 * @version: 1
 */	


namespace Applications\Frontend\Modules\Defaut;
	
class DefautController extends \Library\BackController
{
	public function executeShow($request){
		
		// On contrôle si un nom d'application n'a pas été placé dans l'url
		// dans ce cas on renvoie l'utilisateur à la fiche de l'application
		$user = $this->app->getUser();
		if($user->hasFlash()){
			$nomApplication = urldecode($user->getFlash());
			$nomApplication = substr($nomApplication, 1);
			//On appelle le manager des Applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');
			$application = $managerApplication->getApplicationByName($nomApplication);

			if($application){
				$response = $this->app->getHTTPResponse();
				$response->redirect('/Library/app='.$application->getIdApplication());
			}
		}
	}
}