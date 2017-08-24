<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des catégories.				  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des catégories
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Categorie;

abstract class CategorieManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addCategorie($categorie);

	abstract function saveCategorie($categorie);
	
	abstract function deleteCategorie($categorie);

	abstract function getCategorieById($id);

	abstract function getAllCategories();

	abstract function getCategoriesBetweenIndex($debut, $quantite);

	abstract function getNumberOfCategorie();
	
	abstract function putArticlesInCategorie($categorie);

	abstract function putCoursInCategorie($categorie);

	abstract protected function constructCategorie($donnee);
}