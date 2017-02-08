<?php
namespace Library\Validators;
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP de la classe validateur validant un paramètre non nul.	  |                       								  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe NotNullValidator
 * @access: public
 * @version: 1
 */

class NotNullValidator extends \Library\Validators
{
	
	/**
	* Méthodes de la classe
	*/

	// Pour valider une donnée non nulle
	public function isValid($value){
		if(is_string($value) && !empty($value)){
			return true;
		}else{
			return false;
		}
	}

}