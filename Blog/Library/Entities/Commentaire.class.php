<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les commentaires. 									  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe Commentaire
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe Commentaire
 */
class Commentaire extends \Library\Entity {
	protected 	$idCommentaire,
				$texteCommentaire, 
				$dateCommentaire,
				$enAttenteValidationAuteurCommentaire,
				$enLigneCommentaire,
				$utilisateur,
				$article,
				$cours;
	
	/* Déclaration des constantes de la classe */
	const FORMAT_TEXTE_COMMENTAIRE = 'Votre commentaire doit comporter au moins 5 caractères.';
	

	/**
	 * ******setters******
	 */
	public function setIdCommentaire($idCommentaire) {
		if (ctype_digit($idCommentaire) || is_int($idCommentaire)) {
			$this->idCommentaire = $idCommentaire;
		} else {
			$this->setErreurs("Commentaire setIdCommentaire " . self::FORMAT_INT);
		}
	}
	public function setTexteCommentaire($texteCommentaire) {
		if (is_string ( $texteCommentaire )) {
			if(strlen($texteCommentaire) > 4){
				$this->texteCommentaire = trim($texteCommentaire);
			}else{
				$this->setErreurs(self::FORMAT_TEXTE_COMMENTAIRE);
			}
		} else {
			$this->setErreurs("Commentaire setTexteCommentaire " . self::FORMAT_STRING);
		}
	}
	public function setDateCommentaire($dateCommentaire) {

		if (is_string ( $dateCommentaire )) {
			$this->dateCommentaire = $dateCommentaire;
		} else {
			$this->setErreurs ("Commentaire setDateCommentaire " . self::FORMAT_STRING );
		}
	}
	public function setEnAttenteValidationAuteurCommentaire($bool) {

		if (is_bool ( $bool )) {
			$this->enAttenteValidationAuteurCommentaire = $bool;
		} else {
			$this->setErreurs ("Commentaire setEnAttenteValidationAuteurCommentaire " . self::FORMAT_BOOLEAN );
		}
	}
	public function setEnLigneCommentaire($bool) {

		if (is_bool ( $bool )) {
			$this->enLigneCommentaire = $bool;
		} else {
			$this->setErreurs ("Commentaire setEnLigneCommentaire " . self::FORMAT_BOOLEAN );
		}
	}
	public function setUtilisateur($utilisateur) {
		if ($utilisateur instanceof Utilisateur) {
			$this->utilisateur = $utilisateur;
		} else {
			$this->setErreurs("Commentaire setUtilisateur " . self::FORMAT_UTILISATEUR);
		}
	}
	public function setArticle($article) {
		if ($article instanceof Article) {
			$this->article = $article;
		} else {
			$this->setErreurs("Commentaire setArticle " . self::FORMAT_ARTICLE);
		}
	}
	public function setCours($cours) {
		if ($cours instanceof Cours) {
			$this->cours = $cours;
		} else {
			$this->setErreurs("Commentaire setCours " . self::FORMAT_COURS);
		}
	}

	/**
	 * *******getters*****
	 */
	public function getIdCommentaire() {
		return $this->idCommentaire;
	}
	public function getTexteCommentaire() {
		return $this->texteCommentaire;
	}
	public function getDateCommentaire() {
		return $this->dateCommentaire;
	}
	public function getEnAttenteValidationAuteurCommentaire() {
		return $this->enAttenteValidationAuteurCommentaire;
	}
	public function getEnLigneCommentaire() {
		return $this->enLigneCommentaire;
	}
	public function getUtilisateur() {
		return $this->utilisateur;
	}
	public function getArticle() {
		return $this->article;
	}
	public function getCours() {
		return $this->cours;
	}
}
