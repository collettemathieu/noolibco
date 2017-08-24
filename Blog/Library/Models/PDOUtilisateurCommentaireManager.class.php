<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des UtilisateurCommentaire.			  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des UtilisateurCommentaire
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\UtilisateurCommentaire;
use \Library\Models;

class PDOUtilisateurCommentaireManager extends \Library\Models\UtilisateurCommentaireManager
{

/* Définitions des méthodes action de la classe */

	// Ajout d'un utilisateurCommentaire à la base
	public function addUtilisateurCommentaire($utilisateurCommentaire){

		 if($utilisateurCommentaire instanceof UtilisateurCommentaire){
		
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO utilisateur_commentaire VALUES (:idUtilisateur, :idCommentaire)");

			//bind des valeurs
			$requete->bindValue(':idCommentaire', $utilisateurCommentaire->getCommentaire()->getIdCommentaire(), \PDO::PARAM_INT);
			$requete->bindValue(':idUtilisateur', $utilisateurCommentaire->getUtilisateur()->getIdUtilisateur(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::UtilisateurCommentaire : L\'objet passé en paramètre n\'est pas une instance de UtilisateurCommentaire');
		}
	}
	
	// Supprime un utilisateurCommentaire de la base.
	public function deleteUtilisateurCommentaire($utilisateurCommentaire){

		 if($utilisateurCommentaire instanceof UtilisateurCommentaire){	

			// Suppression de l'utilisateurCommentaire
			$requete = $this->dao->prepare("DELETE FROM utilisateur_commentaire WHERE id_utilisateur = :idUtilisateur and id_commentaire = :idCommentaire;");

			//bind des valeurs
			$requete->bindValue(':idUtilisateur', $utilisateurCommentaire->getUtilisateur()->getIdUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':idCommentaire', $utilisateurCommentaire->getCommentaire()->getIdCommentaire(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::UtilisateurCommentaire : L\'objet passé en paramètre n\'est pas une instance de UtilisateurCommentaire');
		}
	}
	
	
	// Sélection d'un utilisateurCommentaire par son ID
	public function getUtilisateurCommentaireById($idUtilisateur, $idCommentaire){
		
		$requete = $this->dao->prepare("SELECT * FROM utilisateur_commentaire WHERE id_utilisateur = :idUtilisateur AND id_commentaire = : idCommentaire");
		
		//bind des paramètres
		$requete->bindValue(':idUtilisateur', $idUtilisateur, \PDO::PARAM_INT);
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
			$utilisateurCommentaire = $this->constructUtilisateurCommentaire($donnees[0]);
			return $utilisateurCommentaire;
		}
	}

	
	// Permet de contruire un objet utilisateurCommentaire à partir des données de la base.
	protected function constructUtilisateurCommentaire($donnee){
		
		$pdoUtilisateur = new PDOUtilisateurManager($this->dao);
	
	 	$pdoCommentaire = new PDOCommentaireManager($this->dao);

		$data = [
			'utilisateur' => $pdoUtilisateur->getUtilisateurById($donnee['id_utilisateur'],
			'commentaire' => $pdoCommentaire->getCommnetaireById($donnee['id_commentaire'])
		];
		return new UtilisateurCommentaire($data);
	}
}
