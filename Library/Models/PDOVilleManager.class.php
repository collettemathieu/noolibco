<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des Villes.					  		  |
// +----------------------------------------------------------------------+
// | Ville : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des Villes
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\Ville;
use \Library\Models;

class PDOVilleManager extends \Library\Models\VilleManager
{

/* Définition des méthode de classe */

	//ajoute une ville dans la base
	public function addVille($ville){

		if($ville instanceof Ville){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO ville (nom_ville, id_pays) 
					VALUES (:nomVille, :idPays)");

			//bind des valeurs
			$requete->bindValue(':nomVille', $ville->getNomVille(), \PDO::PARAM_STR);
			$requete->bindValue(':idPays', $ville->getPays()->getIdPays(), \PDO::PARAM_INT);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$ville->setIdVille($this->dao->lastInsertId('id_ville'));
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
			$messageClient->addErreur('PDO::Ville : L\'objet passé en paramètre n\'est pas une instance de Ville');
		}
	}
	
	public function addEtablissementsFromVille($ville){

		if($ville instanceof Ville){
			if (sizeof($ville->getEtablissements()) != 0){
			
				foreach ($etablissement->getEtablissements() as $etablissement){
						
					//pr�paration de la requete
					$requete = $this->dao->prepare("UPDATE etablissement SET id_ville = :idVille WHERE id_etablissement = :idEtablissement;");
			
					//bind des valeurs
					$requete->bindValue(':idVille', $ville->getIdVille(), \PDO::PARAM_INT);
					$requete->bindValue(':idEtablissement', $etablissement->getIdEtablissement(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::Ville : L\'objet passé en paramètre n\'est pas une instance de Ville');
		}
	}
	
	//sauvegarde les modifications d'une utilisateur
	public function saveVille($ville){

		if($ville instanceof Ville){
	
			//préparation de la requete
			$requete = $this->dao->prepare("UPDATE ville SET
					nom_ville = :nomVille, 
					id_pays = :idPays
					WHERE id_ville = :idVille;");

			//bind des valeurs
			$requete->bindValue(':idVille', $ville->getIdVille(), \PDO::PARAM_INT);
			$requete->bindValue(':nomVille', $ville->getNomVille(), \PDO::PARAM_STR);
			$requete->bindValue(':idPays', $ville->getPays()->getIdPays(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Ville : L\'objet passé en paramètre n\'est pas une instance de Ville');
		}
	}
	
	public function deleteLinkbetweenEtablissementsVille($ville){

		if($ville instanceof Ville){
			if (sizeof($ville->getEtablissements()) != 0){
			
				foreach ($etablissement->getEtablissements() as $etablissement){
						
					//préparation de la requete
					$requete = $this->dao->prepare("UPDATE etablissement SET id_ville = null WHERE id_ville = :idVille;");
			
					//bind des valeurs
					$requete->bindValue(':idVille', $ville->getIdVille(), \PDO::PARAM_INT);
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
			$messageClient->addErreur('PDO::Ville : L\'objet passé en paramètre n\'est pas une instance de Ville');
		}
	}
	
	//supprime la ville de la base et modifie les données de toutes les utilisateurs avec cette ville.
	public function deleteVille($ville){

		if($ville instanceof Ville){	
		
			$this->deleteLinkbetweenEtablissementsVille($ville);
			
			$requete = $this->dao->prepare("DELETE FROM ville WHERE id_ville = :idVille");
			
			//bind des valeurs
			$requete->bindValue(':idVille', $ville->getIdVille(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::Ville : L\'objet passé en paramètre n\'est pas une instance de Ville');
		}
	}
	
	//selectionne une ville par son ID
	public function getVilleById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM ville WHERE id_ville = :idVille");
		
		//bind des parametre
		$requete->bindValue(':idVille', $id, \PDO::PARAM_INT);
		
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
			$ville = $this->constructVille($donnees[0]);
			return $ville;
		}

	}
	
	public function getVilleByNom($nom){
		
		$requete = $this->dao->prepare("SELECT * FROM ville WHERE nom_ville = :nomVille");
		
		//bind des parametre
		$requete->bindValue(':nomVille', $nom, \PDO::PARAM_STR);
		
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
			$ville = $this->constructVille($donnees[0]);
			return $ville;
		}

	}
	
	//renvoi un tableau de toutes les villes
	public function getAllVilles(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM ville ORDER BY nom_ville");
		
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
		$villes = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($villes, $this->constructVille($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $villes;
	}
	
	//renvoi un tableau de ville a partir de l'index début jusqu'a debut + quantite
	public function getVillesBetweenIndex($debut, $quantite){

		$requete = $this->dao->prepare("SELECT * FROM ville LIMIT :debut,:quantite");
		
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
		$villes = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($villes, $this->constructVille($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $villes;
	}
	
	//retourne le nombre de ville dans la base
	public function getNumberOfVille(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreVille FROM ville');
		
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
		
		return $donnees['nombreVille'];
	}
	
	public function putEtablissementsInVille($ville){

		if($ville instanceof Ville){
			$requete = $this->dao->prepare("SELECT id_etablissement FROM etablissement WHERE id_ville = :idVille");
			
			//bind des parametre
			$requete->bindValue(':idCategorie', $categorie->getIdCategorie(), \PDO::PARAM_INT);
			
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
			$pdoEtablissement = new PDOEtablissementManager($this->dao);
			
			//On construit l'objet application
			while ($donnees = $requete->fetch())
			{
				$categorie->addApplication($pdoEtablissement->getEtablissementById($donnees['id_etablissement']));
			}
			
			//On lib�re la requete
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Ville : L\'objet passé en paramètre n\'est pas une instance de Ville');
		}
		
	}
	
	//permet de contruire un objet ville a partir des ses données de la base.
	protected function constructVille($donnee){
		
		$pdoPays = new PDOPaysManager($this->dao);
		
		$data = [
		'idVille' => $donnee['id_ville']  ,
		'nomVille' => $donnee['nom_ville']  ,
		'pays' => $pdoPays->getPaysById($donnee['id_pays']) 
		];

		return new Ville($data);
	}
}
