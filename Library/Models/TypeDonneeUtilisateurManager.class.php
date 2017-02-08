<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des TypeDonneeUtilisateurs.	  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des TypeDonneeUtilisateurs
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\TypeDonneeUtilisateur;

abstract class TypeDonneeUtilisateurManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addTypeDonneeUtilisateur($typeDonneeUtilisateur);
	
	abstract function saveTypeDonneeUtilisateur($typeDonneeUtilisateur);
	
	abstract function deleteTypeDonneeUtilisateur($typeDonneeUtilisateur);

	abstract function getTypeDonneeUtilisateurById($id);

	abstract function getTypeDonneeUtilisateurByNom($nomTypeDonneeUtilisateur);

	abstract function getTypeDonneeUtilisateurByExtension($extensionTypeDonneeUtilisateur);

	abstract function getAllTypeDonneeUtilisateurs();

	abstract function getTypeDonneeUtilisateursBetweenIndex($debut, $quantite);

	abstract function getNumberOfTypeDonneeUtilisateur();

	abstract protected function constructTypeDonneeUtilisateur($donnee);
}