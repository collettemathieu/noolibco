<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des CoursMotCle.					  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des CoursMotCle
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\CoursMotCle;
use \Library\Models;

class PDOCoursMotCleManager extends \Library\Models\CoursMotCleManager
{

/* Définitions des méthodes action de la classe */

	// Ajout d'un coursMotCle à la base
	public function addCoursMotCle($coursMotCle){

		 if($coursMotCle instanceof CoursMotCle){
		
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO cours_mot_cle VALUES (:idMotCle, :idCours)");

			//bind des valeurs
			$requete->bindValue(':idCours', $coursMotCle->getCours()->getIdCours(), \PDO::PARAM_INT);
			$requete->bindValue(':idMotCle', $coursMotCle->getMotCle()->getIdMotCle(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::CoursMotCle : L\'objet passé en paramètre n\'est pas une instance de CoursMotCle');
		}
	}
	
	// Supprime un coursMotCle de la base.
	public function deleteCoursMotCle($coursMotCle){

		 if($coursMotCle instanceof CoursMotCle){	

			// Suppression de l'coursMotCle
			$requete = $this->dao->prepare("DELETE FROM cours_mot_cle WHERE id_mot_cle = :idMotCle and id_cours = :idCours;");

			//bind des valeurs
			$requete->bindValue(':idMotCle', $coursMotCle->getMotCle()->getIdMotCle(), \PDO::PARAM_INT);
			$requete->bindValue(':idCours', $coursMotCle->getCommentaire()->getIdCommentaire(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::CoursMotCle : L\'objet passé en paramètre n\'est pas une instance de CoursMotCle');
		}
	}
	
	
	// Sélection d'un coursMotCle par son ID
	public function getCoursMotCleById($idMotCle, $idCours){
		
		$requete = $this->dao->prepare("SELECT * FROM cours_mot_cle WHERE id_mot_cle = :idMotCle AND id_cours = : idCours");
		
		//bind des paramètres
		$requete->bindValue(':idMotCle', $idMotCle, \PDO::PARAM_INT);
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
		$donnees = $requete->fetchAll();
		
		$requete->closeCursor ();
		
		if (count($donnees) == 0) {
			return false;
		}
		else {
			$coursMotCle = $this->constructCoursMotCle($donnees[0]);
			return $coursMotCle;
		}
	}

	
	// Permet de contruire un objet CoursMotCle à partir des données de la base.
	protected function constructCoursMotCle($donnee){
		
		$pdoUtilisateur = new PDOUtilisateurManager($this->dao);
	
	 	$pdoCours = new PDOCoursManager($this->dao);

		$data = [
			'utilisateur' => $pdoUtilisateur->getMotCleById($donnee['id_mot_cle']),
			'cours' => $pdoCours->getCoursById($donnee['id_cours'])
		];
		return new CoursMotCle($data);
	}
}
