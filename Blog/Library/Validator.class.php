<?php
namespace Library;
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP de la classe abstraite Validator pour valider les données |
// | entrées dans les formulaires.        								  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe Abstraite Validator
 * @access: public
 * @version: 1
 */

abstract class Validator
{
	/* Définition des constantes de la classe*/

	CONST FORMAT_STRING = 'Le paramètre passé à la fonction n\'est pas au format STRING.';
	CONST FORMAT_INT = 'Le paramètre passé à la fonction n\'est pas au format INT.';
	CONST FORMAT_NEGATIVE = 'Le paramètre passé à la fonction est nul ou négatif.';

	/* Définition des attributs*/
	protected $erreurs = array(), // Tableau des erreurs générées par la classe
			  $erreurClient; // Erreur à renvoyer au client.
	
	/**
	* Contructeur de la classe Validator.
	*/
	public function __construct($erreurClient)
	{
		$this->setErreur($erreurClient);
	}

	/**
	* SETTER
	*/

	public function setErreurClient($erreurClient){
		if(is_string($erreurClient)){
			$this->erreurClient = $erreurClient;
		}else{
			array_push($this->erreurs, 'Validator :: setErreur -> '.FORMAT_STRING);
		}
	}

	
	/**
	* GETTER
	*/

	public function getErreurClient(){
		return $this->erreurClient;
	}


	/**
	* Méthodes de la classe
	*/

	// Pour valider une donnée
	abstract public function isValid();

}