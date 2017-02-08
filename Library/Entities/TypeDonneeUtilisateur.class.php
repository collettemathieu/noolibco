<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 														  |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib 											  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les type données utilisateur.						  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> 		  |
// | Auteurs : Steve Despres  <stevedespres@noolib.com>				      |
// +----------------------------------------------------------------------+

/**
 *
* @name : Classe TypeDonneeUtilisateur
* @access : public
* @version : 1
*/
namespace Library\Entities;

/**
 * Classe TypeDonneeUtilisateur
 */
class TypeDonneeUtilisateur extends \Library\Entity {
	protected $idTypeDonneeUtilisateur, $nomTypeDonneeUtilisateur, $extensionTypeDonneeUtilisateur;

	/**
	 * ******setters******
	 */
	public function setIdTypeDonneeUtilisateur($idTypeDonneeUtilisateur) {
		if (ctype_digit($idTypeDonneeUtilisateur) || is_int($idTypeDonneeUtilisateur)) {
			$this->idTypeDonneeUtilisateur = $idTypeDonneeUtilisateur;
		} else {
			$this->setErreurs("TypeDonneeUtilisateur setIdTypeDonneeUtilisateur " . self::FORMAT_INT);
		}
	}
	public function setNomTypeDonneeUtilisateur($nomTypeDonneeUtilisateur) {
		if (is_string ( $nomTypeDonneeUtilisateur )) {
			$this->nomTypeDonneeUtilisateur = $nomTypeDonneeUtilisateur;
		} else {
			$this->setErreurs("TypeDonneeUtilisateur setNomTypeDonneeUtilisateur " . self::FORMAT_STRING);
		}
	}
	public function setExtensionTypeDonneeUtilisateur($extensionTypeDonneeUtilisateur) {
		if (is_string ( $extensionTypeDonneeUtilisateur )) {
			$this->extensionTypeDonneeUtilisateur = $extensionTypeDonneeUtilisateur;
		} else {
			$this->setErreurs("TypeDonneeUtilisateur setExtensionTypeDonneeUtilisateur " . self::FORMAT_STRING);
		}
	}
	
	/**
	 * *******getters*****
	 */
	public function getIdTypeDonneeUtilisateur() {
		return $this->idTypeDonneeUtilisateur;
	}
	public function getNomTypeDonneeUtilisateur() {
		return $this->nomTypeDonneeUtilisateur;
	}
	public function getExtensionTypeDonneeUtilisateur() {
		return $this->extensionTypeDonneeUtilisateur;
	}
}
?>