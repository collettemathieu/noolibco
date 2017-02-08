<?php
// +----------------------------------------------------------------------+
// | PHP Version 7 														  |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib 											  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des utilisateurs. 					  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// |		   Steve Despres <despressteve@noolib.com>		     		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Manager PDO des utilisateurs
 * @access : public
 * @version : 1
 */
namespace Library\Models;

use Library\Entities\Utilisateur;

class PDOUtilisateurManager extends \Library\Models\UtilisateurManager {
	
	/* Définitions des méthodes action de la classe */
	
	// Méthode pour ajouter un utilisateur.
	public function addUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
	
			// préparatin de la requete d'insertion dans une base de données
			$requete = $this->dao->prepare("INSERT INTO utilisateur
					(nom_utilisateur, prenom_utilisateur, variable_fixe_utilisateur, work_space_folder_utilisateur, mail_utilisateur,
					password_utilisateur, id_statut, date_derniere_connexion_utilisateur, 
					date_inscription_utilisateur, description_utilisateur, 
					url_photo_utilisateur, lien_page_perso_utilisateur, password_admin_utilisateur, etat_banni, url_background_utilisateur, utilisateur_active)
					VALUES (:nomUtilisateur, :prenomUtilisateur, :variableFixeUtilisateur, :workSpaceFolderUtilisateur, :mailUtilisateur, 
					:passwordUtilisateur, :idStatut, CURDATE(), CURDATE(), 
					:descriptionUtilisateur, :urlPhotoUtilisateur,
					:lienPagePersoUtilisateur, :passwordAdminUtilisateur, :etatBanni, :urlBackgroundUtilisateur, :utilisateurActive)");
			
			// Bind des paramètres
		
			$requete->bindValue ( ':nomUtilisateur', $utilisateur->getNomUtilisateur (), \PDO::PARAM_STR );
			$requete->bindValue ( ':prenomUtilisateur', $utilisateur->getPrenomUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':variableFixeUtilisateur', $utilisateur->getVariableFixeUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':mailUtilisateur', $utilisateur->getMailUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':passwordUtilisateur', $utilisateur->getPasswordUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':idStatut', $utilisateur->getStatut()->getIdStatut(), \PDO::PARAM_INT);
			$requete->bindValue ( ':descriptionUtilisateur', $utilisateur->getDescriptionUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':urlPhotoUtilisateur', $utilisateur->getUrlPhotoUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':lienPagePersoUtilisateur', $utilisateur->getLienPagePersoUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':passwordAdminUtilisateur', $utilisateur->getPasswordAdminUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':etatBanni', $utilisateur->getEtatBanniUtilisateur(), \PDO::PARAM_BOOL );
			$requete->bindValue ( ':urlBackgroundUtilisateur', $utilisateur->getUrlBackgroundUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':workSpaceFolderUtilisateur', $utilisateur->getWorkSpaceFolderUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':utilisateurActive', $utilisateur->getUtilisateurActive(), \PDO::PARAM_BOOL );
			
			// execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$utilisateur->setIdUtilisateur ( $this->dao->lastInsertId ('id_utilisateur') );
				$this->dao->commit();

			} catch ( PDOException $e ) {
				$this->dao->rollback ();
				return "Erreur!: " . $e->getMessage () . "</br>";
			}
		
			// On libère la requete
			$requete->closeCursor ();
		
