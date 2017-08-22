<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des UtilisateurCours.				  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des UtilisateurCours
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\UtilisateurCours;
use \Library\Models;

class PDOUtilisateurCoursManager extends \Library\Models\UtilisateurCoursManager
{

/* Définitions des méthodes action de la classe */

	// Ajout d'un utilisateurCours à la base
	public function addUtilisateurCours($utilisateurCours){

		 if($utilisateurCours instanceof UtilisateurCours){
		
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO utilisateur_cours VALUES (:idUtilisateur, :idCours)");

			//bind des valeurs
			$requete->bindValue(':idCours', $utilisateurCours->getCours()->getIdCours(), \PDO::PARAM_INT);
			$requete->bindValue(':idUtilisateur', $utilisateurCours->getUtilisateur()->getIdUtilisateur(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::UtilisateurCours : L\'objet passé en paramètre n\'est pas une instance de UtilisateurCours');
		}
	}
	
	// Supprime un utilisateurCours de la base.
	public function deleteUtilisateurCours($utilisateurCours){

		 if($utilisateurCours instanceof UtilisateurCours){	

			// Suppression de l'utilisateurCours
			$requete = $this->dao->prepare("DELETE FROM utilisateur_cours WHERE id_utilisateur = :idUtilisateur and id_cours = :idCours;");

			//bind des valeurs
			$requete->bindValue(':idUtilisateur', $utilisateurCours->getUtilisateur()->getIdUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':idCours', $utilisateurCours->getCommentaire()->getIdCommentaire(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::UtilisateurCours : L\'objet passé en paramètre n\'est pas une instance de UtilisateurCours');
		}
	}
	
	
	// Sélection d'un utilisateurCours par son ID
	public function getUtilisateurCoursId($idUtilisateur, $idCours){
		
		$requete = $this->dao->prepare("SELECT * FROM utilisateur_cours WHERE id_utilisateur = :idUtilisateur AND id_cours = : idCours");
		
		//bind des paramètres
		$requete->bindValue(':idUtilisateur', $idUtilisateur, \PDO::PARAM_INT);
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
			$utilisateurCours = $this->constructUtilisateurCours($donnees[0]);
			return $utilisateurCours;
		}
	}

	
	// Permet de contruire un objet utilisateurCours à partir des données de la base.
	protected function constructUtilisateurCours($donnee){
		
		$pdoUtilisateur = new PDOUtilisateurManager($this->dao);
	
	 	$pdoCours = new PDOCoursManager($this->dao);

		$data = [
			'utilisateur' => $pdoUtilisateur->getUtilisateurById($donnee['id_utilisateur'],
			'cours' => $pdoCours->getCoursById($donnee['id_cours'])
		];
		return new UtilisateurCours($data);
	}
}
