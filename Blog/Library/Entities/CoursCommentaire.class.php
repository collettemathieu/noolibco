<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les CoursCommentaire.								  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe CoursCommentaire
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe CoursCommentaire
 */
class CoursCommentaire extends \Library\Entity {
	
	/* Définition des attributs */
	protected $cours, $commentaire;
	
	/**
	 * ******Setter *****
	 */
	public function setCours($cours) {
		if ($cours instanceof Cours) {
			$this->cours = $cours;
		} else {
			$this->setErreurs ("CoursCommentaire setCours " . self::FORMAT_COURS );
		}
	}
	public function setCommentaire($commentaire) {
		if ($commentaire instanceof Commentaire) {
			$this->commentaire = $commentaire;
		} else {
			$this->setErreurs ("CoursCommentaire setCommentaire " .  self::FORMAT_COMMENTAIRE );
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getCours() {
		return $this->cours;
	}
	public function getCommentaire() {
		return $this->commentaire;
	}
}
?>