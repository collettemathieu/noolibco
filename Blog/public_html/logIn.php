<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart	         				          |
// +----------------------------------------------------------------------+
// | Lancement de l'application LogIn.									  |						  			  					  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@scienceapart.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name:  LogIn
 * @access: public
 * @version: 1
 */

require '../Library/autoloadClasseEtTrait.php';

$application = new Applications\LogIn\LogInApplication;
$application->run();