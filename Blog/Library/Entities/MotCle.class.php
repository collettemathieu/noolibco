<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Mots-clés. 									  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe MotCle
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe MotCle
 */
class MotCle extends \Library\Entity {
	protected 	$idMotCle, 
				$nomMotCle, 
				$articles = array(), 
				$cours = array();
	
	/**
	 * ******setters******
	 */
	public function setIdMotCle($idMotCle) {
		if (ctype_digit($idMotCle) || is_int($idMotCle)) {
			$this->idMotCle = $idMotCle;
		} else {
			$this->setErreurs("MotCle setIdMotCle " . self::FORMAT_INT);
		}
	}
	public function setNomMotCle($nomMotCle) {
		if (is_string ( $nomMotCle )) {
			$this->nomMotCle = $nomMotCle;
		} else {
			$this->setErreurs("MotCle setNomMotCle " . self::FORMAT_STR);
		} 
	}
	public function setArticles($articles) {
		if (is_array($articles)) {
			$this->articles = $articles;
		} else {
			$this->setErreurs("MotCle setArticles " . self::FORMAT_ARRAY);
		}
	}
	public function setCours($cours) {
		if (is_array($cours)) {
			$this->cours = $cours;
		} else {
			$this->setErreurs("MotCle setCours " . self::FORMAT_ARRAY);
		}
	}
	/**
	 * *******getters*****
	 */
	public function getIdMotCle() {
		return $this->idMotCle;
	}
	public function getNomMotCle() {
		return $this->nomMotCle;
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
			$this->setErreurs("MotCle getApplicationFromApplications " . self::FORMAT_INT);
		}
		return $articleReturn;
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
			$this->setErreurs("MotCle getApplicationFromApplications " . self::FORMAT_INT);
		}
		return $coursReturn;
	}
	
	/**
	 * 
	 * 
	 * Adders des listes
	 * 
	 */
	
	// Permet d'ajouter un article à la liste des articles de l'utilisateur
	public function addArticle($article){
		if ($article instanceof Article){
			array_push($this->articles, $article);
		}
		else{
			$this->setErreurs("MotCle addArticle " . self::FORMAT_ARTICLE);
		}
	}
	// Permet d'ajouter un cours à la liste des cours de l'utilisateur
	public function addCours($cours){
		if ($cours instanceof Cours){
			array_push($this->cours, $cours);
		}
		else{
			$this->setErreurs("MotCle addCours " .self::FORMAT_COURS);
		}
	}
}
?>