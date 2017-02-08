<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des UtilisateurEquipes.				  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des UtilisateurEquipes
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\UtilisateurEquipe;
use \Library\Models;

class PDOUtilisateurEquipeManager extends \Library\Models\UtilisateurEquipeManager
{

/* Définition des méthode de classe */

	//ajoute une equipeUtilisateur dans la base
	public function addUtilisateurEquipe($equipeUtilisateur){

		if($equipeUtilisateur instanceof UtilisateurEquipe){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO utilisateur_equipe VALUES (:idUtilisateur, :idEquipe)");
			
			//bind des valeurs
			$requete->bindValue(':idUtilisateur', $equipeUtilisateur->getUtilisateur()->getIdUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':idEquipe', $equipeUtilisateur->getEquipe()->getIdEquipe(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::UtilisateurEquipe : L\'objet passé en paramètre n\'est pas une instance de UtilisateurEquipe');
		}
	}

	//supprime la equipeUtilisateur de la base et modifie les données de toutes les utilisateurs avec cette equipeUtilisateur.
	public function deleteUtilisateurEquipe($equipeUtilisateur){

		if($equipeUtilisateur instanceof UtilisateurEquipe){	
		
			$requete = $this->dao->prepare("DELETE FROM utilisateur_equipe WHERE id_equipe = :idEquipe and id_utilisateur = :idUtilisateur;");

			//bind des valeurs
			$requete->bindValue(':idUtilisateur', $equipeUtilisateur->getUtilisateur()->getIdUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':idEquipe', $equipeUtilisateur->getEquipe()->getIdEquipe(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::UtilisateurEquipe : L\'objet passé en paramètre n\'est pas une instance de UtilisateurEquipe');
		}
	}
	
	//selectionne une equipeUtilisateur par son ID
	public function getUtilisateurEquipeById($idUtilisateur, $idEquipe){
		
		$requete = $this->dao->prepare("SELECT * FROM utilisateur_equipe  WHERE id_equipe = :idEquipe and id_utilisateur = :idUtilisateur");
		
		//bind des parametre
		$requete->bindValue(':idUtilisateur', $idUtilisateur, \PDO::PARAM_INT);
		$requete->bindValue(':idEquipe', $idEquipe, \PDO::PARAM_INT);
		
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
			$equipeUtilisateur = $this->constructUtilisateurEquipe($donnees[0]);
			return $equipeUtilisateur;
		}
	}
	
	//renvoi un tableau de toutes les equipeUtilisateurs
	public function getAllUtilisateurEquipes(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM utilisateur_equipe");
		
		//execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//creation d'un tableau d'utilisateur
		$equipeUtilisateurs = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($equipeUtilisateurs, $this->constructUtilisateurEquipe($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $equipeUtilisateurs;
	}
	
	//renvoi un tableau de equipeUtilisateur a partir de l'index début jusqu'a debut + quantite
	public function getUtilisateurEquipesBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM utilisateur_equipe LIMIT :debut,:quantite");
		
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
		//creation d'un tableau d'utilisateur
		$equipeUtilisateurs = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($equipeUtilisateurs, $this->constructUtilisateurEquipe($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $equipeUtilisateurs;
	}
	
	//retourne le nombre de equipeUtilisateur dans la base
	public function getNumberOfUtilisateurEquipe(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreUtilisateurEquipe FROM utilisateur_equipe');
		
		//execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		
		$donnees = $requete->fetch();
		
		//On libère la requete
		$requete->closeCursor();
		
		return $donnees['nombreUtilisateurEquipe'];
	}
	
	//permet de contruire un objet equipeUtilisateur a partir des ses données de la base.
	protected function constructUtilisateurEquipe($donnee){
		
		$pdoEquipe = new PDOEquipeManager($this->dao);
		$pdoUtilisateur = new PDOUtilisateurManager($this->dao);
		
		$data = [
		'Utilisateur' => $pdoUtilisateur->getUtilisateurById($donnee['id_utilisateur']),
		'Equipe' => $pdoEquipe->getEquipeById($donnee['id_equipe'])
		];
		
		return new UtilisateurEquipe($data);
	}
}
