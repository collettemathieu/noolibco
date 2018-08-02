<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager PDO des articles. 			  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des Articles
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Article;

abstract class ArticleManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addArticle($article);

	abstract function addMotsClesFromArticle($article);

	abstract function addAuteurFromArticle($article);
	
	abstract function saveArticle($article);

	abstract function publishArticle($article);
	
	abstract function deleteArticle($article);
	
	abstract function deleteLinkbetweenArticleMotCles($article);

	abstract function deleteLinkbetweenArticleAuteur($article);

	abstract function getArticleById($id);

	abstract function getArticleByUrlTitle($urlTitreArticle);

	abstract function getAllArticles();

	abstract function getAllVues();

	abstract function getArticlesBetweenIndex($debut, $quantite);

	abstract function getNumberOfArticles();

	abstract function putMotsClesInArticle($article);

	abstract function putAuteurInArticle($article);

	abstract function putCommentairesInArticle($article);

	abstract protected function constructArticle($donnee);
}