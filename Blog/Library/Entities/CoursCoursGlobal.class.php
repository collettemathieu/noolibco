<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les CoursCoursGlobal.								  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe CoursCoursGlobal
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe CoursCoursGlobal
 */
class CoursCoursGlobal extends \Library\Entity {
	
	/* Définition des attributs */
	protected $cours, $coursGlobal;
	
	/**
	 * ******Setter *****
	 */
	public function setCours($cours) {
		if ($cours instanceof Cours) {
			$this->cours = $cours;
		} else {
			$this->setErreurs ("CoursCoursGlobal setCours " . self::FORMAT_COURS );
		}
	}
	public function setCoursGlobal($coursGlobal) {
		if ($coursGlobal instanceof CoursGlobal) {
			$this->coursGlobal = $coursGlobal;
		} else {
			$this->setErreurs ("CoursCoursGlobal setCoursGlobal " .  self::FORMAT_COURS_GLOBAL );
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getCours() {
		return $this->cours;
	}
	public function getCoursGlobal() {
		return $this->coursGlobal;
	}
}
?>