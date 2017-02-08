<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager de Statut des utilisateurs.	  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>				  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager de Statut des utilisateurs
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\StatutUtilisateur;

abstract class StatutUtilisateurManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addStatut($statut);
	
	abstract function addUtilisateursFromStatut($statut);
	
	abstract function saveStatut($statut);
	
	abstract function deleteStatut($statut);
	
	abstract function deleteLinkbetweenUtilisateursStatut($statut);

	abstract function getStatutById($id);

	abstract function getStatutByNom($nom);

	abstract function getAllStatuts();

	abstract function getNumberOfStatut();
	
	abstract function putUtilisateursInStatut($statut);

	abstract protected function constructStatut($donnee);
}