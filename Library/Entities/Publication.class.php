<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Publications. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe Publication
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe Publication
 */
class Publication extends \Library\Entity {
	protected $idPublication, $titrePublication, $anneePublication, $journalPublication, $urlPublication, $typePublication, 
	$utilisateur, $auteurs = array(), $utilisateurs = array(), $applications = array();
	
	
	const TITRE_PUBLICATION = 'The title of the publication must contain at least 10 letters in length.';
	const ANNEE_PUBLICATION = 'The year of publication must be in date format xxxx.';
	const URL_PUBLICATION_INVALIDE = 'The link for this publication is incorrect.';
	/**
	 * ******setters******
	 */

	public function setIdPublication($idPublication) {
		if (ctype_digit($idPublication) || is_int($idPublication)) {
			$this->idPublication = $idPublication;
		} else {
			$this->setErreurs("Publication setIdPublication " . self::FORMAT_INT);
		}
	}

	public function setTitrePublication($titrePublication) {
		if (is_string ( $titrePublication )) {
			if(strlen($titrePublication) >= 10){
				$this->titrePublication = $titrePublication;
			}
			else{
				$this->setErreurs(self::TITRE_PUBLICATION);
			}
		} else {
			$this->setErreurs("Publication setTitrePublication " . self::FORMAT_STRING);
		}
	}

	public function setAnneePublication($anneePublication) {
		if (ctype_digit($anneePublication) || is_int($anneePublication)){
			if(strlen($anneePublication) == 4){
				$this->anneePublication = $anneePublication;
			}
			else{
				$this->setErreurs(self::ANNEE_PUBLICATION);
			}
			
		} else {
			$this->setErreurs("Publication setAnneePublication " . self::FORMAT_INT);
		}
	}

	public function setJournalPublication($journalPublication) {
		if (is_string ($journalPublication) && !empty($journalPublication)) {
			$this->journalPublication = $journalPublication;
		} else {
			$this->setErreurs("Publication setJournalPublication " . self::FORMAT_STRING . " " . self::FORMAT_EMPTY);
		}
	}

	public function setUrlPublication($urlPublication) {
		if (is_string ( $urlPublication )) {
			//si la chaine est vide ou le filtre d'url valide est incorrect alors on envoi une erreur
			if(empty($urlPublication) || !filter_var($urlPublication, FILTER_VALIDATE_URL)){
				$this->setErreurs(self::URL_PUBLICATION_INVALIDE);
			}
			//sinon pas de problème
			else{
				$this->urlPublication = $urlPublication;
			}
			
		} else {
			$this->setErreurs("Publication setUrlPublication " . self::FORMAT_STRING);
		}
	}

	public function setTypePublication($typePublication) {
		if ($typePublication instanceof TypePublication) {
			$this->typePublication = $typePublication;
		} else {
			$this->setErreurs("Publication setTypePublication " . self::FORMAT_TYPE_PUBLICATION);
		}
	}

	public function setUtilisateur($utilisateur) {
		if ($utilisateur instanceof Utilisateur) {
			$this->utilisateur = $utilisateur;
		} else {
			$this->setErreurs("Publication setUtilisateur " . self::FORMAT_UTILISATEUR);
		}
	}

	public function setAuteurs($auteurs) {
		if (is_array ( $auteurs )) {
			$this->auteurs = $auteurs;
		} else {

			$this->setErreurs("Publication setAuteurs " . self::FORMAT_ARRAY);
		}
	}

	public function setUtilisateurs($utilisateurs) {
		if (is_array ( $utilisateurs )) {
			$this->utilisateurs = $utilisateurs;
		} else {
			$this->setErreurs("Publication setUtilisateurs " . self::FORMAT_ARRAY);
		}
	}

	public function setApplications($applications) {
		if (is_array( $applications )) {
			$this->applications = $applications;
		} else {
			$this->setErreurs("Publication setApplications " . self::FORMAT_ARRAY);
		}
	}
	
