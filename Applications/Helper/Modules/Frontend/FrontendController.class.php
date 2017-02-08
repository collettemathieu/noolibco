<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour le Frontend de l'aide à l'utilisateur. |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe FrontendController
 * @access: public
 * @version: 1
 */

namespace Applications\Helper\Modules\Frontend;
	
class FrontendController extends \Library\BackController{

	use \Library\Traits\MethodeUtilisateurControleur;
	
	/* Permet d'afficher l'aide de la page d'accueil */
	public function executeShowHelpFrontend($request){
		// On récupère l'utilisateur système
		$user = $this->app->getUser();	

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if (!$request->isAjaxRequest()) {
				
			// On ajoute la variable d'erreurs
			$user->getMessageClient()->addErreur(self::DENY_EXECUTE_COMMAND);

			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
			
		}else{

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);
			
			$utilisateur = unserialize($user->getAttribute('userSession'));
			$this->page->addVar('mailUtilisateur', $utilisateur->getMailUtilisateur());
		}
	}

	/* Permet d'envoyer un courrier à NooLib */
	public function executeContact($request){
		$this->sendAMailToNooLib($request);

		$response = $this->app->getHTTPResponse();
		$response->redirect('/');
	}
}