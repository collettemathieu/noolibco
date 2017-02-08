<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des UniteDonneeUtilisateurs.	  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des UniteDonneeUtilisateurs
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\UniteDonneeUtilisateur;

abstract class UniteDonneeUtilisateurManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addUniteDonneeUtilisateur($uniteDonneeUtilisateur);
	
	abstract function saveUniteDonneeUtilisateur($uniteDonneeUtilisateur);
	
	abstract function deleteUniteDonneeUtilisateur($uniteDonneeUtilisateur);

	abstract function getUniteDonneeUtilisateurById($id);

	abstract function getUniteDonneeUtilisateurByNom($nomUniteDonneeUtilisateur);

	abstract function getAllUniteDonneeUtilisateurs();

	abstract function getUniteDonneeUtilisateursBetweenIndex($debut, $quantite);

	abstract function getNumberOfUniteDonneeUtilisateur();

	abstract protected function constructUniteDonneeUtilisateur($donnee);
}