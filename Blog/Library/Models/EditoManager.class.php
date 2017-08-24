<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager PDO des editos. 			  	  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des Editos
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Edito;

abstract class EditoManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addEdito($edito);
	
	abstract function deleteEdito($edito);

	abstract function getEditoById($id);

	abstract function getAllEditos();

	abstract function getNumberOfEditos();

	abstract protected function constructEdito($donnee);
}