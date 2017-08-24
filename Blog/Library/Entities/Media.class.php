<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Medias.	 									  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe Media
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe Media
 */
class Media extends \Library\Entity {
	
	/* Définition des attributs */
	protected 	$idMedia, 
				$urlMedia,
				$urlMediaMiniature,
				$articles = array();

	/* Déclaration des constantes de classe */
	const URL_MEDIA = 'L\'url entré ne semble pas correcte';
	
	/**
	 * ******Setter *****
	 */
	public function setIdMedia($idMedia) {
		// verification que l'id est au format integer
		if (ctype_digit($idMedia) || is_int($idMedia)) {
			$this->idMedia = $idMedia;
		} 
		//sinon envoi d'une erreur sur le format integer. 
		else {
			$this->setErreurs("Media setIdMedia " . self::FORMAT_INT);
		}
	}
	public function setUrlMedia($url) {

		if (is_string ( $url )) {
			//verification que l'url contient au moins 10 caractères.
			
			if (mb_strlen($url, 'UTF8') > 14){
				$this->urlMedia = $url;
			}else {
				$this->setErreurs ("Media setUrlImageMedia " .self::URL_MEDIA );
			}
		} else {
			$this->setErreurs ("Media setUrlImageMedia " . self::FORMAT_STRING );
		}
	}
	public function setUrlMediaMiniature($url) {

		if (is_string ( $url )) {
			//verification que l'url contient au moins 10 caractères.
			
			if (mb_strlen($url, 'UTF8') > 14){
				$this->urlMediaMiniature = $url;
			}else {
				$this->setErreurs ("Media setUrlMediaMiniature " .self::URL_MEDIA );
			}
		} else {
			$this->setErreurs ("Media setUrlMediaMiniature " . self::FORMAT_STRING );
		}
	}
	public function setArticles($articles){
		if (is_array($articles)){
			if(!empty($articles)){
				$this->articles = $articles;
			}
			else{
				$this->setErreurs("Media setArticles " . self::FORMAT_EMPTY);
			}	
		}
		else{
			$this->setErreurs("Media setArticles " . self::FORMAT_ARRAY);
		}	
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getIdMedia() {
		return $this->idMedia;
	}
	public function getUrlMedia(){
		return $this->urlMedia;
	}
	public function getUrlMediaMiniature(){
		return $this->urlMediaMiniature;
	}


	/**
	 * 
	 * adders
	 * 
	 */
	// Permet d'ajouter un article au media
	public function addArticle(Article $article){
		if ($article instanceof Article){
			array_push($this->articles, $article);
		}
		else{
			$this->setErreurs("Media addArticle " . self::FORMAT_ARTICLE);
		}
	}
	
}
