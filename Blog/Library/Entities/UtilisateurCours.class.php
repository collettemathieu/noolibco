<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les UtilisateurCours.								  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe UtilisateurCours
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe UtilisateurCours
 */
class UtilisateurCours extends \Library\Entity {
	
	/* Définition des attributs */
	protected $cours, $utilisateur;
	
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
	public function setUtilisateur($utilisateur) {
		if ($utilisateur instanceof Utilisateur) {
			$this->utilisateur = $utilisateur;
		} else {
			$this->setErreurs ("UtilisateurCours setUtilisateur " .  self::FORMAT_UTILISATEUR );
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getCours() {
		return $this->cours;
	}
	public function getUtilisateur() {
		return $this->utilisateur;
	}
}
?>