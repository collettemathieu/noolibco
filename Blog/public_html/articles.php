<?php
// +----------------------------------------------------------------------+
// | PHP Version 7 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib The Blog	         				          |
// +----------------------------------------------------------------------+
// | Lancement de l'application Articles								  |						  			  					  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>		 		  |
// +----------------------------------------------------------------------+

/**
 * @name:  Articles
 * @access: public
 * @version: 1
 */


require '../Library/autoloadClasseEtTrait.php';

$application = new Applications\Articles\ArticlesApplication;
$application->run();
