<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 AboutScience	         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur Sphinx afin de rechercher les articles et   |
// | les cours sur ScienceAPart.										  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@aboutscience.net>		  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe SphinxApplication
 * @access: public
 * @version: 1
 */

namespace Applications\Sphinx\Modules\Search;
	
class SearchController extends \Library\BackController
{
	/**
	* Fonction qui permet de faire une recherche à partir d'un appel AJAX
	*/
	public function executeSearch($request)	{

		// On récupère l'utilisateur
		$user = $this->app->getUser();	
	
		// On récupère la requête
		$request = $this->getApp()->getHTTPRequest();
		
		// On charge le fichier de configuration
		$config = $this->getApp()->getConfig();

		$mots = $request->getPostData('mots');
		
		if(isset($mots) && !empty($mots)){
			// On récupère le contenu de cette requête: l'expression à rechercher.
			// On contrôle les mots-clés entrés par l'utilisateur			
			// On appelle la fonction multiexplode pour les mots-clés entrés par l'utilisateur
			$delimitateursRecherches = explode('|', $config->getVar('divers', 'divers', 'delimitateurMotsCles')); //Tableau des délimiteurs autorisés
			$motsClesEntreUtilisateur = $config->multiexplode($delimitateursRecherches, $mots);

			//On appelle le manager Sphinx
			$managerSphinx = $this->getManagers()->getManagerOf('Sphinx');

			$articlesBis = array();
			$courssBis = array();

			foreach($motsClesEntreUtilisateur as $motCle){
				$articlesBis = array_merge($articlesBis, $managerSphinx->searchSphinxArticles($motCle));
				$courssBis = array_merge($courssBis, $managerSphinx->searchSphinxCours($motCle));
			}

			$articles = array();
			$tailleArticles = count($articlesBis);
			for($i = 0; $i < $tailleArticles; ++$i){
				$val = false;
				for($j = $i + 1; $j < $tailleArticles; ++$j){
					if($articlesBis[$i]->getIdArticle() === $articlesBis[$j]->getIdArticle()){
						$val = true;
					}
				}
				if(!$val){
					array_push($articles, $articlesBis[$i]);
				}
			}

			$courss = array();
			$tailleCourss = count($courssBis);
			for($i = 0; $i < $tailleCourss; ++$i){
				$val = false;
				for($j = $i + 1; $j < $tailleCourss; ++$j){
					if($courssBis[$i]->getIdCours() === $courssBis[$j]->getIdCours()){
						$val = true;
					}
				}
				if(!$val){
					array_push($courss, $courssBis[$i]);
				}
			}

			// On ajoute la variable à la page
			$this->page->addVar('articles', $articles);
			$this->page->addVar('courss', $courss);
		}

	}
	
	
}