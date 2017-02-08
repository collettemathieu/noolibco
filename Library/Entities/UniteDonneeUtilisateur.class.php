<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 														  |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib 											  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les unités des données utilisateur.				  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
* @name : Classe UniteDonneeUtilisateur
* @access : public
* @version : 1
*/
namespace Library\Entities;

/**
 * Classe UniteDonneeUtilisateur
 */
class UniteDonneeUtilisateur extends \Library\Entity {
	protected $idUniteDonneeUtilisateur, $nomUniteDonneeUtilisateur, $symboleUniteDonneeUtilisateur;

	/**
	 * ******setters******
	 */
	public function setIdUniteDonneeUtilisateur($idUniteDonneeUtilisateur) {
		if (ctype_digit($idUniteDonneeUtilisateur) || is_int($idUniteDonneeUtilisateur)) {
			$this->idUniteDonneeUtilisateur = $idUniteDonneeUtilisateur;
		} else {
			$this->setErreurs("UniteDonneeUtilisateur setIdUniteDonneeUtilisateur " . self::FORMAT_INT);
		}	
	}
	public function setNomUniteDonneeUtilisateur($nomUniteDonneeUtilisateur) {
		if (is_string ( $nomUniteDonneeUtilisateur )) {
			$this->nomUniteDonneeUtilisateur = $nomUniteDonneeUtilisateur;
		} else {
			$this->setErreurs("UniteDonneeUtilisateur setNomUniteDonneeUtilisateur " . self::FORMAT_STRING);
		}
	}
	public function setSymboleUniteDonneeUtilisateur($symboleUniteDonneeUtilisateur) {
		if (is_string ( $symboleUniteDonneeUtilisateur )) {
			$this->symboleUniteDonneeUtilisateur = $symboleUniteDonneeUtilisateur;
		} else {
			$this->setErreurs("UniteDonneeUtilisateur setSymboleUniteDonneeUtilisateur " . self::FORMAT_STRING);
		}
	}
	/**
	 * *******getters*****
	 */
	public function getIdUniteDonneeUtilisateur() {
		return $this->idUniteDonneeUtilisateur;
	}
	public function getNomUniteDonneeUtilisateur() {
		return $this->nomUniteDonneeUtilisateur;
	}
	public function getSymboleUniteDonneeUtilisateur() {
		return $this->symboleUniteDonneeUtilisateur;
	}
}
?>