	/**
	 * *******getters*****
	 */
	public function getIdPublication() {
		return $this->idPublication;
	}
	public function getTitrePublication() {
		return $this->titrePublication;
	}
	public function getAnneePublication() {
		return $this->anneePublication;
	}
	public function getJournalPublication() {
		return $this->journalPublication;
	}
	public function getUrlPublication() {
		return $this->urlPublication;
	}
	public function getTypePublication() {
		return $this->typePublication;
	}
	public function getUtilisateur() {
		return $this->utilisateur;
	}
	public function getAuteurs() {
		return $this->auteurs;
	}
	public function getUtilisateurs() {
		return $this->utilisateurs;
	}
	public function getApplications() {
		return $this->applications;
	}
	public function getAuteurFromAuteurs($idAuteur){
		$auteurReturn = null;
		if (ctype_digit($idAuteur) || is_int($idAuteur)) {
			foreach ($this->auteurs as $auteur){
				if ($auteur->getIdAuteur() == $idAuteur){
					$auteurReturn = $auteur;
				}
			}
		}
		else {
			$this->setErreurs("Publication getAuteurFromAuteurs " . self::FORMAT_INT);
		}
		return $auteurReturn;
	}
	public function getUtilisateurFromUtilisateurs($idUtilisateur){
		$utilisateurReturn = null;
		if (ctype_digit($idUtilisateur) || is_int($idUtilisateur)) {
			foreach ($this->utilisateurs as $utilisateur){
				if ($utilisateur->getIdUtilisateur() == $idUtilisateur){
					$utilisateurReturn = $utilisateur;
				}
			}
		}
		else {
			$this->setErreurs("Publication getUtilisateurFromUtilisateurs " . self::FORMAT_INT);
		}
		return $utilisateurReturn;
	}
	public function getApplicationFromApplications($idApplication){
		$applicationReturn = null;
		if (ctype_digit($idApplication) || is_int($idApplication)) {
			foreach ($this->applications as $application){
				if ($application->getIdApplication() == $idApplication){
					$applicationReturn = $application;
				}
			}
		}
		else {
			$this->setErreurs("Publication getApplicationFromApplications " . self::FORMAT_INT);
		}
		return $applicationReturn;
	}
	
	/**
	 * 
	 * Adders
	 * 
	 */
	
	//adders des auteurs
	public function addAuteur($auteur){
		if ($auteur instanceof Auteur){
			array_push($this->auteurs, $auteur);
		}
		else{
			$this->setErreurs("Publication addAuteur " . self::FORMAT_AUTEUR);
		}
	}
	public function addAllAuteurs($auteurs){
		if (is_array($auteurs)){
			foreach ($auteurs as $auteur){
				if ($auteur instanceof Auteur){
					array_push($this->auteurs, $auteur);
				}
				else{
					$this->setErreurs("Publication addAllAuteurs " . self::FORMAT_AUTEUR);
				}
			}
		}
		else{
			$this->setErreurs("Publication addAllAuteurs " . self::FORMAT_ARRAY);
		}
	}
	
	//adders des utilisateurs
	public function addUtilisateur($utilisateur){
		if ($utilisateur instanceof Utilisateur){
			array_push($this->utilisateurs, $utilisateur);
		}
		else{
			$this->setErreurs("Publication addUtilisateur " . self::FORMAT_UTILISATEUR);
		}
	}
	public function addAllUtilisateurs($utilisateurs){
		if (is_array($utilisateurs)){
			foreach ($utilisateurs as $utilisateur){
				if ($utilisateur instanceof Utilisateur){
					array_push($this->utilisateurs, $utilisateur);
				}
				else{
					$this->setErreurs("Publication addAllUtilisateurs " . self::FORMAT_UTILISATEUR);
				}
			}
		}
		else{
			$this->setErreurs("Publication addAllUtilisateurs " . self::FORMAT_ARRAY);
		}
	}
	
	//adders des applications
	public function addApplication($application){
		if ($application instanceof Application){
			array_push($this->applications, $application);
		}
		else{
			$this->setErreurs("Publication addApplication " . self::FORMAT_APPLICATION);
		}
	}
	public function addAllApplications($applications){
		if (is_array($applications)){
			foreach ($applications as $application){
				if ($application instanceof Application){
					array_push($this->applications, $application);
				}
				else{
					$this->setErreurs("Publication addAllApplications " . self::FORMAT_APPLICATION);
				}
			}
		}
		else{
			$this->setErreurs("Publication addAllApplications " . self::FORMAT_ARRAY);
		}
	}
}
