<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les ArticleMotCle.									  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe ArticleMotCle
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe ArticleMotCle
 */
class ArticleMotCle extends \Library\Entity {
	
	/* Définition des attributs */
	protected $article, $motCle;
	
	/**
	 * ******Setter *****
	 */
	public function setArticle($article) {
		if ($article instanceof Article) {
			$this->article = $article;
		} else {
			$this->setErreurs ("ArticleMotCle setArticle " . self::FORMAT_ARTICLE );
		}
	}
	public function setMotCle($motCle) {
		if ($motCle instanceof MotCle) {
			$this->motCle = $motCle;
		} else {
			$this->setErreurs ("ArticleMotCle setMotCle " .  self::FORMAT_MOT_CLE );
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getArticle() {
		return $this->article;
	}
	public function getMotCle() {
		return $this->motCle;
	}
}
?>