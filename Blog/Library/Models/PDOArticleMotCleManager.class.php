<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des ArticleMotCle.					  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des ArticleMotCle
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\ArticleMotCle;
use \Library\Models;

class PDOArticleMotCleManager extends \Library\Models\ArticleMotCleManager
{

/* Définitions des méthodes action de la classe */

	// Ajout d'un articleMotCle à la base
	public function addArticleMotCle($articleMotCle){

		 if($articleMotCle instanceof ArticleMotCle){
		
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO article_mot_cle VALUES (:idArticle, :idMotCle)");

			//bind des valeurs
			$requete->bindValue(':idArticle', $articleMotCle->getArticle()->getIdArticle(), \PDO::PARAM_INT);
			$requete->bindValue(':idMotCle', $articleMotCle->getMotCle()->getIdMotCle(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::ArticleMotCle : L\'objet passé en paramètre n\'est pas une instance de ArticleMotCle');
		}
	}
	
	// Supprime un articleMotCle de la base.
	public function deleteArticleMotCle($articleMotCle){

		 if($articleMotCle instanceof ArticleMotCle){	

			// Suppression de l'articleMotCle
			$requete = $this->dao->prepare("DELETE FROM article_mot_cle WHERE id_article = :idArticle and id_mot_cle = :idMotCle;");

			//bind des valeurs
			$requete->bindValue(':idArticle', $articleMotCle->getArticle()->getIdArticle(), \PDO::PARAM_INT);
			$requete->bindValue(':idMotCle', $articleMotCle->getMotCle()->getIdMotCle(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::ArticleMotCle : L\'objet passé en paramètre n\'est pas une instance de ArticleMotCle');
		}
	}
	
	
	// Sélection d'un articleMotCle par son ID
	public function getArticleMotCleById($idArticle, $idMotCle){
		
		$requete = $this->dao->prepare("SELECT * FROM article_mot_cle WHERE id_article = :idArticle AND id_mot_cle = :idMotCle");
		
		//bind des paramètres
		$requete->bindValue(':idArticle', $idArticle, \PDO::PARAM_INT);
		$requete->bindValue(':idMotCle', $idMotCle, \PDO::PARAM_INT);
			
		
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
			$articleMotCle = $this->constructArticleMotCle($donnees[0]);
			return $articleMotCle;
		}
	}

	
	// Permet de contruire un objet articleMotCle à partir des données de la base.
	protected function constructArticleMotCle($donnee){
		
		$pdoArticle = new PDOArticleManager($this->dao);
	
	 	$pdoMotCle = new PDOMotCleManager($this->dao);

		$data = [
			'article' => $pdoArticle->getArticleById($donnee['id_article']),
			'motCle' => $pdoMotCle->getMotCleById($donnee['id_mot_cle'])
		];
		return new ArticleMotCle($data);
	}
}
