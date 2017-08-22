<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager PDO des utilisateurCommentaire. |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des utilisateurCommentaire
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\UtilisateurCommentaire;

abstract class UtilisateurCommentaireManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addUtilisateurCommentaire($utilisateurCommentaire);

	abstract function deleteUtilisateurCommentaire($utilisateurCommentaire);
	
	abstract function getUtilisateurCommentaireById($idArticle, $idCommentaire);

	abstract protected function constructUtilisateurCommentaire($donnee);
}