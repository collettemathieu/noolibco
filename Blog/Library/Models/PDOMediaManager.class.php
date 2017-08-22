<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des medias. 					 	  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des medias
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Media;
use \Library\Models;

class PDOMediaManager extends \Library\Models\MediaManager
{

/* Définitions des méthodes action de la classe */

	// Ajout d'un media à la base
	public function addMedia($media){

		 if($media instanceof Media){
		
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO media 
					(url_media, url_media_miniature) 
					VALUES (:urlMedia, :urlMediaMiniature)");
			
			//bind des valeurs
			$requete->bindValue(':urlMedia', $media->getUrlMedia(), \PDO::PARAM_STR);
			$requete->bindValue(':urlMediaMiniature', $media->getUrlMediaMiniature(), \PDO::PARAM_STR);

			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$media->setIdMedia($this->dao->lastInsertId('id_media'));
				$this->dao->commit();
				
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//On libère la requête
			$requete->closeCursor();
			
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Media : L\'objet passé en paramètre n\'est pas une instance de Media');
		}
	}

	// Supprime un media de la base.
	public function deleteMedia($media){

		 if($media instanceof Media){	

			// Suppression de l'media
			$requete = $this->dao->prepare("DELETE FROM media WHERE id_media = :idMedia");

			//bind des valeurs
			$requete->bindValue(':idMedia', $media->getIdMedia(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Media : L\'objet passé en paramètre n\'est pas une instance de Media');
		}
	}
	
	
	// Sélection d'un media par son ID
	public function getMediaById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM media WHERE id_media = :idMedia");
		
		//bind des paramètres
		$requete->bindValue(':idMedia', $id, \PDO::PARAM_INT);
		
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
			$media = $this->constructMedia($donnees[0]);
			return $media;
		}
	}

	
	// Renvoi un tableau de toutes les medias
	public function getAllMedias(){
		
		//préparation de la requête
		$requete = $this->dao->prepare("SELECT * FROM media");
		
		//exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//création d'un tableau d'medias
		$medias = array();
		
		//On construit l'objet media
		while ($donnees = $requete->fetch())
		{
			array_push($medias, $this->constructMedia($donnees));
		}
		
		//On libère la requête
		$requete->closeCursor();
		
		return $medias;
	}
	
	// Retourne le nombre d'medias dans la base
	public function getNumberOfMedias(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreMedias FROM media');
		
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
		
		return $donnees['nombreMedias'];
	}

	// Place les articles associés au media dans l'objet media
	public function putArticlesInMedia($media){

		 if($media instanceof Media){
	
			$requete = $this->dao->prepare("SELECT * FROM article_media WHERE id_media = :idMedia");
		
			//bind des paramètres
			$requete->bindValue(':idMedia', $media->getIdMedia(), \PDO::PARAM_INT);
			
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
			$pdoArticle = new PDOArticleManager($this->dao);
			
			//On construit l'objet mot-clé
			while ($donnees = $requete->fetch())
			{
				$media->addArticle($pdoArticle->getArticleById($donnees['id_article']));
			}
		
			//On libère la requête
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Media : L\'objet passé en paramètre n\'est pas une instance de Media');
		}
	}
	
	// Permet de contruire un objet media à partir des données de la base.
	protected function constructMedia($donnee){
		
		$data = [
			'idMedia' => $donnee['id_media'],
			'urlMedia' => $donnee['url_media'],
			'urlMediaMiniature' => $donnee['url_media_miniature']
		];

		$media = new Media($data);
		$this->putArticlesInMedia($media);
		return $media;
	}
}
