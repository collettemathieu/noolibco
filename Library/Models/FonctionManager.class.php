<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des Fonctions.				  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des Fonctions
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Fonction;

abstract class FonctionManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addFonction($fonction);
	
	abstract function addParametresFromFonction($fonction);
	
	abstract function addTachesFromFonction($fonction);
	
	abstract function saveFonction($fonction);
	
	abstract function deleteLinkBetweenParametresFonction($fonction);
	
	abstract function deleteLinkBetweenTachesFonction($fonction);

	abstract function deleteParametres($fonction);
	
	abstract function deleteFonction($fonction);

	abstract function getFonctionById( $id);

	abstract function getAllFonctions();

	abstract function getFonctionsBetweenIndex( $debut,  $quantite);

	abstract function getNumberOfFonction();
	
	abstract function putParametresInFonction($fonction);
	
	abstract function putTachesInFonction($fonction);

	abstract protected function constructFonction($donnee);
}