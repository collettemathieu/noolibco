<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des Favoris.						  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des Favoris
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\Favori;
use \Library\Models;

class PDOFavoriManager extends \Library\Models\FavoriManager
{

/* Définition des méthode de classe */

	//ajoute une favori dans la base
	public function addFavori($favori){

		 if($favori instanceof Favori){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT IGNORE INTO favori VALUES (:idUtilisateur, :idApplication)");

			//bind des valeurs
			$requete->bindValue(':idUtilisateur', $favori->getUtilisateur()->getIdUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':idApplication', $favori->getApplication()->getIdApplication(), \PDO::PARAM_INT);
			
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
		$messageClient->addErreur('PDO::Favori : L\'objet passé en paramètre n\'est pas une instance de Favori');
		}
	}

	//supprime la favori de la base et modifie les données de toutes les utilisateurs avec cette favori.
	public function deleteFavori($favori){

		 if($favori instanceof Favori){	

			$requete = $this->dao->prepare("DELETE FROM favori WHERE id_application = :idApplication and id_utilisateur = :idUtilisateur;");

			//bind des valeurs
			$requete->bindValue(':idUtilisateur', $favori->getUtilisateur()->getIdUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':idApplication', $favori->getApplication()->getIdApplication(), \PDO::PARAM_INT);

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
		$messageClient->addErreur('PDO::Favori : L\'objet passé en paramètre n\'est pas une instance de Favori');
		}
	}
	
	//selectionne une favori par son ID
	public function getFavoriById($idApplication, $idUtilisateur){
		
		$requete = $this->dao->prepare("SELECT * FROM favori  WHERE id_application = :idApplication and id_utilisateur = :idUtilisateur");
		
		//bind des parametre
		$requete->bindValue(':idUtilisateur', $idApplication, \PDO::PARAM_INT);
		$requete->bindValue(':idApplication', $idUtilisateur, \PDO::PARAM_INT);
		
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
			$favori = $this->constructFavori($donnees[0]);
			return $favori;
		}
	}
	
	//renvoi un tableau de toutes les favoris
	public function getAllFavoris(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM favori");
		
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
		$favoris = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($favoris, $this->constructFavori($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $favoris;
	}
	
	//renvoi un tableau de favori a partir de l'index début jusqu'a debut + quantite
	public function getFavorisBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM favori LIMIT :debut,:quantite");
		
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
		$favoris = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($favoris, $this->constructFavori($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $favoris;
	}
	
	//retourne le nombre de favori dans la base
	public function getNumberOfFavori(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreFavori FROM favori');
		
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
		
		return $donnees['nombreFavori'];
	}
	
	//permet de contruire un objet favori a partir des ses données de la base.
	protected function constructFavori($donnee){
		
		$pdoApplication = new PDOApplicationManager($this->dao);
		$pdoUtilisateur = new PDOUtilisateurManager($this->dao);
		
		$data = [
		'Utilisateur' => $pdoUtilisateur->getUtilisateurById($donnee['id_utilisateur']),
		'Application' => $pdoApplication->getApplicationById($donnee['id_application'])
		];
		return new Favori($data);
	}
}
