<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager PDO des articleMotCle.		  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des ArticleMotCle
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\ArticleMotCle;

abstract class ArticleMotCleManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addArticleMotCle($articleMotCle);

	abstract function deleteArticleMotCle($articleMotCle);
	
	abstract function getArticleMotCleById($idArticle, $idMotCle);

	abstract protected function constructArticleMotCle($donnee);
}