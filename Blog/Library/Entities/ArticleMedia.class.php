<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les ArticleMedias.									  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe ArticleMedia
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe ArticleMedia
 */
class ArticleMedia extends \Library\Entity {
	
	/* Définition des attributs */
	protected $article, $media;
	
	/**
	 * ******Setter *****
	 */
	public function setArticle($article) {
		if ($article instanceof Article) {
			$this->article = $article;
		} else {
			$this->setErreurs ("ArticleMedia setArticle " . self::FORMAT_ARTICLE );
		}
	}
	public function setMedia($media) {
		if ($media instanceof Media) {
			$this->media = $media;
		} else {
			$this->setErreurs ("ArticleMedia setMedia " .  self::FORMAT_MEDIA );
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getArticle() {
		return $this->article;
	}
	public function getMedia() {
		return $this->media;
	}
}
?>