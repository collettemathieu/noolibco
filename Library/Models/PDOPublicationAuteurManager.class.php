<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des PublicationAuteurs.				  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des PublicationAuteurs
 * @access: public
 * @version: 1
 */	

namespace Library\Models;
use \Library\Entities\PublicationAuteur;
use \Library\Models;

class PDOPublicationAuteurManager extends \Library\Models\PublicationAuteurManager
{

/* Définition des méthode de classe */

	//ajoute une publicationAuteur dans la base
	public function addPublicationAuteur($publicationAuteur){

		if($publicationAuteur instanceof PublicationAuteur){
		
			//préparation de la requete
			$requete = $this->dao->prepare("INSERT INTO publication_auteur VALUES (:idAuteur, :idPublication)");

			//bind des valeurs
			$requete->bindValue(':idAuteur', $publicationAuteur->getAuteur()->getIdAuteur(), \PDO::PARAM_INT);
			$requete->bindValue(':idPublication', $publicationAuteur->getPublication()->getIdPublication(), \PDO::PARAM_INT);
			
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
		$messageClient->addErreur('PDO::PublicationAuteur : L\'objet passé en paramètre n\'est pas une instance de PublicationAuteur');
		}
	}

	//supprime la publicationAuteur de la base et modifie les données de toutes les utilisateurs avec cette publicationAuteur.
	public function deletePublicationAuteur($publicationAuteur){

		if($publicationAuteur instanceof PublicationAuteur){	

			$requete = $this->dao->prepare("DELETE FROM publication_auteur WHERE id_publication = :idPublication and id_auteur = :idAuteur;");

			//bind des valeurs
			$requete->bindValue(':idAuteur', $publicationAuteur->getAuteur()->getIdAuteur(), \PDO::PARAM_INT);
			$requete->bindValue(':idPublication', $publicationAuteur->getPublication()->getIdPublication(), \PDO::PARAM_INT);

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
		$messageClient->addErreur('PDO::PublicationAuteur : L\'objet passé en paramètre n\'est pas une instance de PublicationAuteur');
		}
	}
	
	//selectionne une publicationAuteur par son ID
	public function getPublicationAuteurById($idPublication, $idAuteur){
		
		$requete = $this->dao->prepare("SELECT * FROM publication_auteur  WHERE id_publication = :idPublication and id_auteur = :idAuteur");
		
		//bind des parametre
		$requete->bindValue(':idAuteur', $idAuteur, \PDO::PARAM_INT);
		$requete->bindValue(':idPublication', $idPublication, \PDO::PARAM_INT);
		
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
			$publicationAuteur = $this->constructPublicationAuteur($donnees[0]);
			return $publicationAuteur;
		}
	}
	
	//renvoi un tableau de toutes les publicationAuteurs
	public function getAllPublicationAuteurs(){
		
		//preparation de la requete
		$requete = $this->dao->prepare("SELECT * FROM publication_auteur");
		
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
		$publicationAuteurs = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($publicationAuteurs, $this->constructPublicationAuteur($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $publicationAuteurs;
	}
	
	//renvoi un tableau de publicationAuteur a partir de l'index début jusqu'a debut + quantite
	public function getPublicationAuteursBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM publication_auteur LIMIT :debut,:quantite");
		
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
		$publicationAuteurs = array();
		
		//On construit l'objet utilisateur
		while ($donnees = $requete->fetch())
		{
			array_push($publicationAuteurs, $this->constructPublicationAuteur($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $publicationAuteurs;
	}
	
	// Retourne le nombre de publicationAuteur dans la base
	public function getNumberOfPublicationAuteur(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombrePublicationAuteur FROM publication_auteur');
		
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
		
		return $donnees['nombrePublicationAuteur'];
	}

	// Retourne le nombre de fois que l'auteur est cité dans une publication
	public function getNumberOfAuteurInPublication($idAuteur){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreCitationAuteur FROM publication_auteur WHERE id_auteur = :idAuteur');
		
		// Bind des paramètres
		$requete->bindValue(':idAuteur', $idAuteur, \PDO::PARAM_INT);

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
		
		return $donnees['nombreCitationAuteur'];
	}
	
	//permet de contruire un objet publicationAuteur a partir des ses données de la base.
	protected function constructPublicationAuteur($donnee){
		
		$pdoPublication = new PDOPublicationManager($this->dao);
		$pdoAuteur = new PDOAuteurManager($this->dao);
		
		$data = [
		'Auteur' => $pdoAuteur->getAuteurById($donnee['id_auteur']),
		'Publication' => $pdoPublication->getPublicationById($donnee['id_publication'])
		];
		return new PublicationAuteur($data);
	}
}
