<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les ArticleCommentaire.							  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe ArticleCommentaire
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe ArticleCommentaire
 */
class ArticleCommentaire extends \Library\Entity {
	
	/* Définition des attributs */
	protected $article, $commentaire;
	
	/**
	 * ******Setter *****
	 */
	public function setArticle($article) {
		if ($article instanceof Cours) {
			$this->article = $article;
		} else {
			$this->setErreurs ("ArticleCommentaire setArticle " . self::FORMAT_ARTICLE );
		}
	}
	public function setCommentaire($commentaire) {
		if ($commentaire instanceof Commentaire) {
			$this->commentaire = $commentaire;
		} else {
			$this->setErreurs ("ArticleCommentaire setCommentaire " .  self::FORMAT_COMMENTAIRE );
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getArticle() {
		return $this->article;
	}
	public function getCommentaire() {
		return $this->commentaire;
	}
}
?>