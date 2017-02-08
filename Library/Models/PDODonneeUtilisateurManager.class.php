<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des DonneesUtilisateur.				  |
// +----------------------------------------------------------------------+
// | Auteurs : Corentin CHEVALLIER <ChevallierCorentin@noolib.com>		  |
// | 			Mathieu COLLETTE <collettemathieu@noolib.com>	  		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des DonneesUtilisateur
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\DonneeUtilisateur;
use \Library\Models;

class PDODonneeUtilisateurManager extends \Library\Models\DonneeUtilisateurManager
{

/* Définition des méthode de classe */

	// Ajoute une DonneeUtilisateur dans la base
	public function addDonneeUtilisateur($donneeUtilisateur){

		 if($donneeUtilisateur instanceof DonneeUtilisateur){
		
			// Préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO donnee_utilisateur (url_donnee_utilisateur, url_miniature_donnee_utilisateur, nom_donnee_utilisateur, sampleRate_donnee_utilisateur, taille_donnee_utilisateur, temps_minimum_donnee_utilisateur, date_publication_donnee_utilisateur, id_type_donnee_utilisateur, is_in_cache) 
					VALUES (:urlDonneeUtilisateur, :urlMiniatureDonneeUtilisateur, :nomDonneeUtilisateur, :sampleRateDonneeUtilisateur, :tailleDonneeUtilisateur, :tempsMinimumDonneeUtilisateur, CURDATE(), :idTypeDonneeUtilisateur, :isInCache)");

			// Bind des paramètres
			$requete->bindValue(':urlDonneeUtilisateur', $donneeUtilisateur->getUrlDonneeUtilisateur(), \PDO::PARAM_STR);
			$requete->bindValue(':urlMiniatureDonneeUtilisateur', $donneeUtilisateur->getUrlMiniatureDonneeUtilisateur(), \PDO::PARAM_STR);
			$requete->bindValue(':nomDonneeUtilisateur', $donneeUtilisateur->getNomDonneeUtilisateur(), \PDO::PARAM_STR);
			$requete->bindValue(':sampleRateDonneeUtilisateur', $donneeUtilisateur->getSampleRateDonneeUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':tailleDonneeUtilisateur', $donneeUtilisateur->getTailleDonneeUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':tempsMinimumDonneeUtilisateur', $donneeUtilisateur->getTempsMinimumDonneeUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':idTypeDonneeUtilisateur', $donneeUtilisateur->getTypeDonneeUtilisateur()->getIdTypeDonneeUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':isInCache', $donneeUtilisateur->getIsInCache(), \PDO::PARAM_BOOL);
			
			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$donneeUtilisateur->setIdDonneeUtilisateur($this->dao->lastInsertId('id_donnee_utilisateur'));
				$this->dao->commit();
				
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			// On libère la requête
			$requete->closeCursor();
			return $donneeUtilisateur;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::DonneeUtilisateur : L\'objet passé en paramètre n\'est pas une instance de DonneeUtilisateur');
		}
	}

	// Insère dans la table utilisateur_donnee_utilisateur les utilisateurs de la donnée
	public function addUtilisateursFromDonneeUtilisateur($donneeUtilisateur){

		 if($donneeUtilisateur instanceof DonneeUtilisateur){
			if (sizeof($donneeUtilisateur->getUtilisateurs()) != 0){
			
				foreach ($donneeUtilisateur->getUtilisateurs() as $utilisateur){
						
					// Préparation de la requête
					$requete = $this->dao->prepare("INSERT INTO utilisateur_donnee_utilisateur VALUES :idUtilisateur, :idDonneeUtilisateur;");
			
					//bind des valeurs
					$requete->bindValue(':idUtilisateur', $utilisateur->getIdUtilisateur(), \PDO::PARAM_INT);
					$requete->bindValue(':idDonneeUtilisateur', $donneeUtilisateur->getIdDonneeUtilisateur(), \PDO::PARAM_INT);
			
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
				}
			}
			return true;	
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::DonneeUtilisateur : L\'objet passé en paramètre n\'est pas une instance de DonneeUtilisateur');
		}
	}


	// Sauvegarde les modifications des données de sortie
	public function saveDonneeUtilisateur($donneeUtilisateur){

		 if($donneeUtilisateur instanceof DonneeUtilisateur){
	
			//préparation de la requete
			$requete = $this->dao->prepare("UPDATE donnee_utilisateur SET
					url_donnee_utilisateur = :urlDonneeUtilisateur,
					url_miniature_donnee_utilisateur = :urlMiniatureDonneeUtilisateur,
					nom_donnee_utilisateur = :nomDonneeUtilisateur,
					sampleRate_donnee_utilisateur = :sampleRateDonneeUtilisateur,
					taille_donnee_utilisateur = :tailleDonneeUtilisateur,
					temps_minimum_donnee_utilisateur = :tempsMinimumDonneeUtilisateur,
					date_publication_donnee_utilisateur = CURDATE(),
					id_type_donnee_utilisateur = :idTypeDonneeUtilisateur,
					is_in_cache = :isInCache
					WHERE id_donnee_utilisateur = :idDonneeUtilisateur;");

			//bind des valeurs
			$requete->bindValue(':idDonneeUtilisateur', $donneeUtilisateur->getIdDonneeUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':urlDonneeUtilisateur', $donneeUtilisateur->getUrlDonneeUtilisateur(), \PDO::PARAM_STR);
			$requete->bindValue(':urlMiniatureDonneeUtilisateur', $donneeUtilisateur->getUrlMiniatureDonneeUtilisateur(), \PDO::PARAM_STR);
			$requete->bindValue(':nomDonneeUtilisateur', $donneeUtilisateur->getNomDonneeUtilisateur(), \PDO::PARAM_STR);
			$requete->bindValue(':sampleRateDonneeUtilisateur', $donneeUtilisateur->getSampleRateDonneeUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':tailleDonneeUtilisateur', $donneeUtilisateur->getTailleDonneeUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':tempsMinimumDonneeUtilisateur', $donneeUtilisateur->getTempsMinimumDonneeUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':idTypeDonneeUtilisateur', $donneeUtilisateur->getTypeDonneeUtilisateur()->getIdTypeDonneeUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':isInCache', $donneeUtilisateur->getIsInCache(), \PDO::PARAM_BOOL);

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
			$messageClient->addErreur('PDO::DonneeUtilisateur : L\'objet passé en paramètre n\'est pas une instance de DonneeUtilisateur');
		}
	}

	// Met à jour la date de la donnée utilisateur
	public function updateDateDonneeUtilisateur($donneeUtilisateur){

		 if($donneeUtilisateur instanceof DonneeUtilisateur){
	
			//préparation de la requete
			$requete = $this->dao->prepare("UPDATE donnee_utilisateur SET
					date_publication_donnee_utilisateur = CURDATE()
					WHERE id_donnee_utilisateur = :idDonneeUtilisateur;");

			//bind des valeurs
			$requete->bindValue(':idDonneeUtilisateur', $donneeUtilisateur->getIdDonneeUtilisateur(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::DonneeUtilisateur : L\'objet passé en paramètre n\'est pas une instance de DonneeUtilisateur');
		}
	}

	// Supprime le lien entre les utilisateurs et la donnée utilisateur
	public function deleteLinkbetweenUtilisateursDonneeUtilisateur($donneeUtilisateur){

		 if($donneeUtilisateur instanceof DonneeUtilisateur){
			// Préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM utilisateur_donnee_utilisateur WHERE id_donnee_utilisateur = :idDonneeUtilisateur;");
			
			// Bind des paramètres
			$requete->bindValue(':idDonneeUtilisateur', $donneeUtilisateur->getIdDonneeUtilisateur(), \PDO::PARAM_INT);
			
			// Execution de la requete sinon envoi d'une erreur
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
		$messageClient->addErreur('PDO::DonneeUtilisateur : L\'objet passé en paramètre n\'est pas une instance de DonneeUtilisateur');
		}
	}


	// Supprime la DonneeUtilisateur de la base et modifie les données de tous les utilisateurs avec cette DonneeUtilisateur.
	public function deleteDonneeUtilisateur($donneeUtilisateur){

		 if($donneeUtilisateur instanceof DonneeUtilisateur){	

			// Suppression de la liaison entre les utilisateurs et la donnée utilisateur
			$this->deleteLinkbetweenUtilisateursDonneeUtilisateur ( $donneeUtilisateur );

			// Suppression de l'objet donneeUtilisateur
			$requete = $this->dao->prepare("DELETE FROM donnee_utilisateur WHERE id_donnee_utilisateur = :idDonneeUtilisateur");

			//bind des valeurs
			$requete->bindValue(':idDonneeUtilisateur', $donneeUtilisateur->getIdDonneeUtilisateur(), \PDO::PARAM_INT);

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
		$messageClient->addErreur('PDO::DonneeUtilisateur : L\'objet passé en paramètre n\'est pas une instance de DonneeUtilisateur');
		}
	}
	

	// Sélectionne une DonneeUtilisateur par son ID
	public function getDonneeUtilisateurById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM donnee_utilisateur WHERE id_donnee_utilisateur = :idDonneeUtilisateur");
		
		//bind des parametre
		$requete->bindValue(':idDonneeUtilisateur', $id, \PDO::PARAM_INT);
		
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

			$donneeUtilisateur = $this->constructDonneeUtilisateur($donnees[0]);
			return $donneeUtilisateur;
		}
	}


	// Renvoi un tableau de toutes les DonneeUtilisateurs
	public function getAllDonneeUtilisateur(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM donnee_utilisateur");
		
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
		$donneeUtilisateurs = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($donneeUtilisateurs, $this->constructDonneeUtilisateur($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $donneeUtilisateurs;
	}
	
	// Renvoi un tableau de DonneeUtilisateur a partir de l'index début jusqu'a debut + quantite
	public function getDonneeUtilisateursBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM donnee_utilisateur LIMIT :debut,:quantite");
		
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
		$donneeUtilisateurs = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($donneeUtilisateurs, $this->constructDonneeUtilisateur($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $donneeUtilisateurs;
	}
	
	// Retourne le nombre de DonneeUtilisateur dans la base
	public function getNumberOfDonneeUtilisateur(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreDonneeUtilisateur FROM donnee_utilisateur');
		
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
		
		return $donnees['nombreDonneeUtilisateur'];
	}

	// Place les utilisateurs d'une donnéeUtilisateur dans celle-ci
	public function putUtilisateursInDonneeUtilisateur($donneeUtilisateur){

		 if($donneeUtilisateur instanceof DonneeUtilisateur){
				
			// Préparation de la requête
			$requete = $this->dao->prepare("SELECT id_utilisateur FROM utilisateur_donnee_utilisateur WHERE id_donne_utilisateur = :idDonneeUtilisateur;");

			// Bind des paramètres
			$requete->bindValue(':idDonneeUtilisateur', $donneeUtilisateur->getIdDonneeUtilisateur(), \PDO::PARAM_INT);

			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}
			
			$pdoUtilisateur = new PDOUtilisateurManager($this->dao);
			
			//On construit l'objet utilisateur
			while ($donnees = $requete->fetch())
			{
				$donnee_utilisateur->addUtilisateur($pdoUtilisateur->getUtilisateurById($donnees[0]));
			}
			
			// On libère la requete
			$requete->closeCursor();
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
		$messageClient->addErreur('PDO::DonneeUtilisateur : L\'objet passé en paramètre n\'est pas une instance de DonneeUtilisateur');
		}
	}


	// Permet de contruire un objet DonneeUtilisateur a partir des ses données de la base.
	protected function constructDonneeUtilisateur($donnee){

		$pdoTypeDonneeUtilisateur = new PDOTypeDonneeUtilisateurManager($this->dao);
		
		$data = [
		'idDonneeUtilisateur' => $donnee['id_donnee_utilisateur'],
		'urlDonneeUtilisateur' => $donnee['url_donnee_utilisateur'],
		'urlMiniatureDonneeUtilisateur' => $donnee['url_miniature_donnee_utilisateur'],
		'nomDonneeUtilisateur' => $donnee['nom_donnee_utilisateur'],
		'sampleRateDonneeUtilisateur' => (int) $donnee['sampleRate_donnee_utilisateur'],
		'tailleDonneeUtilisateur' => (int) $donnee['taille_donnee_utilisateur'],
		'tempsMinimumDonneeUtilisateur' => (int) $donnee['temps_minimum_donnee_utilisateur'],
		'datePublicationDonneeUtilisateur' => $donnee['date_publication_donnee_utilisateur'],
		'typeDonneeUtilisateur' => $pdoTypeDonneeUtilisateur->getTypeDonneeUtilisateurById($donnee['id_type_donnee_utilisateur']),
		'isInCache' => (bool) $donnee['is_in_cache']
		];
		return new DonneeUtilisateur($data);
	}
}
