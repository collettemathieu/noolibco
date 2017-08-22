<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO des newsletters.					  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO des newsletters
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Newsletter;
use \Library\Models;

class PDONewsletterManager extends \Library\Models\NewsletterManager
{

/* Définitions des méthodes action de la classe */

	// Ajout d'une newsletter à la base
	public function addNewsletter($newsletter){

		 if($newsletter instanceof Newsletter){
		
			//préparation de la requête
			$requete = $this->dao->prepare("INSERT INTO newsletter 
					(titre_newsletter, texte_newsletter, date_newsletter) 
					VALUES (:titreNewsletter, :texteNewsletter, CURDATE())");

			//bind des valeurs
			$requete->bindValue(':texteNewsletter', $newsletter->getTexteNewsletter(), \PDO::PARAM_STR);
			$requete->bindValue(':titreNewsletter', $newsletter->getTitreNewsletter(), \PDO::PARAM_STR);
			
			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$motCle->setIdNewsletter($this->dao->lastInsertId('id_newsletter'));
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
			$messageClient->addErreur('PDO::Newsletter : L\'objet passé en paramètre n\'est pas une instance de Newsletter');
		}
	}
	

	// Sauvegarde les modifications d'une newsletter
	public function saveNewsletter($newsletter){

		 if($newsletter instanceof Newsletter){
	
			//préparation de la requête
			$requete = $this->dao->prepare("UPDATE newsletter
					SET titre_newsletter = :titreNewsletter,
					texte_newsletter = :texteNewsletter, 
					date_newsletter = CURDATE()
					WHERE id_newsletter = :idNewsletter;");

			//bind des valeurs
			$requete->bindValue(':texteNewsletter', $newsletter->getTexteNewsletter(), \PDO::PARAM_STR);
			$requete->bindValue(':titreNewsletter', $newsletter->getTitreNewsletter(), \PDO::PARAM_STR);
			
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
			$messageClient->addErreur('PDO::Newsletter : L\'objet passé en paramètre n\'est pas une instance de Newsletter');
		}
	}
	
	// Supprime une newsletter de la base.
	public function deleteNewsletter($newsletter){

		 if($newsletter instanceof Newsletter){	

			// Suppression du newsletter
			$requete = $this->dao->prepare("DELETE FROM newsletter WHERE id_newsletter = :idNewsletter");

			//bind des valeurs
			$requete->bindValue(':idNewsletter', $newsletter->getIdNewsletter(), \PDO::PARAM_INT);

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
			$messageClient->addErreur('PDO::Newsletter : L\'objet passé en paramètre n\'est pas une instance de Newsletter');
		}
	}
	
	
	// Sélection d'une newsletter par son ID
	public function getNewsletterById($id){
		
		$requete = $this->dao->prepare("SELECT * FROM newsletter WHERE id_newsletter = :idNewsletter");
		
		//bind des paramètres
		$requete->bindValue(':idNewsletter', $id, \PDO::PARAM_INT);
		
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
			$newsletter = $this->constructNewsletter($donnees[0]);
			return $newsletter;
		}
	}

	
	// Renvoi un tableau de toutes les newsletters
	public function getAllNewsletters(){
		
		//préparation de la requête
		$requete = $this->dao->prepare("SELECT * FROM newsletter");
		
		//exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Erreur!: " . $e->getMessage() . "</br>";
		}
		//création d'un tableau de newsletters
		$newsletters = array();
		
		//On construit l'objet newsletter
		while ($donnees = $requete->fetch())
		{
			array_push($newsletters, $this->constructNewsletter($donnees));
		}
		
		//On libère la requete
		$requete->closeCursor();
		
		return $newsletters;
	}
	
	// Renvoi un tableau de newsletters à partir de l'index début jusqu'à debut + quantité
	public function getNewslettersBetweenIndex( $debut,  $quantite){

		$requete = $this->dao->prepare("SELECT * FROM newsletter LIMIT :debut,:quantite");
		
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
		//création d'un tableau d'newsletters
		$newsletters = array();
		
		//On construit l'objet newsletter
		while ($donnees = $requete->fetch())
		{
			array_push($newsletters, $this->constructNewsletter($donnees));
		}
		
		//On libère la requête
		$requete->closeCursor();
		
		return $newsletters;
	}
	
	// Place les utilisateurs souhaitant recevoir la newsletter dans l'objet newsletter
	public function putUtilisateursInNewsletter($newsletter){

		 if($newsletter instanceof Newsletter){
	
			$requete = $this->dao->prepare("SELECT * FROM utilisateur_newsletter WHERE id_newsletter = :idNewsletter");
		
			//bind des paramètres
			$requete->bindValue(':idNewsletter', $newsletter->getIdNewsletter(), \PDO::PARAM_INT);
			
			//exécution de la requête sinon envoi d'une erreur
			try {
				$this->dao->beginTransaction();
				$requete->execute();
				$this->dao->commit();
			} catch(PDOException $e) {
				$this->dao->rollback();
				return "Erreur!: " . $e->getMessage() . "</br>";
			}
			//Appel du pdo des utilisateurs
			$pdoUtilisateur = new PDOUtilisateurManager($this->dao);
			
			//On met à jour l'objet newsletter
			while ($donnees = $requete->fetch())
			{
				$newsletter->addUtilisateur($pdoUtilisateur->getUtilisateurById($donnees['id_utilisateur']));
			}
			
			//On libère la requête
			$requete->closeCursor();

		}else{
			$messageClient = new \Library\MessageClient;
			$messageClient->addErreur('PDO::Newsletter : L\'objet passé en paramètre n\'est pas une instance de Newsletter');
		}
	}

	
	// Retourne le nombre de newsletters dans la base
	public function getNumberOfNewsletters(){
		$requete = $this->dao->prepare('SELECT COUNT(*) AS nombreNewsletters FROM newsletter');
		
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
		
		return $donnees['nombreNewsletters'];
	}
	
	// Permet de construire un objet newsletter à partir des données de la base.
	protected function constructNewsletter($donnee){


		$data = [
			'idNewsletter' => $donnee['id_newsletter'],
			'dateNewsletter' => $donnee['date_newsletter'],
			'texteNewsletter' => $donnee['texte_newsletter'],
			'titreNewsletter' => $donnee['titre_newsletter'],
		];
		return new Newsletter($data);
	}
}
