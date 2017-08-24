<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 aboutscience	         				          |
// +----------------------------------------------------------------------+
// | Lancement de l'application Cours.									  |						  			  					  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@aboutscience.net> 		  |
// +----------------------------------------------------------------------+

/**
 * @name:  Cours
 * @access: public
 * @version: 1
 */


require '../Library/autoloadClasseEtTrait.php';

$application = new Applications\Cours\CoursApplication;
$application->run();
