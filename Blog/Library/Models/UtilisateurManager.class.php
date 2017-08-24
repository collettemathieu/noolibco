<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des Utilisateurs. 			  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+


/**
 * @name: Classe abstraite Manager des utilisateurs
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Utilisateur;

abstract class UtilisateurManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addUtilisateur($utilisateur);
	
	abstract function saveUtilisateur($utilisateur);
	
	abstract function deleteLinkBetweenArticlesUtilisateur($utilisateur);
	
	abstract function deleteLinkBetweenCommentairesUtilisateur($utilisateur);
	
	abstract function deleteLinkBetweenCoursUtilisateur($utilisateur);

	abstract function deleteCommentaires($utilisateur);

	abstract function deleteArticles($utilisateur);

	abstract function deleteCours($utilisateur);
	
	abstract function deleteUtilisateur($utilisateur);

	abstract function getUtilisateurById($id);

	abstract function getUtilisateurByIdWithAllData($id);
	
	abstract function getUtilisateurByMail($mail);

	abstract function getAllUtilisateurs();

	abstract function getUtilisateursBetweenIndex($debut, $quantite);

	abstract function getNumberOfUtilisateur();
	
	abstract function putArticlesInUtilisateur($utilisateur);

	abstract function putCoursInUtilisateur($utilisateur);

	abstract function putCommentairesInUtilisateur($utilisateur);

	abstract protected function constructUtilisateur($donnee);
}