<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2015 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des TacheTypeDonneeUtilisateur. |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>			 	  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des TacheTypeDonneeUtilisateur
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\TacheTypeDonneeUtilisateur;

abstract class TacheTypeDonneeUtilisateurManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addTacheTypeDonneeUtilisateur($tacheTypeDonneeUtilisateur);
	
	abstract function deleteTacheTypeDonneeUtilisateur($tacheTypeDonneeUtilisateur);

	abstract function getTacheTypeDonneeUtilisateurById($idTache, $idTypeDonneeUtilisateur, $idOrdre);

	abstract function getAllTacheTypeDonneeUtilisateurs();

	abstract function getTacheTypeDonneeUtilisateursBetweenIndex($debut, $quantite);

	abstract function getNumberOfTacheTypeDonneeUtilisateur();

	abstract protected function constructTacheTypeDonneeUtilisateur($donnee);
}