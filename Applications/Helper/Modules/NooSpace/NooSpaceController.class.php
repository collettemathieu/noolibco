<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur NooSpace pour l'aide à l'utilisateur.		  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe NooSpaceController
 * @access: public
 * @version: 1
 */

namespace Applications\Helper\Modules\NooSpace;
	
class NooSpaceController extends \Library\BackController
{
	/* Permet d'afficher l'aide de la NooSpace */
	public function executeShowHelpNooSpace($request){
		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if (!$request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();		
			// On ajoute la variable d'erreurs
			$user->getMessageClient()->addErreur(self::DENY_EXECUTE_COMMAND);

			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
			
		}else{
			// On informe que c'est un chargement Ajax
			$user->setAjax(true);
		}
	}
}