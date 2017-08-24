<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Editos.	 									  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe Edito
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe Edito
 */
class Edito extends \Library\Entity {
	
	/* Définition des attributs */
	protected 	$idEdito, 
				$texteEdito,
				$dateEdito;

	/* Déclaration des constantes de classe */
	const TEXTE_EDITO = 'Votre édito doit au moins comporter 50 caractères.';
	
	/**
	 * ******Setter *****
	 */
	public function setIdEdito($idEdito) {
		// verification que l'id est au format integer
		if (ctype_digit($idEdito) || is_int($idEdito)) {
			$this->idEdito = $idEdito;
		} 
		//sinon envoi d'une erreur sur le format integer. 
		else {
			$this->setErreurs("Edito setIdEdito " . self::FORMAT_INT);
		}
	}
	public function setTexteEdito($texte) {

		if (is_string ( $texte )) {
			//verification que le texte contient au moins 50 caractères.
			
			if (mb_strlen($texte, 'UTF8') > 49){
				$this->texteEdito = $texte;
			}else {
				$this->setErreurs ("Edito setTexteEdito " .self::TEXTE_EDITO );
			}
		} else {
			$this->setErreurs ("Edito setTexteEdito " . self::FORMAT_STRING );
		}
	}
	public function setDateEdito($date) {

		if (is_string ( $date )) {
			$this->dateEdito = $date;
		} else {
			$this->setErreurs ("Edito setDateEdito " . self::FORMAT_STRING );
		}
	}
	
	/**
	 * ********** getter ****************
	*/
	public function getIdEdito() {
		return $this->idEdito;
	}
	public function getTexteEdito(){
		return $this->texteEdito;
	}
	public function getDateEdito(){
		return $this->dateEdito;
	}
	
}
