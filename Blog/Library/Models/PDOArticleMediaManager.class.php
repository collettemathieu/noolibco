<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des ArticleMedia.					  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des ArticleMedia
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\ArticleMedia;
use \Library\Models;

class PDOArticleMediaManager extends \Library\Models\ArticleMediaManager
{

/* Définitions des méthodes action de la classe */

	// Ajout d'un articleMedia à la base
	public function addArticleMedia($articleMedia){

		 if($articleMedia instanceof ArticleMedia){
		
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO article_media VALUES (:idArticle, :idMedia)");

			//bind des valeurs
			$requete->bindValue(':idArticle', $articleMedia->getArticle()->getIdArticle(), \PDO::PARAM_INT);
			$requete->bindValue(':idMedia', $articleMedia->getMedia()->getIdMedia(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::ArticleMedia : L\'objet passé en paramètre n\'est pas une instance de ArticleMedia');
		}
	}
	
	// Supprime un articleMedia de la base.
	public function deleteArticleMedia($articleMedia){

		 if($articleMedia instanceof ArticleMedia){	

			// Suppression de l'articleMedia
			$requete = $this->dao->prepare("DELETE FROM article_media WHERE id_article = :idArticle and id_media = :idMedia;");

			//bind des valeurs
			$requete->bindValue(':idArticle', $articleMedia->getArticle()->getIdArticle(), \PDO::PARAM_INT);
			$requete->bindValue(':idMedia', $articleMedia->getMedia()->getIdMedia(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::ArticleMedia : L\'objet passé en paramètre n\'est pas une instance de ArticleMedia');
		}
	}
	
	
	// Sélection d'un articleMedia par son ID
	public function getArticleMediaById($idArticle, $idMedia){
		
		$requete = $this->dao->prepare("SELECT * FROM article_media WHERE id_article = :idArticle AND id_media = : idMedia");
		
		//bind des paramètres
		$requete->bindValue(':idArticle', $idArticle, \PDO::PARAM_INT);
		$requete->bindValue(':idMedia', $idMedia, \PDO::PARAM_INT);
			
		
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
			$articleMedia = $this->constructArticleMedia($donnees[0]);
			return $articleMedia;
		}
	}

	
	// Permet de contruire un objet articleMedia à partir des données de la base.
	protected function constructArticleMedia($donnee){
		
		$pdoArticle = new PDOArticleManager($this->dao);
	
	 	$pdoMedia = new PDOMediaManager($this->dao);

		$data = [
			'article' => $pdoArticle->getArticleById($donnee['id_article']),
			'motCle' => $pdoMedia->getMediaById($donnee['id_media'])
		];
		return new ArticleMedia($data);
	}
}
