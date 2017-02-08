<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager des Etablissements.			  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com>		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des Etablissements
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Etablissement;

abstract class EtablissementManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addEtablissement($etablissement);
	
	abstract function addLaboratoiresFromEtablissement($etablissement);
	
	abstract function saveEtablissement($etablissement);
	
	abstract function deleteEtablissement($etablissement);
	
	abstract function deleteLinkbetweenLaboratoiresEtablissement($etablissement);

	abstract function getEtablissementById( $id);

	abstract function getAllEtablissements();

	abstract function getEtablissementsBetweenIndex( $debut,  $quantite);

	abstract function getNumberOfEtablissement();
	
	abstract function putLaboratoiresInEtablissement($etablissement);

	abstract protected function constructEtablissement($donnee);
}