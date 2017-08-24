<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager PDO des commentaires.			  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des Commentaires
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Commentaire;

abstract class CommentaireManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addCommentaire($commentaire);
	
	abstract function saveCommentaire($commentaire);
	
	abstract function deleteCommentaire($commentaire);
	
	abstract function deleteLinkbetweenCommentaireArticles($commentaire);

	abstract function deleteLinkbetweenCommentaireCours($commentaire);

	abstract function deleteLinkbetweenCommentaireUtilisateurs($commentaire);

	abstract function getCommentaireById( $id);

	abstract function getAllCommentaires();

	abstract function getCommentairesBetweenIndex( $debut,  $quantite);

	abstract function getNumberOfCommentaires();

	abstract function putArticleInCommentaire($commentaire);

	abstract function putCoursInCommentaire($commentaire);

	abstract function putUtilisateurInCommentaire($commentaire);

	abstract protected function constructCommentaire($donnee);
}