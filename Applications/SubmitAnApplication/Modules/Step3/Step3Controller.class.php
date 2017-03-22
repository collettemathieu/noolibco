<?php
// +----------------------------------------------------------------------+
// | PHP Version 7 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour le dépôt des applications. Le dépôt se |
// | réalise en plusieurs étapes avant d'être validé. Ici étape 3 vers 4. |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// | Auteurs : Steve Despres  <stevedespres@noolib.com>				      |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe Step3Controller - Etape 3 vers Etape 4
 * @access: public
 * @version: 1
 */	


namespace Applications\SubmitAnApplication\Modules\Step3;
use Library\Entities\Publication;
use Library\Entities\TypePublication;
use Library\Entities\Auteur;

class Step3Controller extends \Library\BackController
{
	use \Library\Traits\MethodeApplicationControleur;

	// Récupérer les publications de l'application sous forme d'objet
	public function executeGetPublicationsApplication($request){
		// On vérifie que la requête est bien effectuée en ajax
		if ($request->isAjaxRequest()) {

			// On récupère l'objet User
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);
			
			// On récupère l'application en cours de dépôt si elle existe
			$newApp = unserialize($user->getAttribute('newApp'));

			// On met à jour l'application
			// On appelle le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');
			// On récupère l'application en question si elle existe avec tous ses attributs emplis
			$newApp = $managerApplication->getApplicationByIdWithAllParameters($newApp->getIdApplication());
			$user->setAttribute('newApp', serialize($newApp));

			// On vérifie que le bon contrôleur est appelé
			if($newApp && $newApp->getStatut()->getNomStatut() === 'Step3Deposit'){
				// On récupère les publications de l'application
				$publications = $this->getPublications($newApp);
				if(is_array($publications)){
					// On ajoute la variable à la page
					$this->page->addVar('publicationsApplication', $publications);
				}
			}else{
				// On ajoute la variable de réussites
				$user->getMessageClient()->addErreur(self::SUBMITAPPLICATION_ERROR_STEP_APPLICATION);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}

	// Récupérer les types des publications et l'id de l'application
	public function executeGetDataStep3($request){
		
		// On vérifie que la requête est bien effectuée en ajax
		if ($request->isAjaxRequest()) {

			// On récupère l'objet User
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);
		
			// On ajoute le résultat à la page
			$typeAAfficher = $this->getTypePublications();
			$this->page->addVar('typeAAfficher', $typeAAfficher);

			// On récupère l'application en cours de dépôt si elle existe
			$newApp = unserialize($user->getAttribute('newApp'));

			// On ajoute la variable à la page
			$this->page->addVar('idApp', $newApp->getIdApplication());
		}else{

			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}
	
	// Valider le dépôt de l'application et passer à l'arbre de l'application
	public function executeDepositApplication($request){
		
		// On vérifie que la requête est bien effectuée en ajax
		if ($request->isAjaxRequest()) {
			// On récupère l'objet User
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'application en cours de dépôt si elle existe
			$newApp = unserialize($user->getAttribute('newApp'));
			
			// On vérifie que le bon contrôleur est appelé
			if($newApp && $newApp->getStatut()->getNomStatut() === 'Step3Deposit'){
				
				// On appelle le manager des statuts
				$managerStatut = $this->getManagers()->getManagerOf('StatutApplication');

				// On met à jour le statut de l'application
				$newApp->hydrate(array(
					'statut' => $managerStatut->getStatutByNom('Inactive')
				));

				// On sauvegarde dans la BDD le statut de l'application
				$managerApplication = $this->getManagers()->getManagerOf('Application');
				$managerApplication->saveStatutApplication($newApp);

				// On supprime l'objet newApp en Session
				$user->delAttribute('newApp');

				// On ajoute la variable de réussite
				$user->getMessageClient()->addReussite($newApp->getIdApplication());


			}else{
				// On ajoute la variable de réussites
				$user->getMessageClient()->addErreur(self::SUBMITAPPLICATION_ERROR_STEP_APPLICATION);
			}
		}else{

			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}

}