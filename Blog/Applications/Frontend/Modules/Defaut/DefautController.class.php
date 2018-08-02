<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 AboutScience			         		          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour l'affichage de la page par défaut.	  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@aboutscience.com>    	  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe DefautController
 * @access: public
 * @version: 1
 */	


namespace Applications\Frontend\Modules\Defaut;
	
class DefautController extends \Library\BackController
{
	public function executeShow($request){
		//On appelle les managers
		$managerActualite = $this->getManagers()->getManagerOf('Actualite');
		$managerEdito = $this->getManagers()->getManagerOf('Edito');

		// On récupère la liste de tous les actualites
		$actualites = $managerActualite->getAllActualites();

		// On envoie la première actualité à la page
		$this->page->addVar('actualite', $actualites[count($actualites)-1]);

		// On récupère la liste de tous les editos
		$editos = $managerEdito->getAllEditos();

		// On envoie la première actualité à la page
		$this->page->addVar('edito', $editos[count($editos)-1]);
	}

	// Récupérer les statistiques globales du site
	public function executeReturnStats($request){

		//On récupère le client
		$user = $this->app->getUser();
		// On informe que c'est un chargement Ajax
		$user->setAjax(true);

		// On récupère le cache des stats
		$config = $this->getApp()->getConfig();
		$expire = time() - $config->getVar('divers', 'divers', 'cacheStatsDelay'); // xx secondes de durée de vie en cache.
		$cheminFichier = '../Applications/'.$this->getApp()->getNomApplication().'/Cache/stats.txt';

		if(file_exists($cheminFichier) && filemtime($cheminFichier) > $expire){
			$fichier = fopen($cheminFichier, 'r+');
			$entite = fgets($fichier);
			$stats = unserialize($entite); // On le transforme en tableau.
			
			fclose($fichier);
		}
		else{
			ob_start();

			// Création et chargement des stats
			//On appelle les managers
			$managerUtilisateurs = $this->getManagers()->getManagerOf('Utilisateur');
			$managerArticle = $this->getManagers()->getManagerOf('Article');
			$managerCours = $this->getManagers()->getManagerOf('Cours');
			$managerCommentaire = $this->getManagers()->getManagerOf('Commentaire');

			// On récupère les stats
			$nombreUtilisateurs = $managerUtilisateurs->getNumberOfUtilisateur();
			$nombreArticles = $managerArticle->getNumberOfArticles();
			$nombreArticles += $managerCours->getNumberOfCours();
			$nombreCommentaires = $managerCommentaire->getNumberOfCommentaires();
			$nombreVues = $managerArticle->getAllVues();
			$nombreVues += $managerCours->getAllVues();

			$stats = array(
				'nbreUsers' => $nombreUtilisateurs,
				'nbreArticles' => $nombreArticles,
				'nbreVues' => $nombreVues,
				'nbreCommentaires' => $nombreCommentaires

				);

			file_put_contents($cheminFichier, serialize($stats));

		}
		
		// On envoie la première actualité à la page
		$this->page->addVar('stats', $stats);

	}

	// Retourne l'actualité correspondante
	public function executeReturnActualite($request){

		//On récupère le client
		$user = $this->app->getUser();
		// On informe que c'est un chargement Ajax
		$user->setAjax(true);

		$numeroActualite = (int) $request->getPostData('numeroActualite');

		if(isset($numeroActualite) && $numeroActualite >= 0){

			//On appelle les managers
			$managerActualite = $this->getManagers()->getManagerOf('Actualite');
			
			// On récupère la liste de tous les actualites
			$actualites = $managerActualite->getAllActualites();
			$nbreActualites = count($actualites);
			if($numeroActualite <= $nbreActualites -1){

				// On envoie la première actualité à la page
				$this->page->addVar('actualite', $actualites[$nbreActualites - 1 - $numeroActualite]);
				$this->page->addVar('nbreActualites', $nbreActualites);
			}

		}

	}

	// Retourne l'édito correspondant
	public function executeReturnEdito($request){

		//On récupère le client
		$user = $this->app->getUser();
		// On informe que c'est un chargement Ajax
		$user->setAjax(true);

		$numeroEdito = (int) $request->getPostData('numeroEdito');

		if(isset($numeroEdito) && $numeroEdito >= 0){

			//On appelle les managers
			$managerEdito = $this->getManagers()->getManagerOf('Edito');
			
			// On récupère la liste de tous les éditos
			$editos = $managerEdito->getAllEditos();
			$nbreEditos = count($editos);

			if($numeroEdito <= $nbreEditos - 1){

				// On envoie l'édito à la page
				$this->page->addVar('edito', $editos[$nbreEditos - 1 - $numeroEdito]);
				$this->page->addVar('nbreEditos', $nbreEditos);
			}

		}

	}
}