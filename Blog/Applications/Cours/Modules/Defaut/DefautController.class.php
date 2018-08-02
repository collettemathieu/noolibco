<?php
// +----------------------------------------------------------------------+
// | PHP Version 7 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2018 NoolibTheBlog			         		          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour l'affichage du blog.					  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>		    	  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe DefautController
 * @access: public
 * @version: 1
 */	


namespace Applications\Cours\Modules\Defaut;

class DefautController extends \Library\BackController
{
	public function executeShow($request){
		// Deprecated
		// On retourne à la page des cours
		$response = $this->app->getHTTPResponse();
		$response->redirect('/Articles/');
	}

	// Permet d'afficher un cours
	public function executeShowCours($request){
		//On récupère la requête du client
		$user = $this->app->getUser();
			
		//On appelle le manager des Utilisateurs
		$managerCours = $this->getManagers()->getManagerOf('Cours');

		// Cours demandé
		$titreURLCours = $request->getGetData('titreCours');

		// On récupère le cours demandé
		$cours = $managerCours->getCoursByUrlTitle($titreURLCours);

		if($cours instanceof \Library\Entities\Cours){
			
			// On y insère le cours global
			$cours = $managerCours->putCoursGlobalInCours($cours);

			// On cherche si il y a une connexion par cookies
			$emailUser = $request->getCookieData('emailUser');
			$nameUser = $request->getCookieData('nameUser');
			
			if(isset($emailUser) && isset($nameUser)){
				// On envoie les cookies à la page
				$this->page->addVar('emailUser', $emailUser);
				$this->page->addVar('nameUser', $nameUser);
			}

			// On met à jour le nombre de lu (excepté pour l'admin)
			$admin = $request->getCookieData('admin');
			if(!$admin){
				$coursGlobal = $cours->getCoursGlobal();
				if($coursGlobal instanceof \Library\Entities\CoursGlobal){
					$managerCoursGlobal = $this->getManagers()->getManagerOf('CoursGlobal');
					$coursGlobal->hydrate(array(
					'nbreVueCoursGlobal' => (int) $coursGlobal->getNbreVueCoursGlobal() + 1
					));
					$managerCoursGlobal->saveCoursGlobal($coursGlobal);
				}

				$cours->hydrate(array(
					'nbreVueCours' => (int) $cours->getNbreVueCours() + 1
					));
				$managerCours->saveCours($cours);
			}

			// On envoie la liste à la page
			$this->page->addVar('cours', $cours);

		}else{
			$user->getMessageClient()->addErreur('Le cours que vous souhaitez consulter n\'existe pas.');
			// On retourne à la page des cours
			$response = $this->app->getHTTPResponse();
			$response->redirect('/Cours/');
		}	
	}


	// Permet de récupérer les cours d'un cours global
	public function executeGetCoursFromCoursGlobal($request){
		//On récupère la requête du client
		$user = $this->app->getUser();

		// Requête ajax
		$user->setAjax(true);
			
		//On appelle le manager des Utilisateurs
		$managerCoursGlobal = $this->getManagers()->getManagerOf('CoursGlobal');

		// On récupère le cours demandé
		$coursGlobal = $managerCoursGlobal->getCoursGlobalById((int) $request->getPostData('idCoursGlobal'));

		$admin = $request->getCookieData('admin');

		if($coursGlobal instanceof \Library\Entities\CoursGlobal){
			$coursGlobal = $managerCoursGlobal->putCoursInCoursGlobal($coursGlobal);
			$listeCours = array();
			foreach($coursGlobal->getCours() as $cours){
				if($cours->getEnLigneCours() || $admin){
					array_push($listeCours, $cours);
				}
			}

			// On ajoute les cours à la page
			$this->page->addVar('listeCours', $listeCours);
			$this->page->addVar('titreCours', $coursGlobal->getTitreCoursGlobal());

		}else{
			$user->getMessageClient()->addErreur('Le cours que vous souhaitez consulter n\'existe pas.');
		}	
	}
}