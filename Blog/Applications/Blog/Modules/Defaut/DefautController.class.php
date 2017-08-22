<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 AboutScience			         		          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour l'affichage du blog.					  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@aboutscience.com>    	  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe DefautController
 * @access: public
 * @version: 1
 */	


namespace Applications\Blog\Modules\Defaut;
	
class DefautController extends \Library\BackController
{
	public function executeShow($request){
		//On appelle le manager des Utilisateurs
		$managerArticle = $this->getManagers()->getManagerOf('Article');
		//On récupère tous les articles
		$articles = $managerArticle->getAllArticles();

		// On ajoute les articles à la page
		$this->page->addVar('articles', $articles);

		$admin = $request->getCookieData('admin');
		// On ajoute la variable admin à la page
		$this->page->addVar('admin', $admin);

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
