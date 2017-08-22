<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager PDO des coursGlobalUtilisateur. |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des coursGlobalUtilisateur
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\CoursGlobalUtilisateur;

abstract class CoursGlobalUtilisateurManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addCoursGlobalUtilisateur($coursGlobalUtilisateur);

	abstract function deleteCoursGlobalUtilisateur($coursGlobalUtilisateur);
	
	abstract function getCoursGlobalUtilisateurById($idUtilisateur, $idCoursGlobal);

	abstract protected function constructCoursGlobalUtilisateur($donnee);
}