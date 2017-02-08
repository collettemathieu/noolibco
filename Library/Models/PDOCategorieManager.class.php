<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des cat�gories.					 	  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des cat�gories
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Categorie;
use \Library\Models;

class PDOCategorieManager extends \Library\Models\CategorieManager
{

/* D�finitions des m�thodes action de la classe */

	//ajoute une cat�gorie dans la base
	public function addCategorie($categorie){

		 if($categorie instanceof Categorie){
		
			//pr�paration de la requete
			$requete = $this->dao->prepare("INSERT INTO categorie (nom_categorie, description_categorie, id_surcategorie) 
					VALUES (:nomCategorie, :descriptionCategorie, :idSurcategorie)");

			//bind des valeurs
			$requete->bindValue(':nomCategorie', $categorie->getNomCategorie(), \PDO::PARAM_STR);
			$requete->bindValue(':descriptionCategorie', $categorie->getDescriptionCategorie(), \PDO::PARAM_STR);
			$requete->bindValue(':idSurcategorie', $categorie->getSurcategorie()->getIdSurcategorie(), \PDO::PARAM_INT);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$categorie->setIdCategorie($this->dao->lastInsertId('id_categorie'));
				$this->dao->commit();
				
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//On lib�re la requete
			$requete->closeCursor();
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Categorie : L\'objet passé en paramètre n\'est pas une instance de Categorie');
		}
	}
	
	public function addApplicationsFromCategorie($categorie){

		 if($categorie instanceof Categorie){
			if (sizeof($categorie->getApplications()) != 0){
					
				foreach ($categorie->getApplications() as $application){
						
					//pr�paration de la requete
					$requete = $this->dao->prepare("UPDATE application SET id_categorie = :idCategorie WHERE id_application = :idApplication;");
			
					//bind des valeurs
					$requete->bindValue(':idCategorie', $categorie->getIdCategorie(), \PDO::PARAM_INT);
					$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
			
					//execution de la requete sinon envoi d'une erreur
					try {
						$this->dao->beginTransaction();
						$requete->execute();
						$this->dao->commit();
					} catch(PDOException $e) {
						$this->dao->rollback();
						return "Error!: " . $e->getMessage() . "</br>";
					}
			
					//On lib�re la requete
					$requete->closeCursor();
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Categorie : L\'objet passé en paramètre n\'est pas une instance de Categorie');
		}
	}
	
	//sauvegarde les modifications d'une application
	public function saveCategorie($categorie){

		 if($categorie instanceof Categorie){
	
			//pr�paration de la requete
			$requete = $this->dao->prepare("UPDATE categorie SET
					nom_categorie = :nomCategorie,
					description_categorie = :descriptionCategorie, 
					id_surcategorie = :idSurcategorie
					WHERE id_categorie = :idCategorie");

			//bind des valeurs
			$requete->bindValue(':nomCategorie', $categorie->getNomCategorie(), \PDO::PARAM_STR);
			$requete->bindValue(':descriptionCategorie', $categorie->getDescriptionCategorie(), \PDO::PARAM_STR);
			$requete->bindValue(':idSurcategorie', $categorie->getSurcategorie()->getIdSurcategorie(), \PDO::PARAM_INT);
			$requete->bindValue(':idCategorie', $categorie->getIdCategorie(), \PDO::PARAM_INT);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//On lib�re la requete
			$requete->closeCursor();
			
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Categorie : L\'objet passé en paramètre n\'est pas une instance de Categorie');
		}
	}
	
	public function deleteLinkBetweenApplicationsCategorie($categorie){

		 if($categorie instanceof Categorie){
			//suppression de l'id dans la table application
			$requete = $this->dao->prepare("UPDATE application
					SET id_categorie = NULL
					WHERE id_categorie = :idCategorie");
			//bind des valeurs
			$requete->bindValue(':idCategorie', $categorie->getIdCategorie(), \PDO::PARAM_INT);
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Categorie : L\'objet passé en paramètre n\'est pas une instance de Categorie');
		}
	}
	
	//supprime la cat�gorie de la base et modifie les donn�es de toutes les applications avec cette cat�gorie.
	public function deleteCategorie($categorie){

		 if($categorie instanceof Categorie){	
		
			$this->deleteLinkBetweenApplicationsCategorie($categorie);
			
			//suppression de la categorie
			//pr�paration de la requete
			$requete = $this->dao->prepare("DELETE FROM categorie WHERE id_categorie = :idCategorie");

			//bind des valeurs
			$requete->bindValue(':idCategorie', $categorie->getIdCategorie(), \PDO::PARAM_INT);

			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//On lib�re la requete
			$requete->closeCursor();
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Categorie : L\'objet passé en paramètre n\'est pas une instance de Categorie');
		}
	}
	
	
	//selectionne une cat�gorie par son ID
	public function getCategorieById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM categorie WHERE id_categorie = :idCategorie");
		
