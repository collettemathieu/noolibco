<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des uniteDonneeUtilisateurs.		  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des uniteDonneeUtilisateurs
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\UniteDonneeUtilisateur;
use \Library\Models;

class PDOUniteDonneeUtilisateurManager extends \Library\Models\UniteDonneeUtilisateurManager
{

/* Définitions des méthodes action de la classe */

	//ajoute une catégorie dans la base
	public function addUniteDonneeUtilisateur($uniteDonneeUtilisateur){

		if($uniteDonneeUtilisateur instanceof UniteDonneeUtilisateur){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO unite_donnee_utilisateur (nom_unite_donnee_utilisateur, symbole_unite_donnee_utilisateur) 
					VALUES (:nomUniteDonneeUtilisateur, :symboleUniteDonneeUtilisateur)");

			//bind des valeurs
			$requete->bindValue(':nomUniteDonneeUtilisateur', $uniteDonneeUtilisateur->getNomUniteDonneeUtilisateur(), \PDO::PARAM_STR);
			$requete->bindValue(':symboleUniteDonneeUtilisateur', $uniteDonneeUtilisateur->getSymboleUniteDonneeUtilisateur(), \PDO::PARAM_STR);

			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$uniteDonneeUtilisateur->setIdUniteDonneeUtilisateur($this->dao->lastInsertId('id_unite_donnee_utilisateur'));
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
		$messageClient->addErreur('PDO::UniteDonneeUtilisateur : L\'objet passé en paramètre n\'est pas une instance de UniteDonneeUtilisateur');
		}
	}	
	
	//sauvegarde les modifications d'une parametre
	public function saveUniteDonneeUtilisateur($uniteDonneeUtilisateur){

		if($uniteDonneeUtilisateur instanceof UniteDonneeUtilisateur){
	
			//préparation de la requete
			$requete = $this->dao->prepare("UPDATE unite_donnee_utilisateur (nom_unite_donnee_utilisateur, symbole_unite_donnee_utilisateur) SET
					nom_unite_donnee_utilisateur = :nomUniteDonneeUtilisateur,
					symbole_unite_donnee_utilisateur = :symboleUniteDonneeUtilisateur
					WHERE id_unite_donnee_utilisateur = :idUniteDonneeUtilisateur;");

			//bind des valeurs
			$requete->bindValue(':idUniteDonneeUtilisateur', $uniteDonneeUtilisateur->getIdUniteDonneeUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':nomUniteDonneeUtilisateur', $uniteDonneeUtilisateur->getNomUniteDonneeUtilisateur(), \PDO::PARAM_STR);
			$requete->bindValue(':symboleUniteDonneeUtilisateur', $uniteDonneeUtilisateur->getSymboleUniteDonneeUtilisateur(), \PDO::PARAM_STR);


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
		$messageClient->addErreur('PDO::UniteDonneeUtilisateur : L\'objet passé en paramètre n\'est pas une instance de UniteDonneeUtilisateur');
		}
	}
	
	
	//supprime la catégorie de la base et modifie les données de toutes les parametres avec cette catégorie.
	public function deleteUniteDonneeUtilisateur($uniteDonneeUtilisateur){

		if($uniteDonneeUtilisateur instanceof UniteDonneeUtilisateur){	

			//suppression du uniteDonneeUtilisateur
			//préparation de la requete
			$requete = $this->dao->prepare("DELETE FROM unite_donnee_utilisateur WHERE id_unite_donnee_utilisateur = :idUniteDonneeUtilisateur)");

			//bind des valeurs
			$requete->bindValue(':idUniteDonneeUtilisateur', $uniteDonneeUtilisateur->getIdUniteDonneeUtilisateur(), \PDO::PARAM_INT);

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
		$messageClient->addErreur('PDO::UniteDonneeUtilisateur : L\'objet passé en paramètre n\'est pas une instance de UniteDonneeUtilisateur');
		}
	}
	
	
	// Sélectionne un uniteDonneeUtilisateur par son ID
	public function getUniteDonneeUtilisateurById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM unite_donnee_utilisateur WHERE id_unite_donnee_utilisateur = :idUniteDonneeUtilisateur");
		
		//bind des parametre
		$requete->bindValue(':idUniteDonneeUtilisateur', $id, \PDO::PARAM_INT);
		
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
			$uniteDonneeUtilisateur = $this->constructUniteDonneeUtilisateur($donnees[0]);
			return $uniteDonneeUtilisateur;
		}
	}
	
	// Sélectionne un uniteDonneeUtilisateur par son nom
	public function getUniteDonneeUtilisateurByNom($nomUniteDonneeUtilisateur){
		
		$requete = $this->dao->prepare("SELECT * FROM unite_donnee_utilisateur WHERE nom_unite_donnee_utilisateur = :nomUniteDonneeUtilisateur");
		
		//bind des parametre
		$requete->bindValue(':nomUniteDonneeUtilisateur', $nomUniteDonneeUtilisateur, \PDO::PARAM_STR);
		
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
			$uniteDonneeUtilisateur = $this->constructUniteDonneeUtilisateur($donnees[0]);
			return $uniteDonneeUtilisateur;
		}
	}


	//renvoi un tableau de toutes les uniteDonneeUtilisateurs
	public function getAllUniteDonneeUtilisateurs(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM unite_donnee_utilisateur");
		
		//execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//creation d'un tableau d'parametre
		$uniteDonneeUtilisateurs = array();
		
		//On construit l'objet parametre
		while ($donnees = $requete->fetch())
		{
			array_push($uniteDonneeUtilisateurs, $this->constructUniteDonneeUtilisateur($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $uniteDonneeUtilisateurs;
	}
	
	//renvoi un tableau de catégorie a partir de l'index début jusqu'a debut + quantite
	public function getUniteDonneeUtilisateursBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM unite_donnee_utilisateur LIMIT :debut,:quantite");
		
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
		//creation d'un tableau d'parametre
		$uniteDonneeUtilisateurs = array();
		
		//On construit l'objet parametre
		while ($donnees = $requete->fetch())
		{
			array_push($uniteDonneeUtilisateurs, $this->constructUniteDonneeUtilisateur($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $uniteDonneeUtilisateurs;
	}
	
	//retourne le nombre de uniteDonneeUtilisateur dans la base
	public function getNumberOfUniteDonneeUtilisateur(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreUniteDonneeUtilisateur FROM unite_donnee_utilisateur');
		
		//execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//creation d'un tableau d'une parametre
		
		$donnees = $requete->fetch();
		
		//On libère la requete
		$requete->closeCursor();
		
		return $donnees['nombreUniteDonneeUtilisateur'];
	}
	
	// Permet de contruire un objet UniteDonneeUtilisateur à partir de ses données de la base.
	protected function constructUniteDonneeUtilisateur($donnee){
		
		$data = [
		'idUniteDonneeUtilisateur' => $donnee['id_unite_donnee_utilisateur'],
		'nomUniteDonneeUtilisateur' => $donnee['nom_unite_donnee_utilisateur'],
		'symboleUniteDonneeUtilisateur' => $donnee['symbole_unite_donnee_utilisateur']
		];
		return new UniteDonneeUtilisateur($data);
	}
}
