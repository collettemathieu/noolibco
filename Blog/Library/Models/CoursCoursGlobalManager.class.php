<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager PDO des coursCoursGlobal.		  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des coursCoursGlobal
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\CoursCoursGlobal;

abstract class CoursCoursGlobalManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addCoursCoursGlobal($coursCoursGlobal);

	abstract function deleteCoursCoursGlobal($coursCoursGlobal);
	
	abstract function getCoursCoursGlobalById($idCours, $idCoursGlobal);

	abstract function getCoursGlobalFromCours($idCours);

	abstract protected function constructCoursCoursGlobal($donnee);
}