<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des Equipes.					 	  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des Equipes
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\Equipe;
use \Library\Models;

class PDOEquipeManager extends \Library\Models\EquipeManager
{

/* Définition des méthode de classe */

	//ajoute une equipe dans la base
	public function addEquipe($equipe){

		 if($equipe instanceof Equipe){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO equipe (nom_equipe, id_laboratoire) 
					VALUES (:nomEquipe, :idLaboratoire)");

			//bind des valeurs
			$requete->bindValue(':nomEquipe', $equipe->getNomEquipe(), \PDO::PARAM_STR);
			$requete->bindValue(':idLaboratoire', $equipe->getLaboratoire()->getIdLaboratoire(), \PDO::PARAM_INT);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$equipe->setIdEquipe($this->dao->lastInsertId('id_equipe'));
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
		$messageClient->addErreur('PDO::Equipe : L\'objet passé en paramètre n\'est pas une instance de Equipe');
		}

	}
	
	public function addUtilisateursFromEquipe($equipe){

		 if($equipe instanceof Equipe){
			if (sizeof($equipe->getUtilisateurs()) != 0){
			
				foreach ($equipe->getUtilisateurs() as $utilisateur){
						
					//pr�paration de la requete
					$requete = $this->dao->prepare("INSERT INTO utilisateur_equipe VALUES :idUtilisateur, :idEquipe;");
			
					//bind des valeurs
					$requete->bindValue(':idUtilisateur', $utilisateur->getIdUtilisateur(), \PDO::PARAM_INT);
					$requete->bindValue(':idEquipe', $equipe->getIdEquipe(), \PDO::PARAM_INT);
			
					//execution de la requete sinon envoi d'une erreur
					try {
						$this->dao->beginTransaction();
						$requete->execute();
						$this->dao->commit();
					} catch(PDOException $e) {
						$this->dao->rollback();
						return "Error!: " . $e->getMessage() . "</br>";
					}
			
					//On lib�re la requete
					$requete->closeCursor();
				}
			}
			return true;		
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Equipe : L\'objet passé en paramètre n\'est pas une instance de Equipe');
		}
	}
	
	//sauvegarde les modifications d'une utilisateur
	public function saveEquipe($equipe){

		 if($equipe instanceof Equipe){
	
			//préparation de la requete
			$requete = $this->dao->prepare("UPDATE equipe SET
					nom_equipe = :nomEquipe,
					id_laboratoire = :idLaboratoire
					WHERE id_equipe = :idEquipe;");

			//bind des valeurs
			$requete->bindValue(':idEquipe', $equipe->getIdEquipe(), \PDO::PARAM_INT);
			$requete->bindValue(':nomEquipe', $equipe->getNomEquipe(), \PDO::PARAM_STR);
			$requete->bindValue(':idLaboratoire', $equipe->getLaboratoire()->getIdLaboratoire(), \PDO::PARAM_INT);

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
		$messageClient->addErreur('PDO::Equipe : L\'objet passé en paramètre n\'est pas une instance de Equipe');
		}
	}
	
	public function deleteLinkbetweenUtilisateursEquipe($equipe){

		 if($equipe instanceof Equipe){
			// Préparation de la requete
			$requete = $this->dao->prepare("DELETE FROM utilisateur_equipe WHERE id_equipe = :idEquipe;");
			
			//bind des valeurs
			$requete->bindValue(':idEquipe', $equipe->getIdEquipe(), \PDO::PARAM_INT);
			
			//execution de la requete sinon envoi d'une erreur
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
		$messageClient->addErreur('PDO::Equipe : L\'objet passé en paramètre n\'est pas une instance de Equipe');
		}
	}
	
	// Supprime l'objet équipe de la BDD et modifie les données de toutes les utilisateurs avec cette equipe.
	public function deleteEquipe($equipe){

		 if($equipe instanceof Equipe){	

			// Suppression de la liaison entre les utilisateurs et l'équipe
			$this->deleteLinkbetweenUtilisateursEquipe ( $equipe );

			// Suppression de l'objet équipe
			$requete = $this->dao->prepare("DELETE FROM equipe WHERE id_equipe = :idEquipe");
			
			// Bind des valeurs
			$requete->bindValue(':idEquipe', $equipe->getIdEquipe(), \PDO::PARAM_INT);

			// Execution de la requête sinon envoi d'une erreur
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
		$messageClient->addErreur('PDO::Equipe : L\'objet passé en paramètre n\'est pas une instance de Equipe');
		}
	}
	
	// Sélectionne une equipe par son ID
	public function getEquipeById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM equipe WHERE id_equipe = :idEquipe");
		
		//bind des parametre
		$requete->bindValue(':idEquipe', $id, \PDO::PARAM_INT);
		
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
		
		if ($donnees != false){
			$equipe = $this->constructEquipe($donnees);
			return $equipe;
		}else{
			return false;
		}
	}
	
	public function getEquipeByNom($nom) {
		
		$requete = $this->dao->prepare("SELECT * FROM equipe WHERE nom_equipe = :nomEquipe");
		
		//bind des parametre
		$requete->bindValue(':nomEquipe', $nom, \PDO::PARAM_STR);
		
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
		
		$requete->closeCursor();
		
		if (count($donnees) == 0) {
			return false;
		}
		else {
			$equipe = $this->constructEquipe($donnees[0]);
			return $equipe;
		}
	}
	
	//renvoi un tableau de toutes les equipes
	public function getAllEquipes(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM equipe");
		
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
		$equipes = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($equipes, $this->constructEquipe($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $equipes;
	}
	
	//renvoi un tableau de equipe a partir de l'index début jusqu'a debut + quantite
	public function getEquipesBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM equipe LIMIT :debut,:quantite");
		
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
		$equipes = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($equipes, $this->constructEquipe($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $equipes;
	}
	
	//retourne le nombre de equipe dans la base
	public function getNumberOfEquipe(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreEquipe FROM equipe');
		
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
		
		return $donnees['nombreEquipe'];
	}
	
	public function putUtilisateursInEquipe($equipe){

		 if($equipe instanceof Equipe){
				
			// Préparation de la requete
			$requete = $this->dao->prepare("SELECT id_utilisateur FROM utilisateur_equipe WHERE id_equipe = :idEquipe;");

			//bind des valeurs
			$requete->bindValue(':idEquipe', $equipe->getIdEquipe(), \PDO::PARAM_INT);

			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}
			
			$pdoUtilisateur = new PDOUtilisateurManager($this->dao);
			
			//On construit l'objet utilisateur
			while ($donnees = $requete->fetch())
			{
				$equipe->addUtilisateur($pdoUtilisateur->getUtilisateurById($donnees[0]));
			}
				//On lib�re la requete
			$requete->closeCursor();
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Equipe : L\'objet passé en paramètre n\'est pas une instance de Equipe');
		}
	}

	// Permet de contruire un objet equipe a partir des ses données de la base.
	protected function constructEquipe($donnee){
		
		$pdoLaboratoire = new PDOLaboratoireManager ($this->dao);
		
		$data = [
		'IdEquipe' => $donnee['id_equipe'],
		'NomEquipe' => $donnee['nom_equipe'],
		'Laboratoire' => $pdoLaboratoire->getLaboratoireById($donnee['id_laboratoire']) 
		];
		
		
		return new Equipe($data);
	}
}
