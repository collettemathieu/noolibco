<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des TypeLogs.					  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des TypeLogs
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\TypeLog;

abstract class TypeLogManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addTypeLog($typeLog);
	
	abstract function addLogsFromTypeLog($typeLog);
	
	abstract function saveTypeLog($typeLog);
	
	abstract function deleteTypeLog($typeLog);
	
	abstract function deleteLinkbetweenLogsTypeLog($typeLog);

	abstract function getTypeLogById($id);

	abstract function getAllTypeLogs();

	abstract function getTypeLogsBetweenIndex($debut, $quantite);

	abstract function getNumberOfTypeLog();
	
	abstract function putLogsInTypeLog($typeLog);

	abstract protected function constructTypeLog($donnee);
}