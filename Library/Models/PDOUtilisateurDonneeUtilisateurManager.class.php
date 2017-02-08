<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des UtilisateurDonneeUtilisateur.	  |
// +----------------------------------------------------------------------+
// | Auteurs : Corentin Chevallier <ChevallierCorentin@noolib.com> 		  |
// |				Mathieu COLLETTE <collettemathieu@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des UtilisateurDonneeUtilisateur
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\UtilisateurDonneeUtilisateur;
use \Library\Models;

class PDOUtilisateurDonneeUtilisateurManager extends \Library\Models\UtilisateurDonneeUtilisateurManager
{

/* Définition des méthode de classe */

	// Ajoute un UtilisateurDonneeUtilisateur dans la BDD
	public function addUtilisateurDonneeUtilisateur($utilisateurDonneeUtilisateur){

		if($utilisateurDonneeUtilisateur instanceof UtilisateurDonneeUtilisateur){
		
			// Préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO utilisateur_donnee_utilisateur VALUES (:idUtilisateur, :idDonneeUtilisateur)");

			// Bind des paramètres
			$requete->bindValue(':idUtilisateur', $utilisateurDonneeUtilisateur->getUtilisateur()->getIdUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':idDonneeUtilisateur', $utilisateurDonneeUtilisateur->getDonneeUtilisateur()->getIdDonneeUtilisateur(), \PDO::PARAM_INT);
			
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
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::UtilisateurDonneeUtilisateur : L\'objet passé en paramètre n\'est pas une instance de UtilisateurDonneeUtilisateur');
		}
	}

	// Supprime l'objet UtilisateurDonneeUtilisateur de la BDD.
	public function deleteUtilisateurDonneeUtilisateur($utilisateurDonneeUtilisateur){

		if($utilisateurDonneeUtilisateur instanceof UtilisateurDonneeUtilisateur){	

			// Préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM utilisateur_donnee_utilisateur WHERE id_donnee_utilisateur = :idDonneeUtilisateur and id_utilisateur = :idUtilisateur;");

			// Bind des paramètres
			$requete->bindValue(':idUtilisateur', $utilisateurDonneeUtilisateur->getUtilisateur()->getIdUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':idDonneeUtilisateur', $utilisateurDonneesUtilisateur->getDonneeUtilisateur()->getIdDonneeUtilisateur(), \PDO::PARAM_INT);

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
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::UtilisateurDonneeUtilisateur : L\'objet passé en paramètre n\'est pas une instance de UtilisateurDonneeUtilisateur');
		}
	}
	
	// Sélectionne un objet UtilisateurDonneeUtilisateur par son ID
	public function getUtilisateurDonneeUtilisateurById($idUtilisateur, $idDonneeUtilisateur){
		
		// Préparation de la requête
		$requete = $this->dao->prepare("SELECT * FROM utilisateur_donnee_utilisateur WHERE id_donnee_utilisateur = :idDonneeUtilisateur and id_utilisateur = :idUtilisateur");
		
		// Bind des paramètres
		$requete->bindValue(':idUtilisateur', $idUtilisateur, \PDO::PARAM_INT);
		$requete->bindValue(':idDonneeUtilisateur', $idDonneeUtilisateur, \PDO::PARAM_INT);
		
		// Execution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		$donnees = $requete->fetchAll();
		
		// On libère la requête
		$requete->closeCursor();
		
		if (count($donnees) == 0) {
			return false;
		}
		else {
			$utilisateurDonneeUtilisateur = $this->constructUtilisateurDonneeUtilisateur($donnees[0]);
			return $utilisateurDonneeUtilisateur;
		}

	}
	
	// Sélectionne un objet UtilisateurDonneeUtilisateur par son ID
	public function getUtilisateurDonneeUtilisateurByIdUtilisateur($idUtilisateur){
		
		// Préparation de la requête
		$requete = $this->dao->prepare("SELECT * FROM utilisateur_donnee_utilisateur WHERE id_utilisateur = :idUtilisateur");
		
		// Bind des paramètres
		$requete->bindValue(':idUtilisateur', $idUtilisateur, \PDO::PARAM_INT);
		
		// Execution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		$donnees = $requete->fetchAll();
		
		// On libère la requête
		$requete->closeCursor();
		
		if (count($donnees) == 0) {
			return false;
		}
		else {
			$utilisateurDonneeUtilisateur = $this->constructUtilisateurDonneeUtilisateur($donnees[0]);
			return $utilisateurDonneeUtilisateur;
		}

	}
	
	// Renvoi un tableau de toutes les objets utilisateurDonneeUtilisateur
	public function getAllUtilisateurDonneeUtilisateur(){
		
		// Préparation de la requête
		$requete = $this->dao->prepare("SELECT * FROM utilisateur_donnee_utilisateur");
		
		// Execution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		// Création d'un tableau d'utilisateurDonneeUtilisateur
		$utilisateurDonneeUtilisateur = array();
		
		// On construit chaque objet utilisateurDonneeUtilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($utilisateurDonneeUtilisateur, $this->constructUtilisateurDonneeUtilisateur($donnees));
		}
		
		// On libère la requête
		$requete->closeCursor();
		
		return $utilisateurDonneeUtilisateur;
	}
	
	// Renvoi un tableau de utilisateurDonneeUtilisateur à partir de l'index début jusqu'à début + quantite
	public function getUtilisateurDonneeUtilisateurBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM utilisateur_donnee_utilisateur LIMIT :debut,:quantite");
		
		// Bind des paramètres
		$requete->bindValue(':debut', $debut, \PDO::PARAM_INT);
		$requete->bindValue(':quantite', $quantite, \PDO::PARAM_INT);
		
		// Execution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		
		// Création d'un tableau d'utilisateurDonneeUtilisateur
		$utilisateurDonneeUtilisateur = array();
		
		//On construit chaque objet utilisateurDonneeUtilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($utilisateurDonneeUtilisateur, $this->constructUtilisateurDonneeUtilisateur($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $utilisateurDonneeUtilisateur;
	}
	
	// Retourne le nombre d'utilisateurDonneeUtilisateur dans la BDD
	public function getNumberOfUtilisateurDonneeUtilisateur(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreUtilisateurDonneeUtilisateur FROM utilisateur_donnee_utilisateur');
		
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
		
		// On libère la requête
		$requete->closeCursor();
		
		return $donnees['nombreUtilisateurDonneeUtilisateur'];
	}
	
	// Permet de contruire un objet utilisateurDonneeUtilisateur à partir des ses données de la BDD.
	protected function constructUtilisateurDonneeUtilisateur($donnee){
		
		$pdoDonneeUtilisateur = new PDODonneeUtilisateurManager($this->dao);
		$pdoUtilisateur = new PDOUtilisateurManager($this->dao);
		
		$data =[
		'utilisateur' =>$pdoUtilisateur->getUtilisateurById($donnee['id_utilisateur']),
		'donneeUtilisateur' => $pdoDonneeUtilisateur->getDonneeUtilisateurById($donnee['id_donnee_utilisateur'])
		];
		return new UtilisateurDonneeUtilisateur($data);
	}
}
