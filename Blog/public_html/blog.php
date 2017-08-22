<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 aboutscience	         				          |
// +----------------------------------------------------------------------+
// | Lancement de l'application Blog.									  |						  			  					  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@aboutscience.net> 		  |
// +----------------------------------------------------------------------+

/**
 * @name:  Blog
 * @access: public
 * @version: 1
 */


require '../Library/autoloadClasseEtTrait.php';

$application = new Applications\Blog\BlogApplication;
$application->run();
