<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour gérer les applications.				  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe ViewApplicationsController
 * @access: public
 * @version: 1
 */	


namespace Applications\ManagerOfApplications\Modules\ViewApplications;

class ViewApplicationsController extends \Library\BackController
{
	/**
	* Méthode pour afficher le module de gestion des applications de l'utilisateur
	*/
	public function executeShow()
	{
		// On récupère l'utilisateur système
		$user = $this->app->getUser();

		// On récupère l'utilisateur connecté
		$utilisateur = unserialize($user->getAttribute('userSession'));

		// On récupère le manager des applications
		$managerApplication = $this->getManagers()->getManagerOf('Application');

		// On récupère les applications qui sont en cours de dépôt par l'utilisateur
		$applicationsOfUser = $managerApplication->getApplicationsOfUser($utilisateur->getIdUtilisateur());

		// On récupère le manager des Auteurs
		$managerAuteur = $this->getManagers()->getManagerOf('Auteur');

		$auteur = $managerAuteur->getAuteurByMail($utilisateur->getMailUtilisateur());

		if($auteur){
			// On récupère le manager des AuteurApplication
			$managerApplicationAuteur = $this->getManagers()->getManagerOf('ApplicationAuteur');

			// On recherche les applications dont l'utilisateur est l'auteur
			$applications = $managerApplicationAuteur->getAllApplicationsFromAuteur($auteur);

			if(!empty($applications)){
				if(!empty($applicationsOfUser)){
					foreach($applications as $application){
						array_push($applicationsOfUser, $application);
					}
				}else{
					$applicationsOfUser = $applications;
				}
			}

		}

		// On les ajoute à la page
		$this->page->addVar('applicationsOfUser', $applicationsOfUser);
		
	}

}