<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des CoursCoursGlobal.				  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des CoursCoursGlobal
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\CoursCoursGlobal;
use \Library\Models;

class PDOCoursCoursGlobalManager extends \Library\Models\CoursCoursGlobalManager
{

/* Définitions des méthodes action de la classe */

	// Ajout d'un coursCoursGlobal à la base
	public function addCoursCoursGlobal($coursCoursGlobal){

		 if($coursCoursGlobal instanceof CoursCoursGlobal){
		
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO cours_cours_global VALUES (:idCours, :idCoursGlobal)");

			//bind des valeurs
			$requete->bindValue(':idCours', $coursCoursGlobal->getCours()->getIdCours(), \PDO::PARAM_INT);
			$requete->bindValue(':idCoursGlobal', $coursCoursGlobal->getCoursGlobal()->getIdCoursGlobal(), \PDO::PARAM_INT);
			
			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
				
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//On libère la requête
			$requete->closeCursor();
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::CoursCoursGlobal : L\'objet passé en paramètre n\'est pas une instance de CoursCoursGlobal');
		}
	}
	
	// Supprime un coursCoursGlobal de la base.
	public function deleteCoursCoursGlobal($coursCoursGlobal){

		 if($coursCoursGlobal instanceof CoursCoursGlobal){	

			// Suppression du coursCoursGlobal
			$requete = $this->dao->prepare("DELETE FROM cours_cours_global WHERE id_cours = :idCours and id_cours_global = :idCoursGlobal;");

			//bind des valeurs
			$requete->bindValue(':idCours', $coursCoursGlobal->getCours()->getIdCours(), \PDO::PARAM_INT);
			$requete->bindValue(':idCoursGlobal', $coursCoursGlobal->getCoursGlobal()->getIdCoursGlobal(), \PDO::PARAM_INT);

			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//On libère la requête
			$requete->closeCursor();
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::CoursCoursGlobal : L\'objet passé en paramètre n\'est pas une instance de CoursCoursGlobal');
		}
	}
	
	
	// Sélection d'un coursCoursGlobal par son ID
	public function getCoursCoursGlobalById($idCours, $idCoursGlobal){
		
		$requete = $this->dao->prepare("SELECT * FROM cours_cours_global WHERE id_cours = :idCours AND id_cours_global = : idCoursGlobal");
		
		//bind des paramètres
		$requete->bindValue(':idCours', $idCours, \PDO::PARAM_INT);
		$requete->bindValue(':idCoursGlobal', $idCoursGlobal, \PDO::PARAM_INT);
			
		
		//exécution de la requête sinon envoi d'une erreur
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
			$coursCoursGlobal = $this->constructCoursCoursGlobal($donnees[0]);
			return $coursCoursGlobal;
		}
	}

	// Sélection de tous les cours globaux lié à un cours
	public function getCoursGlobalFromCours($idCours){
		
		$requete = $this->dao->prepare("SELECT * FROM cours_cours_global WHERE id_cours = :idCours");
		
		//bind des paramètres
		$requete->bindValue(':idCours', $idCours, \PDO::PARAM_INT);
		
		//exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		$donnees = $requete->fetch();
		$requete->closeCursor ();
		
		if (count($donnees) == 0) {
			return false;
		}
		else {
			$pdoCoursGlobal = new PDOCoursGlobalManager($this->dao);
			$coursGlobal = $pdoCoursGlobal->getCoursGlobalById($donnees['id_cours_global']);
			return $coursGlobal;
		}
	}

	
	// Permet de contruire un objet coursCoursGlobal à partir des données de la base.
	protected function constructCoursCoursGlobal($donnee){
		
		$pdoCours = new PDOCoursManager($this->dao);
	
	 	$pdoCoursGlobal = new PDOCoursGlobalManager($this->dao);

		$data = [
			'cours' => $pdoCours->getCoursById($donnee['id_cours']),
			'coursGlobal' => $pdoCoursGlobal->getCoursGlobalById($donnee['id_cours_global'])
		];
		return new CoursCoursGlobal($data);
	}
}
