<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le PDO manager des catégories.						  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des catégories
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Categorie;
use \Library\Models;

class PDOCategorieManager extends \Library\Models\CategorieManager
{

/* Définitions des méthodes action de la classe */

	// Ajout d'une catégorie à la base
	public function addCategorie($categorie){

		 if($categorie instanceof Categorie){
		
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO categorie (nom_categorie, description_categorie) 
					VALUES (:nomCategorie, :descriptionCategorie)");

			//bind des valeurs
			$requete->bindValue(':nomCategorie', $categorie->getNomCategorie(), \PDO::PARAM_STR);
			$requete->bindValue(':descriptionCategorie', $categorie->getDescriptionCategorie(), \PDO::PARAM_STR);
			
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
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Categorie : L\'objet passé en paramètre n\'est pas une instance de Categorie');
		}
	}
	
	// Sauvegarde les modifications d'une catégorie
	public function saveCategorie($categorie){

		 if($categorie instanceof Categorie){
	
			//préparation de la requête
			$requete = $this->dao->prepare("UPDATE categorie SET
					nom_categorie = :nomCategorie,
					description_categorie = :descriptionCategorie
					WHERE id_categorie = :idCategorie");

			//bind des valeurs
			$requete->bindValue(':nomCategorie', $categorie->getNomCategorie(), \PDO::PARAM_STR);
			$requete->bindValue(':descriptionCategorie', $categorie->getDescriptionCategorie(), \PDO::PARAM_STR);
			$requete->bindValue(':idCategorie', $categorie->getIdCategorie(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Categorie : L\'objet passé en paramètre n\'est pas une instance de Categorie');
		}
	}
	
	
	
	// Supprime une catégorie de la base.
	public function deleteCategorie($categorie){

		 if($categorie instanceof Categorie){	

			// Suppression du motCle
			$requete = $this->dao->prepare("DELETE FROM categorie WHERE id_categorie = :idCategorie");

			//bind des valeurs
			$requete->bindValue(':idCategorie', $categorie->getIdCategorie(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Categorie : L\'objet passé en paramètre n\'est pas une instance de Categorie');
		}
	}
	
	
	// Sélection d'une catégorie par son ID
	public function getCategorieById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM categorie WHERE id_categorie = :idCategorie");
		
		//bind des paramètres
		$requete->bindValue(':idCategorie', $id, \PDO::PARAM_INT);
		
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
			$categorie = $this->constructCategorie($donnees[0]);
			return $categorie;
		}
	}

	// Sélection d'une catégorie par son nom
	public function getCatgeorieByName($nomCategorie){
	
		$requete = $this->dao->prepare("SELECT * FROM categorie WHERE nom_categorie = :nomCategorie");
	
		//bind des paramètres
		$requete->bindValue(':nomCategorie', $nomCategorie, \PDO::PARAM_STR);

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
			$categorie = $this->constructCategorie($donnees[0]);
			return $categorie;
		}
	}
	
	// Renvoi un tableau de toutes les catégories
	public function getAllCategories(){
		
		//préparation de la requête
		$requete = $this->dao->prepare("SELECT * FROM categorie");
		
		//exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//création d'un tableau de catégorie
		$categories = array();
		
		//On construit l'objet tableau de catégorie
		while ($donnees = $requete->fetch())
		{
			array_push($categories, $this->constructCategorie($donnees));
		}
		
		//On libère la requête
		$requete->closeCursor();
		
		return $categories;
	}
	
	// Renvoi un tableau des catégories à partir de l'index début jusqu'à debut + quantité
	public function getCategoriesBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM categorie LIMIT :debut,:quantite");
		
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
		//création d'un tableau de catégories
		$categories = array();
		
		//On construit l'objet tableau de catégorie
		while ($donnees = $requete->fetch())
		{
			array_push($categories, $this->constructCategorie($donnees));
		}
		
		//On libère la requête
		$requete->closeCursor();
		
		return $categories;
	}
	
	// Place les articles associés à la catégorie dans l'objet catégorie
	public function putArticlesInCategorie($categorie){

		 if($categorie instanceof Categorie){
	
			$requete = $this->dao->prepare("SELECT * FROM article WHERE id_categorie = :idCategorie");
		
			//bind des paramètres
			$requete->bindValue(':idCategorie', $categorie->getIdCategorie(), \PDO::PARAM_INT);
			
			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			//appel du pdo des articles
			$pdoArticle = new PDOArticleManager($this->dao);
			
			//On met à jour l'objet catégorie
			while ($donnees = $requete->fetch())
			{
				$categorie->addArticle($pdoArticle->getArticleById($donnees['id_article']));
			}
		
			//On libère la requête
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Categorie : L\'objet passé en paramètre n\'est pas une instance de Categorie');
		}
	}

	// Place les cours associés à la catégorie dans l'objet catégorie
	public function putCoursInCategorie($categorie){

		 if($categorie instanceof Categorie){
	
			$requete = $this->dao->prepare("SELECT * FROM cours WHERE id_categorie = :idCategorie");
		
			//bind des paramètres
			$requete->bindValue(':idCategorie', $categorie->getIdCategorie(), \PDO::PARAM_INT);
			
			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			//appel du pdo des cours
			$pdoCours = new PDOCoursManager($this->dao);
			
			//On met à jour l'objet catégorie
			while ($donnees = $requete->fetch())
			{
				$categorie->addCours($pdoCours->getCoursById($donnees['id_article']));
			}
		
			//On libère la requête
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Categorie : L\'objet passé en paramètre n\'est pas une instance de Categorie');
		}
	}

	
	
	// Retourne le nombre de mots-clés dans la base
	public function getNumberOfCategorie(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreCategorie FROM categorie');
		
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
		
		return $donnees['nombreCategorie'];
	}
	
	// Permet de contruire un objet categorie à partir des données de la base.
	protected function constructCategorie($donnee){
		
		$data = [
		'idCategorie' => $donnee['id_categorie'],
		'nomCategorie' => $donnee['nom_categorie'],
		'descriptionCategorie' => $donnee['description_categorie'] 
		];
		return new Categorie($data);
	}
}
