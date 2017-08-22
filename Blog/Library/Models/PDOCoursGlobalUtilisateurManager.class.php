<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des CoursGlobalUtilisateur.			  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des CoursGlobalUtilisateur
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\CoursGlobalUtilisateur;
use \Library\Models;

class PDOCoursGlobalUtilisateurManager extends \Library\Models\CoursGlobalUtilisateurManager
{

/* Définitions des méthodes action de la classe */

	// Ajout d'un coursGlobalUtilisateur à la base
	public function addCoursGlobalUtilisateur($coursGlobalUtilisateur){

		 if($coursGlobalUtilisateur instanceof CoursGlobalUtilisateur){
		
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO utilisateur_cours_global VALUES (:idUtilisateur, :idCoursGlobal)");

			//bind des valeurs
			$requete->bindValue(':idCoursGlobal', $coursGlobalUtilisateur->getCoursGlobal()->getIdCoursGlobal(), \PDO::PARAM_INT);
			$requete->bindValue(':idUtilisateur', $coursGlobalUtilisateur->getUtilisateur()->getIdUtilisateur(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::CoursGlobalUtilisateur : L\'objet passé en paramètre n\'est pas une instance de CoursGlobalUtilisateur');
		}
	}
	
	// Supprime un coursGlobalUtilisateur de la base.
	public function deleteCoursGlobalUtilisateur($coursGlobalUtilisateur){

		 if($coursGlobalUtilisateur instanceof CoursGlobalUtilisateur){	

			// Suppression du coursGlobalUtilisateur
			$requete = $this->dao->prepare("DELETE FROM utilisateur_cours_global WHERE id_utilisateur = :idUtilisateur and id_coursGlobal = :idCoursGlobal;");

			//bind des valeurs
			$requete->bindValue(':idUtilisateur', $coursGlobalUtilisateur->getUtilisateur()->getIdUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':idCoursGlobal', $coursGlobalUtilisateur->getIdCoursGlobal(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::CoursGlobalUtilisateur : L\'objet passé en paramètre n\'est pas une instance de CoursGlobalUtilisateur');
		}
	}
	
	
	// Sélection d'un coursGlobalUtilisateur par son ID
	public function getCoursGlobalUtilisateurId($idUtilisateur, $idCoursGlobal){
		
		$requete = $this->dao->prepare("SELECT * FROM utilisateur_cours_global WHERE id_utilisateur = :idUtilisateur AND id_coursGlobal = : idCoursGlobal");
		
		//bind des paramètres
		$requete->bindValue(':idUtilisateur', $idUtilisateur, \PDO::PARAM_INT);
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
			$coursGlobalUtilisateur = $this->constructCoursGlobalUtilisateur($donnees[0]);
			return $coursGlobalUtilisateur;
		}
	}

	
	// Permet de contruire un objet coursGlobalUtilisateur à partir des données de la base.
	protected function constructCoursGlobalUtilisateur($donnee){
		
		$pdoUtilisateur = new PDOUtilisateurManager($this->dao);
	
	 	$pdoCoursGlobal = new PDOCoursGlobalManager($this->dao);

		$data = [
			'utilisateur' => $pdoUtilisateur->getUtilisateurById($donnee['id_utilisateur'],
			'coursGlobal' => $pdoCoursGlobal->getCoursGlobalById($donnee['id_coursGlobal'])
		];
		return new CoursGlobalUtilisateur($data);
	}
}
