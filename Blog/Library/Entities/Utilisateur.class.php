<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Utilisateurs. 									  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe Utilisateur
 * @access: public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe Utilisateur
 */
class Utilisateur extends \Library\Entity {
	
	/* Définition des attributs */
	protected 	$idUtilisateur, 
				$nomUtilisateur, 
				$mailUtilisateur, 
				$passwordAdminUtilisateur,
				$superAdminUtilisateur, 
				$newsletterUtilisateur, 
				$dateInscriptionUtilisateur,
				$articles = array(), 
				$commentaires = array(), 
				$cours = array();
	
	/* Déclaration des constantes d'erreurs */
	
	const NOM_INVALIDE = 'Votre nom doit contenir un minimum de deux lettres.';
	const MAIL_INVALIDE = 'Votre adresse électronique ne semble pas valide.';
	const PASSWORD_INVALIDE = 'Votre mot de passe doit contenir au moins 8 lettres avec au minimum un nombre, une lettre majuscule et une lettre miniscule.';
	const CONFIRMATION_PASSWORD_INVALIDE = 'Votre confirmation de mot de passe n\'est pas valide.';
	const MAIL_JETABLE = 'Les adresses électroniques jetables ne sont pas autorisées.';
	const NEWS_LETTER_INVALIDE = 'La valeur de la newsletter doit être booléenne.';
	
	
	/**
	 * ******Setter *****
	*/
	public function setIdUtilisateur($idUtilisateur) {
		// verification que l'id est au format integer
		if (ctype_digit($idUtilisateur) || is_int($idUtilisateur)) {
			$this->idUtilisateur = $idUtilisateur;
		} 
		//sinon envoi d'une erreur sur le format integer. 
		else {
			$this->setErreurs("Utilisateur setIdUtilisateur " .self::FORMAT_INT);
		}
	}
	public function setNomUtilisateur($nomUtilisateur) {
		if (is_string($nomUtilisateur)) {
			if (strlen($nomUtilisateur) > 1){
				$this->nomUtilisateur = trim($nomUtilisateur);
			}
			else{
				$this->setErreurs(self::NOM_INVALIDE);
			}
		} 
		else {
			$this->setErreurs(self::FORMAT_STRING);
		}
	}
	public function setMailUtilisateur($mailUtilisateur) {
		if (is_string($mailUtilisateur)) {
			if( !empty($mailUtilisateur) && preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $mailUtilisateur)){
				$this->mailUtilisateur = $mailUtilisateur;
			}
			else{
				$this->setErreurs(self::MAIL_INVALIDE);
			}
		}elseif($mailUtilisateur === false) {
			$this->setErreurs(self::MAIL_JETABLE);
		}else{
			$this->setErreurs(self::FORMAT_STRING);	
		}
	}
	public function setPasswordAdminUtilisateur($passwordAdminUtilisateur) {
	
		if(is_string($passwordAdminUtilisateur)){
			if(strlen($passwordAdminUtilisateur) > 59 ){
				$this->passwordAdminUtilisateur = $passwordAdminUtilisateur;
			}else{
				$this->passwordAdminUtilisateur = '';
			}
		}else{
			$this->setErreurs(self::PASSWORD_INVALIDE);
		}
	
	}
	public function setSuperAdminUtilisateur($bool){
		if (is_bool( $bool )){
			$this->superAdminUtilisateur = $bool;
		}
		else{
			$this->setErreurs ("Utilisateur setSuperAdminUtilisateur " .self::FORMAT_BOOLEAN );
		}
	}
	public function setNewsletterUtilisateur($bool){
		if (is_bool( $bool )){
			$this->newsletterUtilisateur = $bool;
		}
		else{
			$this->setErreurs ("Utilisateur setNewsletterUtilisateur " .self::FORMAT_BOOLEAN );
		}
	}
	public function setDateInscriptionUtilisateur($dateInscriptionUtilisateur) {
		$this->dateInscriptionUtilisateur = $dateInscriptionUtilisateur;
	}
	public function setArticles($articles){
		if (is_array($articles)){
			$this->articles = $articles;
		}
		else{
			$this->setErreurs("Utilisateur setArticles " .self::FORMAT_ARRAY);
		}	
	}
	public function setCommentaires($commentaires){
		if (is_array($commentaires)){
			$this->commentaires = $commentaires;
		}
		else{
			$this->setErreurs("Utilisateur setCommentaires " .self::FORMAT_ARRAY);
		}
	}
	public function setCours($cours){
		if (is_array($cours)){
			$this->cours = $cours;
		}
		else{
			$this->setErreurs("Utilisateur setCours " .self::FORMAT_ARRAY);
		}	
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getIdUtilisateur() {
		return $this->idUtilisateur;
	}
	public function getNomUtilisateur() {
		return $this->nomUtilisateur;
	}
	public function getMailUtilisateur() {
		return $this->mailUtilisateur;
	}
	public function getNewsletterUtilisateur() {
		return $this->newsletterUtilisateur;
	}
	public function getDateInscriptionUtilisateur() {
		return $this->dateInscriptionUtilisateur;
	}
	public function getPasswordAdminUtilisateur() {
		return $this->passwordAdminUtilisateur;
	}
	public function getSuperAdminUtilisateur() {
		return $this->superAdminUtilisateur;
	}
	public function getArticles(){
		return $this->articles;
	}
	public function getCommentaires(){
		return $this->commentaires;
	}
	public function getCours(){
		return $this->cours;
	}
	
	// Permet de récuperer un article d'une liste d'articles par son ID
	public function getArticleFromArticles($idArticle){
		$articleReturn = null;
		if (ctype_digit($idArticle) || is_int($idArticle)) {
			foreach ($this->articles as $article){
				if ($article->getIdArticle() == $idArticle){
					$articleReturn = $article;
				}
			}
		} 
		else {
			$this->setErreurs("Utilisateur getArticleFromArticles " .self::FORMAT_INT);
		}
		return $articleReturn;
	}

	// Permet de récuperer un commentaire d'une liste de commentaires par son ID
	public function getCommentaireFromCommentaires($idCommentaire){
		$commentaireReturn = null;
		if (ctype_digit($idCommentaire) || is_int($idCommentaire)) {
			foreach ($this->commentaires as $commentaire){
				if ($commentaire->getIdCommentaire() == $idCommentaire){
					$commentaireReturn = $commentaire;
				}
			}
		} 
		else {
			$this->setErreurs("Utilisateur getCommentaireFromCommentaires " .self::FORMAT_INT);
		}
		return $commentaireReturn;
	}

	// Permet de récuperer un cours d'une liste de cours par son ID
	public function getCoursFromCours($idCours){
		$coursReturn = null;
		if (ctype_digit($idCours) || is_int($idCours)) {
			foreach ($this->cours as $cours){
				if ($cours->getIdCours() == $idCours){
					$coursReturn = $cours;
				}
			}
		} 
		else {
			$this->setErreurs("Utilisateur getCoursFromCours " .self::FORMAT_INT);
		}
		return $coursReturn;
	}
	
	/**
	 * 
	 * Add / Update / Remove
	 * 
	 */

	// Permet d'ajouter un article à la liste des articles de l'utilisateur
	public function addArticle($article){
		if ($article instanceof Article){
			array_push($this->articles, $article);
		}
		else{
			$this->setErreurs("Utilisateur addArticle " .self::FORMAT_ARTICLE);
		}
	}

	// Permet de mettre un article à jour dans la liste des articles de l'utilisateur
	public function updateArticle($article){
		if ($article instanceof Article){
			foreach($this->articles as $index => $articleUser)
			{
				if($articleUser->getIdArticle() == $article->getIdArticle())
				{
					$this->articles[$index] = $article;
					break;
				}
			}
		}
		else{
			$this->setErreurs("Utilisateur updateArticle " .self::FORMAT_ARTICLE);
		}
	}

	// Permet de supprimer un article de la liste des articles de l'utilisateur
	public function removeArticle($article){
		if ($article instanceof Article){
			foreach($this->articles as $index => $articleUser)
			{
				if($articleUser->getIdArticle() == $article->getIdArticle())
				{
					array_splice($this->articles, $index, 1);
					break;
				}
			}
		}
		else{
			$this->setErreurs("Utilisateur removeArticle " .self::FORMAT_ARTICLE);
		}
	}

	// Permet d'ajouter un commentaire à la liste des commentaires de l'utilisateur
	public function addCommentaire($commentaire){
		if ($commentaire instanceof Commentaire){
			array_push($this->commentaires, $commentaire);
		}
		else{
			$this->setErreurs("Utilisateur addCommentaire " .self::FORMAT_COMMENTAIRE);
		}
	}

	// Permet de mettre un commentaire à jour dans la liste des commentaires de l'utilisateur
	public function updateCommentaire($commentaire){
		if ($commentaire instanceof Commentaire){
			foreach($this->commentaires as $index => $commentaireUser)
			{
				if($commentaireUser->getIdCommentaire() == $commentaire->getIdCommentaire())
				{
					$this->commentaires[$index] = $commentaire;
					break;
				}
			}
		}
		else{
			$this->setErreurs("Utilisateur updateCommentaire " .self::FORMAT_COMMENTAIRE);
		}
	}

	// Permet de supprimer un commentaire de la liste des commentaires de l'utilisateur
	public function removeCommentaire($commentaire){
		if ($commentaire instanceof Commentaire){
			foreach($this->commentaires as $index => $commentaireUser)
			{
				if($commentaireUser->getIdCommentaire() == $commentaire->getIdCommentaire())
				{
					array_splice($this->commentaires, $index, 1);
					break;
				}
			}
		}
		else{
			$this->setErreurs("Utilisateur removeCommentaire " .self::FORMAT_COMMENTAIRE);
		}
	}
	

	// Permet d'ajouter un cours à la liste des cours de l'utilisateur
	public function addCours($cours){
		if ($cours instanceof Cours){
			array_push($this->cours, $cours);
		}
		else{
			$this->setErreurs("Utilisateur addCours " .self::FORMAT_COURS);
		}
	}

	// Permet de mettre un cours à jour dans la liste des cours de l'utilisateur
	public function updateCours($cours){
		if ($cours instanceof Cours){
			foreach($this->cours as $index => $coursUser)
			{
				if($coursUser->getIdCours() == $cours->getIdCours())
				{
					$this->cours[$index] = $cours;
					break;
				}
			}
		}
		else{
			$this->setErreurs("Utilisateur updateCours " .self::FORMAT_COURS);
		}
	}

	// Permet de supprimer un cours de la liste des cours de l'utilisateur
	public function removeCours($commentaire){
		if ($cours instanceof Cours){
			foreach($this->cours as $index => $coursUser)
			{
				if($coursUser->getIdCours() == $cours->getIdCours())
				{
					array_splice($this->commentaires, $index, 1);
					break;
				}
			}
		}
		else{
			$this->setErreurs("Utilisateur removeCours " .self::FORMAT_COURS);
		}
	}

}
