<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des actualités. 					  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des actualités
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Actualite;
use \Library\Models;

class PDOActualiteManager extends \Library\Models\ActualiteManager
{

/* Définitions des méthodes action de la classe */

	// Ajout d'une actualité à la base
	public function addActualite($actualite){

		 if($actualite instanceof Actualite){
		
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO actualite 
					(titre_actualite, texte_actualite, en_ligne_actualite, url_image_actualite, url_lien_actualite) 
					VALUES (:titreActualite, :texteActualite, :enLigneActualite, :urlImageActualite, :urlLienActualite)");
			
			//bind des valeurs
			$requete->bindValue(':titreActualite', $actualite->getTitreActualite(), \PDO::PARAM_STR);
			$requete->bindValue(':texteActualite', $actualite->getTexteActualite(), \PDO::PARAM_STR);
			$requete->bindValue(':enLigneActualite', $actualite->getEnLigneActualite(), \PDO::PARAM_BOOL);
			$requete->bindValue(':urlImageActualite', $actualite->getUrlImageActualite(), \PDO::PARAM_STR);
			$requete->bindValue(':urlLienActualite', $actualite->getUrlLienActualite(), \PDO::PARAM_STR);
			
			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$actualite->setIdActualite($this->dao->lastInsertId('id_actualite'));
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
			$messageClient->addErreur('PDO::Actualite : L\'objet passé en paramètre n\'est pas une instance de Actualite');
		}
	}
	
	
	// Sauvegarde les modifications d'une actualité
	public function saveActualite($actualite){

		 if($actualite instanceof Actualite){
	
			//préparation de la requête
			$requete = $this->dao->prepare("UPDATE actualite
					SET titre_actualite = :titreActualite,
					texte_actualite = :texteActualite, 
					url_image_actualite = :urlImageActualite,
					url_lien_actualite = :urlLienActualite
					WHERE id_actualite = :idActualite;");

			//bind des valeurs
			$requete->bindValue(':idActualite', $actualite->getIdActualite(), \PDO::PARAM_INT);
			$requete->bindValue(':titreActualite', $actualite->getTitreActualite(), \PDO::PARAM_STR);
			$requete->bindValue(':texteActualite', $actualite->getTexteActualite(), \PDO::PARAM_STR);
			$requete->bindValue(':urlImageActualite', $actualite->getUrlImageActualite(), \PDO::PARAM_STR);
			$requete->bindValue(':urlLienActualite', $actualite->getUrlLienActualite(), \PDO::PARAM_STR);
			
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
			$messageClient->addErreur('PDO::Actualite : L\'objet passé en paramètre n\'est pas une instance de Actualite');
		}
	}

	// Publier/Dépublier d'une actualité
	public function publishActualite($actualite){

		 if($actualite instanceof Actualite){
	
			//préparation de la requête
			$requete = $this->dao->prepare("UPDATE actualite
					SET en_ligne_actualite = :enLigneActualite
					WHERE id_actualite = :idActualite;");

			//bind des valeurs
			$requete->bindValue(':idActualite', $actualite->getIdActualite(), \PDO::PARAM_INT);
			$requete->bindValue(':enLigneActualite', $actualite->getEnLigneActualite(), \PDO::PARAM_BOOL);
			
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
			$messageClient->addErreur('PDO::Actualite : L\'objet passé en paramètre n\'est pas une instance de Actualite');
		}
	}
	
	
	// Supprime une actualité de la base.
	public function deleteActualite($actualite){

		 if($actualite instanceof Actualite){	

			// Suppression de l'actualité
			$requete = $this->dao->prepare("DELETE FROM actualite WHERE id_actualite = :idActualite");

			//bind des valeurs
			$requete->bindValue(':idActualite', $actualite->getIdActualite(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Actualite : L\'objet passé en paramètre n\'est pas une instance de Actualite');
		}
	}
	
	
	// Sélection d'un actualite par son ID
	public function getActualiteById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM actualite WHERE id_actualite = :idActualite");
		
		//bind des paramètres
		$requete->bindValue(':idActualite', $id, \PDO::PARAM_INT);
		
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
			$actualite = $this->constructActualite($donnees[0]);
			return $actualite;
		}
	}

	
	// Renvoi un tableau de toutes les actualités
	public function getAllActualites(){
		
		//préparation de la requête
		$requete = $this->dao->prepare("SELECT * FROM actualite");
		
		//exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//création d'un tableau d'actualités
		$actualites = array();
		
		//On construit l'objet actualite
		while ($donnees = $requete->fetch())
		{
			array_push($actualites, $this->constructActualite($donnees));
		}
		
		//On libère la requête
		$requete->closeCursor();
		
		return $actualites;
	}
	
	// Renvoi un tableau d'actualités à partir de l'index début jusqu'à debut + quantité
	public function getActualitesBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM actualite LIMIT :debut,:quantite");
		
		//bind des paramètres
		$requete->bindValue(':debut', $debut, \PDO::PARAM_INT);
		$requete->bindValue(':quantite', $quantite, \PDO::PARAM_INT);
		
		//exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//création d'un tableau d'actualités
		$actualites = array();
		
		//On construit l'objet actualite
		while ($donnees = $requete->fetch())
		{
			array_push($actualites, $this->constructActualite($donnees));
		}
		
		//On libère la requête
		$requete->closeCursor();
		
		return $actualites;
	}
	
	
	// Retourne le nombre d'actualités dans la base
	public function getNumberOfActualites(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreActualites FROM actualite');
		
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
		
		return $donnees['nombreActualites'];
	}
	
	// Permet de contruire un objet actualité à partir des données de la base.
	protected function constructActualite($donnee){
		
		$data = [
			'idActualite' => $donnee['id_actualite'],
			'titreActualite' => $donnee['titre_actualite'],
			'texteActualite' => $donnee['texte_actualite'],
			'enLigneActualite' => (bool) $donnee['en_ligne_actualite'],
			'urlImageActualite' => $donnee['url_image_actualite'],
			'urlLienActualite' => $donnee['url_lien_actualite']
		];
		return new Actualite($data);
	}
}
