<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des FonctionParametres.		  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des FonctionParametres
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\FonctionParametre;

abstract class FonctionParametreManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addFonctionParametre($fonctionParametre);
	
	abstract function deleteFonctionParametre($fonctionParametre);

	abstract function getFonctionParametreById( $idFonction,  $idParametre, $idOrdre);

	abstract function getAllFonctionParametres();

	abstract function getLastOrdreOfParametres($idFonction);

	abstract function getFonctionParametresBetweenIndex( $debut,  $quantite);

	abstract function getNumberOfFonctionParametre();

	abstract protected function constructFonctionParametre($donnee);
}