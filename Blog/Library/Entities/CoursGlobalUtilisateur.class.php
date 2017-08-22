<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les CoursGlobalUtilisateur.						  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe CoursGlobalUtilisateur
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe CoursGlobalUtilisateur
 */
class CoursGlobalUtilisateur extends \Library\Entity {
	
	/* Définition des attributs */
	protected $coursGlobal, $utilisateur;
	
	/**
	 * ******Setter *****
	 */
	public function setCoursGlobal($coursGlobal) {
		if ($coursGlobal instanceof CoursGlobal) {
			$this->coursGlobal = $coursGlobal;
		} else {
			$this->setErreurs ("UtilisateurCoursGlobal setCoursGlobal " . self::FORMAT_COURS_GLOBAL );
		}
	}
	public function setUtilisateur($utilisateur) {
		if ($utilisateur instanceof Utilisateur) {
			$this->utilisateur = $utilisateur;
		} else {
			$this->setErreurs ("UtilisateurCoursGlobal setUtilisateur " .  self::FORMAT_UTILISATEUR );
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getCoursGlobal() {
		return $this->coursGlobal;
	}
	public function getUtilisateur() {
		return $this->utilisateur;
	}
}
?>