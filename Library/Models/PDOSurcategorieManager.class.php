<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des Surcategories.					  |
// +----------------------------------------------------------------------+
// | Surcategorie : Guénaël Dequeker <DequekerGuenael@noolib.com>	      |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des Surcategories
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\Surcategorie;
use \Library\Models;

class PDOSurcategorieManager extends \Library\Models\SurcategorieManager
{

/* Définition des méthode de classe */

	//ajoute une surcategorie dans la base
	public function addSurcategorie($surcategorie){

		if($surcategorie instanceof Surcategorie){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO surcategorie (nom_surcategorie) 
					VALUES (:nomSurcategorie)");
			
			//bind des valeurs
			$requete->bindValue(':nomSurcategorie', $surcategorie->getNomSurcategorie(), \PDO::PARAM_STR);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$surcategorie->setIdSurcategorie($this->dao->lastInsertId('id_surcategorie'));
				$this->dao->commit();
				
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}
			
			//On libère la requete
			$requete->closeCursor();
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Surcategorie : L\'objet passé en paramètre n\'est pas une instance de Surcategorie');
		}
	}
	
	public function addCategoriesFromSurcategorie($surcategorie){

		if($surcategorie instanceof Surcategorie){
			if (sizeof($surcategorie->getCategories()) != 0){
			
				foreach ($categorie->getCategories() as $categorie){
					
					//pr�paration de la requete
					$requete = $this->dao->prepare("UPDATE categorie SET id_surcategorie = :idSurcategorie WHERE id_categorie = :idCategorie;");
			
					//bind des valeurs
					$requete->bindValue(':idSurcategorie', $surcategorie->getIdSurcategorie(), \PDO::PARAM_INT);
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
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Surcategorie : L\'objet passé en paramètre n\'est pas une instance de Surcategorie');
		}
	}
	
	//sauvegarde les modifications d'une utilisateur
	public function saveSurcategorie($surcategorie){

		if($surcategorie instanceof Surcategorie){
	
			//préparation de la requete
			$requete = $this->dao->prepare("UPDATE surcategorie SET
					nom_surcategorie = :nomSurcategorie 
					WHERE id_surcategorie = :idSurcategorie;");

			//bind des valeurs
			$requete->bindValue(':idSurcategorie', $surcategorie->getIdSurcategorie(), \PDO::PARAM_INT);
			$requete->bindValue(':nomSurcategorie', $surcategorie->getNomSurcategorie(), \PDO::PARAM_STR);

			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//On libère la requete
			$requete->closeCursor();
			
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Surcategorie : L\'objet passé en paramètre n\'est pas une instance de Surcategorie');
		}
	}
	
	public function deleteLinkbetweenCategoriesSurcategorie($surcategorie){

		if($surcategorie instanceof Surcategorie){
					
			//Préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM categorie WHERE id_surcategorie = :idSurcategorie");
			
			//Bind des valeurs
			$requete->bindValue(':idSurcategorie', $surcategorie->getIdSurcategorie(), \PDO::PARAM_INT);
			//execution de la requete sinon envoi d'une erreur
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
			$messageClient->addErreur('PDO::Surcategorie : L\'objet passé en paramètre n\'est pas une instance de Surcategorie');
		}
	}
	
	//supprime la surcategorie de la base et modifie les données de toutes les utilisateurs avec cette surcategorie.
	public function deleteSurcategorie($surcategorie){

		if($surcategorie instanceof Surcategorie){	
		
			$this->deleteLinkbetweenCategoriesSurcategorie($surcategorie);
			
			$requete = $this->dao->prepare("DELETE FROM surcategorie WHERE id_surcategorie = :idSurcategorie");
			
			//bind des valeurs
			$requete->bindValue(':idSurcategorie', $surcategorie->getIdSurcategorie(), \PDO::PARAM_INT);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//On libère la requete
			$requete->closeCursor();
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Surcategorie : L\'objet passé en paramètre n\'est pas une instance de Surcategorie');
		}
	}
	
	//selectionne une surcategorie par son ID
	public function getSurcategorieById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM surcategorie WHERE id_surcategorie = :idSurcategorie");
		
		//bind des parametre
		$requete->bindValue(':idSurcategorie', $id, \PDO::PARAM_INT);
		
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
			$surcategorie = $this->constructSurcategorie($donnees[0]);
			return $surcategorie;
		}

	}
	
	public function getSurcategorieByNom($nom){
		
		$requete = $this->dao->prepare("SELECT * FROM surcategorie WHERE nom_surcategorie = :nomSurcategorie");
		
		//bind des parametre
		$requete->bindValue(':nomSurcategorie', $nom, \PDO::PARAM_STR);
		
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
			$surcategorie = $this->constructSurcategorie($donnees[0]);
			return $surcategorie;
		}

	}
	
	//renvoi un tableau de toutes les surcategories
	public function getAllSurcategories(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM surcategorie ORDER BY nom_surcategorie");
		
		//execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//creation d'un tableau d'utilisateur
		$surcategories = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($surcategories, $this->constructSurcategorie($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $surcategories;
	}
	
	//renvoi un tableau de surcategorie a partir de l'index début jusqu'a debut + quantite
	public function getSurcategoriesBetweenIndex($debut, $quantite){

		$requete = $this->dao->prepare("SELECT * FROM surcategorie LIMIT :debut,:quantite");
		
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
		//creation d'un tableau d'utilisateur
		$surcategories = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($surcategories, $this->constructSurcategorie($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $surcategories;
	}
	
	//retourne le nombre de surcategorie dans la base
	public function getNumberOfSurcategorie(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreSurcategorie FROM surcategorie');
		
		//execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		
		$donnees = $requete->fetch();
		
		//On libère la requete
		$requete->closeCursor();
		
		return $donnees['nombreSurcategorie'];
	}
	
	public function putCategoriesInSurcategorie($surcategorie){

		if($surcategorie instanceof Surcategorie){
		
			$requete = $this->dao->prepare("SELECT id_categorie FROM categorie WHERE id_surcategorie = :idSurcategorie");
			
			//bind des parametre
			$requete->bindValue(':idSurcategorie', $surcategorie->getIdSurcategorie(), \PDO::PARAM_INT);
			
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
			$pdoCategorie = new PDOCategorieManager($this->dao);
			
			//On construit l'objet application
			while ($donnees = $requete->fetch())
			{
				$surcategorie->addCategorie($pdoCategorie->getCategorieById($donnees['id_categorie']));
			}
			
			//On lib�re la requete
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Surcategorie : L\'objet passé en paramètre n\'est pas une instance de Surcategorie');
		}
		
	}
	
	//permet de contruire un objet surcategorie a partir des ses données de la base.
	protected function constructSurcategorie($donnee){
		
		$pdoPays = new PDOPaysManager($this->dao);
		
		$data = [
		'idSurcategorie' => $donnee['id_surcategorie']  ,
		'nomSurcategorie' => $donnee['nom_surcategorie']
		];

		return new Surcategorie($data);
	}
}
