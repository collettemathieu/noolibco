<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les UtilisateurDonneeUtilisateur. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe UtilisateurDonneeUtilisateur
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe UtilisateurDonneeUtilisateur
 */
class UtilisateurDonneeUtilisateur extends \Library\Entity {
	
	/* Définition des attributs */
	protected $donneeUtilisateur, $utilisateur;
	
	/**
	 * ******Setter *****
	 */
	public function setDonneeUtilisateur($donneeUtilisateur) {
		if ($donneeUtilisateur instanceof DonneeUtilisateur) {
			$this->donneeUtilisateur = $donneeUtilisateur;
		} else {
			$this->setErreurs ("UtilisateurDonneeUtilisateur setDonneeUtilisateur " . self::FORMAT_DONNEE_UTILISATEUR );
		}
	}
	public function setUtilisateur($utilisateur) {
		if ($utilisateur instanceof Utilisateur) {
			$this->utilisateur = $utilisateur;
		} else {
			$this->setErreurs ("UtilisateurDonneeUtilisateur setUtilisateur " .  self::FORMAT_UTILISATEUR );
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getDonneeUtilisateur() {
		return $this->donneeUtilisateur;
	}
	public function getUtilisateur() {
		return $this->utilisateur;
	}
}
?>