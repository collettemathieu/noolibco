<?php
namespace Library\Fields;
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP du champ de formulaire string.  		                  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe StringField
 * @access: public
 * @version: 1
 */

class StringField extends \Library\Field
{
	
	/* Définition des attributs*/
	protected $maxLength, // Attribut sur la taille du champ string du formulaire
			  $type; // Attibut sur le type de formulaire : text, password, email, etc.
	

	/**
	* SETTER
	*/

	public function setType($type){
		if(is_string($type)){
			if(!empty($type){
				$this->type = $type;
			}else{
				array_push($this->erreurs, 'StringField :: setType -> '.FORMAT_EMPTY);
			}
		}else{
			array_push($this->erreurs, 'StringField :: setType -> '.FORMAT_STRING);
		}
	}

	public function setMaxLength($maxLength){
		if(ctype_digit($maxLength) || is_int($maxLength)){
			if($maxLength > 0){
				$this->maxLength = $maxLength;
			}else{
				array_push($this->erreurs, 'StringField :: setMaxLength -> '.FORMAT_NEGATIVE);
			}
		}else{
			array_push($this->erreurs, 'StringField :: setMaxLength -> '.FORMAT_INT);
		}
	}

	
	/**
	* GETTER
	*/

	public function getMaxLength(){
		return $this->maxLength;
	}

	public function getType(){
		return $this->type;
	}


	/**
	* Méthodes de la classe
	*/

	// Pour générer la vue du champ
	public function buildWidget(){
		$widget = '';
		if(sizeof($this->erreurs) == 0){

			$widget.= '<label>'.$this->label.'</label><input type="'.$this->type.'" name="'.$this->name.'"';

			if(!empty($this->value)){
				$widget.= ' value="'.htmlspecialchars($this->value).'"';
			}
			if(!empty($this->maxLength)){
				$widget.= ' maxLength="'.$this->maxLength.'"';
			}

			$widget.= ' />';

		}else{
			foreach($this->erreurs as $erreur){
				$widget .= $erreur.'<br/>';
			}

			return $widget;
		}
	}

}
