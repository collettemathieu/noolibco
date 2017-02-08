<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des Fonctions.						  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des Fonctions
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\Fonction;
use \Library\Models;
use Library\Entities\Parametre;

class PDOFonctionManager extends \Library\Models\FonctionManager
{

/* Définition des méthode de classe */

	//ajoute une fonction dans la base
	public function addFonction($fonction){

		 if($fonction instanceof Fonction){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO fonction (nom_fonction, url_fonction, extension_fonction) 
					VALUES (:nomFonction, :urlFonction, :extensionFonction)");

			//bind des valeurs
			$requete->bindValue(':nomFonction', $fonction->getNomFonction(), \PDO::PARAM_STR);
			$requete->bindValue(':urlFonction', $fonction->getUrlFonction(), \PDO::PARAM_STR);
			$requete->bindValue(':extensionFonction', $fonction->getExtensionFonction(), \PDO::PARAM_STR);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$fonction->setIdFonction($this->dao->lastInsertId('id_fonction'));
				$this->dao->commit();
			
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//On libère la requete
			$requete->closeCursor();
			return $fonction;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Fonction : L\'objet passé en paramètre n\'est pas une instance de Fonction');
		}
	}
	
	public function addParametresFromFonction($fonction){

		 if($fonction instanceof Fonction){
			if (sizeof($fonction->getParametres()) != 0){
			
				for ($i = 0 ; $i < sizeof($fonction->getParametres()) ; $i++){
					$parametre = $fonction->getParametres()[i];
					//pr�paration de la requete
					$requete = $this->dao->prepare("INSERT INTO fonction_parametre VALUES :idFonction, :idParametre, :i;");
			
					//bind des valeurs
					$requete->bindValue(':idFonction', $fonction->getIdFonction(), \PDO::PARAM_INT);
					$requete->bindValue(':idParametre', $parametre->getIdParametre(), \PDO::PARAM_INT);
					$requete->bindValue(':i', $i, \PDO::PARAM_INT);
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
		$messageClient->addErreur('PDO::Fonction : L\'objet passé en paramètre n\'est pas une instance de Fonction');
		}
	}
	
	public function addTachesFromFonction($fonction){

		 if($fonction instanceof Fonction){
				if (sizeof($fonction->getTaches()) != 0){
			
				for ($i = 0 ; $i < sizeof($fonction->getTaches()) ; $i++){
					$tache = $fonction->getTaches()[i];
					//pr�paration de la requete
					$requete = $this->dao->prepare("INSERT INTO tache_fonction VALUES :idFonction, :idTache, :i;");
			
					//bind des valeurs
					$requete->bindValue(':idFonction', $fonction->getIdFonction(), \PDO::PARAM_INT);
					$requete->bindValue(':idTache', $tache->getIdTache(), \PDO::PARAM_INT);
					$requete->bindValue(':i', $i, \PDO::PARAM_INT);
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
		$messageClient->addErreur('PDO::Fonction : L\'objet passé en paramètre n\'est pas une instance de Fonction');
		}
	}
	
	//sauvegarde les modifications d'une utilisateur
	public function saveFonction($fonction){

		 if($fonction instanceof Fonction){
	
			//préparation de la requete
			$requete = $this->dao->prepare("UPDATE fonction SET
					nom_fonction = :nomFonction,
					url_fonction = :urlFonction,
					extension_fonction = :extensionFonction
					WHERE id_fonction = :idFonction;");

			//bind des valeurs
			$requete->bindValue(':idFonction', $fonction->getIdFonction(), \PDO::PARAM_INT);
			$requete->bindValue(':nomFonction', $fonction->getNomFonction(), \PDO::PARAM_STR);
			$requete->bindValue(':urlFonction', $fonction->getUrlFonction(), \PDO::PARAM_STR);
			$requete->bindValue(':extensionFonction', $fonction->getExtensionFonction(), \PDO::PARAM_STR);

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
		$messageClient->addErreur('PDO::Fonction : L\'objet passé en paramètre n\'est pas une instance de Fonction');
		}
	}
	
	public function deleteLinkBetweenParametresFonction($fonction){

		 if($fonction instanceof Fonction){
		
			// Prééparation de la requête
			$requete = $this->dao->prepare("DELETE FROM fonction_parametre WHERE id_fonction = :idFonction;");
			
			// Bind des paramètres
			$requete->bindValue(':idFonction', $fonction->getIdFonction(), \PDO::PARAM_INT);
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
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Fonction : L\'objet passé en paramètre n\'est pas une instance de Fonction');
		}
	}
	
	public function deleteLinkBetweenTachesFonction($fonction){

		 if($fonction instanceof Fonction){
		
			$requete = $this->dao->prepare("DELETE FROM tache_fonction WHERE id_fonction = :idFonction;");
			
			//bind des valeurs
			$requete->bindValue(':idFonction', $fonction->getIdFonction(), \PDO::PARAM_INT);
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
		$messageClient->addErreur('PDO::Fonction : L\'objet passé en paramètre n\'est pas une instance de Fonction');
		}
	}

	// Suppression des paramètres en lien avec la fonction
	public function deleteParametres($fonction){

		 if($fonction instanceof Fonction){
			if (sizeof ( $fonction->getParametres () ) != 0) {
				
				foreach ( $fonction->getParametres () as $parametre ) {
					
					$pdoParametre = new PDOParametreManager ( $this->dao );
					$pdoParametre->deleteParametre( $parametre );
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Fonction : L\'objet passé en paramètre n\'est pas une instance de Fonction');
		}
	}
	
	// Supprime la fonction de la base ainsi que les liens avec ses paramètres.
	public function deleteFonction($fonction){

		 if($fonction instanceof Fonction){	

			// Suppression des liens avec Paramètres
			$this->deleteLinkBetweenParametresFonction($fonction);

			// Suppression des liens entre la fonction et la tâche
			$this->deleteLinkBetweenTachesFonction($fonction);

			// Suppression des paramètres en lien avec la fonction
			$this->deleteParametres($fonction);

			// Suppression de la fonction de la tâche
			$requete = $this->dao->prepare("DELETE FROM fonction WHERE id_fonction = :idFonction");

			//bind des valeurs
			$requete->bindValue(':idFonction', $fonction->getIdFonction(), \PDO::PARAM_INT);

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
		$messageClient->addErreur('PDO::Fonction : L\'objet passé en paramètre n\'est pas une instance de Fonction');
		}
	}
	
	//selectionne une fonction par son ID
	public function getFonctionById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM fonction WHERE id_fonction = :idFonction");
		
		//bind des parametre
		$requete->bindValue(':idFonction', $id, \PDO::PARAM_INT);
		
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
			$fonction = $this->constructFonction($donnees[0]);
			$this->putParametresInFonction($fonction);
			$this->putTachesInFonction($fonction);
			return $fonction;
		}
	}
	
	//renvoi un tableau de toutes les fonctions
	public function getAllFonctions(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM fonction");
		
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
		$fonctions = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($fonctions, $this->constructFonction($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $fonctions;
	}
	
	//renvoi un tableau de fonction a partir de l'index début jusqu'a debut + quantite
	public function getFonctionsBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM fonction LIMIT :debut,:quantite");
		
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
		$fonctions = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($fonctions, $this->constructFonction($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $fonctions;
	}
	
	//retourne le nombre de fonction dans la base
	public function getNumberOfFonction(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreFonction FROM fonction');
		
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
		
		return $donnees['nombreFonction'];
	}
	
	public function putParametresInFonction($fonction){

		 if($fonction instanceof Fonction){
			$requete = $this->dao->prepare("SELECT id_parametre FROM fonction_parametre WHERE id_fonction = :idFonction  ORDER BY id_ordre");
			
			//bind des parametre
			$requete->bindValue(':idFonction', $fonction->getIdFonction(), \PDO::PARAM_INT);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			// On appelle le manager des paramètres
			$pdoParametre = new PDOParametreManager($this->dao);
			
			// Création d'un tableau de paramètres
			while ($donnees = $requete->fetch())
			{
				$fonction->addParametre($pdoParametre->getParametreById($donnees['id_parametre']));
			}
			
			// On libère la requete
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Fonction : L\'objet passé en paramètre n\'est pas une instance de Fonction');
		}
	}
	
	public function putTachesInFonction($fonction){

		 if($fonction instanceof Fonction){
				$requete = $this->dao->prepare("SELECT id_tache FROM tache_fonction WHERE id_fonction = :idFonction ORDER BY id_ordre ");
			
			//bind des tache
			$requete->bindValue(':idFonction', $fonction->getIdFonction(), \PDO::PARAM_INT);
			
			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			// On appelle le manager des tâches
			$pdoTache = new PDOTacheManager($this->dao);
			
			// Création d'un tableau de tâches
			while ($donnees = $requete->fetch())
			{
				$fonction->addTache($pdoTache->getTacheByIdLimited($donnees['id_tache']));
			}
			
			// On libère la requete
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Fonction : L\'objet passé en paramètre n\'est pas une instance de Fonction');
		}
		
	}
	
	// Permet de contruire un objet fonction à partir de ses données de la base.
	protected function constructFonction($donnee){
		
		$data = [
		'idFonction' => $donnee['id_fonction'],
		'nomFonction' => $donnee['nom_fonction'],
		'urlFonction' => $donnee['url_fonction'],
		'extensionFonction' => $donnee['extension_fonction'],
		];
		return new Fonction($data);
	}
}
