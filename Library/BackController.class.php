<?php
namespace Library;

// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP BackController pour les contrôleurs des   	  |
// | applications constituant l'architecture de la plateforme NooLib.     |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
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
	const NO_DATA = 'The data asked does not exist.';
	const NO_DATA_ANYMORE = 'The data asked does not exist anymore. You can delete it by dragging it in the trash.';
	const NO_APPLICATION = 'The application asked does not exist.';
	const NO_TASK = 'The task asked does not exist.';
	const NO_FUNCTION = 'The function asked does not exist.';
	const NO_PARAMETER = 'The parameter asked does not exist.';
	const APPLICATION_NOT_ACTIVATED = 'This application is not activated. Please stay tune !';
	const APPLICATION_HAS_NO_TASK = 'This application has no task or no data as parameter.';
	const APPLICATION_HAS_NO_MULE = 'The mule of this application cannot be loaded.';
	const DENY_HANDLE_DATA = 'You are not authorized to edit this data.';
	const DENY_HANDLE_APPLICATION = 'You are not authorized to edit this application.';
	const DENY_HANDLE_AUTHORS_APPLICATION = 'You are not authorized to edit the authors of this application.';
	const DENY_HANDLE_TASK = 'You are not authorized to edit this task.';
	const DENY_HANDLE_FUNCTION = 'You are not authorized to edit this function.';
	const DENY_HANDLE_PARAMETER = 'You are not authorized to edit this parameter.';
	const DENY_USE_APPLICATION = 'You are not authorized to use this application.';
	const DENY_EXECUTE_COMMAND = 'Your are not authorized to execute this command.';
	const DENY_ACCESS_PAGE = 'Access denied: You are not authorized to access this page.';
	const ERROR_LOADING_DATA = 'An error has occurred while laoding data.';
	const ERROR_RUNNING_APPLICATION = 'An error has occurred while running application.';
	const ERROR_LOADING_APPLICATION = 'An error has occurred while loading application.';
	const ERROR_WRITTING_DATA = 'An error has occurred while writing data.';
	const ERROR_DELETING_PUBLICATION = 'Some errors have occurred while deleting publication.';
	const ERROR_REQUEST_NOT_VALID = 'This request is not valid.';
	const PASSWORD_NOT_VALID = 'Enter a password of at least eight letters in length with at least one number, uppercase and lowercase.';
	const PASSWORD_DIFFERENT_ADMIN = 'Your password must be different than your administrator password.';
	const PASSWORDS_NOT_MATCH = 'Your second password does not match with the first.';
	const FORMAT_TACHE = "The parameter passed to the function is not in /Library/Entities/Tache format.";

	/* AddData controller */
	const ADD_DATA_MEMORY_EXCEEDED = 'The size of data exceeds the limit authorized. Please try again with a smaller interval.';
	const ADD_DATA_LOADED_DATA = 'The data is now loaded in your data manager.';
	const ADD_DATA_NO_DATA_SENT = 'No data has been sent. Please try again !';
	const ADD_DATA_DATA_NOT_TAKEN_INTO_ACCOUNT = 'Sorry, this format of data is not yet taken into account by NooLib.';
	const ADD_DATA_ERROR_ENCODING = 'An error while encoding has occurred.';
	const ADD_DATA_DATA_ALREADY_LOADED = 'A data request is already loading. Please wait a while.';

	/* DataManager Controller */
	const DATA_MANAGER_UPDATE_DATA = 'The data is now updated.';
	const DATA_MANAGER_NOT_DISPLAY_AS_GRAPH = 'You cannot display this data with a graph.';
	const DATA_MANAGER_INTERVAL_NOT_VALID = 'The intervals selected are not valid.';
	const DATA_MANAGER_DATA_NOT_BE_SAVED = 'Sorry, you cannot save this type of data.';
	const DATA_MANAGER_DATA_UPDATE_DATE = 'Your data is now saved on NooLib for 30 days.';

	/* Engine controller */
	const ENGINE_NO_ACTION_FOR_TASK = 'No action is associated to this task.';
	const ENGINE_NO_DATA = 'No data has been found in the data box of the application.';
	const ENGINE_NO_DATA_MULE = 'Please, load all data on the mule before running a task.';
	const ENGINE_NO_MATCH_DATA = 'Data entered do not match with the task called. Please verify data loaded on the mule.';

	/* Tree controller */
	const TREE_CHANGE_NAME = 'The name of this application already exists. Please find another name.';
	const TREE_NAME_CHANGED_SUCCESSFULLY = 'The name of the application has been edited successfully.';
	const TREE_APPLICATION_EDITED_SUCCESSFULLY = 'The application has been edited successfully.';
	const TREE_PUBLICATION_EDITED_SUCCESSFULLY = 'The publication has been edited successfully.';
	const TREE_TASK_ADDED = 'The task has been added to the application.';
	const TREE_FUNCTION_ADDED = 'The function has been added to the task.';
	const TREE_PARAMETER_ADDED = 'The parameter has been added to the function.';
	const TREE_DEFAULT_PARAMETER = 'The default value must be suprerior to the minimal value and inferior to the maximal value of the parameter.';
	const TREE_STEP_PARAMETER = 'The step must be inferior to the difference between the minimal and maximal value of the parameter.';
	const TREE_MINIMAL_VALUE_PARAMETER = 'The minimal value must be inferior to the maximal value.';
	const TREE_TASK_DELETED = 'The task has been removed successfully.';
	const TREE_FUNCTION_DELETED = 'The function has been removed successfully.';
	const TREE_PARAMETER_DELETED = 'The parameter has been removed successfully.';
	const TREE_PICTURE_APPLICATION_EDITED = 'The picture of the application has been edited successfully.';
	const TREE_TASK_EDITED = 'The task has been edited successfully.';
	const TREE_FUNCTION_EDITED = 'The function has been edited successfully.';
	const TREE_PARAMETER_EDITED = 'The parameter has been edited successfully.';
	const TREE_NO_TYPE_PARAMETER = 'Please, enter a description for the type of data used.';
	const TREE_TYPE_DISPLAY_PARAMETER_NOT_FOUND = 'The type of display of the parameter has not been found.';
	const TREE_AUTHOR_REMOVED = 'The author has been removed successfully.';
	const TREE_AUTHOR_ADDED = 'The author has been added successfully.';
	const TREE_AUTHOR_NOT_EXIST = 'The author entered does not exist or has already been removed.';
	const TREE_AUTHOR_NOT_AUTHORIZED = 'You cannot add yourself as a new author of this application.';
	const DENY_DELETE_APPLICATION = 'You are not authorized to delete this application.';
	const TREE_ADD_PUBLICATION_ARG_EMPTY = 'Please, enter a valid DOI for your article.';
	const TREE_DOI_NOT_FOUND = 'Sorry, the DOI entered does not match with any article.';
	const TREE_VERSION_NOT_FOUND = 'Sorry, the version of this application has not been found.';
	const TREE_VERSION_NOT_ACTIVATED = 'Sorry, any version of this application is activated. Please, stay tune !';
	const TREE_VERSION_ALREADY_EXIST = 'Sorry, you cannot use twice the same version of the application.';
	const TREE_VERSION_WRONG = 'Enter a valid name of version (eg. 1.0.0).';

	/* Library */
	const LIBRARY_NO_AUTHOR = 'This author does not exist anymore.';

	/* Mail */
	const MAIL_MESSAGE_SENT = 'Your message has been sent successfully.';
	const MAIL_SUBSCRIPTION = 'Welcome to NooLib !';
	const MAIL_RESET_PASSWORD = 'Reset your password.';

	/* Backend */
	const BACKEND_WRONG_PASSWORD = 'The password you entered is not valid.';
	const BACKEND_WELCOME_ADMIN = 'You are now logged as administrator.';
	const BACKEND_APPLICATION_CANNOT_BE_ACTIVATED_WITHOUT_VALID_VERSION = 'The application cannot be activated without validating at least one version of this application.';
	const BACKEND_VERSION_NOT_FOUND = 'The version of application asked is not found.';

	/* SubmitApplication */
	const SUBMITAPPLICATION_ERROR_REGISTRATION = 'A system error has occurred during the registration.';
	const SUBMITAPPLICATION_CHANGE_NAME_APPLICATION = 'Sorry, the name of this application already exists. Please, change the name of your application.';
	const SUMBIT_AUTHOR_NOT_AUTHORIZED = 'You cannot add yourself as a new author. You are already considered as an author.';
	const SUBMITAPPLICATION_ERROR_STEP_APPLICATION = 'Error in the routing of the application.';

	/* LogIn controller */
	const LOGIN_ACCOUNT_NOT_ACTIVATED = 'You have not yet activated your account. Check your email.';
	const LOGIN_WRONG_PASSWORD = 'The password entered does not match with your account.';
	const LOGIN_WRONG_EMAIL = 'This e-mail address is not registered on NooLib.';
	const LOGIN_INVALID_LINK_ACTIVATE_ACCOUNT = 'This link has expired. Please try to register again.';
	const LOGIN_INVALID_LINK_RESET_PASSWORD = 'This link has expired. Please try to reset your password again.';
	const LOGIN_ACCOUNT_ALDREADY_ACTIVATED = 'Your account is already activated. You can log to NooLib !';
	const LOGIN_ACCOUNT_ACTIVATED = 'Your account is now activated. You can log !';
	const LOGIN_ALREADY_LOGGED = 'Your are already logged.';
	const LOGIN_ENTER_VALID_EMAIL = 'Enter a valid e-mail address.';
	const LOGIN_RESET_PASSWORD = 'An e-mail has been sent to you with a link to reset your password.';
	const LOGIN_PASSWORD_EDITED = 'Your new password has been edited successfully.';
	const LOGIN_WELCOME = 'Welcome';
	const LOGIN_MAIL_NOT_VALID = 'The email entered is not valid.';

	/* Inscription controller */
	const INSCRIPTION_ALREADY_REGISTRED = 'You appear to be a registered user. Please log in !';
	const INSCRIPTION_REGISTRATION_SUCCESSFUL = 'Your registration has been successful submitted. Please confirm your e-mail of registration in order to activate your account.';
	const INSCRIPTION_USER_ALREADY_LOGGED = 'Your appear to be already logged.';

	/* Helper */
	const HELPER_NO_HELP = 'Sorry, this page does not contain any help for the moment.';

	/* Settings */
	const SETTINGS_NOT_AUTHORIZE_TO_REMOVE = 'Your are not authorized to remove this account user.';
	const SETTINGS_PASSWORD_INCORRECT = 'The password entered is incorrect.';
	const SETTINGS_ACCOUNT_REMOVED = 'Your account has been well removed. See you soon !';
	const SETTINGS_WALLPAPER_CHANGED = 'The wallpaper has been well edited.';
	const SETTINGS_DEFAULT_WALLPAPER = 'The default wallpaper has been restored.';
	const SETTINGS_DEFAULT_WALLPAPER_ALREADY_RESTORED = 'The default wallpaper is already restored.';

	/* Profile */
	const PROFILE_CHANGE_ADMIN_PASSWORD = 'Your administrator password has been edited successfully.';
	const PROFILE_ADMIN_PASSWORD_DIFFERENT_FROM_USER = 'Your new administrator password must be different than your user password.';
	const PROFILE_OLD_ADMIN_PASSWORD_INCORRECT = 'The old administrator password you entered is incorrect.';
	const PROFILE_OLD_PASSWORD_INCORRECT = 'The old password you entered is incorrect.';
	const PROFILE_PASSWORD_WELL_EDITED = 'Your password has been edited successfully.';
	const PROFILE_STATUS_CHANGED = 'Your status has been edited successfully.';
	const PROFILE_STATUS_NOT_EXIST = 'The status selected does not exist.';
	const PROFILE_DESCRIPTION_CHANGED = 'Your description has been edited successfully.';
	const PROFILE_PAGE_CHANGED = 'The link to your personal page has been edited successfully.';
	const PROFILE_EMAIL_CHANGED = 'Your e-mail address has been edited successfully.';
	const PROFILE_SURNAME_CHANGED = 'Your surname has been edited successfully.';
	const PROFILE_NAME_CHANGED = 'Your name has been edited successfully.';
	const PROFILE_CONTACT_NOT_EXIST = 'This user does not exist anymore.';
	const PROFILE_MESSAGE_SENT = 'Your message has been sent successfully.';

	/* Trait */
	const TRAIT_LINK_NOT_EXIST = 'This link does not exist.';

	/* Trait Application */
	const TRAIT_APPLICATION_FIELD_AUTHOR_EMPTY = 'Please, fill all fields to add a new author.';
	const TRAIT_APPLICATION_FIELD_AUTHOR_IS_CREATOR = 'The owner of this application cannot be also an author.';

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



