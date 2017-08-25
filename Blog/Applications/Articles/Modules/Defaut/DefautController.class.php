<?php
// +----------------------------------------------------------------------+
// | PHP Version 7 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib The Blog			         		          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour l'affichage des articles.			  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>		    	  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe DefautController
 * @access: public
 * @version: 1
 */	


namespace Applications\Articles\Modules\Defaut;
	
class DefautController extends \Library\BackController
{
	public function executeShow($request){
		//On appelle le manager des Utilisateurs
		$managerArticle = $this->getManagers()->getManagerOf('Article');
		//On récupère tous les articles
		$articles = $managerArticle->getAllArticles();

		$admin = $request->getCookieData('admin');
		// On ajoute la variable admin à la page
		$this->page->addVar('admin', $admin);

		//On appelle le manager des Cours
		$managerCours = $this->getManagers()->getManagerOf('Cours');
		//On récupère tous les cours
		$courss = $managerCours->getAllCours();

		foreach($courss as $cours){
			if($cours->getEnLigneCours() || $admin){
				array_push($articles, $cours);
			}
		}

		// On ordonne les articles par date de parution
		uasort($articles, 
			function ($a, $b) {
				if($a instanceof \Library\Entities\Cours){
					$dateA = new \DateTime($a->getDateCreationCours());
				}else{
					$dateA = new \DateTime($a->getDateCreationArticle());
				}
				if($b instanceof \Library\Entities\Cours){
					$dateB = new \DateTime($b->getDateCreationCours());
				}else{
					$dateB = new \DateTime($b->getDateCreationArticle());
				}
				$a = $dateA->getTimestamp();
				$b = $dateB->getTimestamp();
			    if ($a == $b) {
			        return 0;
			    }
			    return ($a > $b) ? -1 : 1;
			}
		);

		// On ajoute les articles à la page
		$this->page->addVar('articles', $articles);

	}

	public function executeShowArticle($request){
		//On récupère la requête du client
		$user = $this->app->getUser();
			
		//On appelle le manager des Utilisateurs
		$managerArticle = $this->getManagers()->getManagerOf('Article');

		// Article demandé
		$titreURLArticle = $request->getGetData('titreArticle');

		// On récupère l'article demandé
		$article = $managerArticle->getArticleByUrlTitle($titreURLArticle);

		if($article instanceof \Library\Entities\Article){
			// On envoie la liste à la page
			$this->page->addVar('article', $article);

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
				$article->hydrate(array(
					'nbreVueArticle' => (int) $article->getNbreVueArticle() + 1
					));
				$managerArticle->saveArticle($article);
			}

		}else{
			$user->getMessageClient()->addErreur('L\'article que vous souhaitez consulter n\'existe pas.');
			// On retourne à la page des articles
			$response = $this->app->getHTTPResponse();
			$response->redirect('/Blog/');
		}	
	}
}
