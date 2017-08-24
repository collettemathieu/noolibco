<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des ArticleCommentaire.				  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des ArticleCommentaire
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\ArticleCommentaire;
use \Library\Models;

class PDOArticleCommentaireManager extends \Library\Models\ArticleCommentaireManager
{

/* Définitions des méthodes action de la classe */

	// Ajout d'un articleCommentaire à la base
	public function addArticleCommentaire($articleCommentaire){

		 if($articleCommentaire instanceof ArticleCommentaire){
		
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO article_commentaire VALUES (:idArticle, :idCommentaire)");

			//bind des valeurs
			$requete->bindValue(':idArticle', $articleCommentaire->getArticle()->getIdArticle(), \PDO::PARAM_INT);
			$requete->bindValue(':idCommentaire', $articleCommentaire->getCommentaire()->getIdCommentaire(), \PDO::PARAM_INT);
			
			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
				
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//On libère la requête
			$requete->closeCursor();
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::ArticleCommentaire : L\'objet passé en paramètre n\'est pas une instance de ArticleCommentaire');
		}
	}
	
	// Supprime un articleCommentaire de la base.
	public function deleteArticleCommentaire($articleCommentaire){

		 if($articleCommentaire instanceof ArticleCommentaire){	

			// Suppression de l'articleCommentaire
			$requete = $this->dao->prepare("DELETE FROM article_commentaire WHERE id_article = :idArticle and id_commentaire = :idCommentaire;");

			//bind des valeurs
			$requete->bindValue(':idArticle', $articleCommentaire->getArticle()->getIdArticle(), \PDO::PARAM_INT);
			$requete->bindValue(':idCommentaire', $articleCommentaire->getCommentaire()->getIdCommentaire(), \PDO::PARAM_INT);

			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//On libère la requête
			$requete->closeCursor();
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::ArticleCommentaire : L\'objet passé en paramètre n\'est pas une instance de ArticleCommentaire');
		}
	}
	
	
	// Sélection d'un articleCommentaire par son ID
	public function getArticleCommentaireId($idArticle, $idCommentaire){
		
		$requete = $this->dao->prepare("SELECT * FROM article_commentaire WHERE id_article = :idArticle AND id_commentaire = : idCommentaire");
		
		//bind des paramètres
		$requete->bindValue(':idArticle', $idArticle, \PDO::PARAM_INT);
		$requete->bindValue(':idCommentaire', $idCommentaire, \PDO::PARAM_INT);
			
		
		//exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		$donnees = $requete->fetchAll();
		
		$requete->closeCursor ();
		
		if (count($donnees) == 0) {
			return false;
		}
		else {
			$articleCommentaire = $this->constructArticleCommentaire($donnees[0]);
			return $articleCommentaire;
		}
	}

	
	// Permet de contruire un objet articleCommentaire à partir des données de la base.
	protected function constructArticleCommentaire($donnee){
		
		$pdoArticle = new PDOArticleManager($this->dao);
	
	 	$pdoCommentaire = new PDOCommentaireManager($this->dao);

		$data = [
			'article' => $pdoArticle->getArticleById($donnee['id_article'],
			'commentaire' => $pdoCommentaire->getCommnetaireById($donnee['id_commentaire'])
		];
		return new ArticleCommentaire($data);
	}
}
