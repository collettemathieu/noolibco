<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur par défaut AJAX de Sphinx.				  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe EngineController
 * @access: public
 * @version: 1
 */

namespace Applications\Sphinx\Modules\Defaut;
	
class DefautController extends \Library\BackController
{
	/* Permet d'executer une tâche d'une application*/
	public function executeShow($request)
	{
		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if (!$request->isAjaxRequest()) {
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
			
		}else{
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);
			
			// On ajoute la variable d'erreurs
			$user->getMessageClient()->addErreur(self::DENY_EXECUTE_COMMAND);
		}
	}
}