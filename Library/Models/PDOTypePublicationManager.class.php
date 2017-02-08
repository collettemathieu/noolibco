<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des typePublications.				  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des typePublications
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\TypePublication;
use \Library\Models;

class PDOTypePublicationManager extends \Library\Models\TypePublicationManager
{

/* Définitions des méthodes action de la classe */

	//ajoute une catégorie dans la base
	public function addTypePublication($typePublication){

		if($typePublication instanceof TypePublication){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO type_publication (nom_type_publication) 
					VALUES (:nomTypePublication)");

			//bind des valeurs
			$requete->bindValue(':nomTypePublication', $typePublication->getNomTypePublication(), \PDO::PARAM_STR);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$typePublication->setIdTypePublication($requete->lastInsertId('id_type_publication'));
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
		$messageClient->addErreur('PDO::TypePublication : L\'objet passé en paramètre n\'est pas une instance de TypePublication');
		}
	}
	
	public function addPublicationsFromTypePublication($typePublication){

		if($typePublication instanceof TypePublication){
	
			if (sizeof($typePublication->getPublications()) != 0){
					
				foreach ($typePublication->getPublications() as $publication){
						
					//pr�paration de la requete
					$requete = $this->dao->prepare("UPDATE publication SET id_type_publication = :idTypePublication WHERE id_publication = :idPublication)");
			
					//bind des valeurs
					$requete->bindValue(':idTypePublication', $typePublication->getIdTypePublication(), \PDO::PARAM_INT);
					$requete->bindValue(':idPublication', $publication->getIdPublication(), \PDO::PARAM_INT);
			
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
		$messageClient->addErreur('PDO::TypePublication : L\'objet passé en paramètre n\'est pas une instance de TypePublication');
		}
	}
	
	
	//sauvegarde les modifications d'une publication
	public function saveTypePublication($typePublication){

		if($typePublication instanceof TypePublication){
	
			//préparation de la requete
			$requete = $this->dao->prepare("UPDATE type_publication (nom_type_publication) SET
					nom_type_publication = :nomTypePublication,
					WHERE id_type_publication = :idTypePublication;");

			//bind des valeurs
			$requete->bindValue(':idTypePublication', $typePublication->getIdTypePublication(), \PDO::PARAM_INT);
			$requete->bindValue(':nomTypePublication', $typePublication->getNomTypePublication(), \PDO::PARAM_STR);

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
		$messageClient->addErreur('PDO::TypePublication : L\'objet passé en paramètre n\'est pas une instance de TypePublication');
		}
	}
	
	public function deleteLinkbetweenPublicationsTypePublication($typePublication){

		if($typePublication instanceof TypePublication){
	
			if (sizeof($typePublication->getPublications()) != 0){
		
				foreach ($typePublication->getPublications() as $publication){
						
					//pr�paration de la requete
					$requete = $this->dao->prepare("UPDATE publication SET id_type_publication = null WHERE id_publication = :idPublication)");
		
					//bind des valeurs
					$requete->bindValue(':idPublication', $publication->getIdPublication(), \PDO::PARAM_INT);
		
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
		$messageClient->addErreur('PDO::TypePublication : L\'objet passé en paramètre n\'est pas une instance de TypePublication');
		}
	}
	
	//supprime la catégorie de la base et modifie les données de toutes les publications avec cette catégorie.
	public function deleteTypePublication($typePublication){

		if($typePublication instanceof TypePublication){	

			//suppression de l'id dans la table publication
			$requete = $this->dao->prepare("UPDATE publication
					SET id_type_publication = NULL
					WHERE id_type_publication = :idTypePublication");
			//bind des valeurs
			$requete->bindValue(':idTypePublication', $typePublication->getIdTypePublication(), \PDO::PARAM_INT);
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}
			
			//suppression du typePublication
			//préparation de la requete
			$requete = $this->dao->prepare("DELETE FROM type_publication WHERE id_type_publication = :idTypePublication)");

			//bind des valeurs
			$requete->bindValue(':idTypePublication', $typePublication->getIdTypePublication(), \PDO::PARAM_INT);

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
		$messageClient->addErreur('PDO::TypePublication : L\'objet passé en paramètre n\'est pas une instance de TypePublication');
		}
	}
	
	
	//selectionne une catégorie par son ID
	public function getTypePublicationById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM type_publication WHERE id_type_publication = :idTypePublication");
		
		//bind des parametre
		$requete->bindValue(':idTypePublication', $id, \PDO::PARAM_INT);
		
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
			$typePublication = $this->constructTypePublication($donnees[0]);
			return $typePublication;
		}
	}
	public function getTypePublicationByName($nomTypePublication){
	
		$requete = $this->dao->prepare("SELECT * FROM type_publication WHERE UPPER(nom_type_publication) = :nomTypePublication");
	
		//bind des parametre
		$requete->bindValue(':nomTypePublication',strtoupper($nomTypePublication), \PDO::PARAM_STR);
	
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
			$typePublication = $this->constructTypePublication($donnees[0]);
			return $typePublication;
		}
	}
	//renvoi un tableau de toutes les catégories
	public function getAllTypePublications(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM type_publication");
		
		//execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//creation d'un tableau d'publication
		$typesPublication = array();
		
		//On construit l'objet publication
		while ($donnees = $requete->fetch())
		{
			array_push($typesPublication, $this->constructTypePublication($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $typesPublication;
	}
	
	//renvoi un tableau de catégorie a partir de l'index début jusqu'a debut + quantite
	public function getTypePublicationsBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM type_publication LIMIT :debut,:quantite");
		
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
		//creation d'un tableau d'publication
		$typePublications = array();
		
		//On construit l'objet publication
		while ($donnees = $requete->fetch())
		{
			array_push($typePublications, $this->constructTypePublication($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $typePublications;
	}

	
	public function putPublicationsInTypePublication($typePublication){

		if($typePublication instanceof TypePublication){
	
			$requete = $this->dao->prepare("SELECT * FROM publication WHERE id_type_publication = :idTypePublication");
		
			//bind des parametre
			$requete->bindValue(':idTypePublication', $typePublication->getIdTypePublication(), \PDO::PARAM_INT);
		
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			//creation d'un tableau d'publication
			$pdoPublication = new PDOPublicationManager($this->dao);
			
			//On construit l'objet publication
			while ($donnees = $requete->fetch())
			{
				$typePublication->addPublication($pdoPublication->getPublicationById($donnees['id_publication']));
			}
		
			//On libère la requete
			$requete->closeCursor();
		
			return $typePublication;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::TypePublication : L\'objet passé en paramètre n\'est pas une instance de TypePublication');
		}
	}
	
	//retourne le nombre de typePublication dans la base
	public function getNumberOfTypePublication(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreTypePublication FROM type_publication');
		
		//execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//creation d'un tableau d'une publication
		
		$donnees = $requete->fetch();
		
		//On libère la requete
		$requete->closeCursor();
		
		return $donnees['nombreTypePublication'];
	}
	
	//permet de contruire un objet catégorie a partir des ses données de la base.
	protected function constructTypePublication($donnee){
		
		$data = [
		'idTypePublication' => $donnee['id_type_publication'],
		'NomTypePublication' => $donnee['nom_type_publication']
		];
		return new TypePublication($data);
	}
}
