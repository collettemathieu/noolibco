<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des ArticleUtilisateur.				  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des ArticleUtilisateur
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\ArticleUtilisateur;
use \Library\Models;

class PDOArticleUtilisateurManager extends \Library\Models\ArticleUtilisateurManager
{

/* Définitions des méthodes action de la classe */

	// Ajout d'un articleUtilisateur à la base
	public function addArticleMotCle($articleUtilisateur){

		 if($articleUtilisateur instanceof ArticleUtilisateur){
		
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO article_utilisateur VALUES (:idArticle, :idUtilisateur)");

			//bind des valeurs
			$requete->bindValue(':idArticle', $articleUtilisateur->getArticle()->getIdArticle(), \PDO::PARAM_INT);
			$requete->bindValue(':idUtilisateur', $articleUtilisateur->getUtilisateur()->getIdUtilisateur(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::ArticleUtilisateur : L\'objet passé en paramètre n\'est pas une instance de ArticleUtilisateur');
		}
	}
	
	// Supprime un articleUtilisateur de la base.
	public function deleteArticleUtilisateur($articleUtilisateur){

		 if($articleUtilisateur instanceof ArticleUtilisateur){	

			// Suppression de l'articleUtilisateur
			$requete = $this->dao->prepare("DELETE FROM article_utilisateur WHERE id_article = :idArticle and id_utilisateur = :idUtilisateur;");

			//bind des valeurs
			$requete->bindValue(':idArticle', $articleUtilisateur->getArticle()->getIdArticle(), \PDO::PARAM_INT);
			$requete->bindValue(':idUtilisateur', $articleUtilisateur->getUtilisateur()->getIdUtilisateur(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::ArticleUtilisateur : L\'objet passé en paramètre n\'est pas une instance de ArticleUtilisateur');
		}
	}
	
	
	// Sélection d'un articleUtilisateur par son ID
	public function getArticleUtilisateurById($idArticle, $idUtilisateur){
		
		$requete = $this->dao->prepare("SELECT * FROM article_utilisateur WHERE id_article = :idArticle AND id_utilisateur = : idUtilisateur");
		
		//bind des paramètres
		$requete->bindValue(':idArticle', $idArticle, \PDO::PARAM_INT);
		$requete->bindValue(':idUtilisateur', $idUtilisateur, \PDO::PARAM_INT);
			
		
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
			$articleUtilisateur = $this->constructArticleUtilisateur($donnees[0]);
			return $articleUtilisateur;
		}
	}

	
	// Permet de contruire un objet articleUtilisateur à partir des données de la base.
	protected function constructArticleUtilisateur($donnee){
		
		$pdoArticle = new PDOArticleManager($this->dao);
	
	 	$pdoUtilisateur = new PDOUtilisateurManager($this->dao);

		$data = [
			'article' => $pdoArticle->getArticleById($donnee['id_article'],
			'utilisateur' => $pdoUtilisateur->getUtilisateurById($donnee['id_utilisateur'])
		];
		return new ArticleUtilisateur($data);
	}
}
