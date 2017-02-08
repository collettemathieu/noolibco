<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des Logs.					 		  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des Logs
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\Log;
use \Library\Models;

class PDOLogManager extends \Library\Models\LogManager
{

/* Définition des méthode de classe */

	//ajoute une log dans la base
	public function addLog($log){

		 if($log instanceof Log){
			
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO log (text_log, date_log, id_utilisateur, id_type_log) 
					VALUES (:textLog, :dateLog, :idUtilisateur, :idTypeLog)");

			//bind des valeurs
			$requete->bindValue(':textLog', $log->getTexteLog(), \PDO::PARAM_STR);
			$requete->bindValue(':dateLog', $log->getDateLog(), \PDO::PARAM_STR);
			$requete->bindValue(':idUtilisateur', $log->getUtilisateur()->getIdUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':idTypeLog', $log->getTypeLog()->getIdTypeLog(), \PDO::PARAM_INT);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$log->setIdLog($this->dao->lastInsertId('id_log'));
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
		$messageClient->addErreur('PDO::Log : L\'objet passé en paramètre n\'est pas une instance de Log');
		}
	}
	
	//sauvegarde les modifications d'une utilisateur
	public function saveLog($log){

		 if($log instanceof Log){
	
			//préparation de la requete
			$requete = $this->dao->prepare("UPDATE log SET
					text_log = :textLog,
					date_log = :dateLog,
					id_utilisateur = :idUtilisateur,
					id_type_log = :idTypeLog,
					WHERE id_log = :idLog;");

			//bind des valeurs
			$requete->bindValue(':idLog', $log->getIdLog(), \PDO::PARAM_INT);
				$requete->bindValue(':textLog', $log->getTexteLog(), \PDO::PARAM_STR);
			$requete->bindValue(':dateLog', $log->getDateLog(), \PDO::PARAM_STR);
			$requete->bindValue(':idUtilisateur', $log->getUtilisateur()->getIdUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':idTypeLog', $log->getTypeLog()->getIdTypeLog(), \PDO::PARAM_INT);

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
		$messageClient->addErreur('PDO::Log : L\'objet passé en paramètre n\'est pas une instance de Log');
		}
	}
	
	//supprime la log de la base et modifie les données de toutes les utilisateurs avec cette log.
	public function deleteLog($log){

		 if($log instanceof Log){	

			$requete = $this->dao->prepare("DELETE FROM log WHERE id_log = :idLog)");

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

			//On libère la requete
			$requete->closeCursor();
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Log : L\'objet passé en paramètre n\'est pas une instance de Log');
		}
	}
	
	//selectionne une log par son ID
	public function getLogById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM log WHERE id_log = :idLog");
		
		//bind des parametre
		$requete->bindValue(':idLog', $id, \PDO::PARAM_INT);
		
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
			$log = $this->constructLog($donnees[0]);
			return $log;
		}
	}
	
	//renvoi un tableau de toutes les logs
	public function getAllLogs(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM log");
		
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
		$logs = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($logs, $this->constructLog($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $logs;
	}
	
	//renvoi un tableau de log a partir de l'index début jusqu'a debut + quantite
	public function getLogsBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM log LIMIT :debut,:quantite");
		
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
		$logs = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($logs, $this->constructLog($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $logs;
	}
	
	//retourne le nombre de log dans la base
	public function getNumberOfLog(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreLog FROM log');
		
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
		
		return $donnees['nombreLog'];
	}
	
	//permet de contruire un objet log a partir des ses données de la base.
	protected function constructLog($donnee){
		
		$pdoUtilisateur = new PDOUtilisateurManager ($this->dao);
		$pdoTypeLog = new PDOTypeLogManager ($this->dao);
		
		$data = [
		'IdLog' => $donnee['id_log'],
		'TexteLog' => $donnee['text_log'],
		'DateLog' => $donnee['date_log'],
		'Utilisateur' => $pdoUtilisateur->getUtilisateurById($donnee['id_utilisateur']),
		'TypeLog' => $pdoTypeLog->getTypeLogById($donnee['id_type_log'])
		
		];
		return new Log($data);
	}
}
