<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des PublicationAuteurs.		  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des PublicationAuteurs
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\PublicationAuteur;

abstract class PublicationAuteurManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addPublicationAuteur($publicationAuteur);
	
	abstract function deletePublicationAuteur($publicationAuteur);

	abstract function getPublicationAuteurById($idPublication, $idAuteur);

	abstract function getAllPublicationAuteurs();

	abstract function getPublicationAuteursBetweenIndex($debut, $quantite);

	abstract function getNumberOfPublicationAuteur();

	abstract function getNumberOfAuteurInPublication($idAuteur);

	abstract protected function constructPublicationAuteur($donnee);
}