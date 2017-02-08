<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2015 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des TacheTypeDonneeUtilisateur.		  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>				  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des TacheTypeDonneeUtilisateur
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\TacheTypeDonneeUtilisateur;
use \Library\Models;

class PDOTacheTypeDonneeUtilisateurManager extends \Library\Models\TacheTypeDonneeUtilisateurManager
{

/* Définition des méthodes de la classe */

	// Ajoute une TacheTypeDonneeUtilisateur dans la BDD
	public function addTacheTypeDonneeUtilisateur($tacheTypeDonneeUtilisateur){

		if($tacheTypeDonneeUtilisateur instanceof TacheTypeDonneeUtilisateur){
		
			// Préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO tache_type_donnee_utilisateur VALUES (:idTache, :idTypeDonneeUtilisateur, :idOrdre, :idUniteDonneeUtilisateur, :description)");

			// Bind des valeurs
			$requete->bindValue(':idTypeDonneeUtilisateur', $tacheTypeDonneeUtilisateur->getTypeDonneeUtilisateur()->getIdTypeDonneeUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':idTache', $tacheTypeDonneeUtilisateur->getTache()->getIdTache(), \PDO::PARAM_INT);
			$requete->bindValue(':idOrdre', $tacheTypeDonneeUtilisateur->getOrdre(), \PDO::PARAM_INT);
			$requete->bindValue(':idUniteDonneeUtilisateur', $tacheTypeDonneeUtilisateur->getUniteDonneeUtilisateur()->getIdUniteDonneeUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':description', $tacheTypeDonneeUtilisateur->getDescription(), \PDO::PARAM_STR);

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
			$messageClient->addErreur('PDO::TacheTypeDonneeUtilisateur : L\'objet passé en paramètre n\'est pas une instance de TacheTypeDonneeUtilisateur.');
		}
	}

	// Supprime la TacheTypeDonneeUtilisateur de la BDD.
	public function deleteTacheTypeDonneeUtilisateur($tacheTypeDonneeUtilisateur){

		if($tacheTypeDonneeUtilisateur instanceof TacheTypeDonneeUtilisateur){	

			$requete = $this->dao->prepare("DELETE FROM tache_type_donnee_utilisateur WHERE id_tache = :idTache and id_type_donnee_utilisateur = :idTypeDonneeUtilisateur and id_ordre = :idOrdre;");

			// Bind des valeurs
			$requete->bindValue(':idTypeDonneeUtilisateur', $tacheTypeDonneeUtilisateur->getTypeDonneeUtilisateur()->getIdTypeDonneeUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':idTache', $tacheTypeDonneeUtilisateur->getTache()->getIdTache(), \PDO::PARAM_INT);
			$requete->bindValue(':idOrdre', $tacheTypeDonneeUtilisateur->getOrdre(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::TacheTypeDonneeUtilisateur : L\'objet passé en paramètre n\'est pas une instance de TacheTypeDonneeUtilisateur.');
		}
	}

	
	// Sélectionne une TacheTypeDonneeUtilisateur par son ID
	public function getTacheTypeDonneeUtilisateurById($idTache, $idTypeDonneeUtilisateur, $idOrdre){
		
		$requete = $this->dao->prepare("SELECT * FROM tache_type_donnee_utilisateur  WHERE id_tache = :idTache and id_type_donnee_utilisateur = :idTypeDonneeUtilisateur and id_ordre = :idOrdre");
		
		// Bind des paramètres
		$requete->bindValue(':idTypeDonneeUtilisateur', $idTypeDonneeUtilisateur, \PDO::PARAM_INT);
		$requete->bindValue(':idTache', $idTache, \PDO::PARAM_INT);
		$requete->bindValue(':idOrdre', $idOrdre, \PDO::PARAM_INT);

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
			$tacheTypeDonneeUtilisateur = $this->constructTacheTypeDonneeUtilisateur($donnees[0]);
			return $tacheTypeDonneeUtilisateur;
		}		
	}
	
	// Retourne un tableau de toutes les TacheTypeDonneeUtilisateurs
	public function getAllTacheTypeDonneeUtilisateurs(){
		
		// Préparation de la requête
		$requete = $this->dao->prepare("SELECT * FROM tache_type_donnee_utilisateur");
		
		// Execution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		// Création d'un tableau de tacheTypeDonneeUtilisateurs 
		$tacheTypeDonneeUtilisateurs = array();
		
		// On construit l'objet tacheTypeDonneeUtilisateurs
		while ($donnees = $requete->fetch())
		{
			array_push($tacheTypeDonneeUtilisateurs, $this->constructTacheTypeDonneeUtilisateur($donnees));
		}
		
		// On libère la requete
		$requete->closeCursor();
		
		return $tacheTypeDonneeUtilisateurs;
	}
	
	// Retourne un tableau de TacheTypeDonneeUtilisateurs a partir de l'index début jusqu'a debut + quantite
	public function getTacheTypeDonneeUtilisateursBetweenIndex($debut, $quantite){

		$requete = $this->dao->prepare("SELECT * FROM tache_type_donnee_utilisateur LIMIT :debut,:quantite");
		
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
		// Création d'un tableau de tacheTypeDonneeUtilisateurs 
		$tacheTypeDonneeUtilisateurs = array();
		
		// On construit l'objet tacheTypeDonneeUtilisateurs
		while ($donnees = $requete->fetch())
		{
			array_push($tacheTypeDonneeUtilisateurs, $this->constructTacheTypeDonneeUtilisateur($donnees));
		}
		
		// On libère la requête
		$requete->closeCursor();
		
		return $tacheTypeDonneeUtilisateurs;
	}
	
	// Retourne le nombre de tacheTypeDonneeUtilisateurs dans la BDD
	public function getNumberOfTacheTypeDonneeUtilisateur(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreTacheTypeDonneeUtilisateurs FROM tache_type_donnee_utilisateur');
		
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
		
		// On libère la requete
		$requete->closeCursor();
		
		return $donnees['nombreTacheTypeDonneeUtilisateurs'];
	}
	
	// Permet de contruire un objet tacheTypeDonneeUtilisateur a partir des ses données de la BDD.
	protected function constructTacheTypeDonneeUtilisateur($donnee){
		
		$pdoTache = new PDOTacheManager($this->dao);
		$pdoTypeDonneeUtilisateur = new PDOTypeDonneeUtilisateurManager($this->dao);
		$pdoUniteDonneeUtilisateur = new PDOUniteDonneeUtilisateurManager($this->dao);
		$data = [
			'typeDonneeUtilisateur' => $pdoTypeDonneeUtilisateur->getTypeDonneeUtilisateurById($donnee['id_type_donnee_utilisateur']),
			'tache' => $pdoTache->getTacheByIdLimited($donnee['id_tache']),
			'ordre' => $donnee['id_ordre'],
			'description' => $donnee['description_tache_type_donnee_utilisateur'],
			'uniteDonneeUtilisateur' => $pdoUniteDonneeUtilisateur->getUniteDonneeUtilisateurById($donnee['id_unite_donnee_utilisateur'])
		];

		return new TacheTypeDonneeUtilisateur($data);
	}
}
