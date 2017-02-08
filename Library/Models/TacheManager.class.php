<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des Taches.					  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des Taches
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Tache;

abstract class TacheManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addTache($tache);
	
	abstract function addVersionsFromTache($tache);
	
	abstract function addFonctionsFromTache($tache);
	
	abstract function saveTache($tache);

	abstract function deleteLinkbetweenVersionsTache($tache);
	
	abstract function deleteLinkbetweenFonctionsTache($tache);

	abstract function deleteLinkbetweenTacheTypeDonneeUtilisateur($tache);

	abstract function deleteFonctions($tache);
	
	abstract function deleteTache($tache);
	
	abstract function getTacheById($id);

	abstract function getTacheByIdLimited($id);

	abstract function getAllTaches();

	abstract function getTachesBetweenIndex($debut, $quantite);

	abstract function getNumberOfTache();
	
	abstract function putVersionsInTache($tache);
	
	abstract function putFonctionsInTache($tache);

	abstract function putTacheTypeDonneeUtilisateursInTache($tache);

	abstract protected function constructTache($donnee);
}