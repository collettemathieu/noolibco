<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des 							  |
// | UtilisateurDonneeUtilisateur. 									  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des UtilisateurDonneeUtilisateur
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\UtilisateurDonneeUtilisateur;

abstract class UtilisateurDonneeUtilisateurManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addUtilisateurDonneeUtilisateur($utilisateurDonneeUtilisateur);
	
	abstract function deleteUtilisateurDonneeUtilisateur($utilisateurDonneeUtilisateur);

	abstract function getUtilisateurDonneeUtilisateurById($idUtilisateur, $idDonneeUtilisateur);
	
	abstract function getUtilisateurDonneeUtilisateurByIdUtilisateur($idUtilisateur);

	abstract function getAllUtilisateurDonneeUtilisateur();

	abstract function getUtilisateurDonneeUtilisateurBetweenIndex($debut, $quantite);

	abstract function getNumberOfUtilisateurDonneeUtilisateur();

	abstract protected function constructUtilisateurDonneeUtilisateur($donnee);
}