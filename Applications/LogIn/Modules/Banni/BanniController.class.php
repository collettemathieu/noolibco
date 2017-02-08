<?php
// +----------------------------------------------------------------------+
// | PHP Version 7 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour l'affichage de la page banni.		  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe BanniController
 * @access: public
 * @version: 1
 */	


namespace Applications\LogIn\Modules\Banni;
	
class BanniController extends \Library\BackController
{
	public function executeShow()
	{
		$user = $this->app->getUser();
		$userSession = unserialize($user->getAttribute('userSession'));

		if($user->isAuthenticated()){
			// On procède à la redirection vers la page principale
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
		
	}
}