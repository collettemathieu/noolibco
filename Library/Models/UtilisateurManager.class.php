<?php
// +----------------------------------------------------------------------+
// | PHP Version 7 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des utilisateurs.				  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des utilisateurs
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Utilisateur;

abstract class UtilisateurManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addUtilisateur($utilisateur);
	
	abstract function addPublicationAuteursFromUtilisateur($utilisateur);
	
	abstract function addPublicationsPublieursFromUtilisateur($utilisateur);
	
	abstract function addApplicationsFromUtilisateur($utilisateur);
	
	abstract function addFavorisFromUtilisateur($utilisateur);
	
	abstract function addEquipesFromUtilisateur($utilisateur);
	
	abstract function addDonneeUtilisateurFromUtilisateur($utilisateur);
	
	abstract function addLogsFromUtilisateur($utilisateur);
	
	abstract function saveUtilisateur($utilisateur);

	abstract function updateWorkSpaceUtilisateur($utilisateur);

	abstract function updateDateConnexionUtilisateur($utilisateur);
	
	abstract function deleteLinkBetweenPublicationAuteursUtilisateur($utilisateur);
	
	abstract function deleteLinkBetweenPublicationsPublieursUtilisateur($utilisateur);
	
	abstract function deleteLinkBetweenFavorisUtilisateur($utilisateur);
	
	abstract function deleteLinkBetweenEquipesUtilisateur($utilisateur);
	
	abstract function deleteLinkBetweenDonneeUtilisateurUtilisateur($utilisateur);
	
	abstract function deleteLinkBetweenLogsUtilisateur($utilisateur);

	abstract function deleteDonneesUtilisateur($utilisateur);

	abstract function deleteApplications($utilisateur);
	
	abstract function deleteUtilisateur($utilisateur);

	abstract function getUtilisateurById($id);

	abstract function getUtilisateurByIdWithAllData($id);
	
	abstract function getUtilisateurByMail($mail);

	abstract function getAllUtilisateurs();

	abstract function getUtilisateursBetweenIndex($debut, $quantite);

	abstract function getNumberOfUtilisateur();
	
	abstract function putPublicationAuteursInUtilisateur($utilisateur);
	
	abstract function putPublicationsPublieursInUtilisateur($utilisateur);
	
	abstract function putApplicationsInUtilisateur($utilisateur);
	
	abstract function putFavorisInUtilisateur($utilisateur);
	
	abstract function putEquipesInUtilisateur($utilisateur);
	
	abstract function putDonneesUtilisateurInUtilisateur($utilisateur);
	
	abstract function putLogsInUtilisateur($utilisateur);

	abstract protected function constructUtilisateur($donnee);
}