<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 														  |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib 											  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les type d'affichage des paramètres.				  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu Collette <collettemathieu@noolib.com> 		      |
// +----------------------------------------------------------------------+

/**
 *
* @name : Classe TypeAffichageParametre
* @access : public
* @version : 1
*/
namespace Library\Entities;

/**
 * Classe TypeAffichageParametre
 */
class TypeAffichageParametre extends \Library\Entity {
	protected $idTypeAffichageParametre, $nomTypeAffichageParametre;

	/**
	 * ******setters******
	 */
	public function setIdTypeAffichageParametre($idTypeAffichageParametre) {
		if (ctype_digit($idTypeAffichageParametre) || is_int($idTypeAffichageParametre)) {
			$this->idTypeAffichageParametre = $idTypeAffichageParametre;
		} else {
			$this->setErreurs("TypeAffichageParametre setIdTypeAffichageParametre " . self::FORMAT_INT);
		}
	}
	public function setNomTypeAffichageParametre($nomTypeAffichageParametre) {
		if (is_string ( $nomTypeAffichageParametre )) {
			$this->nomTypeAffichageParametre = $nomTypeAffichageParametre;
		} else {
			$this->setErreurs("TypeAffichageParametre setNomTypeAffichageParametre " . self::FORMAT_STRING);
		}
	}
	
	/**
	 * *******getters*****
	 */
	public function getIdTypeAffichageParametre() {
		return $this->idTypeAffichageParametre;
	}
	public function getNomTypeAffichageParametre() {
		return $this->nomTypeAffichageParametre;
	}
}
?>