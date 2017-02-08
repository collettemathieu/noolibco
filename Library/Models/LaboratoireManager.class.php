<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des Laboratoires.				  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des Laboratoires
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Laboratoire;

abstract class LaboratoireManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addLaboratoire($laboratoire);
	
	abstract function addEquipesFromLaboratoire($laboratoire);
	
	abstract function saveLaboratoire($laboratoire);
	
	abstract function deleteLaboratoire($laboratoire);
	
	abstract function deleteLinkbetweenEquipesLaboratoire($laboratoire);

	abstract function getLaboratoireById( $id);

	abstract function getAllLaboratoires();

	abstract function getLaboratoiresBetweenIndex( $debut,  $quantite);

	abstract function getNumberOfLaboratoire();
	
	abstract function putEquipesInLaboratoire($laboratoire);

	abstract protected function constructLaboratoire($donnee);
}