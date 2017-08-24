<?php
namespace Library;
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP de la classe abstraite du constructeur de formulaire. 	  |                       								  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe Abstraite FormBuilder
 * @access: public
 * @version: 1
 */

abstract class FormBuilder
{
	/* Définition des constantes de la classe*/

	CONST FORMAT_ENTITY = 'Le paramètre passé à la fonction n\'est pas au format \Library\Entity.';
	CONST FORMAT_FORM = 'Le paramètre passé à la fonction n\'est pas au format \Library\Form.';

	/* Définition des attributs*/
	protected $form, // Formulaire à construire
			  $erreurs = array(); // Erreurs générées par la classe
	
	/**
	* Contructeur de la classe FormBuilder.
	*/
	public function __construct($entity)
	{
		if($entity instanceof Entity){
			$this->setForm(new Form($entity));
		}else{
			array_push($this->erreurs, 'FormBuilder :: __construct -> '.FORMAT_ENTITY);
		}
	}

	/**
	* SETTER
	*/

	public function setForm($form){
		if($form instanceof Form){
			$this->form = $form;
		}else{
			array_push($this->erreurs, 'FormBuilder :: setForm -> '.FORMAT_FORM);
		}
	}

	
	/**
	* GETTER
	*/

	public function getForm(){
		return $this->form;
	}


	/**
	* Méthodes de la classe
	*/

	// Pour construire le formulaire
	abstract public function build();

}