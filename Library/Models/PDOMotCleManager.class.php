<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des cat�gories.						  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des cat�gories
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\MotCle;
use \Library\Models;

class PDOMotCleManager extends \Library\Models\MotCleManager
{

/* D�finitions des m�thodes action de la classe */

	//ajoute une cat�gorie dans la base
	public function addMotCle($motCle){

		 if($motCle instanceof MotCle){
		
			//pr�paration de la requete
			$requete = $this->dao->prepare("INSERT INTO mot_cle (nom_mot_cle) 
					VALUES (:nomMotCle)");

			//bind des valeurs
			$requete->bindValue(':nomMotCle', $motCle->getNomMotCle(), \PDO::PARAM_STR);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$motCle->setIdMotCle($this->dao->lastInsertId('id_mot_cle'));
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
		$messageClient->addErreur('PDO::MotCle : L\'objet passé en paramètre n\'est pas une instance de MotCle');
		}
	}
	
	public function addApplicationsFromMotCle($motCle){

		 if($motCle instanceof MotCle){
			if (sizeof($motCle->getApplications()) != 0){
			
				foreach ($motCle->getApplications() as $application){
						
					//pr�paration de la requete
					$requete = $this->dao->prepare("INSERT INTO application_mot_cle VALUES (:idMotCle, :idApplication) WHERE NOT EXISTS 
							(SELECT 0 FROM application_mot_cle WHERE id_mot_cle = :idMotCle and id_application = :idApplication );");
			
					//bind des valeurs
					$requete->bindValue(':idMotCle', $motCle->GetIdMotCle(), \PDO::PARAM_INT);
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
		$messageClient->addErreur('PDO::MotCle : L\'objet passé en paramètre n\'est pas une instance de MotCle');
		}
	}
	//sauvegarde les modifications d'une application
	public function saveMotCle($motCle){

		 if($motCle instanceof MotCle){
	
			//pr�paration de la requete
			$requete = $this->dao->prepare("UPDATE mot_cle (nom_mot_cle) SET
					nom_mot_cle = :nomMotCle,
					WHERE id_mot_cle = :idMotCle;");

			//bind des valeurs
			$requete->bindValue(':idMotCle', $motCle->getIdMotCle(), \PDO::PARAM_INT);
			$requete->bindValue(':nomMotCle', $motCle->getNomMotCle(), \PDO::PARAM_STR);

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
		$messageClient->addErreur('PDO::MotCle : L\'objet passé en paramètre n\'est pas une instance de MotCle');
		}
	}
	
	public function deleteLinkbetweenApplicationsMotCle($motCle){

		 if($motCle instanceof MotCle){
			//pr�paration de la requete
			$requete = $this->dao->prepare("DELETE FROM application_mot_cle WHERE id_mot_cle = :idMotCle;");

			//bind des valeurs
			$requete->bindValue(':idMotCle', $motCle->GetIdMotCle(), \PDO::PARAM_INT);

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
		$messageClient->addErreur('PDO::MotCle : L\'objet passé en paramètre n\'est pas une instance de MotCle');
		}
	}
	
	//supprime la cat�gorie de la base et modifie les donn�es de toutes les applications avec cette cat�gorie.
	public function deleteMotCle($motCle){

		 if($motCle instanceof MotCle){	

			//suppression de l'id dans la table application
			$requete = $this->dao->prepare("DELETE FROM application_mot_cle WHERE id_mot_cle = :idMotCle");
			//bind des valeurs
			$requete->bindValue(':idMotCle', $motCle->getIdMotCle(), \PDO::PARAM_INT);
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}
			
			//suppression du motCle
			//pr�paration de la requete
			$requete = $this->dao->prepare("DELETE FROM mot_cle WHERE id_mot_cle = :idMotCle)");

			//bind des valeurs
			$requete->bindValue(':idMotCle', $motCle->getIdMotCle(), \PDO::PARAM_INT);

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
		$messageClient->addErreur('PDO::MotCle : L\'objet passé en paramètre n\'est pas une instance de MotCle');
		}
	}
	
	
	//selectionne une cat�gorie par son ID
	public function getMotCleById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM mot_cle WHERE id_mot_cle = :idMotCle");
		
		//bind des parametre
		$requete->bindValue(':idMotCle', $id, \PDO::PARAM_INT);
		
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
			$motCle = $this->constructMotCle($donnees[0]);
			return $motCle;
		}
	}
	public function getMotCleByName($nomMotCle){
	
		$requete = $this->dao->prepare("SELECT * FROM mot_cle WHERE UPPER(nom_mot_cle) = :nomMotCle");
	
		//bind des parametre
		$requete->bindValue(':nomMotCle', strtoupper($nomMotCle), \PDO::PARAM_STR);
	
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
			$motCle = $this->constructMotCle($donnees[0]);
			return $motCle;
		}
	}
	
	//renvoi un tableau de toutes les cat�gories
	public function getAllMotCles(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM mot_cle");
		
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
		$motCles = array();
		
		//On construit l'objet application
		while ($donnees = $requete->fetch())
		{
			array_push($motCles, $this->constructMotCle($donnees));
		}
		
		//On lib�re la requete
		$requete->closeCursor();
		
		return $motCles;
	}
	
	//renvoi un tableau de cat�gorie a partir de l'index d�but jusqu'a debut + quantite
	public function getMotClesBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM mot_cle LIMIT :debut,:quantite");
		
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
		$motCles = array();
		
		//On construit l'objet application
		while ($donnees = $requete->fetch())
		{
			array_push($motCles, $this->constructMotCle($donnees));
		}
		
		//On lib�re la requete
		$requete->closeCursor();
		
		return $motCles;
	}
	
	public function putApplicationsInMotCle($motCle){

		 if($motCle instanceof MotCle){
	
			$requete = $this->dao->prepare("SELECT * FROM application_mot_cle WHERE id_mot_cle = :idMotCle");
		
			//bind des parametre
			$requete->bindValue(':idMotCle', $motCle->getIdMotCle(), \PDO::PARAM_INT);
			
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
				$motCle->addApplication($pdoApplication->getApplicationById($donnees['id_application']));
			}
		
			//On lib�re la requete
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::MotCle : L\'objet passé en paramètre n\'est pas une instance de MotCle');
		}
	}
	
	//retourne le nombre de motCle dans la base
	public function getNumberOfMotCle(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreMotCle FROM mot_cle');
		
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
		
		return $donnees['nombreMotCle'];
	}
	
	//permet de contruire un objet cat�gorie a partir des ses donn�es de la base.
	protected function constructMotCle($donnee){
		
		$data = [
		'IdMotCle' => $donnee['id_mot_cle'],
		'NomMotCle' => $donnee['nom_mot_cle'],
		];
		return new MotCle($data);
	}
}
