<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager PDO des articleMedia.		 	  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des ArticleMedia
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\ArticleMedia;

abstract class ArticleMediaManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addArticleMedia($articleMedia);

	abstract function deleteArticleMedia($articleMedia);
	
	abstract function getArticleMediaById($idArticle, $idMedia);

	abstract protected function constructArticleMedia($donnee);
}