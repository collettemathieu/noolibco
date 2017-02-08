<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des cat�gories.				  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des applications
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Auteur;

abstract class AuteurManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addAuteur($auteur);
	
	abstract function addPublicationsFromAuteur($auteur);

	abstract function saveAuteur($auteur);
	
	abstract function deleteAuteur($auteur);
	
	abstract function deleteLinkbetweenPublicationsAuteur($auteur);

	abstract function getAuteurById($id);

	abstract function getAuteurByMail($mail);
	
	abstract function getAuteurByNameAndSurname($nomAuteur, $prenomAuteur);

	abstract function getAllAuteurs();

	abstract function getAuteursBetweenIndex($debut, $quantite);

	abstract function getNumberOfAuteur();
	
	abstract function putPublicationsInAuteur($auteur);

	abstract protected function constructAuteur($donnee);
}