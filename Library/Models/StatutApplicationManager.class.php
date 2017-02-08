<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager de Statut des applications.	  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager de Statut des applications
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\StatutApplication;

abstract class StatutApplicationManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addStatut($statut);
	
	abstract function addApplicationsFromStatut($statut);
	
	abstract function saveStatut($statut);
	
	abstract function deleteStatut($statut);
	
	abstract function deleteLinkbetweenApplicationsStatut($statut);

	abstract function getStatutById($id);

	abstract function getStatutByNom($nom);

	abstract function getAllStatuts();

	abstract function getStatutsBetweenIndex($debut, $quantite);

	abstract function getNumberOfStatut();
	
	abstract function putApplicationsInStatut($statut);

	abstract protected function constructStatut($donnee);
}