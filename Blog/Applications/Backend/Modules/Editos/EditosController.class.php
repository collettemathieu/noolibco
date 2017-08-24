<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Ce controleur permet d'afficher, modifier ou créer de nouveaux		  |
// | editos		   			  										 	  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: controleur des editos
 * @access: public
 * @version: 1
 */	


namespace Applications\Backend\Modules\Editos;

class EditosController extends \Library\BackController{
	
	// Page principal de la gestion des editos
	public function executeShow($request){
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$response = $this->app->getHTTPResponse();
			$response->redirect('/ForAdminOnly/');
		}
		else{
			
			//On appelle le manager des Editos
			$managerEdito = $this->getManagers()->getManagerOf('Edito');

			// On récupère la liste de tous les editos
			$editos = $managerEdito->getAllEditos();

			// On envoie la liste à la page
			$this->page->addVar('editos', $editos);

		}
	}

	// Page d'édition d'un edito
	public function executeShowEdito($request){
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$response = $this->app->getHTTPResponse();
			$response->redirect('/ForAdminOnly/');
		}
		else{
			
			//On appelle le manager des Utilisateurs
			$managerEdito = $this->getManagers()->getManagerOf('Edito');

			// Edito demandé
			$idEdito = (int) $request->getGetData('idEdito');

			// On récupère l'edito demandé
			$edito = $managerEdito->getEditoById($idEdito);

			if($edito instanceof \Library\Entities\Edito){

				// On envoie la liste à la page
				$this->page->addVar('edito', $edito);

			}else{
				$user->getMessageClient()->addErreur('L\'édito que vous souhaitez consulter n\'existe pas.');
				// On retourne à la page des editos
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Editos/');
			}
		}
	}
	
	// Méthode pour créer un edito
	public function executeCreerEdito($request){
		
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		//On récupère la réponse
		$response = $this->app->getHTTPResponse();

		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$response->redirect('/ForAdminOnly/');
		}else{
			
			$newEdito = new \Library\Entities\Edito(array(
				'texteEdito' => trim($request->getPostData('texteEdito'))
			));
			
			if(sizeof($newEdito->getErreurs()) != 0){
				$user->getMessageClient()->addErreur($newEdito->getErreurs());
				$response->redirect('/ForAdminOnly/Editos/');
			}else{
				// On appelle les managers
				$managerEdito = $this->getManagers()->getManagerOf('Edito');

				$managerEdito->addEdito($newEdito);
				$user->getMessageClient()->addReussite('L\'édito a bien été créé.');
				
				$response->redirect('/ForAdminOnly/Editos/');
			}
			
		}
	}

	// Méthode pour modifier le texte de l'édito
	public function executeModifierTexteEdito($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des editos
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idEdito = (int) $request->getPostData('idEdito');
			//On appelle le manager des Utilisateurs
			$managerEdito = $this->getManagers()->getManagerOf('Edito');
			//On récupère l'edito à administrer
			$edito = $managerEdito->getEditoById($idEdito);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($edito === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des editos
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Editos/');
			}
			else{

				$nouveauTexte = $request->getPostData('nouveauTexte');
				
				$edito->hydrate(array(
					'texteEdito' => trim($nouveauTexte),
					));

				if(sizeof($edito->getErreurs()) === 0){

					//On procède à la mise à jour dans la BDD de l'edito
					$managerEdito->saveEdito($edito);
					$user->getMessageClient()->addReussite('Le texte de l\'édito a bien été modifié.');
					
					// On retourne à la page de l'édito
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Editos/id='.$edito->getIdEdito());
				}else{
					$user->getMessageClient()->addErreur($edito->getErreurs());
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Editos/id='.$edito->getIdEdito());
				}
			}
		}
	}
	
	// Méthode pour supprimer un édito de la base
	public function executeSupprimerEdito($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		

		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des editos
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idEdito = (int) $request->getPostData('idEdito');
			//On appelle le manager des Utilisateurs
			$managerEdito = $this->getManagers()->getManagerOf('Edito');
			//On récupère l'edito à administrer
			$edito = $managerEdito->getEditoById($idEdito);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($edito === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des editos
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Editos/');
			}
			else{

				//On procède à la suppression dans la BDD de l'edito
				$managerEdito->deleteEdito($edito);
				$user->getMessageClient()->addReussite('L\'édito a bien été supprimé.');

				// On retourne à la page des editos
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Editos/');
				
			}
		}
	}

}
