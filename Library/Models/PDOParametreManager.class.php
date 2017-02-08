<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des Parametres.						  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des Parametres
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\Parametre;
use \Library\Models;

class PDOParametreManager extends \Library\Models\ParametreManager
{

/* Définition des méthode de classe */

	//ajoute une parametre dans la base
	public function addParametre($parametre){

		 if($parametre instanceof Parametre){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO parametre (nom_parametre, description_parametre, id_type_affichage_parametre, statut_public_parametre, valeur_defaut_parametre, valeur_min_parametre, valeur_max_parametre, valeur_pas_parametre) 
					VALUES (:nomParametre, :descriptionParametre, :idTypeAffichageParametre, :statutPublicParametre, :valeurDefautParametre, :valeurMinParametre, :valeurMaxParametre, :valeurPasParametre)");

			//bind des valeurs
			$requete->bindValue(':nomParametre', $parametre->getNomParametre(), \PDO::PARAM_STR);
			$requete->bindValue(':descriptionParametre', $parametre->getDescriptionParametre(), \PDO::PARAM_STR);
			$requete->bindValue(':statutPublicParametre', $parametre->getstatutPublicParametre(), \PDO::PARAM_BOOL);
			$requete->bindValue(':valeurDefautParametre', $parametre->getValeurDefautParametre(), \PDO::PARAM_STR);
			$requete->bindValue(':idTypeAffichageParametre', $parametre->getTypeAffichageParametre()->getIdTypeAffichageParametre(), \PDO::PARAM_INT);
			$requete->bindValue(':valeurMinParametre', $parametre->getValeurMinParametre(), \PDO::PARAM_STR);
			$requete->bindValue(':valeurMaxParametre', $parametre->getValeurMaxParametre(), \PDO::PARAM_STR);
			$requete->bindValue(':valeurPasParametre', $parametre->getValeurPasParametre(), \PDO::PARAM_STR);
			
			//execution de la requete sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$parametre->setIdParametre($this->dao->lastInsertId('id_parametre'));
				$this->dao->commit();
				
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//On libère la requete
			$requete->closeCursor();
			return $parametre;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Parametre : L\'objet passé en paramètre n\'est pas une instance de Parametre');
		}
	}
	public function addFonctionFromParametre($parametre){

		 if($parametre instanceof Parametre){
			if (sizeof($parametre->getFonctions()) != 0){
			
				for ($i = 0 ; $i < sizeof($parametre->getFonctions()) ; $i++){
					$fonction = $fonction->getFonctions()[$i];
					//pr�paration de la requete
					$requete = $this->dao->prepare("INSERT INTO fonction_parametre VALUES :idFonction, :idParametre, :i");
			
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
			
					//On libère la requete
					$requete->closeCursor();
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Parametre : L\'objet passé en paramètre n\'est pas une instance de Parametre');
		}
	}
	//sauvegarde les modifications d'une uniteParametre
	public function saveParametre($parametre){

		 if($parametre instanceof Parametre){
	
			//préparation de la requete
			$requete = $this->dao->prepare("UPDATE parametre SET
					nom_parametre = :nomParametre,
					description_parametre = :descriptionParametre,
					statut_public_parametre = :statutPublicParametre,
					id_type_affichage_parametre = :idTypeAffichageParametre,
					valeur_defaut_parametre = :valeurDefautParametre,
					valeur_min_parametre = :valeurMinParametre,
					valeur_max_parametre = :valeurMaxParametre,
					valeur_pas_parametre = :valeurPasParametre
					WHERE id_parametre = :idParametre;");

			//bind des valeurs
			$requete->bindValue(':idParametre', $parametre->getIdParametre(), \PDO::PARAM_INT);
			$requete->bindValue(':nomParametre', $parametre->getNomParametre(), \PDO::PARAM_STR);
			$requete->bindValue(':descriptionParametre', $parametre->getDescriptionParametre(), \PDO::PARAM_STR);
			$requete->bindValue(':statutPublicParametre', $parametre->getstatutPublicParametre(), \PDO::PARAM_BOOL);
			$requete->bindValue(':valeurDefautParametre', $parametre->getValeurDefautParametre(), \PDO::PARAM_STR);
			$requete->bindValue(':idTypeAffichageParametre', $parametre->getTypeAffichageParametre()->getIdTypeAffichageParametre(), \PDO::PARAM_INT);
			$requete->bindValue(':valeurMinParametre', $parametre->getValeurMinParametre(), \PDO::PARAM_STR);
			$requete->bindValue(':valeurMaxParametre', $parametre->getValeurMaxParametre(), \PDO::PARAM_STR);
			$requete->bindValue(':valeurPasParametre', $parametre->getValeurPasParametre(), \PDO::PARAM_STR);
			

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
			$messageClient->addErreur('PDO::Parametre : L\'objet passé en paramètre n\'est pas une instance de Parametre');
		}
	}
	
	public function deleteLinkBetweenFonctionsParametre($parametre){

		 if($parametre instanceof Parametre){
			$requete = $this->dao->prepare("DELETE FROM fonction_parametre WHERE id_parametre = :idParametre");
			
			// Bind des paramètres
			$requete->bindValue(':idParametre', $parametre->getIdParametre(), \PDO::PARAM_INT);
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
			$messageClient->addErreur('PDO::Parametre : L\'objet passé en paramètre n\'est pas une instance de Parametre');
		}
	}
	
	// Supprime le paramètre en lien avec une fonction
	public function deleteParametre($parametre){

		 if($parametre instanceof Parametre){	

			// On supprime le lien entre le paramètre et la fonction
			$this->deleteLinkBetweenFonctionsParametre($parametre);

			// On supprime le paramètre
			$requete = $this->dao->prepare("DELETE FROM parametre WHERE id_parametre = :idParametre");

			// Bind des paramètres
			$requete->bindValue(':idParametre', $parametre->getIdParametre(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Parametre : L\'objet passé en paramètre n\'est pas une instance de Parametre');
		}
	}
	
	// Sélectionne une parametre par son ID
	public function getParametreById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM parametre WHERE id_parametre = :idParametre");
		
		//bind des parametre
		$requete->bindValue(':idParametre', $id, \PDO::PARAM_INT);
		
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
			$parametre = $this->constructParametre($donnees[0]);
			return $parametre;
		}
	}

	//Selectionne les parametres en fonction leur statut
	public function getParametresByStatut($statut){
	
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM parametre WHERE statut_public_parametre = :statutPublicParametre");
		
		//bind des parametre
		$requete->bindValue(':idParametre', $statut, \PDO::PARAM_INT);
		
		//execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//creation d'un tableau d'uniteParametre
		$parametres = array();
		
		//On construit l'objet uniteParametre
		while ($donnees = $requete->fetch())
		{
			array_push($parametres, $this->constructParametre($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $parametres;
	}
	
	//renvoi un tableau de toutes les parametres
	public function getAllParametres(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM parametre");
		
		//execution de la requete sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//creation d'un tableau d'uniteParametre
		$parametres = array();
		
		//On construit l'objet uniteParametre
		while ($donnees = $requete->fetch())
		{
			array_push($parametres, $this->constructParametre($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $parametres;
	}
	
	//renvoi un tableau de parametre a partir de l'index début jusqu'a debut + quantite
	public function getParametresBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM parametre LIMIT :debut,:quantite");
		
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
		//creation d'un tableau d'uniteParametre
		$parametres = array();
		
		//On construit l'objet uniteParametre
		while ($donnees = $requete->fetch())
		{
			array_push($parametres, $this->constructParametre($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $parametres;
	}
	
	//retourne le nombre de parametre dans la base
	public function getNumberOfParametre(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreParametre FROM parametre');
		
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
		
		return $donnees['nombreParametre'];
	}
	
	public function putFonctionsInParametre($parametre){

		 if($parametre instanceof Parametre){
			$requete = $this->dao->prepare("SELECT id_fonction FROM fonction_parametre WHERE id_parametre = :idParametre  ORDER BY id_ordre");
		
			//bind des parametre
			$requete->bindValue(':idParametre', $parametre->getIdParametre(), \PDO::PARAM_INT);
		
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
			$pdoFonction = new PDOFonctionManager($this->dao);
		
			//On construit l'objet application
			while ($donnees = $requete->fetch())
			{
				$parametre->addFonction($pdoFonction->getFonctionById($donnees['id_fonction']));
			}
		
			//On libère la requete
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Parametre : L\'objet passé en paramètre n\'est pas une instance de Parametre');
		}
	}
	
	//permet de contruire un objet parametre a partir des ses données de la base.
	protected function constructParametre($donnee){

		$pdoTypeAffichageParametre = new PDOTypeAffichageParametreManager($this->dao);
		
		$data = [
			'IdParametre' => $donnee['id_parametre'],
			'NomParametre' => $donnee['nom_parametre'],
			'DescriptionParametre' => $donnee['description_parametre'],
			'statutPublicParametre' => (bool) $donnee['statut_public_parametre'],
			'valeurDefautParametre' => floatval($donnee['valeur_defaut_parametre']),
			'typeAffichageParametre' => $pdoTypeAffichageParametre->getTypeAffichageParametreById($donnee['id_type_affichage_parametre']),
			'valeurMinParametre' => floatval($donnee['valeur_min_parametre']),
			'valeurMaxParametre' => floatval($donnee['valeur_max_parametre']),
			'valeurPasParametre' => floatval($donnee['valeur_pas_parametre'])
		];
		return new Parametre($data);
	}
}
