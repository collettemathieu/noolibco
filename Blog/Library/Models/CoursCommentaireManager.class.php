<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager PDO des coursCommentaire.		  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des coursCommentaire
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\CoursCommentaire;

abstract class CoursCommentaireManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addCoursCommentaire($coursCommentaire);

	abstract function deleteCoursCommentaire($coursCommentaire);
	
	abstract function getCoursCommentaireById($idCours, $idCommentaire);

	abstract protected function constructCoursCommentaire($donnee);
}