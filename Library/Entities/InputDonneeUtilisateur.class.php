<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 														  |
// +----------------------------------------------------------------------+
// | Copyright (c) 2015 NooLib 											  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les InputDonneeUtilisateur. 						  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu Collette <collettemathieu@noolib.com> 			  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe InputDonneeUtilisateur
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe InputDonneeUtilisateur
 */
class InputDonneeUtilisateur extends \Library\Entities\DonneeUtilisateur {
	protected $valeurInputDonneeUtilisateur;

	/**
	 * ******setters******
	 */
	public function setValeurInputDonneeUtilisateur($valeur) {
		if (!empty($valeur) && $valeur != null) {
			$this->valeurInputDonneeUtilisateur = $valeur;
		} else {
			$this->setErreurs("InputDonneeUtilisateur setValeurInputDonneeUtilisateur " . self::FORMAT_EMPTY);
		}
	}
	

	/**
	 * *******getters*****
	 */
	public function getValeurInputDonneeUtilisateur() {
		return $this->valeurInputDonneeUtilisateur;
	}
}
