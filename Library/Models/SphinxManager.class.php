<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager du module Shpinx.				  |
// +----------------------------------------------------------------------+
// | Auteur : Antoine FAUCHARD <AntoineFauchard@noolib.com>				  |
// | 		   Mathieu COLLETTE <collettemathieu@noolib.com>   			  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager du module Sphinx
 * @access: public
 * @version: 1
 */	

namespace Library\Models;


abstract class SphinxManager extends \Library\Manager{
/* Définition des méthodes abstraites */

	abstract function searchSphinxApplicationByMotCle($motRecherche);

	abstract function searchSphinxApplicationByNom($motRecherche);
	
}