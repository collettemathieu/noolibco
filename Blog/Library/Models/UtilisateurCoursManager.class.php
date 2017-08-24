<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager PDO des utilisateurCours. 	  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des utilisateurCours
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\UtilisateurCours;

abstract class UtilisateurCoursManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addUtilisateurCours($utilisateurCours);

	abstract function deleteUtilisateurCours($utilisateurCours);
	
	abstract function getUtilisateurCoursById($idArticle, $idCours);

	abstract protected function constructUtilisateurCours($donnee);
}