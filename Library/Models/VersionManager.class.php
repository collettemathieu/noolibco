<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des Versions.					  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des Versions
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Version;

abstract class VersionManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addVersion($version);
	
	abstract function addTachesFromVersion($version);
	
	abstract function saveVersion($version);

	abstract function deleteLinkbetweenTachesVersion($version);

	abstract function deleteTaches($version);
	
	abstract function deleteVersion($version);

	abstract function getVersionById($id);

	abstract function getAllVersions();

	abstract function getVersionsBetweenIndex($debut, $quantite);

	abstract function getNumberOfVersion();
	
	abstract function putTachesInVersion($version);

	abstract protected function constructVersion($donnee);
}