<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des publications.					  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des publications
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Publication;
use \Library\Models;


class PDOPublicationManager extends \Library\Models\PublicationManager
{

/* Définitions des méthodes action de la classe */

	//Méthode pour ajouter une publication.

	public function addPublication($publication){

		if($publication instanceof Publication){
		
			//Préparation de la requete d'insertion dans une base de données
			$requete = $this->dao->prepare("INSERT INTO publication 
					(titre_publication, annee_publication, journal_publication, id_type_publication,
					url_publication, id_utilisateur) 
					VALUES (:titrePublication, :anneePublication, :journalPublication, :idTypePublication,
					:urlPublication, :idUtilisateur)");
			
			//bind des paramêtres
			$requete->bindValue(':titrePublication', $publication->getTitrePublication(), \PDO::PARAM_STR);
			$requete->bindValue(':anneePublication', $publication->getAnneePublication(), \PDO::PARAM_INT);
			$requete->bindValue(':journalPublication', $publication->getJournalPublication(), \PDO::PARAM_STR);
			$requete->bindValue(':urlPublication', $publication->getUrlPublication(), \PDO::PARAM_STR);
			$requete->bindValue(':idTypePublication', $publication->getTypePublication()->getIdTypePublication(), \PDO::PARAM_INT);
			$requete->bindValue(':idUtilisateur', $publication->getUtilisateur()->getIdUtilisateur(), \PDO::PARAM_INT);
			
			//execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$publication->setIdPublication($this->dao->lastInsertId('id_publication'));// Met à jour l'ID de la publication
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			
			//On libère la requete
			$requete->closeCursor();
			
			//Ajout des mot clés liés à la publication dans la table publication_mot_cle
			if($this->addUtilisateursFromPublication($publication) && $this->addApplicationsFromPublication($publication) && $this->addAuteursFromPublication($publication)){
				return true;
			}
			else {
				return false;
			}
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Publication : L\'objet passé en paramètre n\'est pas une instance de Publication');
		}
	}
	
	//Ajout des utilisateurs à la publication.
	public function addUtilisateursFromPublication($publication){

		if($publication instanceof Publication){
			if (sizeof($publication->getUtilisateurs()) != 0){
				
				foreach ($publication->getUtilisateurs() as $utilisateurPublications){
						
					//préparation de la requete
					$requete = $this->dao->prepare("INSERT IGNORE INTO publication_utilisateur_auteur (id_utilisateur, id_publication) VALUES (:idUtilisateur, :idPublication);");
					
					//bind des valeurs
					$requete->bindValue(':idUtilisateur', $utilisateurPublications->getIdUtilisateur(), \PDO::PARAM_INT);
					$requete->bindValue(':idPublication', $publication->getIdPublication(), \PDO::PARAM_INT);
					
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
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Publication : L\'objet passé en paramètre n\'est pas une instance de Publication');
		}
	}
	
	//Ajout des applications à application publication.
	public function addApplicationsFromPublication($publication){

		if($publication instanceof Publication){
			if (sizeof($publication->getApplications()) != 0){
				foreach ($publication->getApplications() as $application){
						
					//Préparation de la requete
					$requete = $this->dao->prepare("INSERT IGNORE INTO application_publication (id_publication, id_application) VALUES (:idPublication, :idApplication);");
						
					//bind des valeurs
					$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
					$requete->bindValue(':idPublication', $publication->getIdPublication(), \PDO::PARAM_INT);
						
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
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Publication : L\'objet passé en paramètre n\'est pas une instance de Publication');
		}
	}
	
	//Fonction d'ajout des utilisateurs auteur de la publication. 
	public function addAuteursFromPublication($publication){

		if($publication instanceof Publication){
			if (sizeof($publication->getAuteurs()) != 0){
				foreach ($publication->getAuteurs() as $auteur){
						
					//préparation de la requete
					$requete = $this->dao->prepare("INSERT IGNORE INTO publication_auteur (id_auteur, id_publication) VALUES (:idAuteur, :idPublication);");
						
					//bind des valeurs
					$requete->bindValue(':idAuteur', $auteur->getIdAuteur(), \PDO::PARAM_INT);
					$requete->bindValue(':idPublication', $publication->getIdPublication(), \PDO::PARAM_INT);
						
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
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Publication : L\'objet passé en paramètre n\'est pas une instance de Publication');
		}
	}
	
	//Sauvegarde des modifications de la publication.
	public function savePublication($publication){

		if($publication instanceof Publication){
		
			$requete = $this->dao->prepare("UPDATE publication 
					SET titre_publication = :titrePublication, 
					annee_publication = :anneePublication, 
					journal_publication = :journalPublication, 
					url_publication = :urlPublication, 
					id_type_publication = :idTypePublication, 
					id_utilisateur = :idUtilisateur
					WHERE id_publication = :idPublication");
			
			//bind des parametre
			$requete->bindValue(':titrePublication', $publication->getTitrePublication(), \PDO::PARAM_STR);
			$requete->bindValue(':anneePublication', $publication->getAnneePublication(), \PDO::PARAM_INT);
			$requete->bindValue(':journalPublication', $publication->getJournalPublication(), \PDO::PARAM_STR);
			$requete->bindValue(':urlPublication', $publication->getUrlPublication(), \PDO::PARAM_STR);
			$requete->bindValue(':idTypePublication', $publication->getTypePublication()->getIdTypePublication(), \PDO::PARAM_INT);
			$requete->bindValue(':idUtilisateur', $publication->getUtilisateur()->getIdUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':idPublication', $publication->getIdPublication(), \PDO::PARAM_INT);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			
			//On libère la requete
			$requete->closeCursor();
			
			if ($this->addAuteursFromPublication($publication) and $this->addUtilisateursFromPublication($publication) and $this->addApplicationsFromPublication($publication)){
				return true;
			}
			else {
				return false;
			}
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Publication : L\'objet passé en paramètre n\'est pas une instance de Publication');
		}
	}
	
	// Supprime la liaison entre l'application et sa publication
	public function deleteLinkBetweenApplicationsPublication($publication){

		if($publication instanceof Publication){
			$requete = $this->dao->prepare("DELETE FROM application_publication WHERE id_publication = :idPublication");
			//bind des parametre
		
			$requete->bindValue(':idPublication', $publication->getIdPublication(), \PDO::PARAM_INT);
		
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
				return true;
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Publication : L\'objet passé en paramètre n\'est pas une instance de Publication');
		}
	}
	
	// Supprime la liaison entre les auteurs de la publication et la publication
	public function deleteLinkBetweenAuteursPublication($publication){

		if($publication instanceof Publication){
			$requete = $this->dao->prepare("DELETE FROM publication_auteur WHERE id_publication = :idPublication");
			//bind des parametre
		
			$requete->bindValue(':idPublication', $publication->getIdPublication(), \PDO::PARAM_INT);
		
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
				return true;
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Publication : L\'objet passé en paramètre n\'est pas une instance de Publication');
		}
		
	}
	
	// Supprime la liaison entre l'utilisateur auteur de la publication et la publication
	public function deleteLinkBetweenUtilisateursPublication($publication){

		if($publication instanceof Publication){
			$requete = $this->dao->prepare("DELETE FROM publication_utilisateur_auteur WHERE id_publication = :idPublication");
			//bind des parametre
		
			$requete->bindValue(':idPublication', $publication->getIdPublication(), \PDO::PARAM_INT);
		
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
				return true;
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Publication : L\'objet passé en paramètre n\'est pas une instance de Publication');
		}
		
	}

