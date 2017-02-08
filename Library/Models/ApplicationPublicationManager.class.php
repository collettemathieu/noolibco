<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des ApplicationPublications.	  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des ApplicationPublications
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\ApplicationPublication;

abstract class ApplicationPublicationManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addApplicationPublication($applicationpublication);
	
	abstract function deleteApplicationPublication($applicationpublication);

	abstract function getApplicationPublicationById($idApplication, $idPublication);

	abstract function getAllApplicationPublications();

	abstract function getApplicationPublicationsBetweenIndex($debut, $quantite);

	abstract function getNumberOfApplicationPublication();

	abstract protected function constructApplicationPublication($donnee);
}