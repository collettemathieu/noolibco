<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des Villes.					  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des Villes
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Surcategorie;

abstract class SurcategorieManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addSurcategorie($surcategorie);
	
	abstract function addCategoriesFromSurcategorie($surcategorie);
	
	abstract function saveSurcategorie($surcategorie);
	
	abstract function deleteLinkbetweenCategoriesSurcategorie($surcategorie);
	
	abstract function deleteSurcategorie($surcategorie);

	abstract function getSurcategorieById($id);

	abstract function getAllSurcategories();

	abstract function getSurcategoriesBetweenIndex($debut, $quantite);

	abstract function getNumberOfSurcategorie();
	
	abstract function putCategoriesInSurcategorie($surcategorie);

	abstract protected function constructSurcategorie($donnee);
}