	// Suppression de toutes les auteurs de la publication
	public function deleteAuteur($publication){

		if($publication instanceof Publication){
			if (sizeof ( $publication->getAuteurs() ) != 0) {
				
				foreach ( $publication->getAuteurs () as $auteur ) {
					// On vérifie si l'auteur n'est pas déjà inscrit dans une autre publication sinon on ne le supprime pas (encore).
					$pdoPublicationAuteur = new PDOPublicationAuteurManager ( $this->dao );
					
					if($pdoPublicationAuteur->getNumberOfAuteurInPublication($auteur->getIdAuteur()) == 0){
						$pdoAuteur = new PDOAuteurManager ( $this->dao );
						$pdoAuteur->deleteAuteur ( $auteur );
					}	
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Publication : L\'objet passé en paramètre n\'est pas une instance de Publication');
		}
	}


	
	// Suppression en cascade des données liés à la publication et à la publication elle-même
	public function deletePublication($publication){

		if($publication instanceof Publication){
		
			// Suppression des données liés à la publication.
			// Suppression du lien entre les auteurs et la publication
			$this->deleteLinkBetweenAuteursPublication($publication);
			// Suppression du lien entre les utilisateurs-auteur et la publication
			$this->deleteLinkBetweenUtilisateursPublication($publication);
			// Suppression du lien entre l'application et la publication
			$this->deleteLinkBetweenApplicationsPublication($publication);

			// Suppression des auteurs liés à la publication
			$this->deleteAuteur($publication);
			
			// Suppression de la publication elle-même
			$requete = $this->dao->prepare("DELETE FROM publication WHERE id_publication = :idPublication");
			
			// Bind des paramètres
			$requete->bindValue(':idPublication', $publication->getIdPublication(), \PDO::PARAM_INT);
			
			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			
			//On libère la requête
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Publication : L\'objet passé en paramètre n\'est pas une instance de Publication');
		}

	}
	
	// Récupération de la publication par son id
	public function getPublicationById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM publication WHERE id_publication = :idPublication");
		
		//bind des parametre
		$requete->bindValue(':idPublication', $id, \PDO::PARAM_INT);
		
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
			$publication = $this->constructPublication($donnees[0]);
			$this->putAuteursInPublication($publication);

