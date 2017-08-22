<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les UtilisateurCommentaire.						  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.net> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe UtilisateurCommentaire
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe UtilisateurCommentaire
 */
class UtilisateurCommentaire extends \Library\Entity {
	
	/* Définition des attributs */
	protected $commentaire, $utilisateur;
	
	/**
	 * ******Setter *****
	 */
	public function setCommentaire($commentaire) {
		if ($commentaire instanceof Commentaire) {
			$this->commentaire = $commentaire;
		} else {
			$this->setErreurs ("UtilisateurCommentaire setCommentaire " . self::FORMAT_COMMENTAIRE );
		}
	}
	public function setUtilisateur($utilisateur) {
		if ($utilisateur instanceof Utilisateur) {
			$this->utilisateur = $utilisateur;
		} else {
			$this->setErreurs ("UtilisateurCommentaire setUtilisateur " .  self::FORMAT_UTILISATEUR );
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getCommentaire() {
		return $this->commentaire;
	}
	public function getUtilisateur() {
		return $this->utilisateur;
	}
}
?>