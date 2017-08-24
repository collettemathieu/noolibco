<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des CoursCommentaire.				  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des CoursCommentaire
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\CoursCommentaire;
use \Library\Models;

class PDOCoursCommentaireManager extends \Library\Models\CoursCommentaireManager
{

/* Définitions des méthodes action de la classe */

	// Ajout d'un coursCommentaire à la base
	public function addCoursCommentaire($coursCommentaire){

		 if($coursCommentaire instanceof CoursCommentaire){
		
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO cours_commentaire VALUES (:idCours, :idCommentaire)");

			//bind des valeurs
			$requete->bindValue(':idCours', $coursCommentaire->getCours()->getIdCours(), \PDO::PARAM_INT);
			$requete->bindValue(':idCommentaire', $coursCommentaire->getCommentaire()->getIdCommentaire(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::CoursCommentaire : L\'objet passé en paramètre n\'est pas une instance de CoursCommentaire');
		}
	}
	
	// Supprime un coursCommentaire de la base.
	public function deleteCoursCommentaire($coursCommentaire){

		 if($coursCommentaire instanceof CoursCommentaire){	

			// Suppression du coursCommentaire
			$requete = $this->dao->prepare("DELETE FROM cours_commentaire WHERE id_cours = :idCours and id_commentaire = :idCommentaire;");

			//bind des valeurs
			$requete->bindValue(':idCours', $coursCommentaire->getCours()->getIdCours(), \PDO::PARAM_INT);
			$requete->bindValue(':idCommentaire', $coursCommentaire->getCommentaire()->getIdCommentaire(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::CoursCommentaire : L\'objet passé en paramètre n\'est pas une instance de CoursCommentaire');
		}
	}
	
	
	// Sélection d'un coursCommentaire par son ID
	public function getCoursCommentaireId($idCours, $idCommentaire){
		
		$requete = $this->dao->prepare("SELECT * FROM cours_commentaire WHERE id_cours = :idCours AND id_commentaire = : idCommentaire");
		
		//bind des paramètres
		$requete->bindValue(':idCours', $idCours, \PDO::PARAM_INT);
		$requete->bindValue(':idCommentaire', $idCommentaire, \PDO::PARAM_INT);
			
		
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
			$coursCommentaire = $this->constructCoursCommentaire($donnees[0]);
			return $coursCommentaire;
		}
	}

	
	// Permet de contruire un objet coursCommentaire à partir des données de la base.
	protected function constructCoursCommentaire($donnee){
		
		$pdoCours = new PDOCoursManager($this->dao);
	
	 	$pdoCommentaire = new PDOCommentaireManager($this->dao);

		$data = [
			'cours' => $pdoCours->getCoursById($donnee['id_cours'],
			'commentaire' => $pdoCommentaire->getCommnetaireById($donnee['id_commentaire'])
		];
		return new CoursCommentaire($data);
	}
}
