<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des applications.				  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des applications
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Application;

abstract class ApplicationManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addApplication($application);

	abstract function addMotClesFromApplication($application);
	
	abstract function addPublicationsFromApplication($application);
	
	abstract function addVersionsFromApplication($application);
	
	abstract function addFavorisFromApplication($application);

	abstract function saveDescriptionAndCategorieApplication($application);

	abstract function saveNameApplication($application);

	abstract function saveStep2DepositApplication($application);

	abstract function saveStatutApplication($application);
	
	abstract function saveAllApplication($application);
	
	abstract function deleteLinkBetweenMotClesApplication($application);
	
	abstract function deleteLinkBetweenFavorisApplication($application);
	
	abstract function deleteLinkBetweenPublicationsApplication($application);
	
	abstract function deleteLinkBetweenVersionsApplication($application);

	abstract function deleteLinkBetweenApplicationAuteur($application);

	abstract function deletePublications($application);

	abstract function deleteVersions($application);
	
	abstract function deleteApplication($application);

	abstract function getApplicationById($id);

	abstract function getApplicationsByIdMotCle($idMotCle);

	abstract function getApplicationByIdWithAllParameters($id);
	
	abstract function getApplicationByName($nomApplication);

	abstract function getApplicationByNameWithAllParameters($nomApplication);

	abstract function getAllApplications();

	abstract function getAllActiveApplications();

	abstract function getApplicationsBetweenIndex($debut, $quantite);

	abstract function getNumberOfApplication();

	abstract function getApplicationsOfUser($idUtilisateur);
	
	abstract function putMotClesInApplication($application);
	
	abstract function putFavorisInApplication($application);
	
	abstract function putPublicationsInApplication($application);

	abstract function putAuteursInApplication($application);
	
	abstract function putVersionsInApplication($application);

	abstract protected function constructApplication($donnee);
}