<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les UtilisateurEquipe. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe UtilisateurEquipe
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe UtilisateurEquipe
 */
class UtilisateurEquipe extends \Library\Entity {
	
	/* Définition des attributs */
	protected $equipe, $utilisateur;
	
	
	
	/**
	 * ******Setter *****
	 */
	public function setEquipe($equipe) {
		if ($equipe instanceof Equipe) {
			$this->equipe = $equipe;
		} else {
			$this->setErreurs ("UtilisateurEquipe setEquipe " . self::FORMAT_EQUIPE );
		}
	}
	public function setUtilisateur($utilisateur) {
		if ($utilisateur instanceof Utilisateur) {
			$this->utilisateur = $utilisateur;
		} else {
			$this->setErreurs ("UtilisateurEquipe setUtilisateur " . self::FORMAT_UTILISATEUR );
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getUtilisateur() {
		return $this->utilisateur;
	}
	public function getEquipe() {
		return $this->equipe;
	}
}
?>