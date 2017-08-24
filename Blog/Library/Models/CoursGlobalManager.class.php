<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager PDO des coursGlobal.	 		  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des CoursGlobal
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\CoursGlobal;

abstract class CoursGlobalManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addCoursGlobal($coursGlobal);

	abstract function addAuteurFromCoursGlobal($coursGlobal);
	
	abstract function saveCoursGlobal($coursGlobal);

	abstract function publishCoursGlobal($coursGlobal);
	
	abstract function deleteCoursGlobal($coursGlobal);
	
	abstract function deleteLinkbetweenCoursGlobalAuteur($coursGlobal);

	abstract function deleteLinkbetweenCoursGlobalCours($coursGlobal);

	abstract function getCoursGlobalById( $id);

	abstract function getCoursGlobalByUrlTitle($urlTitreCoursGlobal);

	abstract function getAllCoursGlobal();

	abstract function getCoursGlobalBetweenIndex( $debut,  $quantite);

	abstract function getNumberOfCoursGlobal();

	abstract function putAuteurInCoursGlobal($coursGlobal);

	abstract function putCoursInCoursGlobal($coursGlobal);

	abstract protected function constructCoursGlobal($donnee);
}