<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Lancement de l'application HandleData.								  |						  			  					  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  HandleData
 * @access: public
 * @version: 1
 */

require '../Library/autoloadClasseEtTrait.php';

$application = new Applications\HandleData\HandleDataApplication;
$application->run();