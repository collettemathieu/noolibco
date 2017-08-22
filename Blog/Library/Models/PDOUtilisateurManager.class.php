<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des Utilisateurs. 					  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Manager PDO des utilisateurs
 * @access: public
 * @version : 1
 */
namespace Library\Models;

use Library\Entities\Utilisateur;

class PDOUtilisateurManager extends \Library\Models\UtilisateurManager {
	
	/* Définitions des méthodes action de la classe */
	
	// Méthode pour ajouter un utilisateur.
	public function addUtilisateur($utilisateur){

		if($utilisateur instanceof Utilisateur){
	
			// préparation de la requête d'insertion dans une base de données
			$requete = $this->dao->prepare("INSERT INTO utilisateur
					(nom_utilisateur, mail_utilisateur,	date_inscription_utilisateur, password_admin_utilisateur,
					 super_admin_utilisateur, newsletter_utilisateur)
					VALUES (:nomUtilisateur, :mailUtilisateur, CURDATE(), 
						:passwordAdminUtilisateur, :superAdminUtilisateur, :newsletterUtilisateur)");
			
			// Bind des paramètres
			$requete->bindValue ( ':nomUtilisateur', $utilisateur->getNomUtilisateur (), \PDO::PARAM_STR );
			$requete->bindValue ( ':mailUtilisateur', $utilisateur->getMailUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':passwordAdminUtilisateur', $utilisateur->getPasswordAdminUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':superAdminUtilisateur', $utilisateur->getSuperAdminUtilisateur(), \PDO::PARAM_BOOL );
			$requete->bindValue ( ':newsletterUtilisateur', $utilisateur->getNewsletterUtilisateur(), \PDO::PARAM_BOOL );
			
			// exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$utilisateur->setIdUtilisateur ( $this->dao->lastInsertId ('id_utilisateur') );
				$this->dao->commit();

			} catch ( PDOException $e ) {
				$this->dao->rollback ();
				return "Erreur!: " . $e->getMessage () . "</br>";
			}
		
			// On libère la requête
			$requete->closeCursor ();

			return $utilisateur;
		
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
					mail_utilisateur = :mailUtilisateur,
					newsletter_utilisateur = :newsletterUtilisateur
					WHERE id_utilisateur = :idUtilisateur" );
			
			// Bind des paramètres
			$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_INT );
			$requete->bindValue ( ':nomUtilisateur', $utilisateur->getNomUtilisateur (), \PDO::PARAM_STR );
			$requete->bindValue ( ':mailUtilisateur', $utilisateur->getMailUtilisateur(), \PDO::PARAM_STR );
			$requete->bindValue ( ':newsletterUtilisateur', $utilisateur->getNewsletterUtilisateur(), \PDO::PARAM_BOOL );
			
			// Execution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction ();
				$requete->execute ();
				$this->dao->commit ();
			} catch ( PDOException $e ) {
				$this->dao->rollback ();
				return "Erreur!: " . $e->getMessage () . "</br>";
			}
			
