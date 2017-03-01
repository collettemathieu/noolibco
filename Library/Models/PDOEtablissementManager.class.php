<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des Etablissements.					  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des Etablissements
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\Etablissement;
use \Library\Models;
use Library\Entities\Laboratoire;

class PDOEtablissementManager extends \Library\Models\EtablissementManager
{

/* Définition des méthode de classe */

	//ajoute une etablissement dans la base
	public function addEtablissement($etablissement){

		 if($etablissement instanceof Etablissement){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO etablissement (nom_etablissement, adresse_etablissement, id_ville) 
					VALUES (:nomEtablissement, :adresseEtablissement, :idVille)");

			//bind des valeurs
			$requete->bindValue(':nomEtablissement', $etablissement->getNomEtablissement(), \PDO::PARAM_STR);
			$requete->bindValue(':adresseEtablissement', $etablissement->getAdresseEtablissement(), \PDO::PARAM_STR);
			$requete->bindValue(':idVille', $etablissement->getVille()->getIdVille(), \PDO::PARAM_INT);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$etablissement->setIdEtablissement($this->dao->lastInsertId('id_etablissement'));
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
		$messageClient->addErreur('PDO::Etablissement : L\'objet passé en paramètre n\'est pas une instance de Etablissement');
		}
	}
	
