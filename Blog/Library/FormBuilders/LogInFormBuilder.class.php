<?php
namespace Library\FormBuilders;
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP de la classe du constructeur de formulaire de type LogIn. |                       								  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe LogInFormBuilder
 * @access: public
 * @version: 1
 */

class LogInFormBuilder extends \Library\FormBuilder
{
	/* Définition des constantes de la classe*/

	CONST FORMAT_ENTITY = 'Le paramètre passé à la fonction n\'est pas au format \Library\Entity.';
	CONST FORMAT_FORM = 'Le paramètre passé à la fonction n\'est pas au format \Library\Form.';


	/**
	* Méthodes de la classe
	*/

	// Pour construire le formulaire
	public function build(){
		$this->form->addField(new \Library\Fields\StringField(array(
			'label' => '',
			'type' => 'email',
			'name' => 'adresseMailLogIn',
			'maxLength' => 50,
			'validators' => array(
				new \Library\Validators\NotNullValidator('Merci de sépicier une adresse électronique')
				)
			)))
			->addField(new \Library\Fields\StringField(array(
			'label' => '',
			'type' => 'password',
			'name' => 'motDePasseFormulaireLogIn',
			'maxLength' => 50,
			'validators' => array(
				new \Library\Validators\NotNullValidator('Merci de sépicier une adresse électronique')
				)
			)));
	}

}