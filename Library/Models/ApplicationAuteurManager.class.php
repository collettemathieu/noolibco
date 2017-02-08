<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2015 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des ApplicationAuteur.		  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>		 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des ApplicationAuteur
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\ApplicationAuteur;

abstract class ApplicationAuteurManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addApplicationAuteur($applicationAuteur);
	
	abstract function deleteApplicationAuteur($applicationAuteur);

	abstract function getApplicationAuteurById($idApplication, $idAuteur);

	abstract function getAllApplicationsFromAuteur($auteur);

	abstract function getApplicationAuteursBetweenIndex($debut, $quantite);

	abstract function getNumberOfApplicationAuteur();

	abstract function getNumberOfAuteurInApplication($idAuteur);

	abstract protected function constructApplicationAuteur($donnee);
}