			return $publication;
		}
	}
	
	// Récupération de la publication par son nom
	public function getPublicationByName($nomPublication){
	
		$requete = $this->dao->prepare("SELECT * FROM publication WHERE nom_publication = :nomPublication");
	
		//bind des parametre
		$requete->bindValue(':nomPublication', $nomPublication, \PDO::PARAM_STR);
	
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
			$publication = $this->constructPublication($donnees[0]);
			return $publication;
		}
	}

	// Récupération de la publication par son titre
	public function getPublicationByTitre($titrePublication){
	
		$requete = $this->dao->prepare("SELECT * FROM publication WHERE titre_publication = :titrePublication");
	
		//bind des parametre
		$requete->bindValue(':titrePublication', $titrePublication, \PDO::PARAM_INT);
	
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
			$publication = $this->constructPublication($donnees[0]);
			return $publication;
		}
	}
	
	// Récupere le tableau de toutes les publications au fromat array();
	public function getAllPublications(){
		
		$requete = $this->dao->prepare("SELECT * FROM publication");
		
		//execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//creation d'un tableau d'publication
		$publications = array();
		
		//On construit l'objet publication
		while ($donnees = $requete->fetch())
		{
			array_push($publications, $this->constructPublication($donnees));
		}

		//On libère la requete
		$requete->closeCursor();
		
		return $publications;
	}
	
	// Récupere le tableau des publications au fromat array() à partir ;
	public function getPublicationsBetweenIndex($debut, $quantite){
		
		$requete = $this->dao->prepare("SELECT * FROM publication LIMIT :debut,:quantite");
		
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
		//creation d'un tableau d'publication
		$publications = array();
		
		//On construit l'objet publication
		while ($donnees = $requete->fetch())
		{
			array_push($publications, $this->constructPublication($donnees));
		}

		//On libère la requete
		$requete->closeCursor();
		
		return $publications;
	}
	
	// Méthode pour retourner le nombre de publication dans la base de donnée
	public function getNumberOfPublication(){
		
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreApps FROM publication');
		
		//execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//creation d'un tableau d'publication
		
		$donnees = $requete->fetch();
		
		//On libère la requete
		$requete->closeCursor();	
		
		return $donnees['nombreApps'];
	}

	// Récupere les mot clés liés à la publication et les inserts dans l'objet publication
	public function putUtilisateursInPublication($publication){

		if($publication instanceof Publication){

			//Préparation de la requetes.
			$requete = $this->dao->prepare('SELECT id_utilisateur FROM publication_utilisateur_auteur WHERE id_publication = :idPublication');
			
			//bind des parametres
			$requete->bindValue(':idPublication', $publication->getIdPublication(), \PDO::PARAM_INT);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			//creation d'un tableau de publications
			$pdoUtilisateur = new PDOUtilisateurManager($this->dao);
			while ($donnee = $requete->fetch())
			{
				$publication->addUtilisateurs($pdoUtilisateur->getUtilisateurById($donnee[0]));
			}
			
			//On libère la requete
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Publication : L\'objet passé en paramètre n\'est pas une instance de Publication');
		}
	}
	
	//Récupere les mot auteurs à la publication et les inserts dans l'objet publication
	public function putAuteursInPublication($publication){

		if($publication instanceof Publication){
		
			// Préparation de la requête.
			$requete = $this->dao->prepare('SELECT id_auteur FROM publication_auteur WHERE id_publication = :idPublication');
			
			// Bind des paramètres
			$requete->bindValue(':idPublication', $publication->getIdPublication(), \PDO::PARAM_INT);
			
			// Execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			// Création d'un tableau d'auteurs
			$pdoAuteur = new PDOAuteurManager($this->dao);
			while ($donnee = $requete->fetch())
			{
				$publication->addAuteur($pdoAuteur->getAuteurById($donnee[0]));
			}
			
			//On libère la requête
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Publication : L\'objet passé en paramètre n\'est pas une instance de Publication');
		}
	}
	
	// Récupere les Applications lié à la publication et les insert dans l'objet publication
	public function putApplicationsInPublication($publication){

		if($publication instanceof Publication){
			//Préparation de la requetes.
			$requete = $this->dao->prepare('SELECT id_application FROM application_publication WHERE id_publication = :idPublication');
			
			//bind des parametres
			$requete->bindValue(':idPublication', $publication->getIdPublication(), \PDO::PARAM_INT);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			//creation d'un tableau d'publication
			$pdoApplication = new PDOApplicationManager($this->dao);
			while ($donnee = $requete->fetch())
			{
				$publication->addApplication($pdoApplication->getApplicationByIdWithAllParameters($donnee[0]));
			}
			
			//On libère la requete
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Publication : L\'objet passé en paramètre n\'est pas une instance de Publication');
		}
	}

	//permet de contruire un objet publication a partir des ses données de la base.
	protected function constructPublication($donnee){
	
		$pdoTypePublication = new PDOTypePublicationManager($this->dao);
	
		$pdoUtilisateur = new PDOUtilisateurManager($this->dao);
	
		$data = [
		'IdPublication' => $donnee[0],
		'TitrePublication' => $donnee[1],
		'AnneePublication' => $donnee[2],
		'JournalPublication' => $donnee[3],
		'UrlPublication' => $donnee[4],
		'TypePublication' => $pdoTypePublication->getTypePublicationById($donnee[5]),
		'Utilisateur' => $pdoUtilisateur->getUtilisateurById($donnee[6])
		];
		return new Publication($data);
	}
}