<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Lancement de l'application Commentaire.							  |						  			  					  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Commentaire
 * @access: public
 * @version: 1
 */

require '../Library/autoloadClasseEtTrait.php';

$application = new Applications\Commentaire\CommentaireApplication;
$application->run();


