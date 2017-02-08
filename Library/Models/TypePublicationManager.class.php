<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des TypePublications.			  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des TypePublications
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\TypePublication;

abstract class TypePublicationManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addTypePublication($typePublication);
	
	abstract function addPublicationsFromTypePublication($typePublication);
	
	abstract function saveTypePublication($typePublication);
	
	abstract function deleteTypePublication($typePublication);
	
	abstract function deleteLinkbetweenPublicationsTypePublication($typePublication);

	abstract function getTypePublicationById($id);

	abstract function getTypePublicationByName($nomTypePublication);
	
	abstract function getAllTypePublications();

	abstract function getTypePublicationsBetweenIndex($debut, $quantite);

	abstract function getNumberOfTypePublication();
	
	abstract function putPublicationsInTypePublication($typePublication);

	abstract protected function constructTypePublication($donnee);
}