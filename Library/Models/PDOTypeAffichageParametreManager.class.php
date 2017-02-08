<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2015 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des typeAffichageParametres.		  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu Collette <collettemathieu@noolib.com>	     		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des typeAffichageParametres
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\TypeAffichageParametre;
use \Library\Models;

class PDOTypeAffichageParametreManager extends \Library\Models\TypeAffichageParametreManager
{

/* Définitions des méthodes action de la classe */

	// Ajoute un typeAffichageParametre dans la BDD
	public function addTypeAffichageParametre($typeAffichageParametre){

		if($typeAffichageParametre instanceof TypeAffichageParametre){
		
			// Préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO type_affichage_parametre (nom_type_affichage_parametre) 
					VALUES (:nomTypeAffichageParametre)");

			//bind des valeurs
			$requete->bindValue(':nomTypeAffichageParametre', $typeAffichageParametre->getNomTypeAffichageParametre(), \PDO::PARAM_STR);

			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$typeAffichageParametre->setIdTypeAffichageParametre($this->dao->lastInsertId('id_type_affichage_parametre'));
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
			$messageClient->addErreur('PDO::TypeAffichageParametre : L\'objet passé en paramètre n\'est pas une instance de TypeAffichageParametre');
		}
	}
	
	
	// Sauvegarde les modifications d'un TypeAffichageParametre
	public function saveTypeAffichageParametre($typeAffichageParametre){

		if($typeAffichageParametre instanceof TypeAffichageParametre){
	
			// Préparation de la requête
			$requete = $this->dao->prepare("UPDATE type_affichage_parametre SET
					nom_type_affichage_parametre = :nomTypeAffichageParametre
					WHERE id_type_affichage_parametre = :idTypeAffichageParametre;");

			// Bind des valeurs
			$requete->bindValue(':idTypeAffichageParametre', $typeAffichageParametre->getIdTypeAffichageParametre(), \PDO::PARAM_INT);
			$requete->bindValue(':nomTypeAffichageParametre', $typeAffichageParametre->getNomTypeAffichageParametre(), \PDO::PARAM_STR);

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
			$messageClient->addErreur('PDO::TypeAffichageParametre : L\'objet passé en paramètre n\'est pas une instance de TypeAffichageParametre');
		}
	}
	

	// Supprime le typeAffichageParametre de la BDD
	public function deleteTypeAffichageParametre($typeAffichageParametre){

		if($typeAffichageParametre instanceof TypeAffichageParametre){	
			
			// Suppression du typeAffichageParametre
			// Préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM type_affichage_parametre WHERE id_type_affichage_parametre = :idTypeAffichageParametre");

			//bind des valeurs
			$requete->bindValue(':idTypeAffichageParametre', $typeAffichageParametre->getIdTypeAffichageParametre(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::TypeAffichageParametre : L\'objet passé en paramètre n\'est pas une instance de TypeAffichageParametre');
		}
	}
	
	
	// Sélectionne un typeAffichageParametre par son ID
	public function getTypeAffichageParametreById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM type_affichage_parametre WHERE id_type_affichage_parametre = :idTypeAffichageParametre");
		
		// Bind des paramètres
		$requete->bindValue(':idTypeAffichageParametre', $id, \PDO::PARAM_INT);
		
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
			$typeAffichageParametre = $this->constructTypeAffichageParametre($donnees[0]);
			return $typeAffichageParametre;
		}
	}
	
	// Sélectionne un typeAffichageParametre par son nom
	public function getTypeAffichageParametreByNom($nomTypeAffichageParametre){
		
		$requete = $this->dao->prepare("SELECT * FROM type_affichage_parametre WHERE nom_type_affichage_parametre = :nomTypeAffichageParametre");
		
		// Bind des paramètres
		$requete->bindValue(':nomTypeAffichageParametre', $nomTypeAffichageParametre, \PDO::PARAM_STR);
		
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
			$typeAffichageParametre = $this->constructTypeAffichageParametre($donnees[0]);
			return $typeAffichageParametre;
		}
	}

	// Renvoi un tableau de toutes les typeAffichageParametres
	public function getAllTypeAffichageParametres(){
		
		// Préparation de la requête
		$requete = $this->dao->prepare("SELECT * FROM type_affichage_parametre");
		
		// Execution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		// Création d'un tableau de typeAffichageParametres
		$typeAffichageParametres = array();
		
		//On construit l'objet typeAffichageParametres
		while ($donnees = $requete->fetch())
		{
			array_push($typeAffichageParametres, $this->constructTypeAffichageParametre($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $typeAffichageParametres;
	}
	
	// Renvoi un tableau de typeAffichageParametres a partir de l'index début jusqu'a debut + quantite
	public function getTypeAffichageParametresBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM type_affichage_parametre LIMIT :debut,:quantite");
		
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
		// Création d'un tableau de typeAffichageParametres
		$typeAffichageParametres = array();
		
		// On construit l'objet typeAffichageParametres
		while ($donnees = $requete->fetch())
		{
			array_push($typeAffichageParametres, $this->constructTypeAffichageParametre($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $typeAffichageParametres;
	}
	
	
	// Retourne le nombre de typeAffichageParametre dans la BDD
	public function getNumberOfTypeAffichageParametre(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreTypeAffichageParametre FROM type_affichage_parametre');
		
		// Execution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		// Creation d'un tableau de données
		$donnees = $requete->fetch();
		
		// On libère la requête
		$requete->closeCursor();
		
		return $donnees['nombreTypeAffichageParametre'];
	}
	
	// Permet de contruire un objet typeAffichageParametre a partir des ses données de la BDD.
	protected function constructTypeAffichageParametre($donnee){
		
		$data = [
		'idTypeAffichageParametre' => $donnee['id_type_affichage_parametre'],
		'nomTypeAffichageParametre' => $donnee['nom_type_affichage_parametre']
		];
		return new TypeAffichageParametre($data);
	}
}