			// On libère la requête
			$requete->closeCursor ();
			
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}


	// Supprimer le lien entre les articles et l'utilisateur associé
	public function deleteLinkBetweenArticlesUtilisateur($utilisateur){

		 if($utilisateur instanceof Utilisateur){
			//préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM article_utilisateur WHERE id_utilisateur = :idUtilisateur;");

			//bind des valeurs
			$requete->bindValue(':idUtilisateur', $utilisateur->getIdUtilisateur(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}

	// Supprimer le lien entre les cours et l'utilisateur associé
	public function deleteLinkBetweenCoursUtilisateur($utilisateur){

		 if($utilisateur instanceof Utilisateur){
			//préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM cours_utilisateur WHERE id_utilisateur = :idUtilisateur;");

			//bind des valeurs
			$requete->bindValue(':idUtilisateur', $utilisateur->getIdUtilisateur(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}
	

	// Supprimer le lien entre les commentaires et l'utilisateur associé
	public function deleteLinkBetweenCommentairesUtilisateur($utilisateur){

		 if($utilisateur instanceof Utilisateur){
			//préparation de la requête
			$requete = $this->dao->prepare("DELETE FROM utilisateur_commentaire WHERE id_utilisateur = :idUtilisateur;");

			//bind des valeurs
			$requete->bindValue(':idUtilisateur', $utilisateur->getIdUtilisateur(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}
	
	// Suppression de tous les commentaires de l'utilisateur
	public function deleteCommentaires($utilisateur){

		if($utilisateur instanceof Utilisateur){
			if (sizeof ( $utilisateur->getCommentaires () ) != 0) {
				
				foreach ( $utilisateur->getCommentaires () as $commentaire ) {
					
					$pdoCommentaire = new PDOCommentaireManager ( $this->dao );
					$pdoCommentaire->deleteCommentaire ( $commentaire );
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}

	// Suppression de tous les articles de l'utilisateur
	public function deleteArticles($utilisateur){

		if($utilisateur instanceof Utilisateur){
			if (sizeof ( $utilisateur->getArticles () ) != 0) {
				
				foreach ( $utilisateur->getArticles () as $article ) {
					
					$pdoArticle = new PDOArticleManager ( $this->dao );
					$pdoArticle->deleteArticle( $article );
				}
			}
			return true;
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}

	// Suppression de tous les cours de l'utilisateur
	public function deleteCours($utilisateur){

		if($utilisateur instanceof Utilisateur){
			if (sizeof ( $utilisateur->getCours () ) != 0) {
				
				foreach ( $utilisateur->getCours () as $cours ) {
					
					$pdoCours = new PDOCoursManager ( $this->dao );
					$pdoCours->deleteCours( $cours );
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
			// suppression de la liaison entre les articles et l'utilisateur
			$this->deleteLinkBetweenArticlesUtilisateur ( $utilisateur );
				
			// suppression de la liaison entre les cours et l'utilisateur
			$this->deleteLinkBetweenCoursUtilisateur ( $utilisateur );
			
			// suppression de la liaison entre les commentaires et l'utilisateur
			$this->deleteLinkBetweenCommentairesUtilisateur ( $utilisateur );
			
			// Suppression des commentaires de l'utilisateur
			$this->deleteCommentaires ( $utilisateur );

			// Suppression des cours de l'utilisateur
			$this->deleteCours ( $utilisateur );

			// Suppression des articles de l'utilisateur
			$this->deleteArticles ( $utilisateur );

			// Suppression de l'utilisateur lui-même
			$requete = $this->dao->prepare ( "DELETE FROM utilisateur WHERE id_utilisateur = :idUtilisateur" );
			// bind des paramètres
			
			$requete->bindValue ( ':idUtilisateur', $utilisateur->getIdUtilisateur (), \PDO::PARAM_INT );
			
			// exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction ();
				$requete->execute ();
				$this->dao->commit ();
			} catch ( PDOException $e ) {
				$this->dao->rollback ();
				return "Erreur!: " . $e->getMessage () . "</br>";
			}
			
			// On libère la requête
			$requete->closeCursor ();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}
	
	// Récupère un utilisateur par son ID et la renvoit au format Utilisateur
	public function getUtilisateurById($id) {
		$requete = $this->dao->prepare ( "SELECT * FROM utilisateur WHERE id_utilisateur = :idUtilisateur" );
		
		// bind des paramètres
		$requete->bindValue ( ':idUtilisateur', $id, \PDO::PARAM_INT );
		
		// exécution de la requête sinon envoi d'une erreur
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
		
		// bind des paramètres
		$requete->bindValue ( ':idUtilisateur', $id, \PDO::PARAM_INT );
		
		// exécution de la requête sinon envoi d'une erreur
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
			$this->putArticlesInUtilisateur($utilisateur);
			$this->putCoursInUtilisateur($utilisateur);
			$this->putCommentairesInUtilisateur($utilisateur);
			return $utilisateur;
		}
	}
	
	
	// Permet de récupérer l'utilisateur via son email et le retourne au format Utilisateur
	public function getUtilisateurByMail($mail) {
		$requete = $this->dao->prepare ( "SELECT * FROM utilisateur WHERE mail_utilisateur = :mailUtilisateur" );
	
		// bind des paramètres
		$requete->bindValue ( ':mailUtilisateur', $mail, \PDO::PARAM_STR );
	
		// exécution de la requête sinon envoi d'une erreur
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
			$this->putArticlesInUtilisateur($utilisateur);
			$this->putCoursInUtilisateur($utilisateur);
			$this->putCommentairesInUtilisateur($utilisateur);
			return $utilisateur;
		}
	}
	
	// Retourne un tableau de tous les utilisateurs au fromat array(Utilisateur);
	public function getAllUtilisateurs() {
		$requete = $this->dao->prepare ( "SELECT * FROM utilisateur ORDER BY mail_utilisateur" );
		
		// exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction ();
			$requete->execute ();
			$this->dao->commit ();
		} catch ( PDOException $e ) {
			$this->dao->rollback ();
			return "Erreur!: " . $e->getMessage () . "</br>";
		}
		// création d'un tableau d'utilisateur
		$utilisateurs = array ();
		
		// On construit l'objet utilisateur
		while ( $donnees = $requete->fetch () ) {
			array_push ( $utilisateurs, $this->constructUtilisateur ( $donnees ) );
		}
		
		// On libère la requête
		$requete->closeCursor ();
		
		return $utilisateurs;
	}
	
	// Récupere le tableau des utilisateurs au fromat array(Utilisateur) entre un début et une fin ;
	public function getUtilisateursBetweenIndex($debut, $quantite) {
		$requete = $this->dao->prepare ( "SELECT * FROM utilisateur LIMIT :debut, :quantite" );
		
		// bind des paramètres
		$requete->bindValue ( ':debut', $debut, \PDO::PARAM_INT );
		$requete->bindValue ( ':quantite', $quantite, \PDO::PARAM_INT );
		
		// exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction ();
			$requete->execute ();
			$this->dao->commit ();
		} catch ( PDOException $e ) {
			$this->dao->rollback ();
			return "Erreur!: " . $e->getMessage () . "</br>";
		}
		// création d'un tableau d'utilisateurs
		$utilisateurs = array ();
		
		// On construit l'objet utilisateur
		while ( $donnees = $requete->fetch () ) {
			array_push ( $utilisateurs, $this->constructUtilisateur ( $donnees ) );
		}
		
		// On libère la requête
		$requete->closeCursor ();
		
		return $utilisateurs;
	}
	
	// Méthode pour retourner le nombre d'utilisateurs dans la BDD
	public function getNumberOfUtilisateur() {
		$requete = $this->dao->prepare ( 'SELECT COUNT(*) AS nbreUser FROM utilisateur' );
		
		// exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction ();
			$requete->execute ();
			$this->dao->commit ();
		} catch ( PDOException $e ) {
			$this->dao->rollback ();
			return "Erreur!: " . $e->getMessage () . "</br>";
		}
		$donnees = $requete->fetch ();

		// On libère la requête
		$requete->closeCursor ();
		
		return $donnees ['nbreUser'];
	}

	// Place les articles associés à l'utilisateur dans l'objet utilisateur
	public function putArticlesInUtilisateur($utilisateur){

		 if($utilisateur instanceof Utilisateur){
	
			$requete = $this->dao->prepare("SELECT * FROM article_utilisateur WHERE id_utilisateur = :idUtilisateur");
		
			//bind des paramètres
			$requete->bindValue(':idUtilisateur', $utilisateur->getIdUtilisateur(), \PDO::PARAM_INT);
			
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
			$pdoArticle = new PDOArticleManager($this->dao);
			
			//On met à jour l'objet utilisateur
			while ($donnees = $requete->fetch())
			{
				$utilisateur->addArticle($pdoArticle->getArticleById($donnees['id_article']));
			}
		
			//On libère la requête
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}

	// Place les cours associés à l'utilisateur dans l'objet utilisateur
	public function putCoursInUtilisateur($utilisateur){

		 if($utilisateur instanceof Utilisateur){
	
			$requete = $this->dao->prepare("SELECT * FROM cours_utilisateur WHERE id_utilisateur = :idUtilisateur");
		
			//bind des paramètres
			$requete->bindValue(':idUtilisateur', $utilisateur->getIdUtilisateur(), \PDO::PARAM_INT);
			
			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			//création d'un tableau de cours
			$pdoCours = new PDOCoursManager($this->dao);
			
			//On met à jour l'objet utilisateur
			while ($donnees = $requete->fetch())
			{
				$utilisateur->addCours($pdoCours->getCoursById($donnees['id_cours']));
			}
		
			//On libère la requête
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}

	// Place les commentaires associés à l'utilisateur dans l'objet utilisateur
	public function putCommentairesInUtilisateur($utilisateur){

		 if($utilisateur instanceof Utilisateur){
	
			$requete = $this->dao->prepare("SELECT * FROM utilisateur_commentaire WHERE id_utilisateur = :idUtilisateur");
		
			//bind des paramètres
			$requete->bindValue(':idUtilisateur', $utilisateur->getIdUtilisateur(), \PDO::PARAM_INT);
			
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
			$pdoCommentaire = new PDOCommentaireManager($this->dao);
			
			//On met à jour l'objet utilisateur
			while ($donnees = $requete->fetch())
			{
				$utilisateur->addCommentaire($pdoCommentaire->getCommentaireById($donnees['id_commentaire']));
			}
		
			//On libère la requête
			$requete->closeCursor();
		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Utilisateur : L\'objet passé en paramètre n\'est pas une instance de Utilisateur');
		}
	}


	// Permet de contruire un objet utilisateur à partir des données de la base.
	protected function constructUtilisateur($donnee) {

		$data = [
		'idUtilisateur' => $donnee ['id_utilisateur'],
		'nomUtilisateur' => $donnee ['nom_utilisateur'],
		'mailUtilisateur' => $donnee ['mail_utilisateur'],
		'passwordAdminUtilisateur' => $donnee['password_admin_utilisateur'], 
		'superAdminUtilisateur' => (bool) $donnee['super_admin_utilisateur'],
		'dateInscriptionUtilisateur' => $donnee['date_inscription_utilisateur'],
		'newsletterUtilisateur' => (bool) $donnee['newsletter_utilisateur'],
		];
		
		return new Utilisateur ( $data );
	}
}