			// Ajout des publications liés à l'utilisateur dans la table utilisateur_publication
			if ($this->addPublicationAuteursFromUtilisateur ( $utilisateur ) and $this->addPublicationsPublieursFromUtilisateur ( $utilisateur ) and $this->addApplicationsFromUtilisateur ( $utilisateur ) and $this->addFavorisFromUtilisateur ( $utilisateur ) and $this->addEquipesFromUtilisateur ( $utilisateur ) and $this->addDonneeUtilisateurFromUtilisateur ( $utilisateur ) and $this->addLogsFromUtilisateur ( $utilisateur )) {
				return true;
			} else {
				return false;
			}
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}
	
	public function addPublicationAuteursFromUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
			if (sizeof ( $utilisateur->getPublicationAuteurs () ) != 0) {
				
				foreach ( $utilisateur->getPublicationAuteurs () as $publication ) {
					
					// Préparation de la requête
					$requete = $this->dao->prepare ( "INSERT IGNORE INTO publication_utilisateur_auteur (id_utilisateur, id_publication) VALUES (:idUtilisateur, :idPublication)" );
					
					// bind des valeurs
					$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_INT );
					$requete->bindValue ( ':idPublication', $publication->getIdPublication (), \PDO::PARAM_INT );
					
					// execution de la requete sinon envoi d'une erreur
					try {
						$this->dao->beginTransaction ();
						$requete->execute ();
						$this->dao->commit ();
					} catch ( PDOException $e ) {
						$this->dao->rollback ();
						return "Error!: " . $e->getMessage () . "</br>";
					}
					
					// On libère la requete
					$requete->closeCursor ();
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}
	public function addPublicationsPublieursFromUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
			if (sizeof ( $utilisateur->getPublicationPublieurs () ) != 0) {
				
				foreach ( $utilisateur->getPublicationPublieurs () as $publication ) {
					
					// pr�paration de la requete
					$requete = $this->dao->prepare ( "UPDATE publication SET id_utilisateur = :idUtilisateur WHERE id_publication = :idPublication;" );
					
					// bind des valeurs
					$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_INT );
					$requete->bindValue ( ':idPublication', $publication->getIdPublication (), \PDO::PARAM_INT );
					
					// execution de la requete sinon envoi d'une erreur
					try {
						$this->dao->beginTransaction ();
						$requete->execute ();
						$this->dao->commit ();
					} catch ( PDOException $e ) {
						$this->dao->rollback ();
						return "Error!: " . $e->getMessage () . "</br>";
					}
					
					// On lib�re la requete
					$requete->closeCursor ();
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}
	public function addApplicationsFromUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
			if (sizeof ( $utilisateur->getApplications () ) != 0) {
				
				foreach ( $utilisateur->getApplications () as $application ) {
					
					// pr�paration de la requete
					$requete = $this->dao->prepare ( "UPDATE application SET id_utilisateur = :idUtilisateur WHERE id_application = :idApplication;" );
					
					// bind des valeurs
					$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_INT );
					$requete->bindValue ( ':idApplication', $application->getIdApplication (), \PDO::PARAM_INT );
					
					// execution de la requete sinon envoi d'une erreur
					try {
						$this->dao->beginTransaction ();
						$requete->execute ();
						$this->dao->commit ();
					} catch ( PDOException $e ) {
						$this->dao->rollback ();
						return "Error!: " . $e->getMessage () . "</br>";
					}
					
					// On lib�re la requete
					$requete->closeCursor ();
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}
	public function addFavorisFromUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
			if (sizeof ( $utilisateur->getFavoris () ) != 0) {
				
				foreach ( $utilisateur->getFavoris () as $application ) {
					
					// pr�paration de la requete
					$requete = $this->dao->prepare ( "INSERT IGNORE INTO favori (id_utilisateur, id_application) VALUES (:idUtilisateur, :idApplication)" );
					
					// bind des valeurs
					$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_INT );
					$requete->bindValue ( ':idApplication', $application->getIdApplication (), \PDO::PARAM_INT );
					
					// execution de la requete sinon envoi d'une erreur
					try {
						$this->dao->beginTransaction ();
						$requete->execute ();
						$this->dao->commit ();
					} catch ( PDOException $e ) {
						$this->dao->rollback ();
						return "Error!: " . $e->getMessage () . "</br>";
					}
					
					// On libère la requete
					$requete->closeCursor ();
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}
	public function addEquipesFromUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
			if (sizeof ( $utilisateur->getEquipes () ) != 0) {
				
				foreach ( $utilisateur->getEquipes () as $equipe ) {
					
					// préparation de la requete
					$requete = $this->dao->prepare ( "INSERT IGNORE INTO utilisateur_equipe (id_utilisateur, id_equipe) VALUES (:idUtilisateur, :idEquipe)" );
					
					// bind des valeurs
					$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_INT );
					$requete->bindValue ( ':idEquipe', $equipe->getIdEquipe (), \PDO::PARAM_INT );
					
					// execution de la requete sinon envoi d'une erreur
					try {
						$this->dao->beginTransaction ();
						$requete->execute ();
						$this->dao->commit ();
					} catch ( PDOException $e ) {
						$this->dao->rollback ();
						return "Error!: " . $e->getMessage () . "</br>";
					}
					
					// On libère la requete
					$requete->closeCursor ();
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}
	public function addDonneeUtilisateurFromUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
			if (sizeof ( $utilisateur->getDonneesUtilisateur () ) != 0) {
				
				foreach ( $utilisateur->getDonneesUtilisateur () as $donneeUtilisateur ) {
					
					// Préparation de la requête
					$requete = $this->dao->prepare ( "INSERT IGNORE INTO utilisateur_donnee_utilisateur (id_donnee_utilisateur, id_utilisateur) VALUES (:idDonneeUtilisateur, :idUtilisateur)" );
					
					// bind des valeurs
					$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_INT );
					$requete->bindValue ( ':idDonneeUtilisateur', $donneeUtilisateur->getIdDonneeUtilisateur (), \PDO::PARAM_INT );
					
					// execution de la requete sinon envoi d'une erreur
					try {
						$this->dao->beginTransaction ();
						$requete->execute ();
						$this->dao->commit ();
					} catch ( PDOException $e ) {
						$this->dao->rollback ();
						return "Error!: " . $e->getMessage () . "</br>";
					}
					
					// On libère la requête
					$requete->closeCursor ();
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}
	public function addLogsFromUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
			if (sizeof ( $utilisateur->getLogs () ) != 0) {
				
				foreach ( $utilisateur->getLogs () as $log ) {
					
					// pr�paration de la requete
					$requete = $this->dao->prepare ( "UPDATE log SET id_utilisateur = :idUtilisateur WHERE id_log = :idLog;" );
					
					// bind des valeurs
					$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_INT );
					$requete->bindValue ( ':idLog', $application->getIdApplication (), \PDO::PARAM_INT );
					
					// execution de la requete sinon envoi d'une erreur
					try {
						$this->dao->beginTransaction ();
						$requete->execute ();
						$this->dao->commit ();
					} catch ( PDOException $e ) {
						$this->dao->rollback ();
						return "Error!: " . $e->getMessage () . "</br>";
					}
					
					// On libère la requete
					$requete->closeCursor ();
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}
	
	// sauvegarde des modifications de l'utilisateur.
	public function saveUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
			$requete = $this->dao->prepare ( "UPDATE utilisateur 
					SET nom_utilisateur = :nomUtilisateur, 
					prenom_utilisateur = :prenomUtilisateur, 
					mail_utilisateur = :mailUtilisateur, 
					password_utilisateur = :passwordUtilisateur,
					id_statut = :idStatut,
					date_derniere_connexion_utilisateur = :dateDerniereConnexionUtilisateur, 
					date_inscription_utilisateur = :dateInscriptionUtilisateur, 
					description_utilisateur = :descriptionUtilisateur, 
					url_photo_utilisateur = :urlPhotoUtilisateur,
					lien_page_perso_utilisateur = :lienPagePersoUtilisateur,
					password_admin_utilisateur = :passwordAdminUtilisateur, 
					etat_banni = :etatBanni, 
					url_background_utilisateur = :urlBackgroundUtilisateur,
					utilisateur_active = :UtilisateurActive
					WHERE id_utilisateur = :idUtilisateur" );
			
			// Bind des paramètres
			$requete->bindValue ( ':nomUtilisateur', $utilisateur->getNomUtilisateur (), \PDO::PARAM_STR );
			$requete->bindValue ( ':prenomUtilisateur', $utilisateur->getPrenomUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':mailUtilisateur', $utilisateur->getMailUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':passwordUtilisateur', $utilisateur->getPasswordUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':idStatut', $utilisateur->getStatut()->getIdStatut(), \PDO::PARAM_INT);
			$requete->bindValue ( ':dateDerniereConnexionUtilisateur', $utilisateur->getDateDerniereConnexionUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':dateInscriptionUtilisateur', $utilisateur->getDateInscriptionUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':descriptionUtilisateur', $utilisateur->getDescriptionUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':urlPhotoUtilisateur', $utilisateur->getUrlPhotoUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':lienPagePersoUtilisateur', $utilisateur->getLienPagePersoUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':passwordAdminUtilisateur', $utilisateur->getPasswordAdminUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_STR );
			$requete->bindValue ( ':etatBanni', $utilisateur->getEtatBanniUtilisateur(), \PDO::PARAM_BOOL );
			$requete->bindValue ( ':urlBackgroundUtilisateur', $utilisateur->getUrlBackgroundUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':UtilisateurActive', $utilisateur->getUtilisateurActive(), \PDO::PARAM_BOOL );
			
			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction ();
				$requete->execute ();
				$this->dao->commit ();
			} catch ( PDOException $e ) {
				$this->dao->rollback ();
				return "Erreur!: " . $e->getMessage () . "</br>";
			}
			
			// On libère la requete
			$requete->closeCursor ();
			
			if ($this->addPublicationAuteursFromUtilisateur ( $utilisateur ) and $this->addPublicationsPublieursFromUtilisateur ( $utilisateur ) and $this->addApplicationsFromUtilisateur ( $utilisateur ) and $this->addFavorisFromUtilisateur ( $utilisateur ) and $this->addEquipesFromUtilisateur ( $utilisateur ) and $this->addDonneeUtilisateurFromUtilisateur ( $utilisateur ) and $this->addLogsFromUtilisateur ( $utilisateur )) {
				return true;
			} else {
				return false;
			}
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}

	/**
	*	Permet de mettre à jour le workspace de l'utilisateur
	*/
	public function updateWorkSpaceUtilisateur($utilisateur){
		if($utilisateur instanceof Utilisateur){
			$requete = $this->dao->prepare ( "UPDATE utilisateur 
					SET work_space_folder_utilisateur = :workSpaceFolderUtilisateur 
					WHERE id_utilisateur = :idUtilisateur" );

			// Bind des paramètres
			$requete->bindValue ( ':workSpaceFolderUtilisateur', $utilisateur->getWorkSpaceFolderUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_STR );

			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction ();
				$requete->execute ();
				$this->dao->commit ();
			} catch ( PDOException $e ) {
				$this->dao->rollback ();
				return "Error: " . $e->getMessage () . "</br>";
			}
			
			// On libère la requete
			$requete->closeCursor ();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur: The object as a parameter of the function is not an instance of UTILISATEUR.');
		}
	}


	/**
	* Permet de mettre à jour la dernière date de connexion de l'utilisateur
	*/
	public function updateDateConnexionUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
			$requete = $this->dao->prepare ( "UPDATE utilisateur 
					SET date_derniere_connexion_utilisateur = CURDATE()
					WHERE id_utilisateur = :idUtilisateur" );

			// Bind des paramètres
			$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_STR );

			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction ();
				$requete->execute ();
				$this->dao->commit ();
			} catch ( PDOException $e ) {
				$this->dao->rollback ();
				return "Erreur!: " . $e->getMessage () . "</br>";
			}
			
			// On libère la requete
			$requete->closeCursor ();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}


	/**
	* Supprimer le lient entre les auteurs de publication et l'utilisateur
	*/
	public function deleteLinkBetweenPublicationAuteursUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
			$requete = $this->dao->prepare ( "DELETE FROM publication_utilisateur_auteur WHERE id_utilisateur = :idUtilisateur" );
			
			// bind des valeurs
			$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur(), \PDO::PARAM_INT );
			
			// execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction ();
				$requete->execute ();
				$this->dao->commit ();
				$requete->closeCursor ();
				
				return true;
			} catch ( PDOException $e ) {
				$this->dao->rollback ();
				return "Error!: " . $e->getMessage () . "</br>";
			}
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
		// On libère la requete
	}
	public function deleteLinkBetweenPublicationsPublieursUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
			if (sizeof ( $utilisateur->getPublicationPublieurs () ) != 0) {
				
				foreach ( $utilisateur->getPublicationPublieurs () as $publication ) {
					
					// Préparation de la requete
					$requete = $this->dao->prepare ( "UPDATE publication SET id_utilisateur = null WHERE id_publication = :idPublication;" );
					
					// bind des valeurs
					$requete->bindValue ( ':idPublication', $publication->getIdPublication (), \PDO::PARAM_INT );
					
					// execution de la requete sinon envoi d'une erreur
					try {
						$this->dao->beginTransaction ();
						$requete->execute ();
						$this->dao->commit ();
					} catch ( PDOException $e ) {
						$this->dao->rollback ();
						return "Error!: " . $e->getMessage () . "</br>";
					}
					
					// On libère la requete
					$requete->closeCursor ();
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}
	public function deleteLinkBetweenFavorisUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
			$requete = $this->dao->prepare ( "DELETE FROM favori WHERE id_utilisateur = :idUtilisateur" );
			
			// bind des valeurs
			$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur(), \PDO::PARAM_INT );
			
			// execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction ();
				$requete->execute ();
				$this->dao->commit ();
			} catch ( PDOException $e ) {
				$this->dao->rollback ();
				return "Error!: " . $e->getMessage () . "</br>";
			}
			
			// On libère la requête
			$requete->closeCursor ();
			
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}
	public function deleteLinkBetweenEquipesUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
			$requete = $this->dao->prepare ( "DELETE FROM utilisateur_equipe WHERE id_utilisateur = :idUtilisateur" );
			
			// bind des valeurs
			$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_INT );
			
			// execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction ();
				$requete->execute ();
				$this->dao->commit ();
			} catch ( PDOException $e ) {
				$this->dao->rollback ();
				return "Error!: " . $e->getMessage () . "</br>";
			}
			
			// On libère la requete
			$requete->closeCursor ();
			
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}
	public function deleteLinkBetweenDonneeUtilisateurUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
		
			// Préparation de la requête
			$requete = $this->dao->prepare ( "DELETE FROM utilisateur_donnee_utilisateur WHERE id_utilisateur = :idUtilisateur" );
			
			// Bind des paramètres
			$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_INT );
			
			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction ();
				$requete->execute ();
				$this->dao->commit ();
			} catch ( PDOException $e ) {
				$this->dao->rollback ();
				return "Error!: " . $e->getMessage () . "</br>";
			}
			
			// On libère la requête
			$requete->closeCursor ();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}
	public function deleteLinkBetweenLogsUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
			if (sizeof ( $utilisateur->getLogs () ) != 0) {
				
				foreach ( $utilisateur->getLogs () as $log ) {
					
					// Préparation de la requête
					$requete = $this->dao->prepare ( "UPDATE log SET id_utilisateur = null WHERE id_log = :idLog;" );
					
					// bind des valeurs
					$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_INT );
					$requete->bindValue ( ':idLog', $application->getIdApplication (), \PDO::PARAM_INT );
					
					// execution de la requete sinon envoi d'une erreur
					try {
						$this->dao->beginTransaction ();
						$requete->execute ();
						$this->dao->commit ();
					} catch ( PDOException $e ) {
						$this->dao->rollback ();
						return "Error!: " . $e->getMessage () . "</br>";
					}
					
					// On libère la requete
					$requete->closeCursor ();
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}

	// Suppression de toutes les données de l'utilisateur
	public function deleteDonneesUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
			if (sizeof ( $utilisateur->getDonneesUtilisateur () ) != 0) {
				
				foreach ( $utilisateur->getDonneesUtilisateur () as $donneeUtilisateur ) {
					
					$pdoDonneeUtilisateur = new PDODonneeUtilisateurManager ( $this->dao );
					$pdoDonneeUtilisateur->deleteDonneeUtilisateur ( $donneeUtilisateur );
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}

	
	// Suppression de toutes les applications de l'utilisateur
	public function deleteApplications($utilisateur){

		if($utilisateur instanceof Utilisateur){
			if (sizeof ( $utilisateur->getApplications () ) != 0) {
				
				foreach ( $utilisateur->getApplications () as $application ) {
					
					$pdoApplication = new PDOApplicationManager ( $this->dao );
					$pdoApplication->deleteApplication ( $application );
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}
	
	
	// Suppression en cascade des données liés à l'utilisateur et l'utilisateur lui-même
	public function deleteUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
		
			// Suppression des liens avec l'utilisateur.
			// suppression de la liaison entre des publication auteurs et l'utilisateur
			$this->deleteLinkBetweenPublicationAuteursUtilisateur ( $utilisateur );
				
			// suppression de la liaison entre les favoris et l'utilisateur
			$this->deleteLinkBetweenFavorisUtilisateur ( $utilisateur );
			
			// suppression de l'éuipe liéé a l'utilisateur
			$this->deleteLinkBetweenEquipesUtilisateur ( $utilisateur );
			
			// supprime la liaison entre les données utilisateur et l'utilisateur
			$this->deleteLinkBetweenDonneeUtilisateurUtilisateur ( $utilisateur );
			
			// supprime la liaison entre les logs et l'utilisateur
			$this->deleteLinkBetweenLogsUtilisateur ( $utilisateur );
			
			// Suppression des données utilisateurs
			$this->deleteDonneesUtilisateur ( $utilisateur );

			// Suppression des applications utilisateurs
			$this->deleteApplications ( $utilisateur );

			// Suppression de l'utilisateur lui-même
			$requete = $this->dao->prepare ( "DELETE FROM utilisateur WHERE id_utilisateur = :idUtilisateur" );
			// bind des parametre
			
			$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_INT );
			
			// execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction ();
				$requete->execute ();
				$this->dao->commit ();
			} catch ( PDOException $e ) {
				$this->dao->rollback ();
				return "Erreur!: " . $e->getMessage () . "</br>";
			}
			
			// On libère la requete
			$requete->closeCursor ();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}
	
	// Récupère un utilisateur par son ID et la renvoit au format Utilisateur
	public function getUtilisateurById($id) {
		$requete = $this->dao->prepare ( "SELECT * FROM utilisateur WHERE id_utilisateur = :idUtilisateur" );
		
		// bind des parametre
		$requete->bindValue ( ':idUtilisateur', $id, \PDO::PARAM_INT );
		
		// execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction ();
			$requete->execute ();
			$this->dao->commit ();
		} catch ( PDOException $e ) {
			$this->dao->rollback ();
			return "Erreur!: " . $e->getMessage () . "</br>";
		}
		
		$donnees = $requete->fetchAll();
		
		$requete->closeCursor ();
		
		if (count($donnees) == 0) {
			return false;
		}
		else {
			$utilisateur = $this->constructUtilisateur($donnees[0]);
			return $utilisateur;
		}
	}


	// Récupère un utilisateur par son ID et la renvoit au format Utilisateur avec toutes les variables utilisateur
	public function getUtilisateurByIdWithAllData($id) {
		$requete = $this->dao->prepare ( "SELECT * FROM utilisateur WHERE id_utilisateur = :idUtilisateur" );
		
		// bind des parametre
		$requete->bindValue ( ':idUtilisateur', $id, \PDO::PARAM_INT );
		
		// execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction ();
			$requete->execute ();
			$this->dao->commit ();
		} catch ( PDOException $e ) {
			$this->dao->rollback ();
			return "Erreur!: " . $e->getMessage () . "</br>";
		}
		
		$donnees = $requete->fetchAll();
		
		$requete->closeCursor ();
		
		if (count($donnees) == 0) {
			return false;
		}
		else {
			$utilisateur = $this->constructUtilisateur($donnees[0]);
			$this->putPublicationAuteursInUtilisateur($utilisateur);
			$this->putPublicationsPublieursInUtilisateur($utilisateur);
			$this->putApplicationsInUtilisateur($utilisateur);
			$this->putFavorisInUtilisateur($utilisateur);
			$this->putEquipesInUtilisateur($utilisateur);
			$this->putDonneesUtilisateurInUtilisateur($utilisateur);
			$this->putLogsInUtilisateur($utilisateur);
			return $utilisateur;
		}
	}
	
	
	// Permet de récupérer l'utilisateur via son email et le retourne au format Utilisateur
	public function getUtilisateurByMail($mail) {
		$requete = $this->dao->prepare ( "SELECT * FROM utilisateur WHERE mail_utilisateur = :mailUtilisateur" );
	
		// bind des parametre
		$requete->bindValue ( ':mailUtilisateur', $mail, \PDO::PARAM_STR );
	
		// execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction ();
			$requete->execute ();
			$this->dao->commit ();
		} catch ( PDOException $e ) {
			$this->dao->rollback ();
			return "Erreur!: " . $e->getMessage () . "</br>";
		}
		
		$donnees = $requete->fetchAll();
		
		$requete->closeCursor ();
		
		if (count($donnees) == 0){
			return false;
		}
		else {
			$utilisateur = $this->constructUtilisateur($donnees[0]);
			$this->putPublicationAuteursInUtilisateur($utilisateur);
			$this->putPublicationsPublieursInUtilisateur($utilisateur);
			$this->putApplicationsInUtilisateur($utilisateur);
			$this->putFavorisInUtilisateur($utilisateur);
			$this->putEquipesInUtilisateur($utilisateur);
			$this->putDonneesUtilisateurInUtilisateur($utilisateur);
			$this->putLogsInUtilisateur($utilisateur);
			return $utilisateur;
		}
	}
	
	// Retourne un tableau de tous les utilisateurs au fromat array(Utilisateur);
	public function getAllUtilisateurs() {
		$requete = $this->dao->prepare ( "SELECT * FROM utilisateur ORDER BY mail_utilisateur" );
		
		// execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction ();
			$requete->execute ();
			$this->dao->commit ();
		} catch ( PDOException $e ) {
			$this->dao->rollback ();
			return "Erreur!: " . $e->getMessage () . "</br>";
		}
		// creation d'un tableau d'utilisateur
		$utilisateurs = array ();
		
		// On construit l'objet utilisateur
		while ( $donnees = $requete->fetch () ) {
			array_push ( $utilisateurs, $this->constructUtilisateur ( $donnees ) );
		}
		
		// On libère la requete
		$requete->closeCursor ();
		
		return $utilisateurs;
	}
	
	// Récupere le tableau des utilisateurs au fromat array(Utilisateur) entre un début et une fin ;
	public function getUtilisateursBetweenIndex($debut, $quantite) {
		$requete = $this->dao->prepare ( "SELECT * FROM utilisateur LIMIT :debut, :quantite" );
		
		// bind des parametre
		$requete->bindValue ( ':debut', $debut, \PDO::PARAM_INT );
		$requete->bindValue ( ':quantite', $quantite, \PDO::PARAM_INT );
		
		// execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction ();
			$requete->execute ();
			$this->dao->commit ();
		} catch ( PDOException $e ) {
			$this->dao->rollback ();
			return "Erreur!: " . $e->getMessage () . "</br>";
		}
		// creation d'un tableau d'utilisateur
		$utilisateurs = array ();
		
		// On construit l'objet utilisateur
		while ( $donnees = $requete->fetch () ) {
			array_push ( $utilisateurs, $this->constructUtilisateur ( $donnees ) );
		}
		
		// On lib�re la requete
		$requete->closeCursor ();
		
		return $utilisateurs;
	}
	
	// Méthode pour retourner le nombre d'utilisateur dans la BDD
	public function getNumberOfUtilisateur() {
		$requete = $this->dao->prepare ( 'SELECT COUNT(*) AS nombreApps FROM utilisateur' );
		
		// execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction ();
			$requete->execute ();
			$this->dao->commit ();
		} catch ( PDOException $e ) {
			$this->dao->rollback ();
			return "Erreur!: " . $e->getMessage () . "</br>";
		}
		// creation d'un tableau d'utilisateur
		

		// On lib�re la requete
		$requete->closeCursor ();
		
		return $donnees ['nombreApps'];
	}

	// Méthode pour insérer les publications dans l'objet Utilisateur
	public function putPublicationAuteursInUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
		
			// Préparation de la requetes.
			$requete = $this->dao->prepare ( 'SELECT id_publication FROM publication_utilisateur_auteur WHERE id_utilisateur = :idUtilisateur' );
			
			// bind des parametres
			$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_INT );
			
			// execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction ();
				$requete->execute ();
				$this->dao->commit ();
			} catch ( PDOException $e ) {
				$this->dao->rollback ();
				return "Erreur!: " . $e->getMessage () . "</br>";
			}
			
			
			// creation d'un tableau d'utilisateur
			$pdoPublication = new PDOPublicationManager ( $this->dao );
			while ( $donnee = $requete->fetch () ) {
				$utilisateur->addPublicationAuteur ( $pdoPublication->getPublicationById ( $donnee [0] ) );
			}
			
			// On libère la requete
			$requete->closeCursor ();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}

	public function putPublicationsPublieursInUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
		
			// pr�paration de la requetes.
			$requete = $this->dao->prepare ( 'SELECT id_publication FROM publication WHERE id_utilisateur = :idUtilisateur' );
			
			// bind des parametres
			$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_INT );
			
			// execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction ();
				$requete->execute ();
				$this->dao->commit ();
			} catch ( PDOException $e ) {
				$this->dao->rollback ();
				return "Erreur!: " . $e->getMessage () . "</br>";
			}
			// creation d'un tableau d'utilisateur
			$pdoPublication = new PDOPublicationManager ( $this->dao );
			while ( $donnee = $requete->fetch () ) {
				$utilisateur->addPublicationPublieur ( $pdoPublication->getPublicationById ( $donnee [0] ) );
			}
			
			// On libère la requete
			$requete->closeCursor ();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}

	// Permet d'insérer les applications dans l'objet Utilisateur
	public function putApplicationsInUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
		
			/****************************************************************/
			/* On récupère les applications dont l'utilisateur est créateur */
			/****************************************************************/
			// Préparation de la requête.
			$requete = $this->dao->prepare('SELECT id_application FROM application WHERE id_utilisateur=:idUtilisateur');
			
			// bind des paramètres
			
			$requete->bindValue(':idUtilisateur', $utilisateur->getIdUtilisateur());
			
			// execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction ();
				$requete->execute();
				$this->dao->commit();
			} catch ( PDOException $e ) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			// Création d'un tableau d'utilisateurs
			$pdoApplication = new PDOApplicationManager($this->dao);
			while ( $donnee = $requete->fetch () ) {
				$utilisateur->addApplication($pdoApplication->getApplicationByIdWithAllParameters($donnee[0]));
			}
			
			// On libère la requête
			$requete->closeCursor ();


			/**************************************************************/
			/* On récupère les applications dont l'utilisateur est auteur */
			/**************************************************************/
			$pdoAuteur = new PDOAuteurManager($this->dao);
			$pdoApplicationAuteur = new PDOApplicationAuteurManager($this->dao);

			$auteur = $pdoAuteur->getAuteurByMail($utilisateur->getMailUtilisateur());

			if($auteur != false){
				$applications = $pdoApplicationAuteur->getAllApplicationsFromAuteur($auteur);
				foreach($applications as $application){
					$utilisateur->addApplication($application);
				}
			}

		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}
	
	// Place les favoris dans l'objet Utilisateur
	public function putFavorisInUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
			$requete = $this->dao->prepare ( 'SELECT id_application FROM favori WHERE id_utilisateur = :idUtilisateur' );
			
			// bind des parametres
			$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_INT );
			
			// execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction ();
				$requete->execute ();
				$this->dao->commit ();
			} catch ( PDOException $e ) {
				$this->dao->rollback ();
				return "Erreur!: " . $e->getMessage () . "</br>";
			}
			// On ajoute les applications favorites de l'utilisateur dans l'objet utilisateur
			$pdoApplication = new PDOApplicationManager ( $this->dao );
			while ( $donnee = $requete->fetch () ) {
				$utilisateur->addFavori ( $pdoApplication->getApplicationByIdWithAllParameters ( $donnee [0] ) );
			}
			
			// On libère la requete
			$requete->closeCursor ();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}

	// Place l'équipe de recherche dans l'objet Utilisateur
	public function putEquipesInUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
		
			// Préparation de la requetes.
			$requete = $this->dao->prepare ( 'SELECT id_equipe FROM utilisateur_equipe WHERE id_utilisateur = :idUtilisateur' );
			
			// bind des parametres
			$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_INT );
			
			// execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction ();
				$requete->execute ();
				$this->dao->commit ();
			} catch ( PDOException $e ) {
				$this->dao->rollback ();
				return "Erreur!: " . $e->getMessage () . "</br>";
			}
			// creation d'un tableau d'utilisateur
			$pdoEquipe = new PDOEquipeManager ( $this->dao );
			while ( $donnee = $requete->fetch () ) {
				$utilisateur->addEquipe ( $pdoEquipe->getEquipeById ( $donnee['id_equipe']) );
			}
			
			// On libère la requete
			$requete->closeCursor ();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}
	
	// Place les données de l'utilisateur dans l'objet Utilisateur
	public function putDonneesUtilisateurInUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
		
			// Préparation de la requetes.
			$requete = $this->dao->prepare ( 'SELECT id_donnee_utilisateur FROM utilisateur_donnee_utilisateur WHERE id_utilisateur = :idUtilisateur' );
			
			// Bind des paramètres
			$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_INT );
			
			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction ();
				$requete->execute ();
				$this->dao->commit ();
			} catch ( PDOException $e ) {
				$this->dao->rollback ();
				return "Erreur!: " . $e->getMessage () . "</br>";
			}

			// Appel au PDO UtilisateurDonneeUtilisateur
			$pdoDonneeUtilisateur = new PDODonneeUtilisateurManager ( $this->dao );
			while ( $donnee = $requete->fetch () ) {
				
				$utilisateur->addDonneeUtilisateur( $pdoDonneeUtilisateur->getDonneeUtilisateurById($donnee[0]));
			}
			
			// On libère la requête
			$requete->closeCursor ();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}
	
	// Place les logs dans l'objet Utilisateur
	public function putLogsInUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
		
			// Préparation de la requetes.
			$requete = $this->dao->prepare ( 'SELECT id_log FROM log WHERE id_utilisateur = :idUtilisateur' );
			
			// bind des parametres
			$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_INT );
			
			// execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction ();
				$requete->execute ();
				$this->dao->commit ();
			} catch ( PDOException $e ) {
				$this->dao->rollback ();
				return "Erreur!: " . $e->getMessage () . "</br>";
			}
			// creation d'un tableau d'utilisateur
			$pdoLog = new PDOLogManager ( $this->dao );
			while ( $donnee = $requete->fetch () ) {
				$utilisateur->addMotCles ( $pdoLog->getLogById ( $donnee [0] ) );
			}
			
			// On libère la requete
			$requete->closeCursor ();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}
	

	// Permet de contruire un objet utilisateur a partir des données de la base.
	protected function constructUtilisateur($donnee) {

		$pdoStatut = new PDOStatutUtilisateurManager($this->dao);

		$data = [
		'idUtilisateur' => $donnee ['id_utilisateur'],
		'nomUtilisateur' => $donnee ['nom_utilisateur'],
		'prenomUtilisateur' => $donnee ['prenom_utilisateur'],
		'variableFixeUtilisateur' => $donnee ['variable_fixe_utilisateur'],
		'workSpaceFolderUtilisateur' => $donnee['work_space_folder_utilisateur'],
		'mailUtilisateur' => $donnee ['mail_utilisateur'],
		'passwordUtilisateur' => $donnee ['password_utilisateur'],
		'statut' => $pdoStatut->getStatutById($donnee['id_statut']),
		'dateDerniereConnexionUtilisateur' => $donnee ['date_derniere_connexion_utilisateur'],
		'dateInscriptionUtilisateur' => $donnee['date_inscription_utilisateur'],
		'descriptionUtilisateur' => $donnee['description_utilisateur'],
		'urlPhotoUtilisateur' => $donnee['url_photo_utilisateur'],
		'lienPagePersoUtilisateur' => $donnee['lien_page_perso_utilisateur'],
		'passwordAdminUtilisateur' => $donnee['password_admin_utilisateur'], 
		'etatBanniUtilisateur' => (bool) $donnee['etat_banni'],
		'urlBackgroundUtilisateur' => $donnee['url_background_utilisateur'],
		'UtilisateurActive' => (bool) $donnee['utilisateur_active']
		];
		
		return new Utilisateur ( $data );
	}
}