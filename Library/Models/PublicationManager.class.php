<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des publications.				  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des publications
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Publication;

abstract class PublicationManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addPublication($publication);
	
	abstract function addUtilisateursFromPublication($publication);
	
	abstract function addApplicationsFromPublication($publication);
	
	abstract function addAuteursFromPublication($publication);
	
	abstract function savePublication($publication);
	
	abstract function deleteLinkBetweenUtilisateursPublication($publication);
	
	abstract function deleteLinkBetweenAuteursPublication($publication);
	
	abstract function deleteLinkBetweenApplicationsPublication($publication);

	abstract function deleteAuteur($publication);
	
	abstract function deletePublication($publication);

	abstract function getPublicationById($id);
	
	abstract function getPublicationByName($nomApplication);
	
	abstract function getPublicationByTitre($titrePublication);
	
	abstract function getAllPublications();

	abstract function getPublicationsBetweenIndex($debut, $quantite);

	abstract function getNumberOfPublication();
	
	abstract function putUtilisateursInPublication($publication);
	
	abstract function putAuteursInPublication($publication);
	
	abstract function putApplicationsInPublication($publication);

	abstract protected function constructPublication($donnee);
}