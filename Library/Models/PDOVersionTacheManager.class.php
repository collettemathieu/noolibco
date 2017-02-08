<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des VersionTaches.					  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des VersionTaches
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\VersionTache;
use \Library\Models;

class PDOVersionTacheManager extends \Library\Models\VersionTacheManager
{

/* Définition des méthode de classe */

	//ajoute une versionTache dans la base
	public function addVersionTache($versionTache){

		if($versionTache instanceof VersionTache){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO version_tache VALUES (:idTache, :idVersion)");

			//bind des valeurs
			$requete->bindValue(':idVersion', $versionTache->getVersion()->getIdVersion(), \PDO::PARAM_INT);
			$requete->bindValue(':idTache', $versionTache->getTache()->getIdTache(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::VersionTache : L\'objet passé en paramètre n\'est pas une instance de VersionTache');
		}
	}

	//supprime la versionTache de la base et modifie les données de toutes les versions avec cette versionTache.
	public function deleteVersionTache($versionTache){

		if($versionTache instanceof VersionTache){	

			$requete = $this->dao->prepare("DELETE FROM version_tache WHERE id_tache = :idTache and id_version = :idVersion;");

			//bind des valeurs
			$requete->bindValue(':idVersion', $versionTache->getVersion()->getIdVersion(), \PDO::PARAM_INT);
			$requete->bindValue(':idTache', $versionTache->getTache()->getIdTache(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::VersionTache : L\'objet passé en paramètre n\'est pas une instance de VersionTache');
		}
	}

	
	//selectionne une versionTache par son ID
	public function getVersionTacheById($idVersion, $idTache){
		
		$requete = $this->dao->prepare("SELECT * FROM version_tache  WHERE id_tache = :idTache and id_version = :idVersion");
		
		//bind des parametre
		$requete->bindValue(':idVersion', $idVersion, \PDO::PARAM_INT);
		$requete->bindValue(':idTache', $idTache, \PDO::PARAM_INT);
		
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
			$versionTache = $this->constructVersionTache($donnees[0]);
			return $versionTache;
		}		
	}
	
	//renvoi un tableau de toutes les versionTaches
	public function getAllVersionTaches(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM version_tache");
		
		//execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//creation d'un tableau d'version
		$versionTaches = array();
		
		//On construit l'objet version
		while ($donnees = $requete->fetch())
		{
			array_push($versionTaches, $this->constructVersionTache($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $versionTaches;
	}
	
	//renvoi un tableau de versionTache a partir de l'index début jusqu'a debut + quantite
	public function getVersionTachesBetweenIndex($debut, $quantite){

		$requete = $this->dao->prepare("SELECT * FROM version_tache LIMIT :debut,:quantite");
		
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
		//creation d'un tableau d'version
		$versionTaches = array();
		
		//On construit l'objet version
		while ($donnees = $requete->fetch())
		{
			array_push($versionTaches, $this->constructVersionTache($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $versionTaches;
	}
	
	//retourne le nombre de versionTache dans la base
	public function getNumberOfVersionTache(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreVersionTache FROM version_tache');
		
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
		
		return $donnees['nombreVersionTache'];
	}
	
	//permet de contruire un objet versionTache a partir des ses données de la base.
	protected function constructVersionTache($donnee){
		
		$pdoTache = new PDOTacheManager($this->dao);
		$pdoVersion = new PDOVersionManager($this->dao);
		$data = [
		'version' => $pdoVersion->getVersionById($donnee['id_version'])  ,
		'tache' => $pdoTache->getTacheById($donnee['id_tache'])		
		];

		return new VersionTache($data);
	}
}
