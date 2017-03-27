<?php
// +----------------------------------------------------------------------+
// | PHP Version 7 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des applications.					  |
// +----------------------------------------------------------------------+
// | Auteurs : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// |			Mathieu COLLETTE <collettemathieu@noolib.com>			  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des applications
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Application;
use \Library\Models;


class PDOApplicationManager extends \Library\Models\ApplicationManager
{

/* Définitions des méthodes action de la classe */

	//Méthode pour ajouter une application.
	public function addApplication($application){
	
		if($application instanceof Application){
		
			//préparatin de la requete d'insertion dans une base de données
			$requete = $this->dao->prepare("INSERT INTO application 
					(nom_application, variable_fixe_application, description_application, lien_application, date_soumission_application, url_logo_application, id_categorie, id_statut, id_utilisateur) 
					VALUES (:nomApplication, :variableFixeApplication, :descriptionApplication, :lienApplication, CURDATE(), :urlLogoApplication, :idCategorie, :idStatut, :idUtilisateur)");
			
			//bind des parametre
			$requete->bindValue(':nomApplication', $application->getNomApplication(), \PDO::PARAM_STR);
			$requete->bindValue(':variableFixeApplication', $application->getVariableFixeApplication(), \PDO::PARAM_STR);
			$requete->bindValue(':descriptionApplication', $application->getDescriptionApplication(), \PDO::PARAM_STR);
			//***
			$requete->bindValue(':lienApplication',$application->getLienApplication(), \PDO::PARAM_STR);
			//***
			$requete->bindValue(':urlLogoApplication', $application->getUrlLogoApplication(), \PDO::PARAM_STR);
			$requete->bindValue(':idCategorie', $application->getCategorie()->getIdCategorie(), \PDO::PARAM_INT);
			$requete->bindValue(':idStatut', $application->getStatut()->getIdStatut(), \PDO::PARAM_INT);
			$requete->bindValue(':idUtilisateur', $application->getCreateur()->getIdUtilisateur(), \PDO::PARAM_INT);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$application->setIdApplication($this->dao->lastInsertId('id_application'));
				$this->dao->commit();
				
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			
			//On libère la requete
			$requete->closeCursor();
			
			// Ajout des mot clés liés à l'application dans la table application_mot_cle
			if ($this->addFavorisFromApplication($application) and $this->addMotClesFromApplication($application) and $this->addPublicationsFromApplication($application)){
				return true;
			}
			else {
				return false;
			}
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
	}

	
	//ajout des mot-clés application.
	public function addMotClesFromApplication($application){
		
		if($application instanceof Application){
		
			if (sizeof($application->getMotCles()) != 0){
				
				foreach ($application->getMotCles() as $motCleApplications){
						
					//préparation de la requete
					$requete = $this->dao->prepare("INSERT IGNORE INTO application_mot_cle (id_mot_cle, id_application) VALUES (:idMotCle, :idApplication)");
					
					//bind des valeurs
					$requete->bindValue(':idMotCle', $motCleApplications->getIdMotCle(), \PDO::PARAM_INT);
					$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
					
					//execution de la requete sinon envoi d'une erreur
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
		$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
	}
	
	//ajout des publications à publication application.
	public function addPublicationsFromApplication($application){
	
		if($application instanceof Application){
	
			if (sizeof($application->getPublications()) != 0){
				foreach ($application->getPublications() as $publication){
						
					//péparation de la requete
					$requete = $this->dao->prepare("INSERT IGNORE INTO application_publication (id_publication, id_application) VALUES (:idPublication, :idApplication)");
						
					//bind des valeurs
					$requete->bindValue(':idPublication', $publication->getIdPublication(), \PDO::PARAM_INT);
					$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
						
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
		$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
	}
	
	//fonction d'ajout des utilisateurs favori de l'application. 
	public function addFavorisFromApplication($application){
	
	
		if($application instanceof Application){

			if (sizeof($application->getUtilisateurs()) != 0){
				foreach ($application->getUtilisateurs() as $utilisateurFavoris){
						
					//préparation de la requete
					$requete = $this->dao->prepare("INSERT IGNORE INTO favori (id_utilisateur, id_application) VALUES (:idUtilisateur, :idApplication)");
						
					//bind des valeurs
					$requete->bindValue(':idUtilisateur', $utilisateurFavoris->getIdUtilisateur(), \PDO::PARAM_INT);
					$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
						
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
		$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
	}

	public function addVersionsFromApplication($application){
	
		if($application instanceof Application){

			if (sizeof($application->getVersions()) != 0){
				foreach ($application->getVersions() as $version){
						
					//pr�paration de la requete
					$requete = $this->dao->prepare("UPDATE version SET id_application = :idApplication WHERE id_version = :idVersion;");
						
					//bind des valeurs
					$requete->bindValue(':id_version', $version->getIdVersion(), \PDO::PARAM_INT);
					$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
						
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
		$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
	}

	//Sauvegarde de tous les attributs de l'application.
	public function saveAllApplication($application){
	
		if($application instanceof Application){

			//modified by Naoures
			$requete = $this->dao->prepare("UPDATE application 
					SET nom_application = :nomApplication, 
					description_application = :descriptionApplication,
					lien_application = :lienApplication, 
					date_soumission_application = :dateSoumissionApplication, 
					date_validation_application = :dateValidationApplication, 
					date_mise_hors_service_application = :dateMiseHorsServiceApplication, 
					url_logo_application = :urlLogoApplication, 
					id_categorie = :idCategorie, 
					id_statut = :idStatut, 
					id_utilisateur = :idUtilisateur 
					WHERE id_application = :idApplication");
			//bind des parametre
			$requete->bindValue(':nomApplication', $application->getNomApplication(), \PDO::PARAM_STR);
			$requete->bindValue(':descriptionApplication', $application->getDescriptionApplication(), \PDO::PARAM_STR);
			//***
			$requete->bindValue(':lienApplication',$application->getLienApplication(), \PDO::PARAM_STR);
			//***
			$requete->bindValue(':dateSoumissionApplication', $application->getDateSoumissionApplication(), \PDO::PARAM_STR);
			$requete->bindValue(':dateValidationApplication', $application->getDateValidationApplication(), \PDO::PARAM_STR);
			$requete->bindValue(':dateMiseHorsServiceApplication', $application->getDateMiseHorsServiceApplication(), \PDO::PARAM_STR);
			$requete->bindValue(':urlLogoApplication', $application->getUrlLogoApplication(), \PDO::PARAM_STR);
			$requete->bindValue(':idCategorie', $application->getCategorie()->getIdCategorie(), \PDO::PARAM_INT);
			$requete->bindValue(':idStatut', $application->getStatut()->getIdStatut(), \PDO::PARAM_INT);
			$requete->bindValue(':idUtilisateur', $application->getCreateur()->getIdUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
			
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
			
			if ($this->addFavorisFromApplication($application) and $this->addMotClesFromApplication($application) and $this->addPublicationsFromApplication($application)){
				return true;
			}
			else {
				return false;
			}
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
			
	}

	// Sauvegarde le nom de l'application.
	public function saveNameApplication($application){
	
		if($application instanceof Application){
		
			$requete = $this->dao->prepare("UPDATE application 
					SET nom_application = :nomApplication
					WHERE id_application = :idApplication");
			//bind des parametre
			$requete->bindValue(':nomApplication', $application->getNomApplication(), \PDO::PARAM_STR);
			$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
			
			//execution de la requete sinon envoi d'une erreur
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
			$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
	}

	// Sauvegarde la description et la catégorie de l'application.
	public function saveDescriptionAndCategorieApplication($application){
	
		if($application instanceof Application){
		
			//modified bu Naoures
			$requete = $this->dao->prepare("UPDATE application 
					SET description_application = :descriptionApplication,
					    
						id_categorie = :idCategorie
					WHERE id_application = :idApplication");
			//bind des parametre
			$requete->bindValue(':descriptionApplication', $application->getDescriptionApplication(), \PDO::PARAM_STR);
			//***
			
			$requete->bindValue(':idCategorie', $application->getCategorie()->getIdCategorie(), \PDO::PARAM_INT);
			$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
			
			//execution de la requete sinon envoi d'une erreur
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
			$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
	}

	//sauvegarde des attributs dans le processus de dépôt de l'application à l'étape 2.
	public function saveStep2DepositApplication($application){
	
		if($application instanceof Application){
		
			$requete = $this->dao->prepare("UPDATE application 
					SET url_logo_application = :urlLogoApplication, 
					id_statut = :idStatut
					WHERE id_application = :idApplication");
			//bind des parametre
			$requete->bindValue(':urlLogoApplication', $application->getUrlLogoApplication(), \PDO::PARAM_STR);
			$requete->bindValue(':idStatut', $application->getStatut()->getIdStatut(), \PDO::PARAM_INT);
			$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
			
			//execution de la requete sinon envoi d'une erreur
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
			$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
	}


	//sauvegarde le statut de l'application
	public function saveStatutApplication($application){
	
		if($application instanceof Application){
		
			$requete = $this->dao->prepare("UPDATE application 
					SET id_statut = :idStatut
					WHERE id_application = :idApplication");
			//bind des parametre
			$requete->bindValue(':idStatut', $application->getStatut()->getIdStatut(), \PDO::PARAM_INT);
			$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
			
			//execution de la requete sinon envoi d'une erreur
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
			$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
	}
	
	//supprime la liaison entre mot clé et application
	public function deleteLinkBetweenMotClesApplication($application){
	
		if($application instanceof Application){
		
			$requete = $this->dao->prepare("DELETE FROM application_mot_cle WHERE id_application = :idApplication");
			//bind des parametre
		
			$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
		
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
			$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
	}
	
	// Supprime des favoris des utilisateurs, l'applications
	public function deleteLinkBetweenFavorisApplication($application){
	
		if($application instanceof Application){
		
			$requete = $this->dao->prepare("DELETE FROM favori WHERE id_application = :idApplication");
			//bind des parametre
		
			$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
		
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
			$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
	}
	
	//supprime la liaison entre les publications et l'application
	public function deleteLinkBetweenPublicationsApplication($application){
	
		if($application instanceof Application){
		
			$requete = $this->dao->prepare("DELETE FROM application_publication WHERE id_application = :idApplication");
			//bind des parametre
		
			$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
		
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
			$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
	}
	
	
	public function deleteLinkBetweenVersionsApplication($application){
	
		if($application instanceof Application){
		
			if (sizeof($application->getVersions()) != 0){
			
				foreach ($application->getVersions() as $version){
						
					//pr�paration de la requete
					$requete = $this->dao->prepare("UPDATE version SET id_application = :idApplication WHERE id_version = :idVersion;");
			
					//bind des valeurs
					$requete->bindValue(':idPublication', $publication->getIdPublication(), \PDO::PARAM_INT);
					$requete->bindValue(':idVersion', $version->getIdVersion(), \PDO::PARAM_INT);
			
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
				}
			}
			return true;
			
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
		
	}

	// Supprime le lien entre les auteurs et l'application
	public function deleteLinkBetweenApplicationAuteur($application){
	
		if($application instanceof Application){
		
			$requete = $this->dao->prepare("DELETE FROM application_auteur WHERE id_application = :idApplication");
			//bind des parametre
		
			$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
		
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
			$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
	}

	// Suppression de toutes les publications de l'application
	public function deletePublications($application){
	
		if($application instanceof Application){
			
			if (sizeof ( $application->getPublications () ) != 0) {
				
				foreach ( $application->getPublications () as $publication ) {
					
					$pdoPublication = new PDOPublicationManager ( $this->dao );
					$pdoPublication->deletePublication ( $publication );
				}
			}
			return true;
			
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
	}

	// Suppression de toutes les publications de l'application
	public function deleteVersions($application){
	
		if($application instanceof Application){
		
			if (sizeof ( $application->getVersions () ) != 0) {
				
				foreach ( $application->getVersions () as $version ) {
					
					$pdoVersion = new PDOVersionManager ( $this->dao );
					$pdoVersion->deleteVersion ( $version );
				}
			}
			return true;
			
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
	}


	// Suppression en cascade des données liés à l'application et l'application elle-même
	public function deleteApplication($application){
	
		if($application instanceof Application){
		
			// Suppression des liens avec l'application.
			// Suppression de la liaison entre les motclés et l'application
			$this->deleteLinkBetweenMotClesApplication($application);
			
			// Suppression des favoris liés a l'application
			$this->deleteLinkBetweenFavorisApplication($application);

			// Suppression des liens entre publication et application
			$this->deleteLinkBetweenPublicationsApplication($application);

			// Suppression des liens entre l'application et les auteurs
			$this->deleteLinkBetweenApplicationAuteur($application);
			
			// Supprime les publications en lien avec l'application
			$this->deletePublications($application);
			
			// Suppression des versions en lien avec l'application
			$this->deleteVersions($application);
			
			// Supprime l'application elle-même
			$requete = $this->dao->prepare("DELETE FROM application WHERE id_application = :idApplication");
			//bind des parametre

			$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
			
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
			
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}

	}
	
	// Récupère une application par son ID et la renvoit au format Application
	public function getApplicationById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM application WHERE id_application = :idApplication");
		
		//bind des parametre
		$requete->bindValue(':idApplication', $id, \PDO::PARAM_INT);
		
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
		
		//On libère la requete
		$requete->closeCursor();
		
		if (count($donnees) == 0){
			return false;
		}
		else {
			$application = $this->constructApplication($donnees[0]);
			return $application;
		}
	}

	// Retourne une liste d'applications associées au mot-clé
	public function getApplicationsByIdMotCle($idMotCle){
		
		$requete = $this->dao->prepare("SELECT * FROM application_mot_cle WHERE id_mot_cle = :idMotCle");
		
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
		
		$applications = array();
		while($donnees = $requete->fetch()){

			$application = $this->getApplicationById($donnees['id_application']);

			if($application instanceof Application){
				array_push($applications, $application);
			}

		}
		
		$requete->closeCursor();

		return $applications;

	}


	// Retourne l'application par son id avec tous les attributs complétés (publications, mots-clés, versions, etc.)
	public function getApplicationByIdWithAllParameters($id){
		$requete = $this->dao->prepare("SELECT * FROM application WHERE id_application = :idApplication");
		
		//Bind des paramètres
		$requete->bindValue(':idApplication', $id, \PDO::PARAM_INT);

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
		
		if(count($donnees) != 0){
			//On construit l'objet application
			$application = $this->constructApplication($donnees[0]);
			$this->putMotClesInApplication($application);
			$this->putPublicationsInApplication($application);
			$this->putVersionsInApplication($application);
			$this->putFavorisInApplication($application);
			$this->putAuteursInApplication($application);

			//On libre la requete
			$requete->closeCursor();
			
			return $application;
		}else{
			return false;
		}
	}

	
	public function getApplicationByName($nomApplication){
	
		$requete = $this->dao->prepare("SELECT * FROM application WHERE nom_application = :nomApplication");
	
		//bind des parametre
		$requete->bindValue(':nomApplication', $nomApplication, \PDO::PARAM_STR);
	
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
	
		//On libère la requete
		$requete->closeCursor();
	
		if (count($donnees) == 0){
			return false;
		}
		else {
			$application = $this->constructApplication($donnees[0]);
			return $application;
		}
	}
	
	/**
	*	Permet de récupérer toutes les applications
	*/
	public function getAllApplications(){
		
		$requete = $this->dao->prepare("SELECT * FROM application ORDER BY nom_application");
		
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
		$applications = array();
		
		//On construit l'objet application
		while ($donnees = $requete->fetch())
		{
			array_push($applications, $this->constructApplication($donnees));
		}

		//On libère la requete
		$requete->closeCursor();
		
		return $applications;
	}

	/**
	* Permet de récupérer la liste des applications actives
	*/
	public function getAllActiveApplications(){
		
		$requete = $this->dao->prepare("SELECT * FROM application WHERE id_statut > 4 ORDER BY nom_application");
		
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
		$applications = array();
		
		//On construit l'objet application
		while ($donnees = $requete->fetch())
		{
			array_push($applications, $this->constructApplication($donnees));
		}

		//On libère la requete
		$requete->closeCursor();
		
		return $applications;
	}
	
	/**
	*	Récupèrer le tableau des applications au fromat array(Utilisateur) à partir ;
	*/
	public function getApplicationsBetweenIndex($debut, $quantite){
		
		$requete = $this->dao->prepare("SELECT * FROM application LIMIT :debut,:quantite");
		
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
		//creation d'un tableau d'application
		$applications = array();
		
		//On construit l'objet application
		while ($donnees = $requete->fetch())
		{
			array_push($applications, $this->constructApplication($donnees));
		}

		//On lib�re la requete
		$requete->closeCursor();
		
		return $applications;
	}
	
	//m�thode pour retourner le nombre d'application dans la base de donn�e
	public function getNumberOfApplication(){
		
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreApps FROM application');
		
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
		
		$donnees = $requete->fetch();
		
		//On libère la requête
		$requete->closeCursor();	
		
		return $donnees['nombreApps'];
	}


	// Méthode pour retourner les applications de l'utilisateur
	public function getApplicationsOfUser($idUtilisateur){
		
		$requete = $this->dao->prepare("SELECT * FROM application WHERE id_utilisateur = :idUtilisateur ORDER BY nom_application");
		
		//Bind des paramètres
		$requete->bindValue(':idUtilisateur', $idUtilisateur, \PDO::PARAM_INT);

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
		$applications = array();
		
		//On construit l'objet application
		while ($donnees = $requete->fetch())
		{
			
			$application = $this->constructApplication($donnees);
			$this->putMotClesInApplication($application);
			$this->putPublicationsInApplication($application);
			$this->putVersionsInApplication($application);
			$this->putAuteursInApplication($application);
			array_push($applications, $application);
		}

		//On libre la requete
		$requete->closeCursor();
		
		return $applications;
	}



	//Récupere les mot clés liés à l'application et les inserts dans l'objet application
	public function putMotClesInApplication($application){
	
		if($application instanceof Application){
		
			//Préparation de la requetes.
			$requete = $this->dao->prepare('SELECT id_mot_cle FROM application_mot_cle WHERE id_application = :idApplication');
			
			//bind des parametres
			$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
			
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
			$pdoMotCle = new PDOMotCleManager($this->dao);
			while ($donnee = $requete->fetch())
			{
				$application->addMotCle($pdoMotCle->getMotCleById($donnee[0]));
			}
			
			//On libère la requete
			$requete->closeCursor();
			
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
			
	}
	
	//Récupère les mot favoris a l'application et les inserts dans l'objet application
	public function putFavorisInApplication($application){
	
		if($application instanceof Application){
		
			//Préparation de la requetes.
			$requete = $this->dao->prepare('SELECT id_utilisateur FROM favori WHERE id_application = :idApplication');
			
			//Bind des parametres
			$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
			
			//Execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			//Création d'un tableau d'application
			$pdoUtilisateur = new PDOUtilisateurManager($this->dao);
			while ($donnee = $requete->fetch())
			{
				$application->addUtilisateur($pdoUtilisateur->getUtilisateurById($donnee[0]));
			}
			
			//On libère la requête
			$requete->closeCursor();
			
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
			
	}
	
	//Récupère les Publications lié a l'application et les inserts dans l'objet application
	public function putPublicationsInApplication($application){
	
		if($application instanceof Application){
		
			//préparation de la requetes.
			$requete = $this->dao->prepare('SELECT id_publication FROM application_publication WHERE id_application = :idApplication');
			
			//bind des paramètres
			$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
			
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
			$pdoPublication = new PDOPublicationManager($this->dao);
			while ($donnee = $requete->fetch())
			{
				$publication = $pdoPublication->getPublicationById($donnee[0]);

				$application->addPublication($publication);
			}
			
			// On libère la requête
			$requete->closeCursor();
			
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
	}
	
	public function putCategorieInApplication($application){
	
		if($application instanceof Application){
		
			//préparation de la requetes.
			$requete = $this->dao->prepare('SELECT id_categorie FROM application WHERE id_application = :idApplication');
			
			//bind des paramètres
			$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			
			$donnee = $requete->fetch();
			
			$pdoCategorie = new PDOCategorieManager($this->dao);
			$application->setCategorie($pdoCategorie->getCategorieById($donnee[0]));
			
			
			// On libère la requête
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
	}

	public function putAuteursInApplication($application){
	
		if($application instanceof Application){
		
			// Préparation de la requetes.
			$requete = $this->dao->prepare('SELECT id_auteur FROM application_auteur WHERE id_application = :idApplication');
			
			// Bind des paramètres
			$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
			
			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			// Création d'un tableau des auteurs
			$pdoAuteur = new PDOAuteurManager($this->dao);
			while ($donnee = $requete->fetch())
			{
				$auteur = $pdoAuteur->getAuteurById($donnee[0]);

				$application->addAuteur($auteur);
			}
			
			// On libère la requête
			$requete->closeCursor();
			
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
	}
	
	public function putVersionsInApplication($application){
	
		if($application instanceof Application){
		
			// Préparation de la requête.
			$requete = $this->dao->prepare('SELECT id_version FROM version WHERE id_application = :idApplication');
			
			//bind des parametres
			$requete->bindValue(':idApplication', $application->getIdApplication(), \PDO::PARAM_INT);
			
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
			$pdoVersion = new PDOVersionManager($this->dao);
			while ($donnee = $requete->fetch())
			{
				$application->addVersion($pdoVersion->getVersionById($donnee[0]));
			}
			
			//On libère la requete
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Application : L\'objet passé en paramètre n\'est pas une instance de Application');
		
		}
		
	}

	//permet de contruire un objet application a partir des ses donn�es de la base.
	protected function constructApplication($donnee){
	
		$pdoCategorie = new PDOCategorieManager($this->dao);
	
	 	$pdoStatut = new PDOStatutApplicationManager($this->dao);
	
		$pdoUtilisateur = new PDOUtilisateurManager($this->dao);
		//modified by Naoures
		$data = [
		'idApplication' => $donnee ['id_application'],
		'nomApplication' => $donnee ['nom_application'],
		'variableFixeApplication' => $donnee ['variable_fixe_application'],
		'descriptionApplication' => $donnee ['description_application'],
		'lienApplication' => $donnee ['lien_application'],
		'dateSoumissionApplication' => $donnee ['date_soumission_application'],
		'dateValidationApplication' => $donnee ['date_validation_application'],
		'dateMiseHorsServiceApplication' => $donnee ['date_mise_hors_service_application'],
		'urlLogoApplication' => $donnee ['url_logo_application'],
		'Categorie' => $pdoCategorie->getCategorieById($donnee['id_categorie']),
		'Statut' => $pdoStatut->getStatutById($donnee['id_statut']),
		'Createur' => $pdoUtilisateur->getUtilisateurById($donnee['id_utilisateur'])
		];
		return new Application($data);
	}
}