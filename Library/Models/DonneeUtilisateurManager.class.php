<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des DonneesUtilisateur.		  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des DonneesUtilisateur
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\DonneeUtilisateur;

abstract class DonneeUtilisateurManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addDonneeUtilisateur($donneeUtilisateur);

	abstract function addUtilisateursFromDonneeUtilisateur($donneeUtilisateur);
	
	abstract function saveDonneeUtilisateur($donneeUtilisateur);

	abstract function updateDateDonneeUtilisateur($donneeUtilisateur);

	abstract function deleteLinkbetweenUtilisateursDonneeUtilisateur($donneeUtilisateur);
	
	abstract function deleteDonneeUtilisateur($donneeUtilisateur);

	abstract function getDonneeUtilisateurById($id);

	abstract function getAllDonneeUtilisateur();

	abstract function getDonneeUtilisateursBetweenIndex($debut, $quantite);

	abstract function getNumberOfDonneeUtilisateur();

	abstract protected function constructDonneeUtilisateur($donnee);
}