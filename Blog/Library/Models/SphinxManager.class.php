<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 AboutScience	         				          |
// +----------------------------------------------------------------------+
// |  Classe abstraite PHP pour le manager du module Shpinx.			  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@aboutscience.net>		  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe SphinxApplication
 * @access: public
 * @version: 1
 */

namespace Library\Models;


abstract class SphinxManager extends \Library\Manager{
/* Définition des méthodes abstraites */

	abstract function searchSphinxArticles($motsRecherche);

	abstract function searchSphinxCours($motsRecherche);
	
}