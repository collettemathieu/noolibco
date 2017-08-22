<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager PDO des articleUtilisateur.	  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des articleUtilisateur
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\ArticleUtilisateur;

abstract class ArticleUtilisateurManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addArticleUtilisateur($articleUtilisateur);

	abstract function deleteArticleUtilisateur($articleUtilisateur);
	
	abstract function getArticleUtilisateurById($idArticle, $idUtilisateur);

	abstract protected function constructArticleUtilisateur($donnee);
}