<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des MotCles.					  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
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
	
	abstract function addApplicationsFromMotCle($motCle);
	
	abstract function saveMotCle($motCle);
	
	abstract function deleteMotCle($motCle);
	
	abstract function deleteLinkbetweenApplicationsMotCle($motCle);

	abstract function getMotCleById($id);

	abstract function getMotCleByName($nomMotCle);

	abstract function getAllMotCles();

	abstract function getMotClesBetweenIndex( $debut,  $quantite);

	abstract function getNumberOfMotCle();
	
	abstract function putApplicationsInMotCle($motCle);

	abstract protected function constructMotCle($donnee);
}