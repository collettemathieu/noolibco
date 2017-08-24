<?php
namespace Library;
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP de la classe abstraite des champs (Fields) pour la gestion|
// | POO des formulaires de la plateforme NooLib.  	          			  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe Abstraite Field
 * @access: public
 * @version: 1
 */

abstract class Field
{
	/* Définition des constantes de la classe*/

	CONST FORMAT_STRING = 'Le paramètre passé à la fonction n\'est pas au format STRING.';
	CONST FORMAT_INT = 'Le paramètre passé à la fonction n\'est pas au format INT.';
	CONST FORMAT_NEGATIVE = 'Le paramètre passé à la fonction est nul ou négatif.';
	CONST FORMAT_ARRAY = 'Le paramètre passé à la fonction n\'est pas au format ARRAY.';
	CONST FORMAT_VALIDATOR = 'Le paramètre passé à la fonction n\'est pas au format \Library\VALIDATOR.';
	CONST FORMAT_EXIST_VALIDATOR = 'Le validateur passé à la fonction existe déjà.';
	CONST FORMAT_EMPTY = 'Le paramètre passé à la fonction est VIDE.';

	/* Définition des attributs*/
	protected $label, // Label du champ
			  $name, // Nom du champ
			  $value, // Valeur du champ
			  $erreursClient = array(), // Message d'erreurs à retourner au client
			  $validators = array(), // Les validateurs validant les données envoyées par le client
			  $erreurs = array(); // Tableau des erreurs générées par la classe
	
	/**
	* Contructeur de la classe Field.
	*/
	public function __construct(array $options)
	{
		if(is_array($options) && !empty($options)){
			$this->hydrate($options);
		}
	}

	public function hydrate($options){

		foreach($options as $type => $value){
			$method = 'set'.ucfirst($type);
			if(is_callable(array($this, $method))){
				$this->$method($value);
			}
		}

	}

	/**
	* SETTER
	*/

	public function setLabel($label){
		if(is_string($label)){
			$this->label = $label;
		}else{
			array_push($this->erreurs, 'Field :: setLabel -> '.FORMAT_STRING);
		}
	}

	public function setName($name){
		if(is_string($name)){
			$this->name = $name;
		}else{
			array_push($this->erreurs, 'Field :: setName -> '.FORMAT_STRING);
		}
	}

	public function setValue($value){
		if(is_string($value)){
			$this->value = $value;
		}else{
			array_push($this->erreurs, 'Field :: setValue -> '.FORMAT_STRING);
		}
	}

	public function setValidators($validators){
		if(is_array($validators)){
			foreach($validators as $validator){
				if($validator instanceof Validator{
					if(!in_array($validator, $this->validators)){
						array_push($this->validators, $validator);
					}else{
						array_push($this->erreurs, 'Field :: setValidators -> '.FORMAT_EXIST_VALIDATOR);
					}
				}else{
					array_push($this->erreurs, 'Field :: setValidators -> '.FORMAT_VALIDATOR);
				}
			}
		}else{
			array_push($this->erreurs, 'Field :: setValidators -> '.FORMAT_ARRAY);
		}
	}
	
	/**
	* GETTER
	*/

	public function getLabel(){
		return $this->label;
	}

	public function getName(){
		return $this->name;
	}

	public function getValue(){
		return $this->value;
	}

	public function getValidators(){
		return $this->validators;
	}


	/**
	* Méthodes de la classe
	*/

	// Pour générer la vue du champ - méthode abstraite qui devra être reprise des classes filles
	abstract public function buildWidget();

	// Pour valider le champ à partir des validateurs
	public function isValid(){
		$valid = true;
		foreach($this->validators as $validator){
			if(!$validator->isValid()){
				array_push($this->erreursClient, $validator->getErreurClient());
				$valid = false;
			}
		}
		return $valid;
	}

}
