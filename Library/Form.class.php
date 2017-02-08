<?php
namespace Library;
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP de la classe formulaire.                                  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe Form
 * @access: public
 * @version: 1
 */

class Form
{
	/* Définition des constantes de la classe*/

	CONST FORMAT_FIELD = 'Le paramètre passé à la fonction n\'est pas au format \Library\Field.';
	CONST FORMAT_ARRAY = 'Le paramètre passé à la fonction n\'est pas au format Array().';
	CONST FORMAT_ENTITY = 'Le paramètre passé à la fonction n\'est pas au format \Library\Entity.';

	/* Définition des attributs*/
	protected $entity, // Entité en lien avec le formulaire
			  $fields = array(), // Champs du formulaire issus de la classe Field
			  $erreurs = array(); // Tableau des erreurs générées par la classe
	
	/**
	* Contructeur de la classe Form.
	*/
	public function __construct($entity)
	{
		if($entity instanceof Entity){
			$this->setEntity($entity);
		}else{
			array_push($this->erreurs, 'Form :: __construct -> '.FORMAT_ENTITY);
		}
	}

	/**
	* SETTER
	*/

	public function setEntity($entity){
		if($entity instanceof Entity){
			$this->entity = $entity;
		}else{
			array_push($this->erreurs, 'Form :: setEntity -> '.FORMAT_ENTITY);
		}
	}

	public function setFields($fields){
		if(is_array($fields)){
			$this->fields = $fields;
		}else{
			array_push($this->erreurs, 'Form :: setFields -> '.FORMAT_ARRAY);
		}
	}
	
	/**
	* GETTER
	*/

	public function getEntity(){
		return $this->entity;
	}

	public function getFields(){
		return $this->fields;
	}


	/**
	* Méthodes de la classe
	*/

	// Pour ajouter un champ de formulaire
	public function addField($field){
		if($field instanceof Field){
			
			$attribut = ucfirst($field->getName()); // On récupère le nom du champ.
			$field->setValue($this->entity->'get'.$attribut()); // On assigne la valeur correspondante au champ si l'utilisateur a déjà utilisé le formulaire une fois.

			array_push($this->fields, $field);

			return $this;
		}else{
			array_push($this->erreurs, 'Form :: addFiled -> '.FORMAT_FIELD);
		}
	}

	// Pour créer la vue du formulaire envoyée à la vue par le contrôleur
	public function createView(){

		$view = ''; // Initialisation de la vue

		// On génère un par un les vues de chacun des champs
		foreach($this->fields as $field){
			$view .= $field->buildWidget().'</br>';
		}

		return $view;
		
	}

	// Pour valider le formulaire
	public function isValid(){

		$valid = true; // Initialisation à vrai du formulaire

		foreach($this->fields as $field){
			if(!$field->isValid()){
				$valid = false;
			}
		}

		return $valid;
		
	}
}