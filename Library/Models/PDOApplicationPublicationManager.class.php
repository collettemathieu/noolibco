<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des ApplicationPublications.		  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des ApplicationPublications
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\ApplicationPublication;
use \Library\Models;

class PDOApplicationPublicationManager extends \Library\Models\ApplicationPublicationManager
{

/* Définition des méthode de classe */

	//ajoute une applicationPublication dans la base
	public function addApplicationPublication($applicationPublication){

		 if($applicationPublication instanceof ApplicationPublication){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO application_publication VALUES (:idPublication, :idApplication)");

			//bind des valeurs
			$requete->bindParam(':idPublication', $applicationPublication->getPublication()->getIdPublication(), \PDO::PARAM_INT);
			$requete->bindParam(':idApplication', $applicationPublication->getApplication()->getIdApplication(), \PDO::PARAM_INT);
			
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
		$messageClient->addErreur('PDO::ApplicationPublication : L\'objet passé en paramètre n\'est pas une instance de ApplicationPublication');
		}
	}

	//supprime la applicationPublication de la base et modifie les données de toutes les utilisateurs avec cette applicationPublication.
	public function deleteApplicationPublication($applicationPublication){

		 if($applicationPublication instanceof ApplicationPublication){	

			$requete = $this->dao->prepare("DELETE FROM application_publication WHERE id_application = :idApplication and id_publication = :idPublication;");

			//bind des valeurs
			$requete->bindParam(':idPublication', $applicationPublication->getPublication()->getIdPublication(), \PDO::PARAM_INT);
			$requete->bindParam(':idApplication', $applicationPublication->getApplication()->getIdApplication(), \PDO::PARAM_INT);

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
		$messageClient->addErreur('PDO::ApplicationPublication : L\'objet passé en paramètre n\'est pas une instance de ApplicationPublication');
		}
	}
	
	//selectionne une applicationPublication par son ID
	public function getApplicationPublicationById($idApplication, $idPublication){
		
		$requete = $this->dao->prepare("SELECT * FROM application_publication  WHERE id_application = :idApplication and id_publication = :idPublication");
		
		//bind des parametre
		$requete->bindParam(':idPublication', $idPublication, \PDO::PARAM_INT);
		$requete->bindParam(':idApplication', $idApplication, \PDO::PARAM_INT);
		
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
			$applicationPublication = $this->constructApplicationPublication($donnees[0]);
			return $applicationPublication;
		}
	}
	
	//renvoi un tableau de toutes les applicationPublications
	public function getAllApplicationPublications(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM application_publication");
		
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
		$applicationPublications = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($applicationPublications, $this->constructApplicationPublication($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $applicationPublications;
	}
	
	//renvoi un tableau de applicationPublication a partir de l'index début jusqu'a debut + quantite
	public function getApplicationPublicationsBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM application_publication LIMIT :debut,:quantite");
		
		//bind des parametre
		$requete->bindParam(':debut', $debut, \PDO::PARAM_INT);
		$requete->bindParam(':quantite', $quantite, \PDO::PARAM_INT);
		
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
		$applicationPublications = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($applicationPublications, $this->constructApplicationPublication($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $applicationPublications;
	}
	
	//retourne le nombre de applicationPublication dans la base
	public function getNumberOfApplicationPublication(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreApplicationPublication FROM application_publication');
		
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
		
		return $donnees['nombreApplicationPublication'];
	}
	
	//permet de contruire un objet applicationPublication a partir des ses données de la base.
	protected function constructApplicationPublication($donnee){
		
		$pdoApplication = new PDOApplicationManager($this->dao);
		$pdoPublication = new PDOPublicationManager($this->dao);
		
		$data = [
		'Publication' => $pdoPublication->getPublicationById($donnee['id_publication']),
		'Application' => $pdoApplication->getApplicationById($donnee['id_application']) 
		];
		return new ApplicationPublication($data);
	}
}
