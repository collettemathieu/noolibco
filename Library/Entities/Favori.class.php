<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Favori. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe Favori
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe Favori
 */
class Favori extends \Library\Entity {
	
	/* Définition des attributs */
	protected $application, $utilisateur;
	
	/**
	 * ******Setter *****
	 */
	public function setApplication($application) {
		if ($application instanceof Application) {
			$this->application = $application;
		} 
		else {
			$this->setErreurs("Favori setApplication " . self::FORMAT_APPLICATION);
		}
	}
	public function setUtilisateur($utilisateur) {
		if ($utilisateur instanceof Utilisateur) {
			$this->utilisateur = $utilisateur;
		}
		else {
			$this->setErreurs("Favori setUtilisateur " . self::FORMAT_UTILISATEUR);
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getApplication() {
		return $this->application;
	}
	public function getUtilisateur() {
		return $this->utilisateur;
	}
}
?>