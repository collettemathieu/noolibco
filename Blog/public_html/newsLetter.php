<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart	         				          |
// +----------------------------------------------------------------------+
// | Lancement de l'application NewsLetter.								  |						  			  					  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@scienceapart.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name:  NewsLetter
 * @access: public
 * @version: 1
 */

require '../Library/autoloadClasseEtTrait.php';

$application = new Applications\NewsLetter\NewsLetterApplication;
$application->run();


