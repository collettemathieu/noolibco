<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des TacheFonctions.					  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des TacheFonctions
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\TacheFonction;
use \Library\Models;

class PDOTacheFonctionManager extends \Library\Models\TacheFonctionManager
{

/* Définition des méthode de classe */

	//ajoute une TacheFonction dans la base
	public function addTacheFonction($tacheFonction){

		if($tacheFonction instanceof TacheFonction){		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO tache_fonction VALUES (:idFonction, :idTache, :idOrdre)");

			//bind des valeurs
			$requete->bindValue(':idFonction', $tacheFonction->getFonction()->getIdFonction(), \PDO::PARAM_INT);
			$requete->bindValue(':idTache', $tacheFonction->getTache()->getIdTache(), \PDO::PARAM_INT);
			$requete->bindValue(':idOrdre', $tacheFonction->getOrdre(), \PDO::PARAM_INT);
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
		$messageClient->addErreur('PDO::TacheFonction : L\'objet passé en paramètre n\'est pas une instance de TacheFonction');
		}
	}

	//supprime la tacheFonction de la base et modifie les données de toutes les utilisateurs avec cette fonctionTache.
	public function deleteTacheFonction($tacheFonction){

		if($tacheFonction instanceof TacheFonction){	

			$requete = $this->dao->prepare("DELETE FROM tache_fonction WHERE id_tache = :idTache and id_fonction = :idFonction and id_ordre = :idOrdre;");

			//bind des valeurs
			$requete->bindValue(':idFonction', $tacheFonction->getFonction()->getIdFonction(), \PDO::PARAM_INT);
			$requete->bindValue(':idTache', $tacheFonction->getTache()->getIdTache(), \PDO::PARAM_INT);
			$requete->bindValue(':idOrdre', $tacheFonction->getOrdre(), \PDO::PARAM_INT);
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
		$messageClient->addErreur('PDO::TacheFonction : L\'objet passé en paramètre n\'est pas une instance de TacheFonction');
		}
	}
	
	//selectionne une tacheFonction par son ID
	public function getTacheFonctionById($idFonction, $idTache, $idOrdre){
		
		$requete = $this->dao->prepare("SELECT * FROM tache_fonction  WHERE id_tache = :idTache and id_fonction = :idFonction and  id_ordre = :idOrdre;");
		
		// Bind des paramètres
		$requete->bindValue(':idFonction', $idFonction, \PDO::PARAM_INT);
		$requete->bindValue(':idTache', $idTache, \PDO::PARAM_INT);
		$requete->bindValue(':idOrdre', $idOrdre, \PDO::PARAM_INT);
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
			$fonctionTache = $this->constructTacheFonction($donnees[0]);
			return $fonctionTache;
		}
	}
	
	//renvoi un tableau de toutes les tacheFonctions
	public function getAllTacheFonctions(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM tache_fonction");
		
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
		$tacheFonctions = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($tacheFonctions, $this->constructTacheFonction($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $tacheFonctions;
	}
	
	//renvoi un tableau de fonctionTache a partir de l'index début jusqu'a debut + quantite
	public function getTacheFonctionsBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM tache_fonction LIMIT :debut,:quantite");
		
		//bind des tache
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
		$fonctionTaches = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($fonctionTaches, $this->constructTacheFonction($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $fonctionTaches;
	}

	// Retourne le dernier numéro d'ordre d'une série de fonctions parmi une tâche
	public function getLastOrdreOfFonctions($idTache){
		$requete = $this->dao->prepare('SELECT MAX(id_ordre) AS dernierNumeroOrdre FROM tache_fonction WHERE id_tache = :idTache');
		
		// Bind des paramètres
		$requete->bindValue(':idTache', $idTache, \PDO::PARAM_INT);

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
		
		return $donnees['dernierNumeroOrdre'];
	}
	
	//retourne le nombre de fonctionTache dans la base
	public function getNumberOfTacheFonction(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreTacheFonction FROM tache_fonction');
		
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
		
		return $donnees['nombreTacheFonction'];
	}
	
	//permet de contruire un objet tacheFonction à partir des ses données de la base.
	protected function constructTacheFonction($donnee){
		
		$pdoTache = new PDOTacheManager($this->dao);
		$pdoFonction = new PDOFonctionManager($this->dao);
		
		$data = [
		'fonction' => $pdoFonction->getFonctionById($donnee['id_fonction']),
		'tache' => $pdoTache->getTacheById($donnee['id_tache']),
		'ordre' => $donnee['id_ordre']
		];
		return new TacheFonction($data);
	}
}
