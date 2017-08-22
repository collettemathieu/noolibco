<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des coursGlobal. 					  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des coursGlobal
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\CoursGlobal;
use \Library\Models;

class PDOCoursGlobalManager extends \Library\Models\CoursGlobalManager
{

/* Définitions des méthodes action de la classe */

	// Ajout d'un coursGlobal à la base
	public function addCoursGlobal($coursGlobal){

		 if($coursGlobal instanceof CoursGlobal){
		
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO cours_global
					(titre_cours_global, description_cours_global, en_ligne_cours_global, date_creation_cours_global, note_cours_global, nbre_vote_cours_global, nbre_vue_cours_global, url_image_cours_global, url_image_miniature_cours_global, url_titre_cours_global, id_categorie) 
					VALUES (:titreCoursGlobal, :descriptionCoursGlobal, :enLigneCoursGlobal, CURDATE(), :noteCoursGlobal, :nbreVoteCoursGlobal, :nbreVueCoursGlobal, :urlImageCoursGlobal, :urlImageMiniatureCoursGlobal, :urlTitreCoursGlobal, :idCategorie)");
			
			//bind des valeurs
			$requete->bindValue(':titreCoursGlobal', $coursGlobal->getTitreCoursGlobal(), \PDO::PARAM_STR);
			$requete->bindValue(':descriptionCoursGlobal', $coursGlobal->getDescriptionCoursGlobal(), \PDO::PARAM_STR);
			$requete->bindValue(':enLigneCoursGlobal', $coursGlobal->getEnLigneCoursGlobal(), \PDO::PARAM_BOOL);
			$requete->bindValue(':noteCoursGlobal', $coursGlobal->getNoteCoursGlobal(), \PDO::PARAM_STR);
			$requete->bindValue(':nbreVoteCoursGlobal', $coursGlobal->getNbreVoteCoursGlobal(), \PDO::PARAM_INT);
			$requete->bindValue(':nbreVueCoursGlobal', $coursGlobal->getNbreVueCoursGlobal(), \PDO::PARAM_INT);
			$requete->bindValue(':urlImageCoursGlobal', $coursGlobal->getUrlImageCoursGlobal(), \PDO::PARAM_STR);
			$requete->bindValue(':urlImageMiniatureCoursGlobal', $coursGlobal->getUrlImageCoursGlobal(), \PDO::PARAM_STR);
			$requete->bindValue(':urlTitreCoursGlobal', $coursGlobal->getUrlTitreCoursGlobal(), \PDO::PARAM_STR);
			$requete->bindValue(':idCategorie', $coursGlobal->getCategorie()->getIdCategorie(), \PDO::PARAM_INT);
			
			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$coursGlobal->setIdCoursGlobal($this->dao->lastInsertId('id_cours_global'));
				$this->dao->commit();
				
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//On libère la requête
			$requete->closeCursor();
			
			// Ajout de l'auteur lié au coursGlobal dans les tables
			if ($this->addAuteurFromCoursGlobal($coursGlobal)){
				return true;
			}
			else {	
				return false;
			}
		
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::CoursGlobal : L\'objet passé en paramètre n\'est pas une instance de CoursGlobal');
		}
	}
	

	//Ajout des liens entre auteur et coursGlobal.
	public function addAuteurFromCoursGlobal($coursGlobal){
		
		if($coursGlobal instanceof CoursGlobal){
				
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT IGNORE INTO cours_global_utilisateur (id_utilisateur, id_cours_global) VALUES (:idUtilisateur, :idCoursGlobal)");
			
			//bind des valeurs
			$requete->bindValue(':idUtilisateur', $coursGlobal->getAuteur()->getIdUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':idCoursGlobal', $coursGlobal->getIdCoursGlobal(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::CoursGlobal : L\'objet passé en paramètre n\'est pas une instance de CoursGlobal');
		}
	}


	// Sauvegarde les modifications d'un coursGlobal
	public function saveCoursGlobal($coursGlobal){

		 if($coursGlobal instanceof CoursGlobal){
	
			//préparation de la requête
			$requete = $this->dao->prepare("UPDATE cours_global
					SET titre_cours_global = :titreCoursGlobal, 
					description_cours_global = :descriptionCoursGlobal, 
					note_cours_global = :noteCoursGlobal, 
					nbre_vote_cours_global = :nbreVoteCoursGlobal, 
					nbre_vue_cours_global = :nbreVueCoursGlobal,
					url_image_cours_global = :urlImageCoursGlobal,
					url_image_miniature_cours_global = :urlImageMiniatureCoursGlobal,
					url_titre_cours_global = :urlTitreCoursGlobal,
					id_categorie = :idCategorie
					WHERE id_cours_global = :idCoursGlobal;");

			//bind des valeurs
			$requete->bindValue(':idCoursGlobal', $coursGlobal->getIdCoursGlobal(), \PDO::PARAM_INT);
			$requete->bindValue(':titreCoursGlobal', $coursGlobal->getTitreCoursGlobal(), \PDO::PARAM_STR);
			$requete->bindValue(':descriptionCoursGlobal', $coursGlobal->getDescriptionCoursGlobal(), \PDO::PARAM_STR);
			$requete->bindValue(':noteCoursGlobal', $coursGlobal->getNoteCoursGlobal(), \PDO::PARAM_STR);
			$requete->bindValue(':nbreVoteCoursGlobal', $coursGlobal->getNbreVoteCoursGlobal(), \PDO::PARAM_INT);
			$requete->bindValue(':nbreVueCoursGlobal', $coursGlobal->getNbreVueCoursGlobal(), \PDO::PARAM_INT);
			$requete->bindValue(':urlImageCoursGlobal', $coursGlobal->getUrlImageCoursGlobal(), \PDO::PARAM_STR);
			$requete->bindValue(':urlImageMiniatureCoursGlobal', $coursGlobal->getUrlImageMiniatureCoursGlobal(), \PDO::PARAM_STR);
			$requete->bindValue(':urlTitreCoursGlobal', $coursGlobal->getUrlTitreCoursGlobal(), \PDO::PARAM_STR);
			$requete->bindValue(':idCategorie', $coursGlobal->getCategorie()->getIdCategorie(), \PDO::PARAM_INT);

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

			// Ajout de l'auteur lié au coursGlobal dans les tables
			if ($this->addAuteurFromCoursGlobal($coursGlobal)){
				return true;
			}
			else {
				return false;
			}
			
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::CoursGlobal : L\'objet passé en paramètre n\'est pas une instance de CoursGlobal');
		}
	}

	// Publier/Dépublier d'un coursGlobal
	public function publishCoursGlobal($coursGlobal){

		 if($coursGlobal instanceof CoursGlobal){
	
			//préparation de la requête
			$requete = $this->dao->prepare("UPDATE cours_global
					SET en_ligne_cours_global = :enLigneCoursGlobal
					WHERE id_cours_global = :idCoursGlobal;");

			//bind des valeurs
			$requete->bindValue(':idCoursGlobal', $coursGlobal->getIdCoursGlobal(), \PDO::PARAM_INT);
			$requete->bindValue(':enLigneCoursGlobal', $coursGlobal->getEnLigneCoursGlobal(), \PDO::PARAM_BOOL);
			
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
			$messageClient->addErreur('PDO::CoursGlobal : L\'objet passé en paramètre n\'est pas une instance de CoursGlobal');
		}
	}
	
	// Supprimer le lien entre l'auteur et le coursGlobal associé
	public function deleteLinkbetweenCoursGlobalAuteur($coursGlobal){

		 if($coursGlobal instanceof CoursGlobal){
			//préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM cours_global_utilisateur WHERE id_cours_global = :idCoursGlobal;");

			//bind des valeurs
			$requete->bindValue(':idCoursGlobal', $coursGlobal->getIdCoursGlobal(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::CoursGlobal : L\'objet passé en paramètre n\'est pas une instance de CoursGlobal');
		}
	}

	// Supprimer le lien entre les cours et le coursGlobal associé
	public function deleteLinkbetweenCoursGlobalCours($coursGlobal){

		 if($coursGlobal instanceof CoursGlobal){
			//préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM cours_cours_global WHERE id_cours_global = :idCoursGlobal;");

			//bind des valeurs
			$requete->bindValue(':idCoursGlobal', $coursGlobal->getIdCoursGlobal(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::CoursGlobal : L\'objet passé en paramètre n\'est pas une instance de CoursGlobal');
		}
	}
	
	// Supprime un coursGlobal de la base.
	public function deleteCoursGlobal($coursGlobal){

		 if($coursGlobal instanceof CoursGlobal){	

			// Suppression des liens
			// Suppression de la liaison entre l'auteur et le coursGlobal
			$this->deleteLinkbetweenCoursGlobalAuteur($coursGlobal);

			// Suppression de la liaison entre les cours et le coursGlobal
			$this->deleteLinkbetweenCoursGlobalCours($coursGlobal);

			// Suppression du coursGlobal
			$requete = $this->dao->prepare("DELETE FROM cours_global WHERE id_cours_global = :idCoursGlobal");

			//bind des valeurs
			$requete->bindValue(':idCoursGlobal', $coursGlobal->getIdCoursGlobal(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::CoursGlobal : L\'objet passé en paramètre n\'est pas une instance de CoursGlobal');
		}
	}
	
	
	// Sélection d'un coursGlobal par son ID
	public function getCoursGlobalById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM cours_global WHERE id_cours_global = :idCoursGlobal");
		
		//bind des paramètres
		$requete->bindValue(':idCoursGlobal', $id, \PDO::PARAM_INT);
		
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
		
		if ($donnees === false) {
			return false;
		}else {
			$coursGlobal = $this->constructCoursGlobal($donnees[0]);
			return $coursGlobal;
		}
	}

	// Sélection d'un coursGlobal par son titre encodé en URL
	public function getCoursGlobalByUrlTitle($urlTitreCoursGlobal){
		
		$requete = $this->dao->prepare("SELECT * FROM cours_global WHERE url_titre_cours_global = :urlTitreCoursGlobal");
		
		//bind des paramètres
		$requete->bindValue(':urlTitreCoursGlobal', $urlTitreCoursGlobal, \PDO::PARAM_STR);
		
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
			$coursGlobal = $this->constructCoursGlobal($donnees[0]);
			return $coursGlobal;
		}
	}

	
	// Renvoi un tableau de toutes les coursGlobal
	public function getAllCoursGlobal(){
		
		//préparation de la requête
		$requete = $this->dao->prepare("SELECT * FROM cours_global ORDER BY date_creation_cours_global DESC");
		
		//exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//création d'un tableau de coursGlobal
		$coursGlobal = array();
		
		//On construit l'objet coursGlobal
		while ($donnees = $requete->fetch())
		{
			array_push($coursGlobal, $this->constructCoursGlobal($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $coursGlobal;
	}
	
	// Renvoi un tableau de coursGlobal à partir de l'index début jusqu'à debut + quantité
	public function getCoursGlobalBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM cours_global LIMIT :debut,:quantite");
		
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
		//création d'un tableau de coursGlobal
		$coursGlobal = array();
		
		//On construit l'objet coursGlobal
		while ($donnees = $requete->fetch())
		{
			array_push($coursGlobal, $this->constructCoursGlobal($donnees));
		}
		
		//On libère la requête
		$requete->closeCursor();
		
		return $coursGlobal;
	}
	
	// Place l'auteur associé au coursGlobal dans l'objet coursGlobal
	public function putAuteurInCoursGlobal($coursGlobal){

		 if($coursGlobal instanceof CoursGlobal){
	
			$requete = $this->dao->prepare("SELECT * FROM cours_global_utilisateur WHERE id_cours_global = :idCoursGlobal");
		
			//bind des paramètres
			$requete->bindValue(':idCoursGlobal', $coursGlobal->getIdCoursGlobal(), \PDO::PARAM_INT);
			
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

			// Appel du pdo Utilisateur
			$pdoUtilisateur = new PDOUtilisateurManager($this->dao);

			//On met à jour l'objet coursGlobal
			$coursGlobal->setAuteur($pdoUtilisateur->getUtilisateurById($donnees['id_utilisateur']));
		
			//On libère la requête
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::CoursGlobal : L\'objet passé en paramètre n\'est pas une instance de CoursGlobal');
		}
	}

	// Place les cours associés au coursGlobal dans l'objet coursGlobal
	public function putCoursInCoursGlobal($coursGlobal){

		 if($coursGlobal instanceof CoursGlobal){
	
			$requete = $this->dao->prepare("SELECT * FROM cours_cours_global WHERE id_cours_global = :idCoursGlobal");
		
			//bind des paramètres
			$requete->bindValue(':idCoursGlobal', $coursGlobal->getIdCoursGlobal(), \PDO::PARAM_INT);
			
			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}

			//Insertion des cours dans le cours global
			$pdoCours = new PDOCoursManager($this->dao);
			
			//On construit l'objet mot-clé
			while ($donnees = $requete->fetch())
			{
				$coursGlobal->addCours($pdoCours->getCoursById($donnees['id_cours']));
			}
			//On libère la requête
			$requete->closeCursor();

			return $coursGlobal;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::CoursGlobal : L\'objet passé en paramètre n\'est pas une instance de CoursGlobal');
		}
	}
	
	// Retourne le nombre de coursGlobal dans la base
	public function getNumberOfCoursGlobal(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreCoursGlobal FROM cours_global');
		
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
		
		return $donnees['nombreCoursGlobal'];
	}
	
	// Permet de contruire un objet coursGlobal à partir des données de la base.
	protected function constructCoursGlobal($donnee){
		
		$pdoCategorie = new PDOCategorieManager($this->dao);
	
		$data = [
			'idCoursGlobal' => $donnee['id_cours_global'],
			'titreCoursGlobal' => $donnee['titre_cours_global'],
			'descriptionCoursGlobal' => $donnee['description_cours_global'],
			'dateCreationCoursGlobal' => $donnee['date_creation_cours_global'],
			'enLigneCoursGlobal' => (bool) $donnee['en_ligne_cours_global'],
			'noteCoursGlobal' => (float) $donnee['note_cours_global'],
			'nbreVoteCoursGlobal' => (int) $donnee['nbre_vote_cours_global'],
			'nbreVueCoursGlobal' => (int) $donnee['nbre_vue_cours_global'],
			'urlImageCoursGlobal' => $donnee['url_image_cours_global'],
			'urlImageMiniatureCoursGlobal' => $donnee['url_image_miniature_cours_global'],
			'categorie' => $pdoCategorie->getCategorieById($donnee['id_categorie'])
		];
		$coursGlobal = new CoursGlobal($data);

		$this->putAuteurInCoursGlobal($coursGlobal);
		return $coursGlobal;
	}
}
