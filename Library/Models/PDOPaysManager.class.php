<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des Pays.							  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des Pays
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\Pays;
use \Library\Models;

class PDOPaysManager extends \Library\Models\PaysManager
{

/* Définition des méthode de classe */

	//ajoute une pays dans la base
	public function addPays($pays){

		 if($pays instanceof Pays){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO pays (nom_pays) 
					VALUES (:nomPays)");

			//bind des valeurs
			$requete->bindValue(':nomPays', $pays->getNomPays(), \PDO::PARAM_STR);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$pays->setIdPays($this->dao->lastInsertId('id_pays'));
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
		$messageClient->addErreur('PDO::Pays : L\'objet passé en paramètre n\'est pas une instance de Pays');
		}
	}
	
	public function addVillesFromPays($pays){

		 if($pays instanceof Pays){
			if (sizeof($pays->getVilles()) != 0){
			
				foreach ($pays->getVilles() as $ville){
			
					//pr�paration de la requete
					$requete = $this->dao->prepare("UPDATE ville SET id_pays = :idPays WHERE id_ville = :idVille;");
			
					//bind des valeurs
					$requete->bindValue(':idPays', $pays->getIdPays(), \PDO::PARAM_INT);
					$requete->bindValue(':idVille', $ville->getIdVille(), \PDO::PARAM_INT);
			
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
		$messageClient->addErreur('PDO::Pays : L\'objet passé en paramètre n\'est pas une instance de Pays');
		}
	}
	
	//sauvegarde les modifications d'une utilisateur
	public function savePays($pays){

		 if($pays instanceof Pays){
	
			//préparation de la requete
			$requete = $this->dao->prepare("UPDATE pays SET
					nom_pays = :nomPays 
					WHERE id_pays = :idPays;");

			//bind des valeurs
			$requete->bindValue(':idPays', $pays->getIdPays(), \PDO::PARAM_INT);
			$requete->bindValue(':nomPays', $pays->getNomPays(), \PDO::PARAM_STR);

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
		$messageClient->addErreur('PDO::Pays : L\'objet passé en paramètre n\'est pas une instance de Pays');
		}
	}
	
	public function deleteLinkbetweenVillesPays($pays){

		 if($pays instanceof Pays){
			$requete = $this->dao->prepare("UPDATE ville SET id_pays = null WHERE id_pays = :idPays;");
			
			//bind des valeurs
			$requete->bindValue(':idPays', $pays->getIdPays(), \PDO::PARAM_INT);
			
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
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Pays : L\'objet passé en paramètre n\'est pas une instance de Pays');
		}
	}
	
	//supprime la pays de la base et modifie les données de toutes les utilisateurs avec cette pays.
	public function deletePays($pays){

		 if($pays instanceof Pays){	
		
			$this->deleteLinkbetweenVillesPays($pays);
			
			$requete = $this->dao->prepare("DELETE FROM pays WHERE id_pays = :idPays");

			//bind des valeurs
			$requete->bindValue(':idPays', $pays->getIdPays(), \PDO::PARAM_INT);

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
		$messageClient->addErreur('PDO::Pays : L\'objet passé en paramètre n\'est pas une instance de Pays');
		}
	}
	
	//selectionne une pays par son ID
	public function getPaysById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM pays WHERE id_pays = :idPays");
		
		//bind des parametre
		$requete->bindValue(':idPays', $id, \PDO::PARAM_INT);
		
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
			$pays = $this->constructPays($donnees[0]);
			return $pays;
		}
	}
	
	//selectionne une pays par son nom
	public function getPaysByNom($nom){
		
		$requete = $this->dao->prepare("SELECT * FROM pays WHERE nom_pays = :nomPays");
		
		//bind des parametre
		$requete->bindValue(':nomPays', $nom, \PDO::PARAM_STR);
		
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
			$pays = $this->constructPays($donnees[0]);
			return $pays;
		}
	}
	
	//renvoi un tableau de toutes les pays
	public function getAllPays(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM pays ORDER BY nom_pays");
		
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
		$pays = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($pays, $this->constructPays($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $pays;
	}
	
	//renvoi un tableau de pays a partir de l'index début jusqu'a debut + quantite
	public function getPaysBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM pays LIMIT :debut,:quantite");
		
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
		$pays = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($pays, $this->constructPays($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $pays;
	}
	
	//retourne le nombre de pays dans la base
	public function getNumberOfPays(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombrePays FROM pays');
		
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
		
		return $donnees['nombrePays'];
	}
	
	public function putVillesInPays($pays){

		 if($pays instanceof Pays){
			$requete = $this->dao->prepare("SELECT id_ville FROM ville WHERE id_pays = :idPays ORDER BY nom_ville");
			
			//bind des parametre
			$requete->bindValue(':idPays', $pays->getIdPays(), \PDO::PARAM_INT);
			
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
			$pdoVille = new PDOVilleManager($this->dao);
			
			//On construit l'objet application
			while ($donnees = $requete->fetch())
			{
				$pays->addVille($pdoVille->getVilleById($donnees['id_ville']));
			}
			
			//On lib�re la requete
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Pays : L\'objet passé en paramètre n\'est pas une instance de Pays');
		}
	}
	
	//permet de contruire un objet pays a partir des ses données de la base.
	protected function constructPays($donnee){
		
		$data = [
		'IdPays' => (int) $donnee['id_pays'],
		'NomPays' => $donnee['nom_pays']
		];
		return new Pays($data);
	}
}
