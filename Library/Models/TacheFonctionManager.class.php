<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des TacheFonctions.			  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des TacheFonctions
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\TacheFonction;

abstract class TacheFonctionManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addTacheFonction($tacheFonction);

	abstract function deleteTacheFonction($tacheFonction);

	abstract function getTacheFonctionById($idTache, $idFonction, $idOrdre);

	abstract function getLastOrdreOfFonctions($idTache);

	abstract function getAllTacheFonctions();

	abstract function getTacheFonctionsBetweenIndex($debut, $quantite);

	abstract function getNumberOfTacheFonction();

	abstract protected function constructTacheFonction($donnee);
}