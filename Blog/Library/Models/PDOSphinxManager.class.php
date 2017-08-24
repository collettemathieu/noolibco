<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 AboutScience	         				          |
// +----------------------------------------------------------------------+
// |  Classe PHP pour le manager PDO de sphinx.							  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@aboutscience.net>		  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe SphinxApplication
 * @access: public
 * @version: 1
 */

namespace Library\Models;

class PDOSphinxManager extends \Library\Models\SphinxManager{

/* Définitions des méthodes action de la classe */

	//Méthode pour rechercher un article.
	public function searchSphinxArticles($motsRecherche){
			
		//préparation de la requête
		$requete = $this->dao->prepare("SELECT * FROM mot_cle WHERE nom_mot_cle LIKE '$motsRecherche%' ORDER BY nom_mot_cle DESC");

		//exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
			
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Error!: " . $e->getMessage() . "</br>";
		}

		//création d'un tableau d'articles
		$listArticles = array();

		// On récupère les articles associés aux mots-clés
		$pdoArticle = new PDOArticleManager($this->dao);
		
		while ($donnees = $requete->fetch()){
			$articles = $pdoArticle->getArticleByIdMotCle($donnees['id_mot_cle']);

			if(count($articles) != 0){
				$listArticles = array_merge($listArticles, $articles);
			}
		}

		//On libère la requête
		$requete->closeCursor();

		return $listArticles;
	}

	//Méthode pour rechercher un article.
	public function searchSphinxCours($motsRecherche){
			
		//préparation de la requête
		$requete = $this->dao->prepare("SELECT * FROM mot_cle WHERE nom_mot_cle LIKE '$motsRecherche%' ORDER BY nom_mot_cle DESC");

		//exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
			
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Error!: " . $e->getMessage() . "</br>";
		}

		//création d'un tableau de cours
		$listCours = array();

		// On récupère les cours associés aux mots-clés
		$pdoCours = new PDOCoursManager($this->dao);
		
		
		while ($donnees = $requete->fetch()){
			$cours = $pdoCours->getCoursByIdMotCle($donnees['id_mot_cle']);

			if(count($cours) != 0){
				$listCours = array_merge($listCours, $cours);
			}
		}

		//On libère la requête
		$requete->closeCursor();

		return $listCours;
	}
	
}