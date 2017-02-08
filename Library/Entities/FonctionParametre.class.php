<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les ParametreFonction. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe ParametreFonction
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe ParametreFonction
 */
class FonctionParametre extends \Library\Entity {
	
	/* Définition des attributs */
	protected $parametre, $fonction, $ordre;
	
	
	/**
	 * ******Setter *****
	 */
	public function setParametre($parametre) {
		if ($parametre instanceof Parametre) {
			$this->parametre = $parametre;
		} else {
			$this->setErreurs ("FonctionParametre setParametre " . self::FORMAT_PARAMETRE );
		}
	}
	public function setFonction($fonction) {
		if ($fonction instanceof Fonction) {
			$this->fonction = $fonction;
		} else {
			$this->setErreurs ("FonctionParametre setFonction " . self::FORMAT_FONCTION );
		}
	}
	public function setOrdre($ordre) {
		if (ctype_digit($ordre) || is_int($ordre)) {
			$this->ordre = $ordre;
		} else {
			$this->setErreurs ("FonctionParametre setOrdre " . self::FORMAT_INTEGER );
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getParametre() {
		return $this->parametre;
	}
	public function getFonction() {
		return $this->fonction;
	}
	public function getOrdre() {
		return $this->ordre;
	}
}
?>