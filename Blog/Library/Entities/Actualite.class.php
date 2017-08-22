<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Actualités. 									  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe Actualite
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe Actualite
 */
class Actualite extends \Library\Entity {
	
	/* Définition des attributs */
	protected 	$idActualite, 
				$titreActualite,
				$texteActualite,
				$enLigneActualite,
				$urlImageActualite,
				$urlLienActualite;
	
	/* Déclaration des constantes d'erreurs particulières à */
	const TITRE_ACTUALITE = 'Le titre de l\'article doit contenir entre 5 et 30 caractères.';
	const TEXTE_ACTUALITE = 'Le texte de l\'article doit contenir entre 5 et 590 caractères.';
	const URL_IMAGE_ACTUALITE = 'L\'url de l\'image de l\'article doit comporter au moins 13 caractères.';
	const URL_LIEN_ACTUALITE = 'L\'url du lien de l\'article ne semble pas correct.';
	
	
	/**
	 * ******Setter *****
	 */
	public function setIdActualite($idActualite) {
		// verification que l'id est au format integer
		if (ctype_digit($idActualite) || is_int($idActualite)) {
			$this->idActualite = $idActualite;
		} 
		//sinon envoi d'une erreur sur le format integer. 
		else {
			$this->setErreurs("Actualite setIdActualite " . self::FORMAT_INT);
		}
	}
	public function setTitreActualite($titreActualite) {

		if (is_string ( $titreActualite )) {
			//verification que le texte contient entre 5 et 30 caractères.
			
			if (mb_strlen($titreActualite, 'UTF8') > 4 && mb_strlen($titreActualite, 'UTF8') < 31){
				$this->titreActualite = $titreActualite;
			}
			else {
				$this->setErreurs ("Actualite setTitreActualite " .self::TITRE_ACTUALITE );
			}
		} else {
			$this->setErreurs ("Actualite setTitreActualite " . self::FORMAT_STRING );
		}
	}
	public function setTexteActualite($texteActualite) {

		if (is_string ( $texteActualite )) {
			//verification que le texte contient entre 5 et 590 caractères.
			
			if (mb_strlen($texteActualite, 'UTF8') > 5 && mb_strlen($texteActualite, 'UTF8') < 591){
				$this->texteActualite = $texteActualite;
			}
			else {
				$this->setErreurs ("Actualite setTexteActualite " .self::TEXTE_ACTUALITE );
			}
		} else {
			$this->setErreurs ("Actualite setTexteActualite " . self::FORMAT_STRING );
		}
	}
	public function setEnLigneActualite($bool) {

		if (is_bool ( $bool )) {
			$this->enLigneActualite = $bool;
		} else {
			$this->setErreurs ("Actualite setEnLigneActualite " . self::FORMAT_BOOLEAN );
		}
	}
	public function setUrlImageActualite($urlImage) {

		if (is_string ( $urlImage )) {
			//verification que l'url contient au moins 10 caractères.
			
			if (mb_strlen($urlImage, 'UTF8') > 14){
				$this->urlImageActualite = $urlImage;
			}else {
				$this->setErreurs ("Actualite setUrlImageActualite " .self::URL_IMAGE_ACTUALITE );
			}
		} else {
			$this->setErreurs ("Actualite setUrlImageActualite " . self::FORMAT_STRING );
		}
	}
	public function setUrlLienActualite($urlLien) {

		if (is_string ( $urlLien )) {
			//verification que l'url est valide
			
			if (preg_match('_(^|[\s.:;?\-\]<\(])(https?://[-\w;/?:@&=+$\|\_.!~*\|\'()\[\]%#,☺]+[\w/#](\(\))?)(?=$|[\s\',\|\(\).:;?\-\[\]>\)])_i', $urlLien)) {
				$this->urlLienActualite = $urlLien;
			}else {
				$this->setErreurs ("Actualite setUrlLienActualite " .self::URL_LIEN_ACTUALITE );
			}
		} else {
			$this->setErreurs ("Actualite setUrlLienActualite " . self::FORMAT_STRING );
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getIdActualite() {
		return $this->idActualite;
	}
	public function getTitreActualite() {
		return $this->titreActualite;
	}
	public function getTexteActualite() {
		return $this->texteActualite;
	}
	public function getEnLigneActualite() {
		return $this->enLigneActualite;
	}
	public function getUrlImageActualite(){
		return $this->urlImageActualite;
	}
	public function getUrlLienActualite() {
		return $this->urlLienActualite;
	}
	
}
