<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les ArticleUtilisateur.							  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe ArticleUtilisateur
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe ArticleUtilisateur
 */
class ArticleUtilisateur extends \Library\Entity {
	
	/* Définition des attributs */
	protected $article, $utilisateur;
	
	/**
	 * ******Setter *****
	 */
	public function setArticle($article) {
		if ($article instanceof Cours) {
			$this->article = $article;
		} else {
			$this->setErreurs ("ArticleUtilisateur setArticle " . self::FORMAT_ARTICLE );
		}
	}
	public function setUtilisateur($utilisateur) {
		if ($utilisateur instanceof Utilisateur) {
			$this->utilisateur = $utilisateur;
		} else {
			$this->setErreurs ("ArticleUtilisateur setUtilisateur " .  self::FORMAT_UTILISATEUR );
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getArticle() {
		return $this->article;
	}
	public function getUtilisateur() {
		return $this->utilisateur;
	}
}
?>