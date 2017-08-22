<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 AboutScience									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les CoursMotCle.									  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@aboutscience.net> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe CoursMotCle
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe CoursMotCle
 */
class CoursMotCle extends \Library\Entity {
	
	/* Définition des attributs */
	protected $cours, $motCle;
	
	/**
	 * ******Setter *****
	 */
	public function setCours($cours) {
		if ($cours instanceof Cours) {
			$this->cours = $cours;
		} else {
			$this->setErreurs ("UtilisateurCours setCours " . self::FORMAT_COURS );
		}
	}
	public function setMotCle($motCle) {
		if ($motCle instanceof MotCle) {
			$this->motCle = $motCle;
		} else {
			$this->setErreurs ("CoursMotCle setMotCle " .  self::FORMAT_MOT_CLE );
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getCours() {
		return $this->cours;
	}
	public function getMotCle() {
		return $this->motCle;
	}
}
?>