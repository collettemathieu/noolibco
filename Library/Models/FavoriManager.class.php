<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des Favoris.				 	  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des Favoris
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Favori;

abstract class FavoriManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addFavori($favori);
	
	abstract function deleteFavori($favori);

	abstract function getFavoriById( $idApplication,  $idUtilisateur);

	abstract function getAllFavoris();

	abstract function getFavorisBetweenIndex( $debut,  $quantite);

	abstract function getNumberOfFavori();

	abstract protected function constructFavori($donnee);
}