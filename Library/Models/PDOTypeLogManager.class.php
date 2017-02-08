<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des typeLogs.					  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des typeLogs
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\TypeLog;
use \Library\Models;

class PDOTypeLogManager extends \Library\Models\TypeLogManager
{

/* Définitions des méthodes action de la classe */

	//ajoute une catégorie dans la base
	public function addTypeLog($typeLog){

		if($typeLog instanceof TypeLog){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO type_log (nom_type_log) 
					VALUES (:nomTypeLog)");

			//bind des valeurs
			$requete->bindValue(':nomTypeLog', $typeLog->getNomTypeLog(), \PDO::PARAM_STR);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$typeLog->setIdTypeLog($this->dao->lastInsertId('id_type_log'));
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
		$messageClient->addErreur('PDO::TypeLog : L\'objet passé en paramètre n\'est pas une instance de TypeLog');
		}
	}
	
	public function addLogsFromTypeLog($typeLog){

		if($typeLog instanceof TypeLog){
		
			if (sizeof($typeLog->getLogs()) != 0){
					
				foreach ($typeLog->getLogs() as $log){
						
					//pr�paration de la requete
					$requete = $this->dao->prepare("UPDATE log SET id_type_log = :idTypeLog WHERE id_log = :idLog)");
			
					//bind des valeurs
					$requete->bindValue(':idTypeLog', $typeLog->getIdTypeLog(), \PDO::PARAM_INT);
					$requete->bindValue(':idLog', $log->getIdLog(), \PDO::PARAM_INT);
			
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
		$messageClient->addErreur('PDO::TypeLog : L\'objet passé en paramètre n\'est pas une instance de TypeLog');
		}
	}
	
	
	//sauvegarde les modifications d'une log
	public function saveTypeLog($typeLog){

		if($typeLog instanceof TypeLog){
		
			//préparation de la requete
			$requete = $this->dao->prepare("UPDATE type_log (nom_type_log) SET
					nom_type_log = :nomTypeLog,
					WHERE id_type_log = :idTypeLog;");

			//bind des valeurs
			$requete->bindValue(':idTypeLog', $typeLog->getIdTypeLog(), \PDO::PARAM_INT);
			$requete->bindValue(':nomTypeLog', $typeLog->getNomTypeLog(), \PDO::PARAM_STR);

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
		$messageClient->addErreur('PDO::TypeLog : L\'objet passé en paramètre n\'est pas une instance de TypeLog');
		}
	}
	
	public function deleteLinkbetweenLogsTypeLog($typeLog){

		if($typeLog instanceof TypeLog){
	
			if (sizeof($typeLog->getLogs()) != 0){
		
				foreach ($typeLog->getLogs() as $log){
						
					//pr�paration de la requete
					$requete = $this->dao->prepare("UPDATE log SET id_type_log = null WHERE id_log = :idLog)");
		
					//bind des valeurs
					$requete->bindValue(':idLog', $log->getIdLog(), \PDO::PARAM_INT);
		
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
		$messageClient->addErreur('PDO::TypeLog : L\'objet passé en paramètre n\'est pas une instance de TypeLog');
		}
	}
	
	//supprime la catégorie de la base et modifie les données de toutes les logs avec cette catégorie.
	public function deleteTypeLog($typeLog){

		if($typeLog instanceof TypeLog){	

			//suppression de l'id dans la table log
			$requete = $this->dao->prepare("UPDATE log
					SET id_type_log = NULL
					WHERE id_type_log = :idTypeLog");
			//bind des valeurs
			$requete->bindValue(':idTypeLog', $typeLog->getIdTypeLog(), \PDO::PARAM_INT);
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}
			
			//suppression du typeLog
			//préparation de la requete
			$requete = $this->dao->prepare("DELETE FROM type_log WHERE id_type_log = :idTypeLog)");

			//bind des valeurs
			$requete->bindValue(':idTypeLog', $typeLog->getIdTypeLog(), \PDO::PARAM_INT);

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
		$messageClient->addErreur('PDO::TypeLog : L\'objet passé en paramètre n\'est pas une instance de TypeLog');
		}
	}
	
	
	//selectionne une catégorie par son ID
	public function getTypeLogById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM type_log WHERE id_type_log = :idTypeLog");
		
		//bind des parametre
		$requete->bindValue(':idTypeLog', $id, \PDO::PARAM_INT);
		
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
			$typeLog = $this->constructTypeLog($donnees[0]);
			return $typeLog;
		}
	}
	
	//renvoi un tableau de toutes les catégories
	public function getAllTypeLogs(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM type_log");
		
		//execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//creation d'un tableau d'log
		$typeLogs = array();
		
		//On construit l'objet log
		while ($donnees = $requete->fetch())
		{
			array_push($typeLogs, $this->constructTypeLog($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $typeLogs;
	}
	
	//renvoi un tableau de catégorie a partir de l'index début jusqu'a debut + quantite
	public function getTypeLogsBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM type_log LIMIT :debut,:quantite");
		
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
		//creation d'un tableau d'log
		$typeLogs = array();
		
		//On construit l'objet log
		while ($donnees = $requete->fetch())
		{
			array_push($typeLogs, $this->constructTypeLog($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $typeLogs;
	}
	
	public function putLogsInTypeLog($typeLog){

		if($typeLog instanceof TypeLog){
	
			$requete = $this->dao->prepare("SELECT * FROM log WHERE id_type_log = :idTypeLog");
		
			//bind des parametre
			$requete->bindValue(':idTypeLog', $typeLog->getIdTypeLog(), \PDO::PARAM_INT);
		
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			//creation d'un tableau d'log
			$pdoLog = new PDOLogManager($this->dao);
			
			//On construit l'objet log
			while ($donnees = $requete->fetch())
			{
				$typeLog->addLog($pdoLog->getLogById($donnees['id_log']));
			}
		
			//On libère la requete
			$requete->closeCursor();
		
			return $typeLogs;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::TypeLog : L\'objet passé en paramètre n\'est pas une instance de TypeLog');
		}
	}
	
	//retourne le nombre de typeLog dans la base
	public function getNumberOfTypeLog(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreTypeLog FROM type_log');
		
		//execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//creation d'un tableau d'une log
		
		$donnees = $requete->fetch();
		
		//On libère la requete
		$requete->closeCursor();
		
		return $donnees['nombreTypeLog'];
	}
	
	//permet de contruire un objet catégorie a partir des ses données de la base.
	protected function constructTypeLog($donnee){
		
		$data = [
		'IdTypeLog' => $donnee['id_type_log'],
		'NomTypeLog' => $donnee['nom_type_log'] 
		];
		return new TypeLog($data);
	}
}
