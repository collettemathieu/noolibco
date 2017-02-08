<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des FonctionParametres.				  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des FonctionParametres
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\FonctionParametre;
use \Library\Models;

class PDOFonctionParametreManager extends \Library\Models\FonctionParametreManager
{

/* Définition des méthode de classe */

	//ajoute une fonctionParametre dans la base
	public function addFonctionParametre($fonctionParametre){

		 if($fonctionParametre instanceof FonctionParametre){		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO fonction_parametre VALUES (:idParametre, :idFonction, :idOrdre)");

			//bind des valeurs
			$requete->bindValue(':idFonction', $fonctionParametre->getFonction()->getIdFonction(), \PDO::PARAM_INT);
			$requete->bindValue(':idParametre', $fonctionParametre->getParametre()->getIdParametre(), \PDO::PARAM_INT);
			$requete->bindValue(':idOrdre', $fonctionParametre->getOrdre(), \PDO::PARAM_INT);
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
		$messageClient->addErreur('PDO::FonctionParametre : L\'objet passé en paramètre n\'est pas une instance de FonctionParametre');
		}
	}

	//supprime la fonctionParametre de la base et modifie les données de toutes les utilisateurs avec cette fonctionParametre.
	public function deleteFonctionParametre($fonctionParametre){

		 if($fonctionParametre instanceof FonctionParametre){	

			$requete = $this->dao->prepare("DELETE FROM fonction_parametre WHERE id_parametre = :idParametre and id_fonction = :idFonction and id_ordre = :idOrdre;");

			//bind des valeurs
			$requete->bindValue(':idFonction', $fonctionParametre->getFonction()->getIdFonction(), \PDO::PARAM_INT);
			$requete->bindValue(':idParametre', $fonctionParametre->getParametre()->getIdParametre(), \PDO::PARAM_INT);
			$requete->bindValue(':idOrdre', $fonctionParametre->getOrdre(), \PDO::PARAM_INT);
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
		$messageClient->addErreur('PDO::FonctionParametre : L\'objet passé en paramètre n\'est pas une instance de FonctionParametre');
		}
	}
	
	//selectionne une fonctionParametre par son ID
	public function getFonctionParametreById($idFonction, $idParametre, $idOrdre){
		
		$requete = $this->dao->prepare("SELECT * FROM fonction_parametre  WHERE id_parametre = :idParametre and id_fonction = :idFonction and  id_ordre = :idOrdre;");
		
		//bind des parametre
		$requete->bindValue(':idFonction', $idFonction, \PDO::PARAM_INT);
		$requete->bindValue(':idParametre', $idParametre, \PDO::PARAM_INT);
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
			$fonctionParametre = $this->constructFonctionParametre($donnees[0]);
			return $fonctionParametre;
		}
	}
	
	//renvoi un tableau de toutes les fonctionParametres
	public function getAllFonctionParametres(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM fonction_parametre");
		
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
		$fonctionParametres = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($fonctionParametres, $this->constructFonctionParametre($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $fonctionParametres;
	}

	// Retourne le dernier numéro d'ordre d'une série de paramètres parmi une fonction
	public function getLastOrdreOfParametres($idFonction){
		$requete = $this->dao->prepare('SELECT MAX(id_ordre) AS dernierNumeroOrdre FROM fonction_parametre WHERE id_fonction = :idFonction');
		
		// Bind des paramètres
		$requete->bindValue(':idFonction', $idFonction, \PDO::PARAM_INT);

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
	
	//renvoi un tableau de fonctionParametre a partir de l'index début jusqu'a debut + quantite
	public function getFonctionParametresBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM fonction_parametre LIMIT :debut,:quantite");
		
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
		$fonctionParametres = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($fonctionParametres, $this->constructFonctionParametre($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $fonctionParametres;
	}
	
	//retourne le nombre de fonctionParametre dans la base
	public function getNumberOfFonctionParametre(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreFonctionParametre FROM fonction_parametre');
		
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
		
		return $donnees['nombreFonctionParametre'];
	}
	
	//permet de contruire un objet fonctionParametre a partir des ses données de la base.
	protected function constructFonctionParametre($donnee){
		
		$pdoParametre = new PDOParametreManager($this->dao);
		$pdoFonction = new PDOFonctionManager($this->dao);
		
		$data = [
		'Fonction' => $pdoFonction->getFonctionById($donnee['id_fonction']),
		'Parametre' => $pdoParametre->getParametreById($donnee['id_parametre']),
		'Ordre' => $donnee['id_ordre']
		];
		return new FonctionParametre($data);
	}
}
