<?php
namespace Library\Fields;
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP du champ de formulaire textArea.  		                  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe TextAreaField
 * @access: public
 * @version: 1
 */

class TextAreaField extends \Library\Field
{

	/* Définition des attributs*/
	protected $cols, // Attribut sur le nombre de colonnes du champ textarea
			  $rows; // Attribut sur le nombre de lignes du champ textarea
	

	/**
	* SETTER
	*/

	public function setCols($cols){
		if(ctype_digit($cols) || is_int($cols)){
			if($cols > 0){
				$this->cols = $cols;
			}else{
				array_push($this->erreurs, 'TextAreaField :: setCols -> '.FORMAT_NEGATIVE);
			}
		}else{
			array_push($this->erreurs, 'TextAreaField :: setCols -> '.FORMAT_INT);
		}
	}

	public function setRows($rows){
		if(ctype_digit($rows) || is_int($rows)){
			if($rows > 0){
				$this->rows = $rows;
			}else{
				array_push($this->erreurs, 'TextAreaField :: setRows -> '.FORMAT_NEGATIVE);
			}
		}else{
			array_push($this->erreurs, 'TextAreaField :: setRows -> '.FORMAT_INT);
		}
	}

	
	/**
	* GETTER
	*/

	public function getCols(){
		return $this->cols;
	}

	public function getRows(){
		return $this->rows;
	}


	/**
	* Méthodes de la classe
	*/

	// Pour générer la vue du champ
	public function buildWidget(){
		
		$widget = '';

		if(sizeof($this->erreurs) == 0){

			$widget.= '<label>'.$this->label.'</label><textarea name="'.$this->name.'"';

			if(!empty($this->cols)){
				$widget.= ' cols="'.$this->cols.'"';
			}
			if(!empty($this->rows)){
				$widget.= ' rows="'.$this->rows.'"';
			}

			$widget.= ' />';

			if(!empty($this->value)){
				$widget.= htmlspecialchars($this->value);
			}

			$widget.= '</textarea>';

			return $widget;

		}else{
			foreach($this->erreurs as $erreur){
				$widget .= $erreur.'<br/>';
			}

			return $widget;
		}
	}

}
