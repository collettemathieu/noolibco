<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour le manager PDO des cours.	 			  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe abstraite Manager des Cours
 * @access: public
 * @version: 1
 */	

namespace Library\Models;

use \Library\Entities\Cours;

abstract class CoursManager extends \Library\Manager
{
/* Définition des méthodes abstraites */

	abstract function addCours($cours);

	abstract function addMotsClesFromCours($cours);

	abstract function addAuteurFromCours($cours);
	
	abstract function saveCours($cours);

	abstract function saveSommaireCours($cours);

	abstract function publishCours($cours);
	
	abstract function deleteCours($cours);
	
	abstract function deleteLinkbetweenCoursMotCles($cours);

	abstract function deleteLinkbetweenCoursAuteur($cours);

	abstract function getCoursById($id);

	abstract function getCoursByUrlTitle($urlTitreCours);

	abstract function getAllCours();

	abstract function getAllVues();

	abstract function getCoursBetweenIndex($debut, $quantite);

	abstract function getNumberOfCours();

	abstract function putMotsClesInCours($cours);

	abstract function putAuteurInCours($cours);

	abstract function putCommentairesInCours($cours);

	abstract function putCoursGlobalInCours($cours);

	abstract protected function constructCours($donnee);
}