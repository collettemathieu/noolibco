<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des Versions.					  	  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des Versions
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\Version;
use \Library\Models;
use Library\Entities;

class PDOVersionManager extends \Library\Models\VersionManager
{

/* Définition des méthode de classe */

	//Ajoute une version dans la base
	public function addVersion($version){

		if($version instanceof Version){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO version (num_version, active_version, date_publication_version, note_maj_version, id_application) 
					VALUES (:numVersion, :activeVersion, CURDATE(), :noteMajVersion, :idApplication)");

			//bind des valeurs
			$requete->bindValue(':numVersion', $version->getNumVersion(), \PDO::PARAM_STR);
			$requete->bindValue(':activeVersion', $version->getActiveVersion(), \PDO::PARAM_BOOL);
			$requete->bindValue(':noteMajVersion', $version->getNoteMajVersion(), \PDO::PARAM_STR);
			$requete->bindValue(':idApplication', $version->getApplication()->getIdApplication(), \PDO::PARAM_INT);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$version->setIdVersion($this->dao->lastInsertId('id_version'));
				$this->dao->commit();
				
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//On libère la requete
			$requete->closeCursor();
			return $version;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Version : L\'objet passé en paramètre n\'est pas une instance de Version');
		}
	}

	public function addTachesFromVersion($version){

		if($version instanceof Version){
			if (sizeof($version->getTaches()) != 0){
			
				for ($i = 0 ; $i < sizeof($version->getTaches()) ; $i++){
					$tache = $version->getTaches()[i];
					//pr�paration de la requete
					$requete = $this->dao->prepare("INSERT INTO version_tache VALUES :idTache, :idVersion;");
			
					//bind des valeurs
					$requete->bindValue(':idVersion', $version->getIdVersion(), \PDO::PARAM_INT);
					$requete->bindValue(':idTache', $tache->getIdTache(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Version : L\'objet passé en paramètre n\'est pas une instance de Version');
		}
	}
	
	
	
	// Sauvegarde les modifications d'une version d'une application
	public function saveVersion($version){

		if($version instanceof Version){
	
			// Préparation de la requête
			$requete = $this->dao->prepare("UPDATE version 
					SET
					active_version = :activeVersion,
					num_version = :numVersion,
					date_publication_version = CURDATE(),
					note_maj_version = :noteMajVersion,
					id_application = :idApplication
					WHERE id_version = :idVersion");
			//bind des valeurs
			$requete->bindValue(':idVersion', $version->getIdVersion(), \PDO::PARAM_INT);
			$requete->bindValue(':activeVersion', $version->getActiveVersion(), \PDO::PARAM_BOOL);
			$requete->bindValue(':numVersion', $version->getNumVersion(), \PDO::PARAM_STR);
			$requete->bindValue(':noteMajVersion', $version->getNoteMajVersion(), \PDO::PARAM_STR);
			$requete->bindValue(':idApplication', $version->getApplication()->getIdApplication(), \PDO::PARAM_INT);

			//execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				$messageClient = new \Library\MessageClient;
				$messageClient->addErreur('PDO::Version: '.$e->getMessage());
				return false;
			}

			//On libère la requête
			$requete->closeCursor();
			
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Version : L\'objet passé en paramètre n\'est pas une instance de Version');
		}
	}
	
	public function deleteLinkBetweenTachesVersion($version){

		if($version instanceof Version){
		
			// Préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM version_tache WHERE id_version = :idVersion;");
			
			//bind des valeurs
			$requete->bindValue(':idVersion', $version->getIdVersion(), \PDO::PARAM_INT);
			//execution de la requete sinon envoi d'une erreur
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
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Version : L\'objet passé en paramètre n\'est pas une instance de Version');
		}
	}

	// Suppression des tâches en lien avec la version
	public function deleteTaches($version){

		if($version instanceof Version){
			if (sizeof ( $version->getTaches () ) != 0) {
				
				foreach ( $version->getTaches () as $tache ) {
					
					$pdoTache = new PDOTacheManager ( $this->dao );
					$pdoTache->deleteTache ( $tache );
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Version : L\'objet passé en paramètre n\'est pas une instance de Version');
		}
	}

	
	// Supprime la version de la base ainsi que ses liens avec Taches.
	public function deleteVersion($version){

		if($version instanceof Version){	

			// Suppression des liens avec Taches
			$this->deleteLinkBetweenTachesVersion($version);

			// Suppression des tâches en lien avec la version
			$this->deleteTaches($version);

			// Suppression de la version de l'application
			$requete = $this->dao->prepare("DELETE FROM version WHERE id_version = :idVersion");

			//bind des valeurs
			$requete->bindValue(':idVersion', $version->getIdVersion(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Version : L\'objet passé en paramètre n\'est pas une instance de Version');
		}
	}
	
	//selectionne une version par son ID
	public function getVersionById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM version WHERE id_version = :idVersion");
		
		//bind des tache
		$requete->bindValue(':idVersion', $id, \PDO::PARAM_INT);
		
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
			$version = $this->constructVersion($donnees[0]);
			$this->putTachesInVersion($version);
			return  $version;
		}

	}
	
	//renvoi un tableau de toutes les versions
	public function getAllVersions(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM version");
		
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
		$versions = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($versions, $this->constructVersion($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $versions;
	}
	
	//renvoi un tableau de version a partir de l'index début jusqu'a debut + quantite
	public function getVersionsBetweenIndex($debut, $quantite){

		$requete = $this->dao->prepare("SELECT * FROM version LIMIT :debut,:quantite");
		
		//bind des tache
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
		$versions = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($versions, $this->constructVersion($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $versions;
	}
	
	//retourne le nombre de version dans la base
	public function getNumberOfVersion(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreVersion FROM version');
		
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
		
		return $donnees['nombreVersion'];
	}
	
	public function putTachesInVersion($version){

		if($version instanceof Version){
			$requete = $this->dao->prepare("SELECT id_tache FROM version_tache WHERE id_version = :idVersion");
			
			//bind des tache
			$requete->bindValue(':idVersion', $version->getIdVersion(), \PDO::PARAM_INT);
			
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
			$pdoTache = new PDOTacheManager($this->dao);
			
			//On construit l'objet application
			while ($donnees = $requete->fetch())
			{
				$version->addTache($pdoTache->getTacheById($donnees['id_tache']));
			}
			
			//On lib�re la requete
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Version : L\'objet passé en paramètre n\'est pas une instance de Version');
		}
	}
	
	public function putVersionsInVersion($version){

		if($version instanceof Version){
				$requete = $this->dao->prepare("SELECT id_version FROM version_version WHERE id_version = :idVersion ");
			
			//bind des version
			$requete->bindValue(':idVersion', $version->getIdVersion(), \PDO::PARAM_INT);
			
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
			$pdoVersion = new PDOVersionManager($this->dao);
			
			//On construit l'objet application
			while ($donnees = $requete->fetch())
			{
				$version->addVersion($pdoVersion->getVersionById($donnees['id_version']));
			}
			
			//On lib�re la requete
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Version : L\'objet passé en paramètre n\'est pas une instance de Version');
		}
	}

	//permet de contruire un objet version a partir des ses données de la base.
	protected function constructVersion($donnee){
		
		$pdoApplication = new PDOApplicationManager ($this->dao);

		$data = [
		'idVersion' =>$donnee['id_version'],
		'activeVersion' => (bool) $donnee['active_version'],
		'numVersion' =>$donnee['num_version'],
		'datePublicationVersion' =>$donnee['date_publication_version'],
		'noteMajVersion' =>$donnee['note_maj_version'],
		'Application' => $pdoApplication->getApplicationById($donnee['id_application'])
		];
		return new Version($data);
	}
}
