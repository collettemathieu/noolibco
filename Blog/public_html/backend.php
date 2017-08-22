<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Lancement de l'application Backend.								  |						  			  					  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Backend
 * @access: public
 * @version: 1
 */

require '../Library/autoloadClasseEtTrait.php';

$application = new Applications\Backend\BackendApplication;
$application->run();


