<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP pour le manager PDO de sphinx.					  		  |
// +----------------------------------------------------------------------+
// | Auteurs : Antoine FAUCHARD <AntoineFauchard@noolib.com>			  |
// |		   Guénaël DEQUEKER <dequekerguenael@noolib.com>    		  |
// | 		   Mathieu COLLETTE <collettemathieu@noolib.com>   			  |
// +----------------------------------------------------------------------+

/**
 * @name: Manager PDO de sphinx
 * @access: public
 * @version: 1
 */	

namespace Library\Models;


class PDOSphinxManager extends \Library\Models\SphinxManager{

/* Définitions des méthodes action de la classe */

	//Méthode pour chercher une application par mot-clé
	public function searchSphinxApplicationByMotCle($motRecherche){
		
		//préparation de la requête
		$requete = $this->dao->prepare("SELECT id_mot_cle FROM mot_cle WHERE nom_mot_cle LIKE '$motRecherche%' ORDER BY nom_mot_cle DESC");

		//exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
			
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Error!: " . $e->getMessage() . "</br>";
		}

		// Création d'une liste d'applications
		$listeApplications = array();

		// On récupère les applications associés aux mots-clés
		$pdoApplication = new PDOApplicationManager($this->dao);
		
		while ($donnees = $requete->fetch()){
			$applications = $pdoApplication->getApplicationsByIdMotCle($donnees['id_mot_cle']);
			
			if(count($applications) != 0){
				$listeApplications = array_merge($listeApplications, $applications);
			}
		}

		//On libère la requête
		$requete->closeCursor();

		return $listeApplications;	
		
	}

	//Méthode pour chercher une application par nom de l'application
	public function searchSphinxApplicationByNom($motRecherche){
		
		//préparation de la requête
		$requete = $this->dao->prepare("SELECT id_application FROM application WHERE nom_application LIKE '$motRecherche%' ORDER BY nom_application DESC");

		//exécution de la requête sinon envoi d'une erreur
		try {
			$this->dao->beginTransaction();
			$requete->execute();
			$this->dao->commit();
			
		} catch(PDOException $e) {
			$this->dao->rollback();
			return "Error!: " . $e->getMessage() . "</br>";
		}

		// Création d'une liste d'applications
		$liste = array();

		// On récupère les applications associés aux mots-clés
		$pdoApplication = new PDOApplicationManager($this->dao);
		$donnees = $requete->fetchall();

		foreach($donnees as $key=>$donnee) {

			$application = $pdoApplication->getApplicationById($donnee['id_application']);
			
			if($application !=false){
				$liste[$key] = $application;
			}
		}

		//On libère la requête
		$requete->closeCursor();
		
		return $liste;	
		
	}
	
}