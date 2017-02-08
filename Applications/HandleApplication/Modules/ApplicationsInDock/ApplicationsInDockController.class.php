<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour contrôler les applications du dock.	  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe ApplicationsInDockController
 * @access: public
 * @version: 1
 */

namespace Applications\HandleApplication\Modules\ApplicationsInDock;
use \Library\Entities\Favori;
	
class ApplicationsInDockController extends \Library\BackController
{
	public function executeAddRemoveToDock($request)
	{
		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			
			// On récupère l'id de l'application que le client veut ajouter ou retirer du Dock
			$idApplication = (int) $request->getPostData('idApplication');

			// On appelle les managers
			$managerApp = $this->getManagers()->getManagerOf('Application');
			// On récupère l'application en question si elle existe avec tous ses attributs emplis
			$application = $managerApp->getApplicationByIdWithAllParameters($idApplication);
			
			if ($application != false){
				// On récupère l'utilisateur système
				$user = $this->app->getUser();

				// On informe que c'est un chargement Ajax
				$user->setAjax(true);

				// On récupère l'utilisateur en session
				$userSession = unserialize($user->getAttribute('userSession'));

				if(isset($userSession)){

					// On vérifie que l'application n'est pas déjà présente dans le dock (cad dans les favoris de l'utilisateur)
					$appIsInDock = false;
					foreach($userSession->getFavoris() as $key => $applicationInDock){
						if($applicationInDock->getIdApplication() === $application->getIdApplication()){
							$appIsInDock = true;
							$index = $key;
							break;
						}
					}
					// On sauvegarde les favoris de l'utilisateur en BDD par l'objet Favori
					$favoris = new Favori(array(
						'Utilisateur' => $userSession,
						'Application' => $application
						));
					// On appelle le manager des Favoris
					$managerFavori = $this->getManagers()->getManagerOf('Favori');

					// Si l'application n'est pas présente dans le dock, on l'ajoute, sinon on la supprime
					if(!$appIsInDock){
						$userSession->addFavori($application);
						$managerFavori->addFavori($favoris);
					}else{
						$userSession->removeFavori($application, $index);
						$managerFavori->deleteFavori($favoris);
					}

					// On sauvegarde la session
					$user->setAttribute('userSession', serialize($userSession));

				}

			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}
}