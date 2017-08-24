<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager PDO des actualités. 			  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des Actualites
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Actualite;

abstract class ActualiteManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addActualite($actualite);
	
	abstract function saveActualite($actualite);

	abstract function publishActualite($actualite);
	
	abstract function deleteActualite($actualite);

	abstract function getActualiteById( $id);

	abstract function getAllActualites();

	abstract function getActualitesBetweenIndex( $debut,  $quantite);

	abstract function getNumberOfActualites();

	abstract protected function constructActualite($donnee);
}