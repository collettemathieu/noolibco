<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager PDO des coursMotCle. 	 		  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des coursMotCle
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\CoursMotCle;

abstract class CoursMotCleManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addCoursMotCle($coursMotCle);

	abstract function deleteCoursMotCle($coursMotCle);
	
	abstract function getCoursMotCleById($idMotCle, $idCours);

	abstract protected function constructCoursMotCle($donnee);
}