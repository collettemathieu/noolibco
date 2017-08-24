<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart	         				          |
// +----------------------------------------------------------------------+
// | Lancement de l'application Information.							  |						  			  					  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@scienceapart.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name:  Information
 * @access: public
 * @version: 1
 */

require '../Library/autoloadClasseEtTrait.php';

$application = new Applications\Information\InformationApplication;
$application->run();