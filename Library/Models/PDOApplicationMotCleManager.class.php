<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des ApplicationMotCles.				  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des ApplicationMotCles
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\ApplicationMotCle;
use \Library\Models;

class PDOApplicationMotCleManager extends \Library\Models\ApplicationMotCleManager
{

/* Définition des méthode de classe */

	//ajoute une applicationMotCle dans la base
	public function addApplicationMotCle($applicationMotCle){

		 if($applicationMotCle instanceof ApplicationMotCle){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO application_mot_cle VALUES (:idMotCle, :idApplication)");

			//bind des valeurs
			$requete->bindValue(':idMotCle', $applicationMotCle->getMotCle()->getIdMotCle(), \PDO::PARAM_INT);
			$requete->bindValue(':idApplication', $applicationMotCle->getApplication()->getIdApplication(), \PDO::PARAM_INT);
			
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
		$messageClient->addErreur('PDO::ApplicationMotCle : L\'objet passé en paramètre n\'est pas une instance de ApplicationMotCle');
		}
	}

	//supprime la applicationMotCle de la base et modifie les données de toutes les utilisateurs avec cette applicationMotCle.
	public function deleteApplicationMotCle($applicationMotCle){

		 if($applicationMotCle instanceof ApplicationMotCle){	

			$requete = $this->dao->prepare("DELETE FROM application_mot_cle WHERE id_application = :idApplication and id_mot_cle = :idMotCle;");

			//bind des valeurs
			$requete->bindValue(':idMotCle', $applicationMotCle->getMotCle()->getIdMotCle(), \PDO::PARAM_INT);
			$requete->bindValue(':idApplication', $applicationMotCle->getApplication()->getIdApplication(), \PDO::PARAM_INT);

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
		$messageClient->addErreur('PDO::ApplicationMotCle : L\'objet passé en paramètre n\'est pas une instance de ApplicationMotCle');
		}
	}
	
	//selectionne une applicationMotCle par son ID
	public function getApplicationMotCleById($idApplication, $idMotCle){
		
		$requete = $this->dao->prepare("SELECT * FROM application_mot_cle  WHERE id_application = :idApplication and id_mot_cle = :idMotCle");
		
		//bind des parametre
		$requete->bindValue(':idMotCle', $idMotCle, \PDO::PARAM_INT);
		$requete->bindValue(':idApplication', $idApplication, \PDO::PARAM_INT);
		
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
			$applicationMotCle = $this->constructApplicationMotCle($donnees[0]);
			return $applicationMotCle;
		}
	}
	
	//renvoi un tableau de toutes les applicationMotCles
	public function getAllApplicationMotCles(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM application_mot_cle");
		
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
		$applicationMotCles = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($applicationMotCles, $this->constructApplicationMotCle($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $applicationMotCles;
	}
	
	//renvoi un tableau de applicationMotCle a partir de l'index début jusqu'a debut + quantite
	public function getApplicationMotClesBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM application_mot_cle LIMIT :debut,:quantite");
		
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
		$applicationMotCles = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($applicationMotCles, $this->constructApplicationMotCle($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $applicationMotCles;
	}
	
	//retourne le nombre de applicationMotCle dans la base
	public function getNumberOfApplicationMotCle(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreApplicationMotCle FROM application_mot_cle');
		
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
		
		return $donnees['nombreApplicationMotCle'];
	}
	
	//permet de contruire un objet applicationMotCle a partir des ses données de la base.
	protected function constructApplicationMotCle($donnee){
		
		$pdoApplication = new PDOApplicationManager($this->dao);
		$pdoMotCle = new PDOMotCleManager($this->dao);
		
		$data = [
		'MotCle' => $pdoMotCle->getMotCleById($donnee['id_mot_cle']),
		'Application' => $pdoApplication->getApplicationById($donnee['id_application'])
		];
		return new ApplicationMotCle($data);
	}
}
