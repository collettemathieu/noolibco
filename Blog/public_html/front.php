<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 aboutscience	         				          |
// +----------------------------------------------------------------------+
// | Lancement de l'application Frontend.								  |						  			  					  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@aboutscience.net> 		  |
// +----------------------------------------------------------------------+

/**
 * @name:  Frontend
 * @access: public
 * @version: 1
 */


require '../Library/autoloadClasseEtTrait.php';

$application = new Applications\Frontend\FrontendApplication;
$application->run();
