<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des Equipes.					  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des Equipes
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Equipe;

abstract class EquipeManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addEquipe($equipe);
	
	abstract function addUtilisateursFromEquipe($equipe);
	
	abstract function saveEquipe($equipe);
	
	abstract function deleteEquipe($equipe);
	
	abstract function deleteLinkbetweenUtilisateursEquipe($equipe);

	abstract function getEquipeById( $id);

	abstract function getAllEquipes();

	abstract function getEquipesBetweenIndex( $debut,  $quantite);

	abstract function getNumberOfEquipe();
	
	abstract function putUtilisateursInEquipe($equipe);

	abstract protected function constructEquipe($donnee);
}