<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager PDO des articleCommentaire.	  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des articleCommentaire
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\ArticleCommentaire;

abstract class ArticleCommentaireManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addArticleCommentaire($articleCommentaire);

	abstract function deleteArticleCommentaire($articleCommentaire);
	
	abstract function getArticleCommentaireById($idArticle, $idCommentaire);

	abstract protected function constructArticleCommentaire($donnee);
}