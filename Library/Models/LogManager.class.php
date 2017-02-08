<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des Logs.						  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des Logs
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Log;

abstract class LogManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addLog($log);
	
	abstract function saveLog($log);
	
	abstract function deleteLog($log);

	abstract function getLogById( $id);

	abstract function getAllLogs();

	abstract function getLogsBetweenIndex( $debut,  $quantite);

	abstract function getNumberOfLog();

	abstract protected function constructLog($donnee);
}