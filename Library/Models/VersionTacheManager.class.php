<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des VersionTaches.			  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des VersionTaches
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\VersionTache;

abstract class VersionTacheManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addVersionTache($versionTache);
	
	abstract function deleteVersionTache($versionTache);

	abstract function getVersionTacheById($idVersion, $idTache);

	abstract function getAllVersionTaches();

	abstract function getVersionTachesBetweenIndex($debut, $quantite);

	abstract function getNumberOfVersionTache();

	abstract protected function constructVersionTache($donnee);
}