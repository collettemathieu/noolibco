<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des Laboratoires.					  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des Laboratoires
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\Laboratoire;
use \Library\Models;

class PDOLaboratoireManager extends \Library\Models\LaboratoireManager
{
	/* Définition des méthode de classe */
	
	//ajoute une laboratoire dans la base
	public function addLaboratoire($laboratoire){

		 if($laboratoire instanceof Laboratoire){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO laboratoire (nom_laboratoire, url_laboratoire, id_etablissement) 
					VALUES (:nomLaboratoire, :urlLaboratoire, :idEtablissement)");
			
			//bind des valeurs
			$requete->bindValue(':nomLaboratoire', $laboratoire->getNomLaboratoire(), \PDO::PARAM_STR);
			$requete->bindValue(':urlLaboratoire', $laboratoire->getUrlLaboratoire(), \PDO::PARAM_STR);
			$requete->bindValue(':idEtablissement', $laboratoire->getEtablissement()->getIdEtablissement(), \PDO::PARAM_INT);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$laboratoire->setIdLaboratoire($this->dao->lastInsertId('id_laboratoire'));
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
		$messageClient->addErreur('PDO::Laboratoire : L\'objet passé en paramètre n\'est pas une instance de Laboratoire');
		}
	}
	
	public function addEquipesFromLaboratoire($laboratoire){

		 if($laboratoire instanceof Laboratoire){
			if (sizeof($laboratoire->getEquipes()) != 0){
			
				foreach ($equipe->getEquipes() as $equipe){
						
					//pr�paration de la requete
					$requete = $this->dao->prepare("UPDATE equipe SET id_labo = :idLaboratoire WHERE id_equipe = :idEquipe;");
			
					//bind des valeurs
					$requete->bindValue(':idLaboratoire', $laboratoire->getIdLaboratoire(), \PDO::PARAM_INT);
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
		$messageClient->addErreur('PDO::Laboratoire : L\'objet passé en paramètre n\'est pas une instance de Laboratoire');
		}
	}
	
	//sauvegarde les modifications d'une utilisateur
	public function saveLaboratoire($laboratoire){

		 if($laboratoire instanceof Laboratoire){
	
			//préparation de la requete
			$requete = $this->dao->prepare("UPDATE laboratoire SET
					nom_laboratoire = :nomLaboratoire,
					url_laboratoire = :urlLaboratoire,
					id_etablissement = :idEtablissement
					WHERE id_laboratoire = :idLaboratoire;");

			//bind des valeurs
			$requete->bindValue(':idLaboratoire', $laboratoire->getIdLaboratoire(), \PDO::PARAM_INT);
			$requete->bindValue(':nomLaboratoire', $laboratoire->getNomLaboratoire(), \PDO::PARAM_STR);
			$requete->bindValue(':urlLaboratoire', $laboratoire->getUrlLaboratoire(), \PDO::PARAM_STR);
			$requete->bindValue(':idEtablissement', $laboratoire->getEtablissement()->getIdEtablissement(), \PDO::PARAM_INT);

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
		$messageClient->addErreur('PDO::Laboratoire : L\'objet passé en paramètre n\'est pas une instance de Laboratoire');
		}
	}
	
	public function deleteLinkbetweenEquipesLaboratoire($laboratoire){

		 if($laboratoire instanceof Laboratoire){
			if (sizeof($laboratoire->getEquipes()) != 0){
			
				foreach ($equipe->getEquipes() as $equipe){
						
					//pr�paration de la requete
					$requete = $this->dao->prepare("UPDATE equipe SET id_labo = null WHERE id_labo = :idLaboratoire;");
			
					//bind des valeurs
					$requete->bindValue(':idLaboratoire', $laboratoire->getIdLaboratoire(), \PDO::PARAM_INT);
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
		$messageClient->addErreur('PDO::Laboratoire : L\'objet passé en paramètre n\'est pas une instance de Laboratoire');
		}
	}
	
	//supprime la laboratoire de la base et modifie les données de toutes les utilisateurs avec cette laboratoire.
	public function deleteLaboratoire($laboratoire){

		 if($laboratoire instanceof Laboratoire){
		
			$pdoEquipe = new PDOEquipeManager($this->dao);
			
			$this->putEquipesInLaboratoire($laboratoire);
			foreach($laboratoire->getEquipes() as $equipe)
			{
				$pdoEquipe->deleteEquipe($equipe);
			}
			
			
			$requete = $this->dao->prepare("DELETE FROM laboratoire WHERE id_laboratoire = :idLaboratoire");
			
			//bind des valeurs
			$requete->bindValue(':idLaboratoire', $laboratoire->getIdLaboratoire(), \PDO::PARAM_INT);

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
		$messageClient->addErreur('PDO::Laboratoire : L\'objet passé en paramètre n\'est pas une instance de Laboratoire');
		}
	}
	
	//selectionne une laboratoire par son ID
	public function getLaboratoireById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM laboratoire WHERE id_laboratoire = :idLaboratoire");
		
		//bind des parametre
		$requete->bindValue(':idLaboratoire', $id, \PDO::PARAM_INT);
		
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
			$laboratoire = $this->constructLaboratoire($donnees[0]);
			return $laboratoire;
		}
	}
	
	public function getLaboratoireByNom($nom){
		
		$requete = $this->dao->prepare("SELECT * FROM laboratoire WHERE nom_laboratoire = :nomLaboratoire");
		
		//bind des parametre
		$requete->bindValue(':nomLaboratoire', $nom, \PDO::PARAM_STR);
		
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
			$laboratoire = $this->constructLaboratoire($donnees[0]);
			return $laboratoire;
		}
	}
	
	
	//renvoi un tableau de toutes les laboratoires
	public function getAllLaboratoires(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM laboratoire");
		
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
		$laboratoires = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($laboratoires, $this->constructLaboratoire($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $laboratoires;
	}
	
	//renvoi un tableau de laboratoire a partir de l'index début jusqu'a debut + quantite
	public function getLaboratoiresBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM laboratoire LIMIT :debut,:quantite");
		
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
		$laboratoires = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($laboratoires, $this->constructLaboratoire($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $laboratoires;
	}
	
	//retourne le nombre de laboratoire dans la base
	public function getNumberOfLaboratoire(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreLaboratoire FROM laboratoire');
		
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
		
		return $donnees['nombreLaboratoire'];
	}
	
	public function putEquipesInLaboratoire($laboratoire){

		 if($laboratoire instanceof Laboratoire){
			$requete = $this->dao->prepare("SELECT id_equipe FROM equipe WHERE id_laboratoire = :idLaboratoire");
			
			//bind des parametre
			$requete->bindValue(':idLaboratoire', $laboratoire->getIdLaboratoire(), \PDO::PARAM_INT);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			//creation d'un tableau d'application
			$pdoEquipe = new PDOEquipeManager($this->dao);
			
			//On construit l'objet application
			while ($donnees = $requete->fetch())
			{
				$laboratoire->addEquipe($pdoEquipe->getEquipeById($donnees['id_equipe']));
			}
			
			//On lib�re la requete
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Laboratoire : L\'objet passé en paramètre n\'est pas une instance de Laboratoire');
		}
		
	}
	
	//permet de contruire un objet laboratoire a partir des ses données de la base.
	protected function constructLaboratoire($donnee){
		
		$pdoEtablissement = new PDOEtablissementManager ($this->dao);
		
		$data = [
		'IdLaboratoire' => $donnee['id_laboratoire'],
		'NomLaboratoire' => $donnee['nom_laboratoire'],
		'UrlLaboratoire' => $donnee['url_laboratoire'],
		'Etablissement' => $pdoEtablissement->getEtablissementById($donnee['id_etablissement'])
		];
		return new Laboratoire($data);
	}
}
