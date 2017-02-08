<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des Parametres.				  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des Parametres
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Parametre;

abstract class ParametreManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addParametre($parametre);
	
	abstract function addFonctionFromParametre($parametre);
	
	abstract function saveParametre($parametre);
	
	abstract function deleteLinkBetweenFonctionsParametre($parametre);
	
	abstract function deleteParametre($parametre);

	abstract function getParametreById( $id);
	
	abstract function getParametresByStatut($statut);
	
	abstract function getAllParametres();

	abstract function getParametresBetweenIndex( $debut,  $quantite);

	abstract function getNumberOfParametre();
	
	abstract function putFonctionsInParametre($parametre);

	abstract protected function constructParametre($donnee);
}