<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des cat�gories.				  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des applications
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Categorie;

abstract class CategorieManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addCategorie($categorie);
	
	abstract function addApplicationsFromCategorie($categorie);

	abstract function saveCategorie($categorie);
	
	abstract function deleteCategorie($categorie);
	
	abstract function deleteLinkBetweenApplicationsCategorie($categorie);

	abstract function getCategorieById($id);

	abstract function getAllCategories();

	abstract function getCategoriesBetweenIndex($debut, $quantite);

	abstract function getNumberOfCategorie();
	
	abstract function putApplicationsInCategorie($categorie);

	abstract protected function constructCategorie($donnee);
}