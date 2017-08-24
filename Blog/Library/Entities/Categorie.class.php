<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les categories. 									  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe Categorie
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

use Library\Entities;

/**
 * Classe Categorie
 */
class Categorie extends \Library\Entity {
	protected $idCategorie, $nomCategorie, $descriptionCategorie, $articles = array(), $cours = array();
	
	/**
	 * ******setters******
	 */
	public function setIdCategorie($idCategorie) {
		if (ctype_digit($idCategorie) || is_int($idCategorie)) {
			$this->idCategorie = $idCategorie;
		} else {
			$this->setErreurs("Categorie setIdCategorie " . self::FORMAT_INT);
		}
	}
	public function setNomCategorie($nomCategorie) {
		if (is_string ( $nomCategorie )) {
			$this->nomCategorie = $nomCategorie;
		} else {
			$this->setErreurs("Categorie setNomCategorie " . self::FORMAT_STRING);
		}
	}
	public function setDescriptionCategorie($descriptionCategorie) {
		if (is_string ( $descriptionCategorie )) {
			$this->descriptionCategorie = $descriptionCategorie;
		} else {
			$this->setErreurs("Categorie setDescriptionCategorie " . self::FORMAT_STRING);
		}
	}
	public function setArticles($articles) {
		if (is_array ( $articles )) {
			$this->articles = $articles;
		} else {
			$this->setErreurs("Categorie setArticles " . self::FORMAT_ARRAY);
		}
	}
	public function setCours($cours) {
		if (is_array ( $cours )) {
			$this->cours = $cours;
		} else {
			$this->setErreurs("Categorie setCours " . self::FORMAT_ARRAY);
		}
	}
	/**
	 * *******getters*****
	 */
	public function getIdCategorie() {
		return $this->idCategorie;
	}
	public function getNomCategorie() {
		return $this->nomCategorie;
	}
	public function getDescriptionCategorie() {
		return $this->descriptionCategorie;
	}
	public function getArticles() {
		return $this->articles;
	}
	public function getCours() {
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
			$this->setErreurs("Categorie getArticleFromArticles " .self::FORMAT_INT);
		}
		return $articleReturn;
	}

	// Permet de récuperer un cours d'une liste de cours par son ID
	public function getCoursFromCours($idCours){
		$coursReturn = null;
		if (ctype_digit($idCours) || is_int($idCours)) {
			foreach ($this->cours as $cours){
				if ($cours->getIdArticle() == $idCours){
					$coursReturn = $cours;
				}
			}
		} 
		else {
			$this->setErreurs("Categorie getCoursFromCours " .self::FORMAT_INT);
		}
		return $coursReturn;
	}
	
	/**
	 * 
	 * *******Adder des tableaux
	 * 
	 */
	
	// Permet d'ajouter un article à la liste des articles de la categorie
	public function addArticle($article){
		if ($article instanceof Article){
			array_push($this->articles, $article);
		}
		else{
			$this->setErreurs("Categorie addArticle " .self::FORMAT_ARTICLE);
		}
	}

	// Permet d'ajouter un cours à la liste des cours de la categorie
	public function addCours($cours){
		if ($cours instanceof Cours){
			array_push($this->cours, $cours);
		}
		else{
			$this->setErreurs("Categorie addCours " .self::FORMAT_COURS);
		}
	}

}
