<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les TacheFonction. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe TacheFonction
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe TacheFonction
 */
class TacheFonction extends \Library\Entity {
	
	/* Définition des attributs */
	protected $tache, $fonction, $ordre;
	
	/* Déclaration des constantes d'erreurs */
	/**
	 * ******Setter *****
	 */
	public function setTache($tache) {
		if ($tache instanceof Tache) {
			$this->tache = $tache;
		} else {
			$this->setErreurs ("TacheFonction setTache " . self::FORMAT_TACHE );
		}
	}
	public function setFonction($fonction) {
		if ($fonction instanceof Fonction) {
			$this->fonction = $fonction;
		} else {
			$this->setErreurs ("TacheFonction setFonction " .  self::FORMAT_FONCTION );
		}
	}
	public function setOrdre($ordre) {
		if (ctype_digit($ordre) || is_int($ordre))  {
			$this->ordre = $ordre;
		} else {
			$this->setErreurs ("TacheFonction setUtilisateur " .  self::FORMAT_INTEGER );
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getTache() {
		return $this->tache;
	}
	public function getFonction() {
		return $this->fonction;
	}
	public function getOrdre() {
		return $this->ordre;
	}
}
?>