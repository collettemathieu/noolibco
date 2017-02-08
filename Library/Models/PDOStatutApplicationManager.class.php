<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO de statut des applications.			  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO de statut des applications
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\StatutApplication;
use \Library\Models;

class PDOStatutApplicationManager extends \Library\Models\StatutApplicationManager
{

/* Définitions des méthodes action de la classe */

	//ajoute une catègorie dans la base
	public function addStatut($statut){

		if($statut instanceof StatutApplication){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO statut_application (nom_statut, couleur_statut) 
					VALUES (:nomStatut, :couleurStatut)");

			//bind des valeurs
			$requete->bindValue(':nomStatut', $statut->getNomStatut(), \PDO::PARAM_STR);
			$requete->bindValue(':couleurStatut', $statut->getCouleurStatut(), \PDO::PARAM_STR);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$statut->setIdStatut($this->dao->lastInsertId('id_statut'));
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
		$messageClient->addErreur('PDO::StatutApplication : L\'objet passé en paramètre n\'est pas une instance de StatutApplication');
		}
	}
	
	public function addApplicationsFromStatut($statut){

		if($statut instanceof StatutApplication){
			if (sizeof($statut->getApplications()) != 0){
					
				foreach ($statut->getApplications() as $application){
						
					//préparation de la requete
					$requete = $this->dao->prepare("UPDATE application SET id_statut = :idStatut WHERE id_application = :idApplication;");
			
					//bind des valeurs
					$requete->bindValue(':idStatut', $statut->getIdStatut(), \PDO::PARAM_INT);
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
		$messageClient->addErreur('PDO::StatutApplication : L\'objet passé en paramètre n\'est pas une instance de StatutApplication');
		}
	}
	
	//sauvegarde les modifications d'une application
	public function saveStatut($statut){

		if($statut instanceof StatutApplication){
	
			//pr�paration de la requete
			$requete = $this->dao->prepare("UPDATE statut_application SET
					nom_statut = :nomStatut,
					couleur_statut = :couleurStatut
					WHERE id_statut = :idStatut");

			//bind des valeurs
			$requete->bindValue(':idStatut', $statut->getIdStatut(), \PDO::PARAM_INT);
			$requete->bindValue(':nomStatut', $statut->getNomStatut(), \PDO::PARAM_STR);
			$requete->bindValue(':couleurStatut', $statut->getCouleurStatut(), \PDO::PARAM_STR);
			
			
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
		$messageClient->addErreur('PDO::StatutApplication : L\'objet passé en paramètre n\'est pas une instance de StatutApplication');
		}
	}
	
	public function deleteLinkbetweenApplicationsStatut($statut){

		if($statut instanceof StatutApplication){
			//pr�paration de la requete
			$requete = $this->dao->prepare("UPDATE application SET id_statut = null WHERE id_statut = :idStatut;");
			
			//bind des valeurs
			$requete->bindValue(':idStatut', $statut->getIdStatut(), \PDO::PARAM_INT);
			
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
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::StatutApplication : L\'objet passé en paramètre n\'est pas une instance de StatutApplication');
		}
		
	}
	
	//supprime la catégorie de la base et modifie les donn�es de toutes les applications avec cette cat�gorie.
	public function deleteStatut($statut){

		if($statut instanceof StatutApplication){	

			//suppression de l'id dans la table application
			$requete = $this->dao->prepare("UPDATE application
					SET id_statut = NULL
					WHERE id_statut = :idStatut");
			//bind des valeurs
			$requete->bindValue(':idStatut', $statut->getIdStatut(), \PDO::PARAM_INT);
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}
			
			//suppression du statut
			//préparation de la requete
			$requete = $this->dao->prepare("DELETE FROM statut_application WHERE id_statut = :idStatut");

			//bind des valeurs
			$requete->bindValue(':idStatut', $statut->getIdStatut(), \PDO::PARAM_INT);

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
		$messageClient->addErreur('PDO::StatutApplication : L\'objet passé en paramètre n\'est pas une instance de StatutApplication');
		}
	}
	
	
	//selectionne une catégorie par son ID
	public function getStatutById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM statut_application WHERE id_statut = :idStatut");
		
		//bind des parametre
		$requete->bindValue(':idStatut', $id, \PDO::PARAM_INT);
		
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
			$statut = $this->constructStatut($donnees[0]);
			return $statut;
		}
	}

	//selectionne une catégorie par son nom
	public function getStatutByNom($nom){
		
		$requete = $this->dao->prepare("SELECT * FROM statut_application WHERE nom_statut = :nomStatut");
		
		//bind des parametre
		$requete->bindValue(':nomStatut', $nom, \PDO::PARAM_STR);
		
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
			$statut = $this->constructStatut($donnees[0]);
			return $statut;
		}
	}



	public function getStatutDefaut(){
	
		$requete = $this->dao->prepare("SELECT * FROM statut_application WHERE nom_statut = 'Begin'");
	
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
			$statut = $this->constructStatut($donnees[0]);
			return $statut;
		}
	}
	
	//renvoi un tableau de toutes les cat�gories
	public function getAllStatuts(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM statut_application");
		
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
		$statuts = array();
		
		//On construit l'objet application
		while ($donnees = $requete->fetch())
		{
			array_push($statuts, $this->constructStatut($donnees));
		}
		
		//On lib�re la requete
		$requete->closeCursor();
		
		return $statuts;
	}
	
	//renvoi un tableau de cat�gorie a partir de l'index d�but jusqu'a debut + quantite
	public function getStatutsBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM statut_application LIMIT :debut,:quantite");
		
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
		$statuts = array();
		
		//On construit l'objet application
		while ($donnees = $requete->fetch())
		{
			array_push($statuts, $this->constructStatut($donnees));
		}
		
		//On lib�re la requete
		$requete->closeCursor();
		
		return $statuts;
	}
	
	public function putApplicationsInStatut($statut){

		if($statut instanceof StatutApplication){
	
			$requete = $this->dao->prepare("SELECT * FROM application WHERE id_statut = :idStatut");
		
			// Bind des parametre
			$requete->bindValue(':idStatut', $statut->getIdStatut(), \PDO::PARAM_INT);
		
			// Execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			// Création d'un tableau d'application
			$pdoApplication = new PDOApplicationManager($this->dao);
			
			// On construit l'objet application
			while ($donnees = $requete->fetch())
			{
				$statut->addApplication($pdoApplication->getApplicationById($donnees['id_application']));
			}
		
			// On libère la requete
			$requete->closeCursor();
		
			return $statuts;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::StatutApplication : L\'objet passé en paramètre n\'est pas une instance de StatutApplication');
		}
	}
	
	//retourne le nombre de statut dans la base
	public function getNumberOfStatut(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreStatut FROM statut_application');
		
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
		
		//On lib�re la requete
		$requete->closeCursor();
		
		return $donnees['nombreStatut'];
	}
	
	// Permet de contruire un objet statutApplication à partir des ses données de la base.
	protected function constructStatut($donnee){
		
		$data = [
		'IdStatut' => $donnee['id_statut'],
		'NomStatut' => $donnee['nom_statut'],
		'CouleurStatut' => $donnee['couleur_statut']
		];
		return new StatutApplication($data);
	}
}
