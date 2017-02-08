<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des Pays.						  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des Pays
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Pays;

abstract class PaysManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addPays($pays);
	
	abstract function addVillesFromPays($pays);
	
	abstract function savePays($pays);
	
	abstract function deletePays($pays);
	
	abstract function deleteLinkbetweenVillesPays($pays);

	abstract function getPaysById( $id);

	abstract function getAllPays();

	abstract function getPaysBetweenIndex( $debut,  $quantite);

	abstract function getNumberOfPays();
	
	abstract function putVillesInPays($pays);

	abstract protected function constructPays($donnee);
}