<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des Taches.							  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des Taches
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\Tache;
use \Library\Models;
use Library\Entities\Fonction;

class PDOTacheManager extends \Library\Models\TacheManager{

/* Définition des méthode de classe */

	// Ajoute une tache dans la base
	public function addTache($tache){

		if($tache instanceof Tache){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO tache (nom_tache, description_tache) 
					VALUES (:nomTache, :descriptionTache)");

			//bind des valeurs
			$requete->bindValue(':nomTache', $tache->getNomTache(), \PDO::PARAM_STR);
			$requete->bindValue(':descriptionTache', $tache->getDescriptionTache(), \PDO::PARAM_STR);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$tache->setIdTache($this->dao->lastInsertId('id_tache'));
				$this->dao->commit();
				
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//On libère la requete
			$requete->closeCursor();
			return $tache;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Tache : L\'objet passé en paramètre n\'est pas une instance de Tache');
		}
	}
	
	public function addFonctionsFromTache($tache){

		if($tache instanceof Tache){
			if (sizeof($tache->getFonctions()) != 0){
			
				for ($i = 0 ; $i < sizeof($tache->getFonctions()) ; $i++){
					$fonction = $tache->getFonctions()[$i];
					// Préparation de la requete
					$requete = $this->dao->prepare("INSERT INTO tache_fonction VALUES :idFonction, :idTache, :i;");
			
					//bind des valeurs
					$requete->bindValue(':idTache', $tache->getIdTache(), \PDO::PARAM_INT);
					$requete->bindValue(':idFonction', $fonction->getIdFonction(), \PDO::PARAM_INT);
					$requete->bindValue(':i', $i, \PDO::PARAM_INT);
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
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Tache : L\'objet passé en paramètre n\'est pas une instance de Tache');
		}
	}
	
	public function addVersionsFromTache($tache){

		if($tache instanceof Tache){
		
			if (sizeof($tache->getVersions()) != 0){
			
				foreach ($tache->getVersions() as $version){
					// Préparation de la requete
					$requete = $this->dao->prepare("INSERT INTO version_tache VALUES :idTache, :idVersion;");
			
					//bind des valeurs
					$requete->bindValue(':idTache', $tache->getIdTache(), \PDO::PARAM_INT);
					$requete->bindValue(':idVersion', $version->getIdVersion, \PDO::PARAM_INT);
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
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Tache : L\'objet passé en paramètre n\'est pas une instance de Tache');
		}
	}
	
	//sauvegarde les modifications d'une utilisateur
	public function saveTache($tache){

		if($tache instanceof Tache){
	
			//préparation de la requete
			$requete = $this->dao->prepare("UPDATE tache SET
					nom_tache = :nomTache,
					description_tache = :descriptionTache
					WHERE id_tache = :idTache;");
		
			//bind des valeurs
			$requete->bindValue(':idTache', $tache->getIdTache(), \PDO::PARAM_INT);
			$requete->bindValue(':nomTache', $tache->getNomTache(), \PDO::PARAM_STR);
			$requete->bindValue(':descriptionTache', $tache->getDescriptionTache(), \PDO::PARAM_STR);

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
		$messageClient->addErreur('PDO::Tache : L\'objet passé en paramètre n\'est pas une instance de Tache');
		}
	}
	
	public function deleteLinkBetweenFonctionsTache($tache){

		if($tache instanceof Tache){
		
			// Préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM tache_fonction WHERE id_tache = :idTache;");
			
			// Bind des paramètres
			$requete->bindValue(':idTache', $tache->getIdTache(), \PDO::PARAM_INT);
			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}
			
			// On libère la requête
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Tache : L\'objet passé en paramètre n\'est pas une instance de Tache');
		}
	}
	
	public function deleteLinkbetweenVersionsTache($tache){

		if($tache instanceof Tache){
		
			$requete = $this->dao->prepare("DELETE FROM version_tache WHERE id_tache = :idTache;");
			
			// Bind des paramètres
			$requete->bindValue(':idTache', $tache->getIdTache(), \PDO::PARAM_INT);
			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}
			
			// On libère la requête
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Tache : L\'objet passé en paramètre n\'est pas une instance de Tache');
		}
	}

	public function deleteLinkbetweenTacheTypeDonneeUtilisateur($tache){

		if($tache instanceof Tache){
		
			$requete = $this->dao->prepare("DELETE FROM tache_type_donnee_utilisateur WHERE id_tache = :idTache;");
			
			// Bind des paramètres
			$requete->bindValue(':idTache', $tache->getIdTache(), \PDO::PARAM_INT);
			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}
			
			// On libère la requête
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Tache : L\'objet passé en paramètre n\'est pas une instance de Tache');
		}
	}

	// Suppression des fonctions en lien avec la tâche
	public function deleteFonctions($tache){

		if($tache instanceof Tache){
			if (sizeof ( $tache->getFonctions () ) != 0) {
				
				foreach ( $tache->getFonctions () as $fonction ) {
					
					$pdoFonction = new PDOFonctionManager ( $this->dao );
					$pdoFonction->deleteFonction( $fonction );
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Tache : L\'objet passé en paramètre n\'est pas une instance de Tache');
		}
	}

	
	//supprime la tache de la base et modifie les données de toutes les utilisateurs avec cette tache.
	public function deleteTache($tache){

		if($tache instanceof Tache){	

			// Suppression des liens avec Fonctions
			$this->deleteLinkBetweenFonctionsTache($tache);

			// Suppression des liens avec les versions
			$this->deleteLinkbetweenVersionsTache($tache);

			// Suppression des liens avec les types de paramètres
			$this->deleteLinkbetweenTacheTypeDonneeUtilisateur($tache);

			// Suppression des fonctions en lien avec la tâche
			$this->deleteFonctions($tache);

			// Suppression de la tache de la version
			$requete = $this->dao->prepare("DELETE FROM tache WHERE id_tache = :idTache");

			//bind des valeurs
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

			//On libère la requete
			$requete->closeCursor();
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Tache : L\'objet passé en paramètre n\'est pas une instance de Tache');
		}
	}
	
	// Sélectionne une tache par son ID avec tous ces éléments
	public function getTacheById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM tache WHERE id_tache = :idTache");
		
		//bind des fonction
		$requete->bindValue(':idTache', $id, \PDO::PARAM_INT);
		
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
			$tache = $this->constructTache($donnees[0]);
			$this->putFonctionsInTache($tache);
			$this->putTacheTypeDonneeUtilisateursInTache($tache);
			return $tache;
		}
	}

	// Sélectionne une tache par son ID avec que les élements principaux
	public function getTacheByIdLimited($id){
		
		$requete = $this->dao->prepare("SELECT * FROM tache WHERE id_tache = :idTache");
		
		//bind des fonction
		$requete->bindValue(':idTache', $id, \PDO::PARAM_INT);
		
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
			$tache = $this->constructTache($donnees[0]);
			return $tache;
		}
	}
	
	//renvoi un tableau de toutes les taches
	public function getAllTaches(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM tache");
		
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
		$taches = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($taches, $this->constructTache($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $taches;
	}
	
	//renvoi un tableau de tache a partir de l'index début jusqu'a debut + quantite
	public function getTachesBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM tache LIMIT :debut,:quantite");
		
		//bind des fonction
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
		$taches = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($taches, $this->constructTache($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $taches;
	}
	
	//retourne le nombre de tache dans la base
	public function getNumberOfTache(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreTache FROM tache');
		
		// Execution de la requête sinon envoi d'une erreur
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
		
		return $donnees['nombreTache'];
	}
	
	public function putFonctionsInTache($tache){

		if($tache instanceof Tache){
			$requete = $this->dao->prepare("SELECT id_fonction FROM tache_fonction WHERE id_tache = :idTache ORDER BY id_ordre");
			
			// Bind des paramètres
			$requete->bindValue(':idTache', $tache->getIdTache(), \PDO::PARAM_INT);
			
			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			// Création d'un tableau d'application
			$pdoFonction = new PDOFonctionManager($this->dao);
			
			// On construit l'objet application
			while ($donnees = $requete->fetch())
			{
				$tache->addFonction($pdoFonction->getFonctionById($donnees['id_fonction']));
			}
			
			// On libère la requete
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Tache : L\'objet passé en paramètre n\'est pas une instance de Tache');
		}
	}
	
	public function putVersionsInTache($tache){

		if($tache instanceof Tache){
				$requete = $this->dao->prepare("SELECT id_version FROM version_tache WHERE id_tache = :idTache ");
			
			//bind des tache
			$requete->bindValue(':idTache', $tache->getIdTache(), \PDO::PARAM_INT);
			
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
				$tache->addTache($pdoVersion->getVersionById($donnees['id_verion']));
			}
			
			//On libère la requete
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Tache : L\'objet passé en paramètre n\'est pas une instance de Tache');
		}
		
	}

	public function putTacheTypeDonneeUtilisateursInTache($tache){

		if($tache instanceof Tache){
			$requete = $this->dao->prepare("SELECT id_type_donnee_utilisateur, id_ordre FROM tache_type_donnee_utilisateur WHERE id_tache = :idTache ORDER BY id_ordre");
			
			// Bind des paramètres
			$requete->bindValue(':idTache', $tache->getIdTache(), \PDO::PARAM_INT);
			
			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			// On appelle le pdo des TacheTypeDonneeUtilisateur
			$pdoTacheTypeDonneeUtilisateur = new PDOTacheTypeDonneeUtilisateurManager($this->dao);
			
			// On ajoute à la tâche l'ensemble des paramètres
			while ($donnees = $requete->fetch())
			{
				
				$tache->addTacheTypeDonneeUtilisateur($pdoTacheTypeDonneeUtilisateur->getTacheTypeDonneeUtilisateurById($tache->getIdTache(), $donnees['id_type_donnee_utilisateur'], $donnees['id_ordre']));
			}
			
			// On libère la requete
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Tache : L\'objet passé en paramètre n\'est pas une instance de Tache');
		}
		
	}
	
	// Permet de contruire un objet tache a partir des données de la base.
	protected function constructTache($donnee){
		
		$data = [
		'idTache' => $donnee['id_tache'],
		'nomTache' => $donnee['nom_tache'],
		'descriptionTache' => $donnee['description_tache']
		];
		return new Tache($data);
	}
}
