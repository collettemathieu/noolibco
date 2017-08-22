<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des Mots-clés. 						  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des mots-clés
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\MotCle;
use \Library\Models;

class PDOMotCleManager extends \Library\Models\MotCleManager{

/* Définitions des méthodes action de la classe */

	// Ajout d'un mot-clé à la base
	public function addMotCle($motCle){

		 if($motCle instanceof MotCle){
		
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO mot_cle (nom_mot_cle) 
					VALUES (:nomMotCle)");

			//bind des valeurs
			$requete->bindValue(':nomMotCle', $motCle->getNomMotCle(), \PDO::PARAM_STR);
			
			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$motCle->setIdMotCle($this->dao->lastInsertId('id_mot_cle'));
				$this->dao->commit();
				
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//On libère la requête
			$requete->closeCursor();
			return $motCle;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::MotCle : L\'objet passé en paramètre n\'est pas une instance de MotCle');
		}
	}
	
	// Ajout du mot-clé de tous les articles associés
	public function addArticlesFromMotCle($motCle){

		 if($motCle instanceof MotCle){
			if (sizeof($motCle->getArticles()) != 0){
			
				foreach ($motCle->getArticles() as $article){
						
					//préparation de la requête
					$requete = $this->dao->prepare("INSERT INTO article_mot_cle VALUES (:idMotCle, :idArticle) WHERE NOT EXISTS 
							(SELECT 0 FROM article_mot_cle WHERE id_mot_cle = :idMotCle and id_article = :idArticle );");
			
					//bind des valeurs
					$requete->bindValue(':idMotCle', $motCle->getIdMotCle(), \PDO::PARAM_INT);
					$requete->bindValue(':idArticle', $article->getIdArticle(), \PDO::PARAM_INT);
			
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
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::MotCle : L\'objet passé en paramètre n\'est pas une instance de MotCle');
		}
	}

	// Ajout du mot-clé de tous les cours associés
	public function addCoursFromMotCle($motCle){

		 if($motCle instanceof MotCle){
			if (sizeof($motCle->getCours()) != 0){
			
				foreach ($motCle->getCours() as $cours){
						
					//préparation de la requête
					$requete = $this->dao->prepare("INSERT INTO cours_mot_cle VALUES (:idMotCle, :idCours) WHERE NOT EXISTS 
							(SELECT 0 FROM cours_mot_cle WHERE id_mot_cle = :idMotCle and id_cours = :idCours );");
			
					//bind des valeurs
					$requete->bindValue(':idMotCle', $motCle->getIdMotCle(), \PDO::PARAM_INT);
					$requete->bindValue(':idCours', $cours->getIdCours(), \PDO::PARAM_INT);
			
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
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::MotCle : L\'objet passé en paramètre n\'est pas une instance de MotCle');
		}
	}

	// Sauvegarde les modifications d'un mot-clé
	public function saveMotCle($motCle){

		 if($motCle instanceof MotCle){
	
			//préparation de la requête
			$requete = $this->dao->prepare("UPDATE mot_cle (nom_mot_cle) SET
					nom_mot_cle = :nomMotCle,
					WHERE id_mot_cle = :idMotCle;");

			//bind des valeurs
			$requete->bindValue(':idMotCle', $motCle->getIdMotCle(), \PDO::PARAM_INT);
			$requete->bindValue(':nomMotCle', $motCle->getNomMotCle(), \PDO::PARAM_STR);

			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//on libère la requête
			$requete->closeCursor();
			
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::MotCle : L\'objet passé en paramètre n\'est pas une instance de MotCle');
		}
	}
	
	// Supprimer le lien entre un mot-clé et les articles associés
	public function deleteLinkbetweenArticlesMotCle($motCle){

		 if($motCle instanceof MotCle){
			//préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM article_mot_cle WHERE id_mot_cle = :idMotCle;");

			//bind des valeurs
			$requete->bindValue(':idMotCle', $motCle->getIdMotCle(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::MotCle : L\'objet passé en paramètre n\'est pas une instance de MotCle');
		}
	}

	// Supprimer le lien entre un mot-clé et les cours associés
	public function deleteLinkbetweenCoursMotCle($motCle){

		 if($motCle instanceof MotCle){
			//préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM cours_mot_cle WHERE id_mot_cle = :idMotCle;");

			//bind des valeurs
			$requete->bindValue(':idMotCle', $motCle->getIdMotCle(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::MotCle : L\'objet passé en paramètre n\'est pas une instance de MotCle');
		}
	}
	
	// Supprime un mot-clé de la base.
	public function deleteMotCle($motCle){

		 if($motCle instanceof MotCle){	

			// Suppression des liens
			// Suppression de la liaison entre les motclés et les articles
			$this->deleteLinkbetweenArticlesMotCle($motCle);

			// Suppression de la liaison entre les motclés et les cours
			$this->deleteLinkbetweenCoursMotCle($motCle);

			// Suppression du motCle
			$requete = $this->dao->prepare("DELETE FROM mot_cle WHERE id_mot_cle = :idMotCle");

			//bind des valeurs
			$requete->bindValue(':idMotCle', $motCle->getIdMotCle(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::MotCle : L\'objet passé en paramètre n\'est pas une instance de MotCle');
		}
	}
	
	
	// Sélection d'un mot-clé par son ID
	public function getMotCleById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM mot_cle WHERE id_mot_cle = :idMotCle");
		
		//bind des paramètres
		$requete->bindValue(':idMotCle', $id, \PDO::PARAM_INT);
		
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
			$motCle = $this->constructMotCle($donnees[0]);
			return $motCle;
		}
	}

	// Sélection d'un mot-clé par son nom
	public function getMotCleByName($nomMotCle){
	
		$requete = $this->dao->prepare("SELECT * FROM mot_cle WHERE UPPER(nom_mot_cle) = :nomMotCle");
	
		//bind des paramètres
		$requete->bindValue(':nomMotCle', strtoupper($nomMotCle), \PDO::PARAM_STR);
	
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
			$motCle = $this->constructMotCle($donnees[0]);
			return $motCle;
		}
	}
	
	// Renvoi un tableau de toutes les mots-clés
	public function getAllMotCles(){
		
		//préparation de la requête
		$requete = $this->dao->prepare("SELECT * FROM mot_cle");
		
		//exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//création d'un tableau de mots-clés
		$motCles = array();
		
		//On construit l'objet mot-clé
		while ($donnees = $requete->fetch())
		{
			array_push($motCles, $this->constructMotCle($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $motCles;
	}
	
	// Renvoi un tableau de mots-clés à partir de l'index début jusqu'à debut + quantité
	public function getMotClesBetweenIndex($debut, $quantite){

		$requete = $this->dao->prepare("SELECT * FROM mot_cle LIMIT :debut,:quantite");
		
		//bind des paramètres
		$requete->bindValue(':debut', $debut, \PDO::PARAM_INT);
		$requete->bindValue(':quantite', $quantite, \PDO::PARAM_INT);
		
		//exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//création d'un tableau de mots-clés
		$motCles = array();
		
		//On construit l'objet mot-clé
		while ($donnees = $requete->fetch())
		{
			array_push($motCles, $this->constructMotCle($donnees));
		}
		
		//On libère la requête
		$requete->closeCursor();
		
		return $motCles;
	}
	
	// Place les articles associés au mot-clé dans l'objet mot-clé
	public function putArticlesInMotCle($motCle){

		 if($motCle instanceof MotCle){
	
			$requete = $this->dao->prepare("SELECT * FROM article_mot_cle WHERE id_mot_cle = :idMotCle");
		
			//bind des paramètres
			$requete->bindValue(':idMotCle', $motCle->getIdMotCle(), \PDO::PARAM_INT);
			
			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			//création d'un tableau de mots-clés
			$pdoArticle = new PDOArticleManager($this->dao);
			
			//On construit l'objet mot-clé
			while ($donnees = $requete->fetch())
			{
				$motCle->addArticle($pdoArticle->getArticleById($donnees['id_article']));
			}
		
			//On libère la requête
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::MotCle : L\'objet passé en paramètre n\'est pas une instance de MotCle');
		}
	}

	// Place les cours associés au mot-clé dans l'objet mot-clé
	public function putCoursInMotCle($motCle){

		 if($motCle instanceof MotCle){
	
			$requete = $this->dao->prepare("SELECT * FROM cours_mot_cle WHERE id_mot_cle = :idMotCle");
		
			//bind des paramètres
			$requete->bindValue(':idMotCle', $motCle->getIdMotCle(), \PDO::PARAM_INT);
			
			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			//création d'un tableau de mots-clés
			$pdoCours = new PDOCoursManager($this->dao);
			
			//On construit l'objet mot-clé
			while ($donnees = $requete->fetch())
			{
				$motCle->addArticle($pdoCours->getCoursById($donnees['id_cours']));
			}
		
			//On libère la requête
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::MotCle : L\'objet passé en paramètre n\'est pas une instance de MotCle');
		}
	}
	
	// Retourne le nombre de mots-clés dans la base
	public function getNumberOfMotCle(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreMotCle FROM mot_cle');
		
		//exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		
		$donnees = $requete->fetch();
		
		//On libère la requête
		$requete->closeCursor();
		
		return $donnees['nombreMotCle'];
	}
	
	// Permet de contruire un objet mot-clé à partir des données de la base.
	protected function constructMotCle($donnee){
		
		$data = [
			'idMotCle' => $donnee['id_mot_cle'],
			'nomMotCle' => $donnee['nom_mot_cle'],
		];
		return new MotCle($data);
	}
}
