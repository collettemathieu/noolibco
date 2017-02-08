<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des typeDonneeUtilisateurs.			  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// |		   Steve Despres <despressteve@noolib.com>		     		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des typeDonneeUtilisateurs
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\TypeDonneeUtilisateur;
use \Library\Models;

class PDOTypeDonneeUtilisateurManager extends \Library\Models\TypeDonneeUtilisateurManager
{

/* Définitions des méthodes action de la classe */

	// Ajoute un typeDonneeUtilisateur dans la base
	public function addTypeDonneeUtilisateur($typeDonneeUtilisateur){

		if($typeDonneeUtilisateur instanceof TypeDonneeUtilisateur){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO type_donnee_utilisateur (nom_type_donnee_utilisateur, extension_type_donnee_utilisateur) 
					VALUES (:nomTypeDonneeUtilisateur, :extensionTypeDonneeUtilisateur)");

			//bind des valeurs
			$requete->bindValue(':nomTypeDonneeUtilisateur', $typeDonneeUtilisateur->getNomTypeDonneeUtilisateur(), \PDO::PARAM_STR);
			$requete->bindValue(':extensionTypeDonneeUtilisateur', $typeDonneeUtilisateur->getExtensionTypeDonneeUtilisateur(), \PDO::PARAM_STR);

			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$typeDonneeUtilisateur->setIdTypeDonneeUtilisateur($this->dao->lastInsertId('id_type_donnee_utilisateur'));
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
			$messageClient->addErreur('PDO::TypeDonneeUtilisateur : L\'objet passé en paramètre n\'est pas une instance de TypeDonneeUtilisateur');
		}
	}
	
	
	// Sauvegarde les modifications d'un typeDonneeUtilisateur
	public function saveTypeDonneeUtilisateur($typeDonneeUtilisateur){

		if($typeDonneeUtilisateur instanceof TypeDonneeUtilisateur){
	
			//préparation de la requete
			$requete = $this->dao->prepare("UPDATE type_donnee_utilisateur SET
					nom_type_donnee_utilisateur = :nomTypeDonneeUtilisateur,
					extension_type_donnee_utilisateur = :extensionTypeDonneeUtilisateur
					WHERE id_type_donnee_utilisateur = :idTypeDonneeUtilisateur;");

			//bind des valeurs
			$requete->bindValue(':idTypeDonneeUtilisateur', $typeDonneeUtilisateur->getIdTypeDonneeUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':nomTypeDonneeUtilisateur', $typeDonneeUtilisateur->getNomTypeDonneeUtilisateur(), \PDO::PARAM_STR);
			$requete->bindValue(':extensionTypeDonneeUtilisateur', $typeDonneeUtilisateur->getExtensionTypeDonneeUtilisateur(), \PDO::PARAM_STR);

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
		$messageClient->addErreur('PDO::TypeDonneeUtilisateur : L\'objet passé en paramètre n\'est pas une instance de TypeDonneeUtilisateur');
		}
	}
	

	// Supprime le typeDonneeUtilisateur de la base et modifie les données de toutes les parametres avec cette catégorie.
	public function deleteTypeDonneeUtilisateur($typeDonneeUtilisateur){

		if($typeDonneeUtilisateur instanceof TypeDonneeUtilisateur){	
			
			//suppression du typeDonneeUtilisateur
			//préparation de la requete
			$requete = $this->dao->prepare("DELETE FROM type_donnee_utilisateur WHERE id_type_donnee_utilisateur = :idTypeDonneeUtilisateur");

			//bind des valeurs
			$requete->bindValue(':idTypeDonneeUtilisateur', $typeDonneeUtilisateur->getIdTypeDonneeUtilisateur(), \PDO::PARAM_INT);

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
		$messageClient->addErreur('PDO::TypeDonneeUtilisateur : L\'objet passé en paramètre n\'est pas une instance de TypeDonneeUtilisateur');
		}
	}
	
	
	//selectionne un typeDonneeUtilisateur par son ID
	public function getTypeDonneeUtilisateurById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM type_donnee_utilisateur WHERE id_type_donnee_utilisateur = :idTypeDonneeUtilisateur");
		
		//bind des parametre
		$requete->bindValue(':idTypeDonneeUtilisateur', $id, \PDO::PARAM_INT);
		
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
			$typeDonneeUtilisateur = $this->constructTypeDonneeUtilisateur($donnees[0]);
			return $typeDonneeUtilisateur;
		}
	}
	
	// Sélectionne un typeDonneeUtilisateur par son nom
	public function getTypeDonneeUtilisateurByNom($nomTypeDonneeUtilisateur){
		
		$requete = $this->dao->prepare("SELECT * FROM type_donnee_utilisateur WHERE nom_type_donnee_utilisateur = :nomTypeDonneeUtilisateur");
		
		//bind des parametre
		$requete->bindValue(':nomTypeDonneeUtilisateur', $nomTypeDonneeUtilisateur, \PDO::PARAM_STR);
		
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
			$typeDonneeUtilisateur = $this->constructTypeDonneeUtilisateur($donnees[0]);
			return $typeDonneeUtilisateur;
		}
	}

	// Sélectionne un typeDonneeUtilisateur par son extension
	public function getTypeDonneeUtilisateurByExtension($extensionTypeDonneeUtilisateur){
		
		$requete = $this->dao->prepare("SELECT * FROM type_donnee_utilisateur WHERE extension_type_donnee_utilisateur = :extensionTypeDonneeUtilisateur");
		
		//bind des parametre
		$requete->bindValue(':extensionTypeDonneeUtilisateur', $extensionTypeDonneeUtilisateur, \PDO::PARAM_STR);
		
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
			$typeDonneeUtilisateur = $this->constructTypeDonneeUtilisateur($donnees[0]);
			return $typeDonneeUtilisateur;
		}
	}

	//renvoi un tableau de toutes les catégories
	public function getAllTypeDonneeUtilisateurs(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM type_donnee_utilisateur ORDER BY nom_type_donnee_utilisateur");
		
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
		$typeDonneeUtilisateurs = array();
		
		//On construit l'objet parametre
		while ($donnees = $requete->fetch())
		{
			array_push($typeDonneeUtilisateurs, $this->constructTypeDonneeUtilisateur($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $typeDonneeUtilisateurs;
	}
	
	//renvoi un tableau de catégorie a partir de l'index début jusqu'a debut + quantite
	public function getTypeDonneeUtilisateursBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM type_donnee_utilisateur LIMIT :debut,:quantite");
		
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
		$typeDonneeUtilisateurs = array();
		
		//On construit l'objet parametre
		while ($donnees = $requete->fetch())
		{
			array_push($typeDonneeUtilisateurs, $this->constructTypeDonneeUtilisateur($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $typeDonneeUtilisateurs;
	}
	
	
	//retourne le nombre de typeDonneeUtilisateur dans la base
	public function getNumberOfTypeDonneeUtilisateur(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreTypeDonneeUtilisateur FROM type_donnee_utilisateur');
		
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
		
		return $donnees['nombreTypeDonneeUtilisateur'];
	}
	
	//permet de contruire un objet catégorie a partir des ses données de la base.
	protected function constructTypeDonneeUtilisateur($donnee){
		
		$data = [
		'idTypeDonneeUtilisateur' => $donnee['id_type_donnee_utilisateur'],
		'nomTypeDonneeUtilisateur' => $donnee['nom_type_donnee_utilisateur'],
		'extensionTypeDonneeUtilisateur' => $donnee['extension_type_donnee_utilisateur']
		];
		return new TypeDonneeUtilisateur($data);
	}
}
