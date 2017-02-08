<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des ApplicationMotCles.		  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des ApplicationMotCles
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\ApplicationMotCle;

abstract class ApplicationMotCleManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addApplicationMotCle($applicationmotcle);
	
	abstract function deleteApplicationMotCle($applicationmotcle);

	abstract function getApplicationMotCleById($idApplication, $idMotCle);

	abstract function getAllApplicationMotCles();

	abstract function getApplicationMotClesBetweenIndex($debut, $quantite);

	abstract function getNumberOfApplicationMotCle();

	abstract protected function constructApplicationMotCle($donnee);
}