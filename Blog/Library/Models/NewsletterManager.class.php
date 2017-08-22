<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager PDO des newsletters.			  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des Newsletters
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Newsletter;

abstract class NewsletterManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addNewsletter($newsletter);
	
	abstract function saveNewsletter($newsletter);
	
	abstract function deleteNewsletter($newsletter);

	abstract function getNewsletterById( $id);

	abstract function getAllNewsletters();

	abstract function getNewslettersBetweenIndex( $debut,  $quantite);

	abstract function getNumberOfNewsletters();

	abstract function putUtilisateursInNewsletter($newsletter);

	abstract protected function constructNewsletter($donnee);
}