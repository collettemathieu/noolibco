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

use \Library\Entities\Ville;

abstract class VilleManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addVille($ville);
	
	abstract function addEtablissementsFromVille($ville);
	
	abstract function saveVille($ville);
	
	abstract function deleteVille($ville);
	
	abstract function deleteLinkbetweenEtablissementsVille($ville);

	abstract function getVilleById($id);

	abstract function getAllVilles();

	abstract function getVillesBetweenIndex($debut, $quantite);

	abstract function getNumberOfVille();
	
	abstract function putEtablissementsInVille($ville);

	abstract protected function constructVille($donnee);
}