	public function addLaboratoiresFromEtablissement($etablissement){

		 if($etablissement instanceof Etablissement){
			if (sizeof($etablissement->getLaboratoires()) != 0)
			{
				foreach ($etablissement->getLaboratoires() as $laboratoire){
					
					//pr�paration de la requete
					$requete = $this->dao->prepare("UPDATE laboratoire SET id_etablissement = :idEtablissement WHERE id_laboratoire = :idLaboratoire;");
			
					//bind des valeurs
					$requete->bindValue(':idEtablissement', $etablissement->getIdEtablissement(), \PDO::PARAM_INT);
					$requete->bindValue(':idLaboratoire', $laboratoire->getIdLaboratoire(), \PDO::PARAM_INT);
					
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
		$messageClient->addErreur('PDO::Etablissement : L\'objet passé en paramètre n\'est pas une instance de Etablissement');
		}
	}
	//sauvegarde les modifications d'une utilisateur
	public function saveEtablissement($etablissement){

		 if($etablissement instanceof Etablissement){
	
			//préparation de la requete
			$requete = $this->dao->prepare("UPDATE etablissement SET 
					nom_etablissement = :nomEtablissement, 
					adresse_etablissement = :adresseEtablissement, 
					id_ville = :idVille 
					WHERE id_etablissement = :idEtablissement;");

			//bind des valeurs
			$requete->bindValue(':idEtablissement', $etablissement->getIdEtablissement(), \PDO::PARAM_INT);
			$requete->bindValue(':nomEtablissement', $etablissement->getNomEtablissement(), \PDO::PARAM_STR);
			$requete->bindValue(':adresseEtablissement', $etablissement->getAdresseEtablissement(), \PDO::PARAM_STR);
			$requete->bindValue(':idVille', $etablissement->getVille()->getIdVille(), \PDO::PARAM_INT);
			
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
		$messageClient->addErreur('PDO::Etablissement : L\'objet passé en paramètre n\'est pas une instance de Etablissement');
		}
	}
	
	public function deleteLinkbetweenLaboratoiresEtablissement($etablissement){

		 if($etablissement instanceof Etablissement){
			if (sizeof($etablissement->getApplications()) != 0){
			
				foreach ($etablissement->getLaboratoires() as $laboratoire){
			
					//préparation de la requete
					$requete = $this->dao->prepare("UPDATE laboratoire SET id_etablissement = null WHERE id_laboratoire = :idLaboratoire;");
					
					//bind des valeurs
					$requete->bindValue(':idLaboratoire', $laboratoire->getIdLaboratoire(), \PDO::PARAM_INT);
			
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
		$messageClient->addErreur('PDO::Etablissement : L\'objet passé en paramètre n\'est pas une instance de Etablissement');
		}
	}
	
	//supprime la etablissement de la base et modifie les données de toutes les utilisateurs avec cette etablissement.
	public function deleteEtablissement($etablissement){

		 if($etablissement instanceof Etablissement){	
			
			$pdoLaboratoire = new PDOLaboratoireManager($this->dao);
			
			$this->putLaboratoiresInEtablissement($etablissement);
			foreach($etablissement->getLaboratoires() as $laboratoire)
			{
				$pdoLaboratoire->deleteLaboratoire($laboratoire);
			}
			
			$requete = $this->dao->prepare("DELETE FROM etablissement WHERE id_etablissement = :idEtablissement");

			//bind des valeurs
			$requete->bindValue(':idEtablissement', $etablissement->getIdEtablissement(), \PDO::PARAM_INT);

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
		$messageClient->addErreur('PDO::Etablissement : L\'objet passé en paramètre n\'est pas une instance de Etablissement');
		}
	}
	
	//selectionne une etablissement par son ID
	public function getEtablissementById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM etablissement WHERE id_etablissement = :idEtablissement");
		
		//bind des parametre
		$requete->bindValue(':idEtablissement', $id, \PDO::PARAM_INT);
		
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
			$etablissement = $this->constructEtablissement($donnees[0]);
			return $etablissement;
		}
	}
	
	//selectionne un etablissement par son nom
	public function getEtablissementByNom($nom){
		
		$requete = $this->dao->prepare("SELECT * FROM etablissement WHERE nom_etablissement = :nomEtablissement");
		
		//bind des parametre
		$requete->bindValue(':nomEtablissement', $nom, \PDO::PARAM_STR);
		
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
			$etablissement = $this->constructEtablissement($donnees[0]);
			return $etablissement;
		}
	}
	
	//renvoi un tableau de toutes les etablissements
	public function getAllEtablissements(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM etablissement ORDER BY nom_etablissement ASC");
		
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
		$etablissements = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($etablissements, $this->constructEtablissement($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $etablissements;
	}
	
	//renvoi un tableau de etablissement a partir de l'index début jusqu'a debut + quantite
	public function getEtablissementsBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM etablissement LIMIT :debut,:quantite");
		
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
		$etablissements = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($etablissements, $this->constructEtablissement($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $etablissements;
	}
	
	//retourne le nombre de etablissement dans la base
	public function getNumberOfEtablissement(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreEtablissement FROM etablissement');
		
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
		
		return $donnees['nombreEtablissement'];
	}
	
	public function putLaboratoiresInEtablissement($etablissement){

		 if($etablissement instanceof Etablissement){
		
			$requete = $this->dao->prepare("SELECT id_laboratoire FROM laboratoire WHERE id_etablissement = :idEtablissement");
			
			//bind des parametre
			$requete->bindValue(':idEtablissement', $etablissement->getIdEtablissement(), \PDO::PARAM_INT);
			
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
			$pdoLaboratoire = new PDOLaboratoireManager($this->dao);
			
			//On construit l'objet application
			while ($donnees = $requete->fetch())
			{
				$etablissement->addLaboratoire($pdoLaboratoire->getLaboratoireById($donnees['id_laboratoire']));
			}
			
			//On lib�re la requete
			$requete->closeCursor();
			
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Etablissement : L\'objet passé en paramètre n\'est pas une instance de Etablissement');
		}
	}
	
	//permet de contruire un objet etablissement a partir des ses données de la base.
	protected function constructEtablissement($donnee){
		
		$pdoVille = new PDOVilleManager ($this->dao);
		
		$data = [
		'IdEtablissement' => $donnee['id_etablissement'],
		'NomEtablissement' => $donnee['nom_etablissement'],
		'AdresseEtablissement' => $donnee['adresse_etablissement'], 
		'Ville' => $pdoVille->getVilleById($donnee['id_ville']) 
		];
		return new Etablissement($data);
	}
}
