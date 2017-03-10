<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO de Statut des utilisateurs.			  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>				  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO de statut des utilisateurs
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\StatutUtilisateur;
use \Library\Models;

class PDOStatutUtilisateurManager extends \Library\Models\StatutUtilisateurManager
{

/* Définitions des méthodes action de la classe */

	// Ajoute un statut dans la BDD
	public function addStatut($statut){

		if($statut instanceof StatutUtilisateur){
		
			// Préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO statut_utilisateur (nom_statut) 
					VALUES (:nomStatut)");

			// Bind des paramètres
			$requete->bindValue(':nomStatut', $statut->getNomStatut(), \PDO::PARAM_STR);
			
			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$statut->setIdStatut($this->dao->lastInsertId('id_statut'));
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
		$messageClient->addErreur('PDO::StatutUtilisateur : L\'objet passé en paramètre n\'est pas une instance de StatutUtilisateur');
		}
	}
	

	// Met à jour le statut de tous les utilisateurs
	public function addUtilisateursFromStatut($statut){

		if($statut instanceof StatutUtilisateur){
			if (sizeof($statut->getUtilisateurs()) != 0){
					
				foreach ($statut->getUtilisateurs() as $utilisateur){
						
					//préparation de la requete
					$requete = $this->dao->prepare("UPDATE utilisateur SET id_statut = :idStatutUtilisateur WHERE id_utilisateur = :idUtilisateur;");
			
					// Bind des paramètres
					$requete->bindValue(':idStatutUtilisateur', $statut->getIdStatut(), \PDO::PARAM_INT);
					$requete->bindValue(':idUtilisateur', $application->getIdApplication(), \PDO::PARAM_INT);
			
					// Execution de la requete sinon envoi d'une erreur
					try {
						$this->dao->beginTransaction();
						$requete->execute();
						$this->dao->commit();
					} catch(PDOException $e) {
						$this->dao->rollback();
						return "Error!: " . $e->getMessage() . "</br>";
					}
			
					// On libère la requete
					$requete->closeCursor();
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::StatutUtilisateur : L\'objet passé en paramètre n\'est pas une instance de StatutUtilisateur');
		}
	}
	

	// Sauvegarde les modifications d'un statut utilisateur
	public function saveStatut($statut){

		if($statut instanceof StatutUtilisateur){
	
			// Préparation de la requete
			$requete = $this->dao->prepare("UPDATE statut_utilisateur SET
					nom_statut = :nomStatut
					WHERE id_statut = :idStatut;");

			// Bind des paramètres
			$requete->bindValue(':idStatut', $statut->getIdStatut(), \PDO::PARAM_INT);
			$requete->bindValue(':nomStatut', $statut->getNomStatut(), \PDO::PARAM_STR);
			
			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			// On libère la requete
			$requete->closeCursor();
			
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::StatutUtilisateur : L\'objet passé en paramètre n\'est pas une instance de StatutUtilisateur');
		}
	}
	
	// Supprime le lien entre l'utilisateur et son statut
	public function deleteLinkbetweenUtilisateursStatut($statut){

		if($statut instanceof StatutUtilisateur){
			// Préparation de la requete
			$requete = $this->dao->prepare("UPDATE utilisateur SET id_statut = 1 WHERE id_statut = :idStatut;");
			
			// Bind des paramètres
			$requete->bindValue(':idStatut', $statut->getIdStatut(), \PDO::PARAM_INT);
			
			// Execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}
			
			// On libère la requete
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::StatutUtilisateur : L\'objet passé en paramètre n\'est pas une instance de StatutUtilisateur');
		}
		
	}
	

	// Supprime le statut de la base et modifie les données de toutes les utilisateurs avec ce statut.
	public function deleteStatut($statut){

		if($statut instanceof StatutUtilisateur){	

			// Suppression du lien entre les utilisateurs et le statut
			$this->deleteLinkbetweenUtilisateursStatut($statut);
			
			// Suppression du statut
			// Préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM statut_utilisateur WHERE id_statut = :idStatut");

			// Bind des valeurs
			$requete->bindValue(':idStatut', $statut->getIdStatut(), \PDO::PARAM_INT);

			// Execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			// On libère la requete
			$requete->closeCursor();
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::StatutUtilisateur : L\'objet passé en paramètre n\'est pas une instance de StatutUtilisateur');
		}
	}
	
	
	// Sélectionne un statut utilisateur par son ID
	public function getStatutById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM statut_utilisateur WHERE id_statut = :idStatut");
		
		// Bind des paramètres
		$requete->bindValue(':idStatut', $id, \PDO::PARAM_INT);
		
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
		
		$requete->closeCursor ();
		
		if (count($donnees) == 0) {
			return false;
		}
		else {
			$statut = $this->constructStatut($donnees[0]);
			return $statut;
		}
	}

	// Sélectionne un statut par son nom
	public function getStatutByNom($nom){
		
		$requete = $this->dao->prepare("SELECT * FROM statut_utilisateur WHERE nom_statut = :nomStatut");
		
		// Bind des paramètres
		$requete->bindValue(':nomStatut', $nom, \PDO::PARAM_STR);
		
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
		
		$requete->closeCursor ();
		
		if (count($donnees) == 0) {
			return null;
		}
		else {
			$statut = $this->constructStatut($donnees[0]);
			return $statut;
		}
	}
	
	// Renvoi un tableau de tous les statuts des utilisateurs
	public function getAllStatuts(){
		
		// Préparation de la requête
		$requete = $this->dao->prepare("SELECT * FROM statut_utilisateur");
		
		// Execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		// Création d'un tableau de statuts utilisateurs
		$statuts = array();
		
		// On construit l'objet statut utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($statuts, $this->constructStatut($donnees));
		}
		
		// On libère la requete
		$requete->closeCursor();
		
		return $statuts;
	}
	
	// Place tous les utilisateurs ayant le même statut dans l'objet statut
	public function putUtilisateursInStatut($statut){

		if($statut instanceof StatutUtilisateur){
	
			$requete = $this->dao->prepare("SELECT * FROM utilisateur WHERE id_statut = :idStatut");
		
			// Bind des paramètres
			$requete->bindValue(':idStatut', $statut->getIdStatut(), \PDO::PARAM_INT);
		
			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			
			$pdoUtilisateur = new PDOUtilisateurManager($this->dao);
			
			// On remplit l'objet statut utilisateur
			while ($donnees = $requete->fetch())
			{
				$statut->addUtilisateur($pdoUtilisateur->getUtilisateurById($donnees['id_utilisateur']));
			}
		
			// On libère la requete
			$requete->closeCursor();
		
			return $statuts;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::StatutUtilisateur : L\'objet passé en paramètre n\'est pas une instance de StatutUtilisateur');
		}
	}
	
	// Retourne le nombre de statut utilisateur dans la base
	public function getNumberOfStatut(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreStatut FROM statut_utilisateur');
		
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
		
		$donnees = $requete->fetch();
		
		// On libère la requete
		$requete->closeCursor();
		
		return $donnees['nombreStatut'];
	}
	
	// Permet de contruire un objet statutUtilisateur à partir des ses données de la base.
	protected function constructStatut($donnee){
		
		$data = [
		'idStatut' => $donnee['id_statut'],
		'nomStatut' => $donnee['nom_statut']
		];
		return new StatutUtilisateur($data);
	}
}
