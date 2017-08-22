<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des editos. 					 	  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des editos
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Edito;
use \Library\Models;

class PDOEditoManager extends \Library\Models\EditoManager
{

/* Définitions des méthodes action de la classe */

	// Ajout d'un edito à la base
	public function addEdito($edito){

		 if($edito instanceof Edito){
		
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO edito 
					(texte_edito, date_edito) 
					VALUES (:texteEdito, CURDATE())");
			
			//bind des valeurs
			$requete->bindValue(':texteEdito', $edito->getTexteEdito(), \PDO::PARAM_STR);

			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$edito->setIdEdito($this->dao->lastInsertId('id_edito'));
				$this->dao->commit();
				
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//On libère la requête
			$requete->closeCursor();
			
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Edito : L\'objet passé en paramètre n\'est pas une instance de Edito');
		}
	}

	// Sauvegarde les modifications d'un edito
	public function saveEdito($edito){

		 if($edito instanceof Edito){
	
			//préparation de la requête
			$requete = $this->dao->prepare("UPDATE edito
					SET texte_edito = :texteEdito
					WHERE id_edito = :idEdito;");

			//bind des valeurs
			$requete->bindValue(':idEdito', $edito->getIdEdito(), \PDO::PARAM_INT);
			$requete->bindValue(':texteEdito', $edito->getTexteEdito(), \PDO::PARAM_STR);
			
			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//on libère la requête
			$requete->closeCursor();

			return true;
			
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Edito : L\'objet passé en paramètre n\'est pas une instance de Edito');
		}
	}

	// Supprime un edito de la base.
	public function deleteEdito($edito){

		 if($edito instanceof Edito){	

			// Suppression de l'edito
			$requete = $this->dao->prepare("DELETE FROM edito WHERE id_edito = :idEdito");

			//bind des valeurs
			$requete->bindValue(':idEdito', $edito->getIdEdito(), \PDO::PARAM_INT);

			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//On libère la requête
			$requete->closeCursor();
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Edito : L\'objet passé en paramètre n\'est pas une instance de Edito');
		}
	}
	
	
	// Sélection d'un edito par son ID
	public function getEditoById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM edito WHERE id_edito = :idEdito");
		
		//bind des paramètres
		$requete->bindValue(':idEdito', $id, \PDO::PARAM_INT);
		
		//exécution de la requête sinon envoi d'une erreur
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
			$edito = $this->constructEdito($donnees[0]);
			return $edito;
		}
	}

	
	// Renvoi un tableau de toutes les editos
	public function getAllEditos(){
		
		//préparation de la requête
		$requete = $this->dao->prepare("SELECT * FROM edito");
		
		//exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//création d'un tableau des editos
		$editos = array();
		
		//On construit l'objet edito
		while ($donnees = $requete->fetch())
		{
			array_push($editos, $this->constructEdito($donnees));
		}
		
		//On libère la requête
		$requete->closeCursor();
		
		return $editos;
	}
	
	// Retourne le nombre d'editos dans la base
	public function getNumberOfEditos(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreEditos FROM edito');
		
		//exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		
		$donnees = $requete->fetch();
		
		//On libère la requête
		$requete->closeCursor();
		
		return $donnees['nombreEditos'];
	}
	
	// Permet de contruire un objet edito à partir des données de la base.
	protected function constructEdito($donnee){
		
		$data = [
			'idEdito' => $donnee['id_edito'],
			'texteEdito' => $donnee['texte_edito'],
			'dateEdito' => $donnee['date_edito']
		];

		return new Edito($data);
	}
}
