<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des UtilisateurEquipes.		  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des UtilisateurEquipes
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\UtilisateurEquipe;

abstract class UtilisateurEquipeManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addUtilisateurEquipe($utilisateurEquipe);
	
	
	abstract function deleteUtilisateurEquipe($utilisateurEquipe);

	abstract function getUtilisateurEquipeById($idUtilisateur, $idEquipe);

	abstract function getAllUtilisateurEquipes();

	abstract function getUtilisateurEquipesBetweenIndex($debut, $quantite);

	abstract function getNumberOfUtilisateurEquipe();

	abstract protected function constructUtilisateurEquipe($donnee);
}