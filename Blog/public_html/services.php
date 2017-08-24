<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart	         				          |
// +----------------------------------------------------------------------+
// | Lancement de l'application Services.								  |						  			  					  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name:  Services
 * @access: public
 * @version: 1
 */


require '../Library/autoloadClasseEtTrait.php';

$application = new Applications\Services\ServicesApplication;
$application->run();
