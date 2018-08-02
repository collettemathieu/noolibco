<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des cours. 							  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des cours
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Cours;
use \Library\Models;

class PDOCoursManager extends \Library\Models\CoursManager
{

/* Définitions des méthodes action de la classe */

	// Ajout d'un cours à la base
	public function addCours($cours){

		 if($cours instanceof Cours){
		
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO cours 
					(titre_cours, description_cours, texte_cours, references_cours, en_ligne_cours, date_creation_cours, note_cours, nbre_vote_cours, nbre_vue_cours, url_image_cours, url_image_miniature_cours, url_titre_cours, id_categorie) 
					VALUES (:titreCours, :descriptionCours, :texteCours, :referencesCours, :enLigneCours, CURDATE(), :noteCours, :nbreVoteCours, :nbreVueCours, :urlImageCours, :urlImageMiniatureCours, :urlTitreCours, :idCategorie)");
			
			//bind des valeurs
			$requete->bindValue(':titreCours', $cours->getTitreCours(), \PDO::PARAM_STR);
			$requete->bindValue(':descriptionCours', $cours->getDescriptionCours(), \PDO::PARAM_STR);
			$requete->bindValue(':texteCours', $cours->getTexteCours(), \PDO::PARAM_STR);
			$requete->bindValue(':referencesCours', $cours->getReferencesCours(), \PDO::PARAM_STR);
			$requete->bindValue(':enLigneCours', $cours->getEnLigneCours(), \PDO::PARAM_BOOL);
			$requete->bindValue(':noteCours', $cours->getNoteCours(), \PDO::PARAM_STR);
			$requete->bindValue(':nbreVoteCours', $cours->getNbreVoteCours(), \PDO::PARAM_INT);
			$requete->bindValue(':nbreVueCours', $cours->getNbreVueCours(), \PDO::PARAM_INT);
			$requete->bindValue(':urlImageCours', $cours->getUrlImageCours(), \PDO::PARAM_STR);
			$requete->bindValue(':urlImageMiniatureCours', $cours->getUrlImageCours(), \PDO::PARAM_STR);
			$requete->bindValue(':urlTitreCours', $cours->getUrlTitreCours(), \PDO::PARAM_STR);
			$requete->bindValue(':idCategorie', $cours->getCategorie()->getIdCategorie(), \PDO::PARAM_INT);
			
			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$cours->setIdCours($this->dao->lastInsertId('id_cours'));
				$this->dao->commit();
				
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//On libère la requête
			$requete->closeCursor();
			
			// Ajout des mot-clés et des utilisateurs liés au cours dans les tables
			if ($this->addMotsClesFromCours($cours) and $this->addAuteurFromCours($cours)){
				return $cours;
			}
			else {	
				return false;
			}
		
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Cours : L\'objet passé en paramètre n\'est pas une instance de Cours');
		}
	}
	
	// Ajout des mot-clés de l'cours
	public function addMotsClesFromCours($cours){

		 if($cours instanceof Cours){
			if (sizeof($cours->getMotCles()) != 0){
			
				foreach ($cours->getMotCles() as $motCle){
						
					//préparation de la requête
					$requete = $this->dao->prepare("INSERT IGNORE INTO cours_mot_cle (id_cours, id_mot_cle) VALUES (:idCours, :idMotCle)");
					
					//bind des valeurs
					$requete->bindValue(':idMotCle', $motCle->getIdMotCle(), \PDO::PARAM_INT);
					$requete->bindValue(':idCours', $cours->getIdCours(), \PDO::PARAM_INT);
			
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
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Cours : L\'objet passé en paramètre n\'est pas une instance de Cours');
		}
	}

	//Ajout des liens entre auteur et cours.
	public function addAuteurFromCours($cours){
		
		if($cours instanceof Cours){
				
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT IGNORE INTO cours_utilisateur (id_utilisateur, id_cours) VALUES (:idUtilisateur, :idCours)");
			
			//bind des valeurs
			$requete->bindValue(':idUtilisateur', $cours->getAuteur()->getIdUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':idCours', $cours->getIdCours(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::Cours : L\'objet passé en paramètre n\'est pas une instance de Cours');
		}
	}


	// Sauvegarde les modifications d'un cours
	public function saveCours($cours){

		 if($cours instanceof Cours){
	
			//préparation de la requête
			$requete = $this->dao->prepare("UPDATE cours
					SET titre_cours = :titreCours, 
					description_cours = :descriptionCours, 
					texte_cours = :texteCours, 
					references_cours = :referencesCours, 
					note_cours = :noteCours, 
					nbre_vote_cours = :nbreVoteCours, 
					nbre_vue_cours = :nbreVueCours,
					url_image_cours = :urlImageCours,
					url_image_miniature_cours = :urlImageMiniatureCours,
					url_titre_cours = :urlTitreCours,
					id_categorie = :idCategorie
					WHERE id_cours = :idCours;");

			//bind des valeurs
			$requete->bindValue(':idCours', $cours->getIdCours(), \PDO::PARAM_INT);
			$requete->bindValue(':titreCours', $cours->getTitreCours(), \PDO::PARAM_STR);
			$requete->bindValue(':descriptionCours', $cours->getDescriptionCours(), \PDO::PARAM_STR);
			$requete->bindValue(':texteCours', $cours->getTexteCours(), \PDO::PARAM_STR);
			$requete->bindValue(':referencesCours', $cours->getReferencesCours(), \PDO::PARAM_STR);
			$requete->bindValue(':noteCours', $cours->getNoteCours(), \PDO::PARAM_STR);
			$requete->bindValue(':nbreVoteCours', $cours->getNbreVoteCours(), \PDO::PARAM_INT);
			$requete->bindValue(':nbreVueCours', $cours->getNbreVueCours(), \PDO::PARAM_INT);
			$requete->bindValue(':urlImageCours', $cours->getUrlImageCours(), \PDO::PARAM_STR);
			$requete->bindValue(':urlImageMiniatureCours', $cours->getUrlImageMiniatureCours(), \PDO::PARAM_STR);
			$requete->bindValue(':urlTitreCours', $cours->getUrlTitreCours(), \PDO::PARAM_STR);
			$requete->bindValue(':idCategorie', $cours->getCategorie()->getIdCategorie(), \PDO::PARAM_INT);

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

			// Ajout des mot-clés et des utilisateurs liés au cours dans les tables
			if ($this->addMotsClesFromCours($cours) and $this->addAuteurFromCours($cours)){
				return true;
			}
			else {
				return false;
			}
			
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Cours : L\'objet passé en paramètre n\'est pas une instance de Cours');
		}
	}


	// Sauvegarde le sommaire du cours
	public function saveSommaireCours($cours){

		 if($cours instanceof Cours){
	
			//préparation de la requête
			$requete = $this->dao->prepare("UPDATE cours
					SET sommaire_cours = :sommaireCours,
						texte_cours = :texteCours
					WHERE id_cours = :idCours;");

			//bind des valeurs
			$requete->bindValue(':idCours', $cours->getIdCours(), \PDO::PARAM_INT);
			$requete->bindValue(':sommaireCours', $cours->getSommaireCours(), \PDO::PARAM_STR);
			$requete->bindValue(':texteCours', $cours->getTexteCours(), \PDO::PARAM_STR);

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
			$messageClient->addErreur('PDO::Cours : L\'objet passé en paramètre n\'est pas une instance de Cours');
		}
	}


	// Publier/Dépublier d'un cours
	public function publishCours($cours){

		 if($cours instanceof Cours){
	
			//préparation de la requête
			$requete = $this->dao->prepare("UPDATE cours
					SET en_ligne_cours = :enLigneCours
					WHERE id_cours = :idCours;");

			//bind des valeurs
			$requete->bindValue(':idCours', $cours->getIdCours(), \PDO::PARAM_INT);
			$requete->bindValue(':enLigneCours', $cours->getEnLigneCours(), \PDO::PARAM_BOOL);
			
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
			$messageClient->addErreur('PDO::Cours : L\'objet passé en paramètre n\'est pas une instance de Cours');
		}
	}
	
	// Supprimer le lien entre les mots-clés et le cours associé
	public function deleteLinkbetweenCoursMotCles($cours){

		 if($cours instanceof Cours){
			//préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM cours_mot_cle WHERE id_cours = :idCours;");

			//bind des valeurs
			$requete->bindValue(':idCours', $cours->getIdCours(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Cours : L\'objet passé en paramètre n\'est pas une instance de Cours');
		}
	}

	// Supprimer le lien entre le cours global et le cours associé
	public function deleteLinkbetweenCoursCoursGlobal($cours){

		 if($cours instanceof Cours){
			//préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM cours_cours_global WHERE id_cours = :idCours;");

			//bind des valeurs
			$requete->bindValue(':idCours', $cours->getIdCours(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Cours : L\'objet passé en paramètre n\'est pas une instance de Cours');
		}
	}

	// Supprimer le lien entre l'auteur et le cours associé
	public function deleteLinkbetweenCoursAuteur($cours){

		 if($cours instanceof Cours){
			//préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM cours_utilisateur WHERE id_cours = :idCours;");

			//bind des valeurs
			$requete->bindValue(':idCours', $cours->getIdCours(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Cours : L\'objet passé en paramètre n\'est pas une instance de Cours');
		}
	}
	
	// Supprime un cours de la base.
	public function deleteCours($cours){

		 if($cours instanceof Cours){	

			// Suppression des liens
			// Suppression de la liaison entre les motclés et le cours
			$this->deleteLinkbetweenCoursMotCles($cours);

			// Suppression de la liaison entre le cours global et le cours
			$this->deleteLinkbetweenCoursCoursGlobal($cours);

			// Suppression de la liaison entre l'auteur et le cours
			$this->deleteLinkbetweenCoursAuteur($cours);

			// Suppression de l'cours
			$requete = $this->dao->prepare("DELETE FROM cours WHERE id_cours = :idCours");

			//bind des valeurs
			$requete->bindValue(':idCours', $cours->getIdCours(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Cours : L\'objet passé en paramètre n\'est pas une instance de Cours');
		}
	}
	
	
	// Sélection d'un cours par son ID
	public function getCoursById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM cours WHERE id_cours = :idCours");
		
		//bind des paramètres
		$requete->bindValue(':idCours', $id, \PDO::PARAM_INT);
		
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
			$cours = $this->constructCours($donnees[0]);
			return $cours;
		}
	}

	// Sélection d'un cours par son mot-clé
	public function getCoursByIdMotCle($idMotCle){
		
		$requete = $this->dao->prepare("SELECT * FROM cours_mot_cle WHERE id_mot_cle = :idMotCle");
		
		//bind des paramètres
		$requete->bindValue(':idMotCle', $idMotCle, \PDO::PARAM_INT);
			
		
		//exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		
		$courss = array();
		while($donnees = $requete->fetch()){

			$cours = $this->getCoursById($donnees['id_cours']);

			if($cours instanceof Cours){
				array_push($courss, $cours);
			}

		}
		
		$requete->closeCursor();

		return $courss;
	}

	// Sélection d'un cours par son titre encodé en URL
	public function getCoursByUrlTitle($urlTitreCours){
		
		$requete = $this->dao->prepare("SELECT * FROM cours WHERE url_titre_cours = :urlTitreCours");
		
		//bind des paramètres
		$requete->bindValue(':urlTitreCours', $urlTitreCours, \PDO::PARAM_STR);
		
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
			$cours = $this->constructCours($donnees[0]);
			return $cours;
		}
	}

	
	// Renvoi un tableau de toutes les cours
	public function getAllCours(){
		
		//préparation de la requête
		$requete = $this->dao->prepare("SELECT * FROM cours ORDER BY date_creation_cours DESC");
		
		//exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//création d'un tableau de cours
		$cours = array();
		
		//On construit l'objet cours
		while ($donnees = $requete->fetch())
		{
			array_push($cours, $this->constructCours($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $cours;
	}

	// Retourne le nombre de vues de tous les cours
	public function getAllVues(){
		
		//Préparation de la requête
		$requete = $this->dao->prepare("SELECT SUM(nbre_vue_cours) AS nbVues FROM cours");
		
		//Exécution de la requête sinon envoi d'une erreur
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
		
		return $donnees[0]['nbVues'];
	}
	
	// Renvoi un tableau de cours à partir de l'index début jusqu'à debut + quantité
	public function getCoursBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM cours LIMIT :debut,:quantite");
		
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
		//création d'un tableau de cours
		$cours = array();
		
		//On construit l'objet cours
		while ($donnees = $requete->fetch())
		{
			array_push($cours, $this->constructCours($donnees));
		}
		
		//On libère la requête
		$requete->closeCursor();
		
		return $cours;
	}
	
	// Place les mots-clés associés au cours dans l'objet cours
	public function putMotsClesInCours($cours){

		 if($cours instanceof Cours){
	
			$requete = $this->dao->prepare("SELECT * FROM cours_mot_cle WHERE id_cours = :idCours");
		
			//bind des paramètres
			$requete->bindValue(':idCours', $cours->getIdCours(), \PDO::PARAM_INT);
			
			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			//création d'un tableau de mots-clés
			$pdoMotCle = new PDOMotCleManager($this->dao);
			
			//On construit l'objet mot-clé
			while ($donnees = $requete->fetch())
			{
				$cours->addMotCle($pdoMotCle->getMotCleById($donnees['id_mot_cle']));
			}
		
			//On libère la requête
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Cours : L\'objet passé en paramètre n\'est pas une instance de Cours');
		}
	}

	// Place l'auteur associé au cours dans l'objet cours
	public function putAuteurInCours($cours){

		 if($cours instanceof Cours){
	
			$requete = $this->dao->prepare("SELECT * FROM cours_utilisateur WHERE id_cours = :idCours");
		
			//bind des paramètres
			$requete->bindValue(':idCours', $cours->getIdCours(), \PDO::PARAM_INT);
			
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

			//On met à jour l'objet cours
			$cours->setAuteur($pdoUtilisateur->getUtilisateurById($donnees['id_utilisateur']));
		
			//On libère la requête
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Cours : L\'objet passé en paramètre n\'est pas une instance de Cours');
		}
	}

	// Place les commentaires associé au cours dans l'objet cours
	public function putCommentairesInCours($cours){

		 if($cours instanceof Cours){
	
			$requete = $this->dao->prepare("SELECT * FROM cours_commentaire WHERE id_cours = :idCours");
		
			//bind des paramètres
			$requete->bindValue(':idCours', $cours->getIdCours(), \PDO::PARAM_INT);
			
			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			//création d'un tableau de mots-clés
			$pdoCommentaire = new PDOCommentaireManager($this->dao);
			
			//On construit l'objet mot-clé
			while ($donnees = $requete->fetch())
			{
				$cours->addCommentaire($pdoCommentaire->getCommentaireById($donnees['id_commentaire']));
			}
		
			//On libère la requête
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Cours : L\'objet passé en paramètre n\'est pas une instance de Cours');
		}
	}

	// Place le cours global associé au cours dans l'objet cours
	public function putCoursGlobalInCours($cours){

		 if($cours instanceof Cours){
	
			$requete = $this->dao->prepare("SELECT * FROM cours_cours_global WHERE id_cours = :idCours");
		
			//bind des paramètres
			$requete->bindValue(':idCours', $cours->getIdCours(), \PDO::PARAM_INT);
			
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
			
			// Appel du pdo CoursGlobal
			if($donnees != false){
				$pdoCoursGlobal = new PDOCoursGlobalManager($this->dao);

				//On met à jour l'objet cours
				$coursGlobal = $pdoCoursGlobal->getCoursGlobalById($donnees['id_cours_global']);
				$coursGlobal = $pdoCoursGlobal->putCoursInCoursGlobal($coursGlobal);
				$cours->setCoursGlobal($coursGlobal);
				
			}
			return $cours;
			
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Cours : L\'objet passé en paramètre n\'est pas une instance de Cours');
		}
	}

	
	// Retourne le nombre de cours dans la base
	public function getNumberOfCours(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreCours FROM cours WHERE en_ligne_cours = 1');
		
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
		
		return $donnees['nombreCours'];
	}
	
	// Permet de contruire un objet cours à partir des données de la base.
	protected function constructCours($donnee){
		
		$pdoCategorie = new PDOCategorieManager($this->dao);
	
		$data = [
			'idCours' => $donnee['id_cours'],
			'titreCours' => $donnee['titre_cours'],
			'descriptionCours' => $donnee['description_cours'],
			'dateCreationCours' => $donnee['date_creation_cours'],
			'texteCours' => $donnee['texte_cours'],
			'sommaireCours' => $donnee['sommaire_cours'],
			'referencesCours' => $donnee['references_cours'],
			'enLigneCours' => (bool) $donnee['en_ligne_cours'],
			'noteCours' => (float) $donnee['note_cours'],
			'nbreVoteCours' => (int) $donnee['nbre_vote_cours'],
			'nbreVueCours' => (int) $donnee['nbre_vue_cours'],
			'urlImageCours' => $donnee['url_image_cours'],
			'urlImageMiniatureCours' => $donnee['url_image_miniature_cours'],
			'categorie' => $pdoCategorie->getCategorieById($donnee['id_categorie'])
		];
		$cours = new Cours($data);

		$this->putMotsClesInCours($cours);
		$this->putAuteurInCours($cours);
		$this->putCommentairesInCours($cours);
		return $cours;
	}
}
