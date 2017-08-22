<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager PDO des Mots-clés. 			  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des MotCles
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\MotCle;

abstract class MotCleManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addMotCle($motCle);
	
	abstract function addArticlesFromMotCle($motCle);

	abstract function addCoursFromMotCle($motCle);
	
	abstract function saveMotCle($motCle);
	
	abstract function deleteMotCle($motCle);
	
	abstract function deleteLinkbetweenArticlesMotCle($motCle);

	abstract function deleteLinkbetweenCoursMotCle($motCle);

	abstract function getMotCleById( $id);

	abstract function getMotCleByName($nomMotCle);

	abstract function getAllMotCles();

	abstract function getMotClesBetweenIndex($debut, $quantite);

	abstract function getNumberOfMotCle();

	abstract function putArticlesInMotCle($motCle);
	
	abstract function putCoursInMotCle($motCle);

	abstract protected function constructMotCle($donnee);
}