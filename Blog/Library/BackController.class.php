<?php
namespace Library;

// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// |  Classe abstraite PHP BackController pour les contrôleurs des   	  |
// | applications constituant l'architecture de la plateforme.			  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+


/**
 * @name:  Classe abstraite BackController
 * @access: public
 * @version: 1
 */

abstract class BackController extends ApplicationComponent
{

	/* Définitions des retours d'erreurs */
	/* Générals */
	const DATA_REQUIRED = 'User data is required as parameter for this function.';
	const ALL_FIELDS_REQUIRED = 'You need to fill in all the fields required.';
	const ERROR_FILE_OPEN = 'An technical error has occurred while opening file.';
	const NO_FOLDER_OR_FILE = 'No folder or file found.';
	const FILE_SIZE_EXCEEDED = 'The file size exceeds the limit authorized.';
	const FILE_EXTENSION = 'This file extension is not authorized.';
	const NO_SYSTEM_VARIABLE = 'The system variables do not exist.';
	const PASSWORD_NOT_VALID = 'Entrer un mot de passe possédant au moins 8 caractères comprenant un chiffre, une majuscule et une miniscule.';
	const PASSWORD_DIFFERENT_ADMIN = 'Your password must be different than your administrator password.';
	const PASSWORDS_NOT_MATCH = 'Your second password does not match with the first.';
	
	const TREE_DOI_NOT_FOUND = 'Désolé, la DOI mentionnée ne correspond à aucune référence.';

	/* Mail */
	const MAIL_MESSAGE_SENT = 'Votre message a été envoyé avec succès.';
	const MAIL_SUBSCRIPTION = 'Bienvenue sur NooLib The Blog !';
	const MAIL_RESET_PASSWORD = 'Reset your password.';

	/* Backend */
	const BACKEND_WRONG_PASSWORD = 'Veuillez entrer une adresse électronique et un mot de passe valides.';
	const BACKEND_WELCOME_SUPER_ADMIN = 'Vous êtes à présent connecté en tant que super administrateur.';
	const BACKEND_WELCOME_ADMIN = 'Vous êtes à présent connecté en tant qu\'administrateur.';

	
	/* Définition des attributs */
	protected $action = '',
			  $module = '',
			  $page = null,
			  $view = '',
			  $managers = null;

	/**
	* Constructeur de la classe.
	*/
	public function __construct(Application $app, $module, $action)
	{
		parent::__construct($app);

		$this->page = new Page($app);
		$this->managers = new Managers('PDO', PDOFactory::getMySQLConnexion());

		$this->setModule($module);
		$this->setAction($action);
		$this->setView($action);

		// Permet de gérer les erreurs fatales avec javascript via l'appel en ajax
		ini_set('display_errors', false);
	}


	/**
	* Permet d'exécuter une action
	*/
	public function execute()
	{
		$method = 'execute'.ucfirst($this->getAction());

		if(is_callable(array($this, $method)))
		{
			$this->$method($this->getApp()->getHTTPRequest());
		}
		else
		{
			// On récupère l'utilisateur système
			$user = $this->app->getUser();
			$user->getMessageClient()->addErreur('Erreur système : L\'action '.$this->getAction().' n\'est pas définie dans le module du contrôleur.');
		}
	}


	/**
	* Permet d'assigner à la page une vue.
	*/
	public function setView($view)
	{
		if(is_string($view) && !empty($view))
		{
			$this->view = $view;
			// On assigne à la page la vue du contrôleur
			if($this->getApp()->isStandAlone()){
				$this->page->setContentFile(dirname(__FILE__).'/../Applications/ApplicationsStandAlone/'.$this->getApp()->getNomApplication().'/Modules/'.$this->getModule().'/Views/'.$this->getView().'.php');
			}else{
				$this->page->setContentFile(dirname(__FILE__).'/../Applications/'.$this->getApp()->getNomApplication().'/Modules/'.$this->getModule().'/Views/'.$this->getView().'.php');
			}
		}
		else
		{
			// On récupère l'utilisateur système
			$user = $this->app->getUser();
			$user->getMessageClient()->addErreur('Erreur système : La vue doit être de type String.');
		}
	}

	/* SETTERS */

	public function setModule($module)
	{
		if(is_string($module) && !empty($module))
		{
			$this->module = $module;
		}
		else
		{
			throw new \InvalidArgumentException('Module must be a string.');
		}
	}


	public function setAction($action)
	{
		if(is_string($action) && !empty($action))
		{
			$this->action = $action;
		}
		else
		{
			throw new \InvalidArgumentException('Action must be a string.');
		}
	}

	/* GETTERS */

	public function getPage()
	{
		return $this->page;
	}

	public function getModule()
	{
		return $this->module;
	}

	public function getAction()
	{
		return $this->action;
	}

	public function getView()
	{
		return $this->view;
	}

	public function getManagers()
	{
		return $this->managers;
	}

}



