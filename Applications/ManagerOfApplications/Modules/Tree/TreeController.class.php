<?php
// +----------------------------------------------------------------------+
// | PHP Version 7 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour gérer une application à travers son 	  |
// | arbre. 															  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// | Auteur : Steve Despres  <stevedespres@noolib.com>				      |
// +----------------------------------------------------------------------+

/**
 * @name: Classe TreeController - Arbre de l'application
 * @access: public
 * @version: 1
 */	


namespace Applications\ManagerOfApplications\Modules\Tree;

use Library\Entities\Utilisateur;

class TreeController extends \Library\BackController
{
	/**
	* Méthode pour afficher l'arbre de l'application
	*/
	public function executeShow($request)
	{
		// On vérifie que l'utilisateur est bien identifié
		$user = $this->app->getUser();

		// On récupère l'utilisateur de session
		$userSession = unserialize($user->getAttribute('userSession'));
		
		// On récupère l'ID de l'application à mettre en cache
		$idApp = (int) $request->getGetData('app');

		// On récupère le manager des applications
		$managerApplication = $this->getManagers()->getManagerOf('Application');

		// On récupère l'application via son ID
		$application = $managerApplication->getApplicationByIdWithAllParameters($idApp);

		// On oriente l'utilisateur selon le statut de dépôt de l'application.
		if($application && ($application->getStatut()->getNomStatut() === 'Inactive' || $application->getStatut()->getNomStatut() === 'Validated' || $application->getStatut()->getNomStatut() === 'Not validated')){
			
			// On charge les utilisateurs autorisés 
			$idAuteursAutorises = array();
			// On récupère le manager des Utilisateurs
			$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
			// On ajoute le créateur comme ID autorisé
			array_push($idAuteursAutorises, $application->getCreateur()->getIdUtilisateur());
			foreach($application->getAuteurs() as $auteur){
				$utilisateur = $managerUtilisateur->getUtilisateurByMail($auteur->getMailAuteur());
				if($utilisateur){
					array_push($idAuteursAutorises, $utilisateur->getIdUtilisateur());
				}
			}	
			
			if(in_array($userSession->getIdUtilisateur(), $idAuteursAutorises, true) || $user->getAttribute('isAdmin')){
				
				/* Pour la création de l'arbre de l'application */
				// On récupère la dernière version de l'application
				$version = $application->getVersions()[count($application->getVersions())-1];

				// On ajoute la variable version à la page
				$this->page->addVar('app', $application);

			}else{
				$user->getMessageClient()->addErreur(self::DENY_HANDLE_APPLICATION);
				// On procède à la redirection les outils des applications
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ManagerOfApplications/');
			}
					
		}else{
			// On procède à la redirection les outils des applications
			$response = $this->app->getHTTPResponse();
			$response->redirect('/ManagerOfApplications/');
		}
	}
}