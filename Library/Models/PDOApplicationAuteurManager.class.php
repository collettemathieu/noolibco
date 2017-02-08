<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2015 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des ApplicationAuteur.				  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>				  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des ApplicationAuteur
 * @access: public
 * @version: 1
 */

namespace Library\Models;
use \Library\Entities\ApplicationAuteur;
use \Library\Entities\Auteur;
use \Library\Models;

class PDOApplicationAuteurManager extends \Library\Models\ApplicationAuteurManager
{

/* Définition des méthode de classe */

	//ajoute une applicationAuteur dans la base
	public function addApplicationAuteur($applicationAuteur){

		if($applicationAuteur instanceof ApplicationAuteur){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT IGNORE INTO application_auteur VALUES (:idApplication, :idAuteur)");

			//bind des valeurs
			$requete->bindValue(':idApplication', $applicationAuteur->getApplication()->getIdApplication(), \PDO::PARAM_INT);
			$requete->bindValue(':idAuteur', $applicationAuteur->getAuteur()->getIdAuteur(), \PDO::PARAM_INT);
			
			
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
		$messageClient->addErreur('PDO::ApplicationAuteur : L\'objet passé en paramètre n\'est pas une instance de ApplicationAuteur');
		}
	}

	//supprime la applicationAuteur de la base et modifie les données de toutes les utilisateurs avec cette applicationAuteur.
	public function deleteApplicationAuteur($applicationAuteur){

		if($applicationAuteur instanceof ApplicationAuteur){	

			$requete = $this->dao->prepare("DELETE FROM application_auteur WHERE id_application = :idApplication and id_auteur = :idAuteur");

			//bind des valeurs
			$requete->bindValue(':idAuteur', $applicationAuteur->getAuteur()->getIdAuteur(), \PDO::PARAM_INT);
			$requete->bindValue(':idApplication', $applicationAuteur->getApplication()->getIdApplication(), \PDO::PARAM_INT);

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
		$messageClient->addErreur('PDO::ApplicationAuteur : L\'objet passé en paramètre n\'est pas une instance de ApplicationAuteur');
		}
	}
	
	//selectionne une applicationAuteur par son ID
	public function getApplicationAuteurById($idApplication, $idAuteur){
		
		$requete = $this->dao->prepare("SELECT * FROM application_auteur  WHERE id_application = :idApplication and id_auteur = :idAuteur");
		
		//bind des parametre
		$requete->bindValue(':idAuteur', $idAuteur, \PDO::PARAM_INT);
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
			$applicationAuteur = $this->constructApplicationAuteur($donnees[0]);
			return $applicationAuteur;
		}
	}
	
	//renvoi un tableau de toutes les application de l'auteur
	public function getAllApplicationsFromAuteur($auteur){
		
		if($auteur instanceof Auteur){

			//preparation de la requete
			$requete = $this->dao->prepare("SELECT * FROM application_auteur WHERE id_auteur = :idAuteur");

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
			//creation d'un tableau d'applications
			$applications = array();
			
			$pdoApplication = new PDOApplicationManager($this->dao);
			
			while ($donnees = $requete->fetch())
			{
				array_push($applications, $pdoApplication->getApplicationByIdWithAllParameters($donnees['id_application']));
			}
			
			//On libère la requete
			$requete->closeCursor();
			return $applications;

		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::ApplicationAuteur : L\'objet passé en paramètre n\'est pas une instance de Auteur');
		}
	}
	
	//renvoi un tableau de applicationAuteur a partir de l'index début jusqu'a debut + quantite
	public function getApplicationAuteursBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM application_auteur LIMIT :debut,:quantite");
		
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
		//creation d'un tableau d'applicationAuteurs
		$applicationAuteurs = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($applicationAuteurs, $this->constructApplicationAuteur($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $applicationAuteurs;
	}
	
	// Retourne le nombre de applicationAuteur dans la base
	public function getNumberOfApplicationAuteur(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreApplicationAuteur FROM application_auteur');
		
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
		
		return $donnees['nombreApplicationAuteur'];
	}

	// Retourne le nombre de fois que l'auteur est cité dans une application
	public function getNumberOfAuteurInApplication($idAuteur){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreCitationAuteur FROM application_auteur WHERE id_auteur = :idAuteur');
		
		// Bind des paramètres
		$requete->bindValue(':idAuteur', $idAuteur, \PDO::PARAM_INT);

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
		
		return $donnees['nombreCitationAuteur'];
	}
	
	//permet de contruire un objet applicationAuteur a partir des ses données de la base.
	protected function constructApplicationAuteur($donnee){
		
		$pdoApplication = new PDOApplicationManager($this->dao);
		$pdoAuteur = new PDOAuteurManager($this->dao);
		
		$data = [
		'Auteur' => $pdoAuteur->getAuteurById($donnee['id_auteur']),
		'Application' => $pdoApplication->getApplicationById($donnee['id_application'])
		];
		return new ApplicationAuteur($data);
	}
}
