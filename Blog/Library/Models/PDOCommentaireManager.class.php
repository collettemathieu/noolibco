<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des commentaires.					  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des commentaires
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Commentaire;
use \Library\Models;

class PDOCommentaireManager extends \Library\Models\CommentaireManager
{

/* Définitions des méthodes action de la classe */

	// Ajout d'un commentaire à la base
	public function addCommentaire($commentaire){

		 if($commentaire instanceof Commentaire){
		
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO commentaire 
					(texte_commentaire, date_commentaire, en_attente_validation_auteur_commentaire, en_ligne_commentaire) 
					VALUES (:texteCommentaire, CURDATE(), :enAttenteValidationAuteurCommentaire, :enLigneCommentaire)");

			//bind des valeurs
			$requete->bindValue(':texteCommentaire', $commentaire->getTexteCommentaire(), \PDO::PARAM_STR);
			$requete->bindValue(':enLigneCommentaire', $commentaire->getEnLigneCommentaire(), \PDO::PARAM_BOOL);
			$requete->bindValue(':enAttenteValidationAuteurCommentaire', $commentaire->getEnAttenteValidationAuteurCommentaire(), \PDO::PARAM_BOOL);
			
			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$commentaire->setIdCommentaire($this->dao->lastInsertId('id_commentaire'));
				$this->dao->commit();
				
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			// Ajout des liens entre le commentaire et l'article et l'utilisateur dans les tables
			if ($this->addAuteurFromCommentaire($commentaire)){
				$article = $commentaire->getArticle();
				$cours = $commentaire->getCours();
				if($article instanceof \Library\Entities\Article){
					$this->addArticleFromCommentaire($commentaire);
				}elseif($cours instanceof \Library\Entities\Cours){
					$this->addCoursFromCommentaire($commentaire);
				}else{
					return false;
				}
			}
			else {
				return false;
			}

			//On libère la requête
			$requete->closeCursor();
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Commentaire : L\'objet passé en paramètre n\'est pas une instance de Commentaire');
		}
	}

	//Ajout des liens entre auteur et commentaire.
	public function addAuteurFromCommentaire($commentaire){
		
		if($commentaire instanceof Commentaire){
				
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT IGNORE INTO utilisateur_commentaire (id_utilisateur, id_commentaire) VALUES (:idUtilisateur, :idCommentaire)");
			
			//bind des valeurs
			$requete->bindValue(':idUtilisateur', $commentaire->getUtilisateur()->getIdUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':idCommentaire', $commentaire->getIdCommentaire(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::Commentaire : L\'objet passé en paramètre n\'est pas une instance de Commentaire');
		}
	}

	//Ajout des liens entre article et commentaire.
	public function addArticleFromCommentaire($commentaire){
		
		if($commentaire instanceof Commentaire){
				
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT IGNORE INTO article_commentaire (id_article, id_commentaire) VALUES (:idArticle, :idCommentaire)");
			
			//bind des valeurs
			$requete->bindValue(':idArticle', $commentaire->getArticle()->getIdArticle(), \PDO::PARAM_INT);
			$requete->bindValue(':idCommentaire', $commentaire->getIdCommentaire(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::Commentaire : L\'objet passé en paramètre n\'est pas une instance de Commentaire');
		}
	}

	//Ajout des liens entre cours et commentaire.
	public function addCoursFromCommentaire($commentaire){
		
		if($commentaire instanceof Commentaire){
				
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT IGNORE INTO cours_commentaire (id_cours, id_commentaire) VALUES (:idCours, :idCommentaire)");
			
			//bind des valeurs
			$requete->bindValue(':idCours', $commentaire->getCours()->getIdCours(), \PDO::PARAM_INT);
			$requete->bindValue(':idCommentaire', $commentaire->getIdCommentaire(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::Commentaire : L\'objet passé en paramètre n\'est pas une instance de Commentaire');
		}
	}
	
	// Sauvegarde les modifications d'un commentaire
	public function saveCommentaire($commentaire){

		 if($commentaire instanceof Commentaire){
	
			//préparation de la requête
			$requete = $this->dao->prepare("UPDATE commentaire
					SET texte_commentaire = :texteCommentaire, 
					date_commentaire = CURDATE(),
					en_attente_validation_auteur_commentaire = :enAttenteValidationAuteurCommentaire,
					en_ligne_commentaire = :enLigneCommentaire
					WHERE id_commentaire = :idCommentaire;");

			//bind des valeurs
			$requete->bindValue(':idCommentaire', $commentaire->getIdCommentaire(), \PDO::PARAM_INT);
			$requete->bindValue(':texteCommentaire', $commentaire->getTexteCommentaire(), \PDO::PARAM_STR);
			$requete->bindValue(':enLigneCommentaire', $commentaire->getEnLigneCommentaire(), \PDO::PARAM_BOOL);
			$requete->bindValue(':enAttenteValidationAuteurCommentaire', $commentaire->getEnAttenteValidationAuteurCommentaire(), \PDO::PARAM_BOOL);
			

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
			$messageClient->addErreur('PDO::Commentaire : L\'objet passé en paramètre n\'est pas une instance de Commentaire');
		}
	}
	
	// Supprimer le lien entre les articles et le commentaire associé
	public function deleteLinkbetweenCommentaireArticles($commentaire){

		 if($commentaire instanceof Commentaire){
			//préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM article_commentaire WHERE id_commentaire = :idCommentaire;");

			//bind des valeurs
			$requete->bindValue(':idCommentaire', $commentaire->getIdCommentaire(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Commentaire : L\'objet passé en paramètre n\'est pas une instance de Commentaire');
		}
	}

	// Supprimer le lien entre les cours et le commentaire associé
	public function deleteLinkbetweenCommentaireCours($commentaire){

		 if($commentaire instanceof Commentaire){
			//préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM cours_commentaire WHERE id_commentaire = :idCommentaire;");

			//bind des valeurs
			$requete->bindValue(':idCommentaire', $commentaire->getIdCommentaire(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Commentaire : L\'objet passé en paramètre n\'est pas une instance de Commentaire');
		}
	}

	// Supprimer le lien entre les utilisateurs et le commentaire associé
	public function deleteLinkbetweenCommentaireUtilisateurs($commentaire){

		 if($commentaire instanceof Commentaire){
			//préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM utilisateur_commentaire WHERE id_commentaire = :idCommentaire;");

			//bind des valeurs
			$requete->bindValue(':idCommentaire', $commentaire->getIdCommentaire(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Commentaire : L\'objet passé en paramètre n\'est pas une instance de Commentaire');
		}
	}
	
	// Supprime un commentaire de la base.
	public function deleteCommentaire($commentaire){

		 if($commentaire instanceof Commentaire){	

			// Suppression des liens
			// Suppression de la liaison entre les utilisateurs et le commentaire
			$this->deleteLinkbetweenCommentaireUtilisateurs($commentaire);

			// Suppression de la liaison entre les articles et le commentaire
			$this->deleteLinkbetweenCommentaireArticles($commentaire);

			// Suppression de la liaison entre les cours et le commentaire
			$this->deleteLinkbetweenCommentaireCours($commentaire);

			// Suppression du commentaire
			$requete = $this->dao->prepare("DELETE FROM commentaire WHERE id_commentaire = :idCommentaire");

			//bind des valeurs
			$requete->bindValue(':idCommentaire', $commentaire->getIdCommentaire(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Commentaire : L\'objet passé en paramètre n\'est pas une instance de Commentaire');
		}
	}
	
	
	// Sélection d'un commentaire par son ID
	public function getCommentaireById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM commentaire WHERE id_commentaire = :idCommentaire");
		
		//bind des paramètres
		$requete->bindValue(':idCommentaire', $id, \PDO::PARAM_INT);
		
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
			$commentaire = $this->constructCommentaire($donnees[0]);
			return $commentaire;
		}
	}

	
	// Renvoi un tableau de toutes les commentaires
	public function getAllCommentaires(){
		
		//préparation de la requête
		$requete = $this->dao->prepare("SELECT * FROM commentaire");
		
		//exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//création d'un tableau de commentaires
		$commentaires = array();
		
		//On construit l'objet commentaire
		while ($donnees = $requete->fetch())
		{
			array_push($commentaires, $this->constructCommentaire($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $commentaires;
	}
	
	// Renvoi un tableau de commentaires à partir de l'index début jusqu'à debut + quantité
	public function getCommentairesBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM commentaire LIMIT :debut,:quantite");
		
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
		//création d'un tableau d'commentaires
		$commentaires = array();
		
		//On construit l'objet commentaire
		while ($donnees = $requete->fetch())
		{
			array_push($commentaires, $this->constructCommentaire($donnees));
		}
		
		//On libère la requête
		$requete->closeCursor();
		
		return $commentaires;
	}
	
	// Place l'article associé au commentaire dans l'objet commentaire
	public function putArticleInCommentaire($commentaire){

		 if($commentaire instanceof Commentaire){
	
			$requete = $this->dao->prepare("SELECT * FROM article_commentaire WHERE id_commentaire = :idCommentaire");
		
			//bind des paramètres
			$requete->bindValue(':idCommentaire', $commentaire->getIdCommentaire(), \PDO::PARAM_INT);
			
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

			//Appel du pdo des articles
			$pdoArticle = new PDOArticleManager($this->dao);
			
			//On met à jour l'objet commentaire
			$commentaire->setArticle($pdoArticle->getArticleById($donnees['id_article']));
			
			//On libère la requête
			$requete->closeCursor();

			return $commentaire;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Commentaire : L\'objet passé en paramètre n\'est pas une instance de Commentaire');
		}
	}

	// Place le cours associé au commentaire dans l'objet commentaire
	public function putCoursInCommentaire($commentaire){

		 if($commentaire instanceof Commentaire){
	
			$requete = $this->dao->prepare("SELECT * FROM cours_commentaire WHERE id_commentaire = :idCommentaire");
		
			//bind des paramètres
			$requete->bindValue(':idCommentaire', $commentaire->getIdCommentaire(), \PDO::PARAM_INT);
			
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

			//Appel du pdo des cours
			$pdoCours = new PDOCoursManager($this->dao);
			
			//On met à jour l'objet commentaire
			$commentaire->setCours($pdoCours->getCoursById($donnees['id_cours']));
			
			//On libère la requête
			$requete->closeCursor();

			return $commentaire;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Commentaire : L\'objet passé en paramètre n\'est pas une instance de Commentaire');
		}
	}

	// Place l'utilisateur associé au commentaire dans l'objet commentaire
	public function putUtilisateurInCommentaire($commentaire){

		 if($commentaire instanceof Commentaire){
	
			$requete = $this->dao->prepare("SELECT * FROM utilisateur_commentaire WHERE id_commentaire = :idCommentaire");
		
			//bind des paramètres
			$requete->bindValue(':idCommentaire', $commentaire->getIdCommentaire(), \PDO::PARAM_INT);
			
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

			//Appel du pdo des utilisateurs
			$pdoUtilisateur = new PDOUtilisateurManager($this->dao);
			
			//On met à jour l'objet commentaire
			$commentaire->setUtilisateur($pdoUtilisateur->getUtilisateurById($donnees['id_utilisateur']));
			
			//On libère la requête
			$requete->closeCursor();

		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Commentaire : L\'objet passé en paramètre n\'est pas une instance de Commentaire');
		}
	}

	
	// Retourne le nombre de commentaires dans la base
	public function getNumberOfCommentaires(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreCommentaires FROM commentaire');
		
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
		
		return $donnees['nombreCommentaires'];
	}
	
	// Permet de construire un objet commentaire à partir des données de la base.
	protected function constructCommentaire($donnee){

		$data = [
			'idCommentaire' => $donnee['id_commentaire'],
			'dateCommentaire' => $donnee['date_commentaire'],
			'texteCommentaire' => $donnee['texte_commentaire'],
			'enLigneCommentaire' => (bool) $donnee['en_ligne_commentaire'],
			'enAttenteValidationAuteurCommentaire' => (bool) $donnee['en_attente_validation_auteur_commentaire']
		];
		$commentaire = new Commentaire($data);
		$this->putUtilisateurInCommentaire($commentaire);
		return $commentaire;
	}
}
