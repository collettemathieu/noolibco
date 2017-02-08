<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des Auteurs.					 	  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des Auteurs
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Auteur;
use \Library\Models;

class PDOAuteurManager extends \Library\Models\AuteurManager{

/* Définition des méthode de classe */

	//ajoute une auteur dans la base
	public function addAuteur($auteur){

		 if($auteur instanceof Auteur){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO auteur (nom_auteur, prenom_auteur, mail_auteur) 
					VALUES (:nomAuteur, :prenomAuteur, :mailAuteur)");

			//bind des valeurs
			$requete->bindValue(':nomAuteur', $auteur->getNomAuteur(), \PDO::PARAM_STR);
			$requete->bindValue(':prenomAuteur', $auteur->getPrenomAuteur(), \PDO::PARAM_STR);
			$requete->bindValue(':mailAuteur', $auteur->getMailAuteur(), \PDO::PARAM_STR);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$auteur->setIdAuteur($this->dao->lastInsertId('id_auteur'));
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
			$messageClient->addErreur('PDO::Auteur : L\'objet passé en paramètre n\'est pas une instance de Auteur');
		}
	}
	
	public function addPublicationsFromAuteur($auteur){

		 if($auteur instanceof Auteur){
			if (sizeof($auteur->getPublications()) != 0){
			
				foreach ($auteur->getPublications() as $publication){
						
					//pr�paration de la requete
					$requete = $this->dao->prepare("INSERT INTO publication_auteur VALUES :idAuteur, :idPublication;");
			
					//bind des valeurs
					$requete->bindValue(':idAuteur', $auteur->getIdAuteur(), \PDO::PARAM_INT);
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
		$messageClient->addErreur('PDO::Auteur : L\'objet passé en paramètre n\'est pas une instance de Auteur');
		}
	}
	
	//sauvegarde les modifications d'une publication
	public function saveAuteur($auteur){

		 if($auteur instanceof Auteur){
	
			//préparation de la requete
			$requete = $this->dao->prepare("UPDATE auteur (nom_auteur, prenom_auteur, mail_auteur) SET
					nom_auteur = :nomAuteur,
					prenom_auteur = :prenom_auteur,
					mail_auteur = :mailAuteur
					WHERE id_auteur = :idAuteur;");

			//bind des valeurs
			$requete->bindValue(':idAuteur', $auteur->getIdAuteur(), \PDO::PARAM_INT);
			$requete->bindValue(':nomAuteur', $auteur->getNomAuteur(), \PDO::PARAM_STR);
			$requete->bindValue(':prenomAuteur', $auteur->getPrenomAuteur(), \PDO::PARAM_STR);
			$requete->bindValue(':mailAuteur', $auteur->getMailAuteur(), \PDO::PARAM_STR);

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
		$messageClient->addErreur('PDO::Auteur : L\'objet passé en paramètre n\'est pas une instance de Auteur');
		}
	}

	// Suppression du lien entre l'auteur et ses publications
	public function deleteLinkbetweenPublicationsAuteur($auteur){

		 if($auteur instanceof Auteur){
			// Préparation de la requete
			$requete = $this->dao->prepare("DELETE FROM publication_auteur WHERE id_auteur = :idAuteur");

			// Bind des paramètres
			$requete->bindValue(':idAuteur', $auteur->getIdAuteur(), \PDO::PARAM_INT);

			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			// On libère la requete
			$requete->closeCursor();
			
			return true;
			
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Auteur : L\'objet passé en paramètre n\'est pas une instance de Auteur');
		}
	}
	
	// Supprime l'auteur d'une publication
	public function deleteAuteur($auteur){

		 if($auteur instanceof Auteur){	

			// Préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM auteur WHERE id_auteur = :idAuteur");

			// Bind des paramètres
			$requete->bindValue(':idAuteur', $auteur->getIdAuteur(), \PDO::PARAM_INT);

			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			// On libère la requete
			$requete->closeCursor();
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Auteur : L\'objet passé en paramètre n\'est pas une instance de Auteur');
		}
	}
	
	// Sélectionne une auteur par son ID
	public function getAuteurById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM auteur WHERE id_auteur = :idAuteur");
		
		//bind des parametre
		$requete->bindValue(':idAuteur', $id, \PDO::PARAM_INT);
		
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
			$auteur = $this->constructAuteur($donnees[0]);
			return $auteur;
		}
	}
	
	// Sélectionne une auteur par son ID
	public function getAuteurByMail($mail){
		
		$requete = $this->dao->prepare("SELECT * FROM auteur WHERE mail_auteur = :mailAuteur");
		
		//bind des parametre
		$requete->bindValue(':mailAuteur', $mail, \PDO::PARAM_INT);
		
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
			$auteur = $this->constructAuteur($donnees[0]);
			return $auteur;
		}
	}

	// Retourne s'il existe l'auteur via son nom et prénom
	public function getAuteurByNameAndSurname($nomAuteur, $prenomAuteur){
		$requete = $this->dao->prepare("SELECT * FROM auteur WHERE UPPER(nom_auteur) = :nomAuteur AND UPPER(prenom_auteur) = :prenomAuteur");
		
		//bind des paramètres
		$requete->bindValue(':nomAuteur', strtoupper($nomAuteur), \PDO::PARAM_STR);
		$requete->bindValue(':prenomAuteur', strtoupper($prenomAuteur), \PDO::PARAM_STR);
		
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
			$auteur = $this->constructAuteur($donnees[0]);
			return $auteur;
		}
	}


	//renvoi un tableau de toutes les auteurs
	public function getAllAuteurs(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM auteur");
		
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
		$auteurs = array();
		
		//On construit l'objet publication
		while ($donnees = $requete->fetch())
		{
			array_push($auteurs, $this->constructAuteur($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $auteurs;
	}
	
	//renvoi un tableau de auteur a partir de l'index début jusqu'a debut + quantite
	public function getAuteursBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM auteur LIMIT :debut,:quantite");
		
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
		$auteurs = array();
		
		//On construit l'objet publication
		while ($donnees = $requete->fetch())
		{
			array_push($auteurs, $this->constructAuteur($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $auteurs;
	}
	
	public function putPublicationsInAuteur($auteur){

		 if($auteur instanceof Auteur){
	
			$requete = $this->dao->prepare("SELECT * FROM publication WHERE id_auteur = :idAuteur");
		
			//bind des parametre
			$requete->bindValue(':idAuteur', $auteur->getIdAuteur(), \PDO::PARAM_INT);
		
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
				$auteur->addPublication($pdoPublication->getPublicationById($donnees['id_publication']));
			}
		
			//On libère la requete
			$requete->closeCursor();
		
			return $auteurs;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Auteur : L\'objet passé en paramètre n\'est pas une instance de Auteur');
		}
	}
	
	//retourne le nombre de auteur dans la base
	public function getNumberOfAuteur(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreAuteur FROM auteur');
		
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
		
		return $donnees['nombreAuteur'];
	}
	
	//permet de contruire un objet auteur a partir des ses données de la base.
	protected function constructAuteur($donnee){
		
		$data =[
		'IdAuteur' => $donnee['id_auteur'],
		'NomAuteur' => $donnee['nom_auteur'],
		'PrenomAuteur' => $donnee['prenom_auteur'],
		'mailAuteur' => $donnee['mail_auteur'],
		];
		return new Auteur($data);
	}
}
