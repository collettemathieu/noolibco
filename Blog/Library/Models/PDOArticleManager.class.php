<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des articles. 						  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des articles
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Article;
use \Library\Models;

class PDOArticleManager extends \Library\Models\ArticleManager
{

/* Définitions des méthodes action de la classe */

	// Ajout d'un article à la base
	public function addArticle($article){

		 if($article instanceof Article){
		
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO article 
					(titre_article, description_article, texte_article, references_article, en_ligne_article, date_creation_article, note_article, nbre_vote_article, nbre_vue_article, url_image_article, url_image_miniature_article, url_titre_article, id_categorie) 
					VALUES (:titreArticle, :descriptionArticle, :texteArticle, :referencesArticle, :enLigneArticle, CURDATE(), :noteArticle, :nbreVoteArticle, :nbreVueArticle, :urlImageArticle, :urlImageMiniatureArticle, :urlTitreArticle, :idCategorie)");
			
			//bind des valeurs
			$requete->bindValue(':titreArticle', $article->getTitreArticle(), \PDO::PARAM_STR);
			$requete->bindValue(':descriptionArticle', $article->getDescriptionArticle(), \PDO::PARAM_STR);
			$requete->bindValue(':texteArticle', $article->getTexteArticle(), \PDO::PARAM_STR);
			$requete->bindValue(':referencesArticle', $article->getReferencesArticle(), \PDO::PARAM_STR);
			$requete->bindValue(':enLigneArticle', $article->getEnLigneArticle(), \PDO::PARAM_BOOL);
			$requete->bindValue(':noteArticle', $article->getNoteArticle(), \PDO::PARAM_STR);
			$requete->bindValue(':nbreVoteArticle', $article->getNbreVoteArticle(), \PDO::PARAM_INT);
			$requete->bindValue(':nbreVueArticle', $article->getNbreVueArticle(), \PDO::PARAM_INT);
			$requete->bindValue(':urlImageArticle', $article->getUrlImageArticle(), \PDO::PARAM_STR);
			$requete->bindValue(':urlImageMiniatureArticle', $article->getUrlImageArticle(), \PDO::PARAM_STR);
			$requete->bindValue(':urlTitreArticle', $article->getUrlTitreArticle(), \PDO::PARAM_STR);
			$requete->bindValue(':idCategorie', $article->getCategorie()->getIdCategorie(), \PDO::PARAM_INT);
			
			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$article->setIdArticle($this->dao->lastInsertId('id_article'));
				$this->dao->commit();
				
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Error!: " . $e->getMessage() . "</br>";
			}

			//On libère la requête
			$requete->closeCursor();
			
			// Ajout des mot-clés et des utilisateurs liés à l'article dans les tables
			if ($this->addMotsClesFromArticle($article) and $this->addAuteurFromArticle($article)){
				return true;
			}
			else {	
				return false;
			}
		
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Article : L\'objet passé en paramètre n\'est pas une instance de Article');
		}
	}
	
	// Ajout des mot-clés de l'article
	public function addMotsClesFromArticle($article){

		 if($article instanceof Article){
			if (sizeof($article->getMotCles()) != 0){
			
				foreach ($article->getMotCles() as $motCle){
						
					//préparation de la requête
					$requete = $this->dao->prepare("INSERT IGNORE INTO article_mot_cle (id_article, id_mot_cle) VALUES (:idArticle, :idMotCle)");
					
					//bind des valeurs
					$requete->bindValue(':idMotCle', $motCle->getIdMotCle(), \PDO::PARAM_INT);
					$requete->bindValue(':idArticle', $article->getIdArticle(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::Article : L\'objet passé en paramètre n\'est pas une instance de Article');
		}
	}

	//Ajout des liens entre auteur et article.
	public function addAuteurFromArticle($article){
		
		if($article instanceof Article){
				
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT IGNORE INTO article_utilisateur (id_utilisateur, id_article) VALUES (:idUtilisateur, :idArticle)");
			
			//bind des valeurs
			$requete->bindValue(':idUtilisateur', $article->getAuteur()->getIdUtilisateur(), \PDO::PARAM_INT);
			$requete->bindValue(':idArticle', $article->getIdArticle(), \PDO::PARAM_INT);
			
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
			$messageClient->addErreur('PDO::Article : L\'objet passé en paramètre n\'est pas une instance de Article');
		}
	}


	// Sauvegarde les modifications d'un article
	public function saveArticle($article){

		 if($article instanceof Article){
	
			//préparation de la requête
			$requete = $this->dao->prepare("UPDATE article
					SET titre_article = :titreArticle, 
					description_article = :descriptionArticle, 
					texte_article = :texteArticle, 
					references_article = :referencesArticle, 
					note_article = :noteArticle, 
					nbre_vote_article = :nbreVoteArticle, 
					nbre_vue_article = :nbreVueArticle,
					url_image_article = :urlImageArticle,
					url_image_miniature_article = :urlImageMiniatureArticle,
					url_titre_article = :urlTitreArticle,
					id_categorie = :idCategorie
					WHERE id_article = :idArticle;");

			//bind des valeurs
			$requete->bindValue(':idArticle', $article->getIdArticle(), \PDO::PARAM_INT);
			$requete->bindValue(':titreArticle', $article->getTitreArticle(), \PDO::PARAM_STR);
			$requete->bindValue(':descriptionArticle', $article->getDescriptionArticle(), \PDO::PARAM_STR);
			$requete->bindValue(':texteArticle', $article->getTexteArticle(), \PDO::PARAM_STR);
			$requete->bindValue(':referencesArticle', $article->getReferencesArticle(), \PDO::PARAM_STR);
			$requete->bindValue(':noteArticle', $article->getNoteArticle(), \PDO::PARAM_STR);
			$requete->bindValue(':nbreVoteArticle', $article->getNbreVoteArticle(), \PDO::PARAM_INT);
			$requete->bindValue(':nbreVueArticle', $article->getNbreVueArticle(), \PDO::PARAM_INT);
			$requete->bindValue(':urlImageArticle', $article->getUrlImageArticle(), \PDO::PARAM_STR);
			$requete->bindValue(':urlImageMiniatureArticle', $article->getUrlImageMiniatureArticle(), \PDO::PARAM_STR);
			$requete->bindValue(':urlTitreArticle', $article->getUrlTitreArticle(), \PDO::PARAM_STR);
			$requete->bindValue(':idCategorie', $article->getCategorie()->getIdCategorie(), \PDO::PARAM_INT);

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

			// Ajout des mot-clés et des utilisateurs liés à l'article dans les tables
			if ($this->addMotsClesFromArticle($article) and $this->addAuteurFromArticle($article)){
				return true;
			}
			else {
				return false;
			}
			
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Article : L\'objet passé en paramètre n\'est pas une instance de Article');
		}
	}

	// Publier/Dépublier d'un article
	public function publishArticle($article){

		 if($article instanceof Article){
	
			//préparation de la requête
			$requete = $this->dao->prepare("UPDATE article
					SET en_ligne_article = :enLigneArticle
					WHERE id_article = :idArticle;");

			//bind des valeurs
			$requete->bindValue(':idArticle', $article->getIdArticle(), \PDO::PARAM_INT);
			$requete->bindValue(':enLigneArticle', $article->getEnLigneArticle(), \PDO::PARAM_BOOL);
			
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
			$messageClient->addErreur('PDO::Article : L\'objet passé en paramètre n\'est pas une instance de Article');
		}
	}
	
	// Supprimer le lien entre les mots-clés et l'article associé
	public function deleteLinkbetweenArticleMotCles($article){

		 if($article instanceof Article){
			//préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM article_mot_cle WHERE id_article = :idArticle;");

			//bind des valeurs
			$requete->bindValue(':idArticle', $article->getIdArticle(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Article : L\'objet passé en paramètre n\'est pas une instance de Article');
		}
	}

	// Supprimer le lien entre l'auteur et l'article associé
	public function deleteLinkbetweenArticleAuteur($article){

		 if($article instanceof Article){
			//préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM article_utilisateur WHERE id_article = :idArticle;");

			//bind des valeurs
			$requete->bindValue(':idArticle', $article->getIdArticle(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Article : L\'objet passé en paramètre n\'est pas une instance de Article');
		}
	}
	
	// Supprime un article de la base.
	public function deleteArticle($article){

		 if($article instanceof Article){	

			// Suppression des liens
			// Suppression de la liaison entre les motclés et l'article
			$this->deleteLinkbetweenArticleMotCles($article);

			// Suppression de la liaison entre l'auteur et l'article
			$this->deleteLinkbetweenArticleAuteur($article);

			// Suppression de l'article
			$requete = $this->dao->prepare("DELETE FROM article WHERE id_article = :idArticle");

			//bind des valeurs
			$requete->bindValue(':idArticle', $article->getIdArticle(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Article : L\'objet passé en paramètre n\'est pas une instance de Article');
		}
	}
	
	
	// Sélection d'un article par son ID
	public function getArticleById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM article WHERE id_article = :idArticle");
		
		//bind des paramètres
		$requete->bindValue(':idArticle', $id, \PDO::PARAM_INT);
		
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
			$article = $this->constructArticle($donnees[0]);
			return $article;
		}
	}

	// Sélection d'un article par son titre encodé en URL
	public function getArticleByUrlTitle($urlTitreArticle){
		
		$requete = $this->dao->prepare("SELECT * FROM article WHERE url_titre_article = :urlTitreArticle");
		
		//bind des paramètres
		$requete->bindValue(':urlTitreArticle', $urlTitreArticle, \PDO::PARAM_STR);
		
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
			$article = $this->constructArticle($donnees[0]);
			return $article;
		}
	}

	// Sélection d'un article par son mot-clé
	public function getArticleByIdMotCle($idMotCle){
		
		$requete = $this->dao->prepare("SELECT * FROM article_mot_cle WHERE id_mot_cle = :idMotCle");
		
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
		
		$articles = array();
		while($donnees = $requete->fetch()){

			$article = $this->getArticleById($donnees['id_article']);

			if($article instanceof Article){
				array_push($articles, $article);
			}

		}
		
		$requete->closeCursor();

		return $articles;
	}

	
	// Renvoi un tableau de toutes les articles
	public function getAllArticles(){
		
		//préparation de la requête
		$requete = $this->dao->prepare("SELECT * FROM article ORDER BY date_creation_article DESC");
		
		//exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//création d'un tableau d'articles
		$articles = array();
		
		//On construit l'objet article
		while ($donnees = $requete->fetch())
		{
			array_push($articles, $this->constructArticle($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $articles;
	}
	
	// Renvoi un tableau d'articles à partir de l'index début jusqu'à debut + quantité
	public function getArticlesBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM article LIMIT :debut,:quantite");
		
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
		//création d'un tableau d'articles
		$articles = array();
		
		//On construit l'objet article
		while ($donnees = $requete->fetch())
		{
			array_push($articles, $this->constructArticle($donnees));
		}
		
		//On libère la requête
		$requete->closeCursor();
		
		return $articles;
	}
	
	// Place les mots-clés associés à l'article dans l'objet article
	public function putMotsClesInArticle($article){

		 if($article instanceof Article){
	
			$requete = $this->dao->prepare("SELECT * FROM article_mot_cle WHERE id_article = :idArticle");
		
			//bind des paramètres
			$requete->bindValue(':idArticle', $article->getIdArticle(), \PDO::PARAM_INT);
			
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
				$article->addMotCle($pdoMotCle->getMotCleById($donnees['id_mot_cle']));
			}
		
			//On libère la requête
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Article : L\'objet passé en paramètre n\'est pas une instance de Article');
		}
	}

	// Place l'auteur associé à l'article dans l'objet article
	public function putAuteurInArticle($article){

		 if($article instanceof Article){
	
			$requete = $this->dao->prepare("SELECT * FROM article_utilisateur WHERE id_article = :idArticle");
		
			//bind des paramètres
			$requete->bindValue(':idArticle', $article->getIdArticle(), \PDO::PARAM_INT);
			
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

			//On met à jour l'objet article
			$article->setAuteur($pdoUtilisateur->getUtilisateurById($donnees['id_utilisateur']));
		
			//On libère la requête
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Article : L\'objet passé en paramètre n\'est pas une instance de Article');
		}
	}

	// Place les commentaires associé à l'article dans l'objet article
	public function putCommentairesInArticle($article){

		 if($article instanceof Article){
	
			$requete = $this->dao->prepare("SELECT * FROM article_commentaire WHERE id_article = :idArticle");
		
			//bind des paramètres
			$requete->bindValue(':idArticle', $article->getIdArticle(), \PDO::PARAM_INT);
			
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
				$article->addCommentaire($pdoCommentaire->getCommentaireById($donnees['id_commentaire']));
			}
		
			//On libère la requête
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Article : L\'objet passé en paramètre n\'est pas une instance de Article');
		}
	}

	
	// Retourne le nombre d'articles dans la base
	public function getNumberOfArticles(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreArticles FROM article WHERE en_ligne_article = 1');
		
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
		
		return $donnees['nombreArticles'];
	}
	
	// Permet de contruire un objet article à partir des données de la base.
	protected function constructArticle($donnee){
		
		$pdoCategorie = new PDOCategorieManager($this->dao);
	
		$data = [
			'idArticle' => $donnee['id_article'],
			'titreArticle' => $donnee['titre_article'],
			'descriptionArticle' => $donnee['description_article'],
			'dateCreationArticle' => $donnee['date_creation_article'],
			'texteArticle' => $donnee['texte_article'],
			'referencesArticle' => $donnee['references_article'],
			'enLigneArticle' => (bool) $donnee['en_ligne_article'],
			'noteArticle' => (float) $donnee['note_article'],
			'nbreVoteArticle' => (int) $donnee['nbre_vote_article'],
			'nbreVueArticle' => (int) $donnee['nbre_vue_article'],
			'urlImageArticle' => $donnee['url_image_article'],
			'urlImageMiniatureArticle' => $donnee['url_image_miniature_article'],
			'categorie' => $pdoCategorie->getCategorieById($donnee['id_categorie'])
		];
		$article = new Article($data);

		$this->putMotsClesInArticle($article);
		$this->putAuteurInArticle($article);
		$this->putCommentairesInArticle($article);
		return $article;
	}
}
