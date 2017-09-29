<?php
// +----------------------------------------------------------------------+
// | PHP Version 7 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur qui permet d'entrer dans la NooSpace,		  |
// | d'afficher les données et de sélectionner le traitement des données. |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@noolib.com>    		  |	  
// |			Naoures Hassine <naoureshassine@noolib.com>				  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe DefautController
 * @access: public
 * @version: 1
 */	

namespace Applications\NooSpace\Modules\Defaut;
	
class DefautController extends \Library\BackController
{
	/**
	* Fonction qui afficher la NooSpace
	*/
	public function executeShow($request)
	{
		// On récupère l'utilisateur système
		$user = $this->app->getUser();

		// On récupère l'utilisateur connecté
		$utilisateur = unserialize($user->getAttribute('userSession'));
		
		// On récupère l'id de l'application que le client veut executer
		$idApplication = (int) $request->getGetData('idApplication');
		if(!empty($idApplication)){
			// On récupère les applications des utilisateurs
			// On appelle les managers
			$managerApp = $this->getManagers()->getManagerOf('Application');
			$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
			// On récupère l'application en question si elle existe avec tous ses attributs emplis
			$application = $managerApp->getApplicationByIdWithAllParameters($idApplication);
			
			if ($application) {

				// On ajoute l'application à la page
				$this->page->addVar('applicationNooSpace', $application);

				// On récupère l'id de la version que le client veut executer
				$idVersion = (int) $request->getGetData('idVersion');

				// On vérifie s'il existe une version d'exécution demandée et si
				// l'utilisateur est autorisé à l'exécuter.
				// On récupère les auteurs/développeurs de l'application
				$auteurs = $application->getAuteurs();
				$idAuteurs = array();
				foreach($auteurs as $auteur){
					$mailAuteur = $auteur->getMailAuteur();
					if(isset($mailAuteur)){
						$utilisateurAuteur = $managerUtilisateur->getUtilisateurByMail($mailAuteur);
						if($utilisateurAuteur !=false){
							array_push($idAuteurs, $utilisateurAuteur->getIdUtilisateur());
						}
					}
				}
				// On ajoute le créateur de l'application
				array_push($idAuteurs, $application->getCreateur()->getIdUtilisateur());
				// On vérifie
				if(!empty($idVersion) && in_array($utilisateur->getIdUtilisateur(), $idAuteurs)){
					
					// On vérifie que l'id de la version existe pour l'application
					foreach($application->getVersions() as $version){
						if($version->getIdVersion() === $idVersion){
							// On ajoute la version à la page
							$this->page->addVar('version', $version);
							break;
						}
					}
				}else{
					// On prend la dernière version active de l'application
					for ($i=count($application->getVersions())-1; $i >=0 ; --$i) {
						$version = $application->getVersions()[$i];
						if($version->getActiveVersion()){
							// On ajoute la version à la page
							$this->page->addVar('version', $version);
							break;
						}
					}
				}
			}
		}
	}

	/**
	* Récupérer la session utilisateur pour NodeJs
	*/
	public function executeGetSession($request)
	{
		if ($request->isAjaxRequest()) {

			// On vérifie que l'utilisateur est bien identifié
			$user = $this->app->getUser();
			$utilisateur = unserialize($user->getAttribute('userSession'));
			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			$tableOfSession = array();
				$session = array(
					'id' => $utilisateur->getIdUtilisateur(),
					'isAdmin' =>($user->getAttribute('isAdmin')) ? 'true' : 'false'				
					);

				array_push($tableOfSession, $session);
			// On ajoute
			$this->page->addVar('tableOfSession', $session);
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}
}