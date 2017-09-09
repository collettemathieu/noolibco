<?php
// +----------------------------------------------------------------------+
// | PHP Version 7 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur par défaut AJAX de l'aide à l'utilisateur.  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe DefaultController
 * @access: public
 * @version: 1
 */

namespace Applications\Helper\Modules\Defaut;
	
class DefautController extends \Library\BackController{
	
	use \Library\Traits\MethodeUtilisateurControleur;

	/* Permet d'afficher le formulaire de contact */
	public function executeFormContact($request){
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
		
		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		// On récupère l'utilisateur système
		$user = $this->app->getUser();

		if (!$request->isAjaxRequest()) {
				
			// On ajoute la variable d'erreurs
			$user->getMessageClient()->addErreur(self::DENY_EXECUTE_COMMAND);

			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
			
		}else{

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On envoie le mail à l'équipe NooLib
			$response = $this->sendAMailToNooLib($request);
		}
	}
}