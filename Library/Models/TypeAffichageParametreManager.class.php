<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2015 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des TypeAffichageParametres.	  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu Collette <collettemathieu@noolib.com>		 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des TypeAffichageParametre
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\TypeAffichageParametre;

abstract class TypeAffichageParametreManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addTypeAffichageParametre($typeAffichageParametre);
	
	abstract function saveTypeAffichageParametre($typeAffichageParametre);
	
	abstract function deleteTypeAffichageParametre($typeAffichageParametre);

	abstract function getTypeAffichageParametreById($id);

	abstract function getTypeAffichageParametreByNom($nomTypeAffichageParametre);

	abstract function getAllTypeAffichageParametres();

	abstract function getTypeAffichageParametresBetweenIndex($debut, $quantite);

	abstract function getNumberOfTypeAffichageParametre();

	abstract protected function constructTypeAffichageParametre($donnee);
}