<?php
namespace Library\Validators;
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP de la classe validateur validant la longueur d'un 		  |
// | paramètre.		                       								  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe MaxLenghtValidator
 * @access: public
 * @version: 1
 */

class MaxLenghtValidator extends \Library\Validators
{
	/* Définition des attributs*/
	protected $maxLength; // Longueur maximale
	
	/**
	* Contructeur de la classe MaxLenghtValidator.
	*/
	public function __construct($erreurClient, $maxLength)
	{
		parent::__construct($erreurClient);

		$this->setMaxLength($maxLength);

	}

	public function setMaxLength($maxLength){
		if(ctype_digit($maxLength) || is_int($maxLength)){
			if($maxLength > 0){
				$this->maxLength = $maxLength;
			}else{
				array_push($this->erreurs, 'MaxLenghtValidator :: setMaxLength -> '.FORMAT_NEGATIVE);
			}
		}else{
			array_push($this->erreurs, 'MaxLenghtValidator :: setMaxLength -> '.FORMAT_INT);
		}
	}
	
	/**
	* Méthodes de la classe
	*/

	// Pour valider une donnée non nulle
	public function isValid($value){
		return strlen($value) <= $this->maxLength;
	}

}