		//bind des parametre
		$requete->bindValue(':idCategorie', $id, \PDO::PARAM_INT);
		
		//execution de la requete sinon envoi d'une erreur
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
	
	public function getCategorieByNom($nomCategorie){
	
		$requete = $this->dao->prepare("SELECT * FROM categorie WHERE nom_categorie = :nomCategorie");
	
		//bind des parametre
		$requete->bindValue(':nomCategorie', $nomCategorie, \PDO::PARAM_STR);
	
		//execution de la requete sinon envoi d'une erreur
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
	//renvoi un tableau de toutes les cat�gories
	public function getAllCategories(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM categorie ORDER BY id_surcategorie");
		
		//execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//creation d'un tableau d'application
		$categories = array();
		
		//On construit l'objet application
		while ($donnees = $requete->fetch())
		{
			array_push($categories, $this->constructCategorie($donnees));
		}
		
		//On lib�re la requete
		$requete->closeCursor();
		
		return $categories;
	}
	
	//renvoi un tableau de cat�gorie a partir de l'index d�but jusqu'a debut + quantite
	public function getCategoriesBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM categorie LIMIT :debut,:quantite");
		
		//bind des parametre
		$requete->bindValue(':debut', $debut, \PDO::PARAM_INT);
		$requete->bindValue(':quantite', $quantite, \PDO::PARAM_INT);
		
		//execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//creation d'un tableau d'application
		$categories = array();
		
		//On construit l'objet application
		while ($donnees = $requete->fetch())
		{
			array_push($categories, $this->constructCategorie($donnees));
		}
		
		//On lib�re la requete
		$requete->closeCursor();
		
		return $categories;
	}
	
	public function putApplicationsInCategorie($categorie){

		 if($categorie instanceof Categorie){
	
			$requete = $this->dao->prepare("SELECT * FROM application WHERE id_categorie = :idCategorie");
		
			//bind des parametre
			$requete->bindValue(':idCategorie', $categorie->getIdCategorie(), \PDO::PARAM_INT);
		
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			//creation d'un tableau d'application
			$pdoApplication = new PDOApplicationManager($this->dao);
			
			//On construit l'objet application
			while ($donnees = $requete->fetch())
			{
				$categorie->addApplication($pdoApplication->getApplicationById($donnees['id_application']));
			}
		
			//On lib�re la requete
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Categorie : L\'objet passé en paramètre n\'est pas une instance de Categorie');
		}
	}
	
	//retourne le nombre de categorie dans la base
	public function getNumberOfCategorie(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreCategorie FROM categorie');
		
		//execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//creation d'un tableau d'application
		
		$donnees = $requete->fetch();
		
		//On libère la requete
		$requete->closeCursor();
		
		return $donnees['nombreCategorie'];
	}
	
	//permet de contruire un objet catégorie a partir des ses données de la base.
	protected function constructCategorie($donnee){
		
		$pdoSurcategorie = new PDOSurcategorieManager($this->dao);
		
		$data = [
		'IdCategorie' => $donnee['id_categorie'],
		'NomCategorie' => $donnee['nom_categorie'],
		'DescriptionCategorie' => $donnee['description_categorie'], 
		'Surcategorie' => $pdoSurcategorie->getSurcategorieById($donnee['id_surcategorie']) 
		];
		return new Categorie($data);
	}
}
