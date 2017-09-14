<?php
// +----------------------------------------------------------------------+
// | PHP Version 7 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP comme controleur d'identification des utilisateurs. 	  |
// | Ce controleur permet d'identifier l'utilisateur sur le site. 		  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// | Auteur : Guénaël DEQUEKER <dequekerguenael@noolib.com>    			  |
// | Auteurs : Steve Despres  <stevedespres@noolib.com>				      |
// +----------------------------------------------------------------------+

/**
 * @name: controleur de la rubrique identification des utilisateurs
 * @access: public
 * @version: 1
 */	

namespace Applications\LogIn\Modules\LogIn;

class LogInController extends \Library\BackController{

	use \Library\Traits\MethodeUtilisateurControleur;
	use \Library\Traits\MethodeApplicationControleur;
	use \Library\Traits\FonctionsUniverselles;

	/**
	*	Affichage de la page d'identification de l'utilisateur
	*/
	public function executeShow($request){
		
		// On vérifie que l'utilisateur n'est pas déjà connecté
		$user = $this->app->getUser();

		if($user->isAuthenticated()){

			// On procède à la redirection vers la page principale
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}

	/**
	* Pour initialiser les données de l'utilisateur
	*/
	private function initDonneeUtilisateur($utilisateur){

		try{

			// On supprime les données temporaires et les données > xx jours de l'utilisateur
			// On appelle le manager des données utilisateur
			$managerDonneeUtilisateur = $this->getManagers()->getManagerOf('DonneeUtilisateur');
			$presentTime = time();

			// On charge l'objet File
			$file = $this->getApp()->getFileDelete();

			// On charge le fichier de configuration
			$config = $this->getApp()->getConfig();
			$delaySaveDataUser = $config->getVar('divers','divers','delaySaveDataUser');

			foreach($utilisateur->getDonneesUtilisateur() as $donneeUtilisateur){
				preg_match('#(.+)-(.+)-(.+)#', $donneeUtilisateur->getDatePublicationDonneeUtilisateur(), $date);
				$timeStampData = mktime(0,0,0,$date[2],$date[3],$date[1]);
				
				if($donneeUtilisateur->getIsInCache() || ($timeStampData + $delaySaveDataUser < $presentTime)){ // On supprime les données de plus de 30 jours
					// On supprime la donnée de la BDD
					$managerDonneeUtilisateur->deleteDonneeUtilisateur($donneeUtilisateur);
					// On supprime la donnée de l'objet utilisateur
					$utilisateur->removeDonneeUtilisateur($donneeUtilisateur);
					// On supprime le fichier de la donnée
					if(!$file->deleteFile(array($donneeUtilisateur->getUrlDonneeUtilisateur(), $donneeUtilisateur->getUrlMiniatureDonneeUtilisateur()))){
						//Une erreur est envoyée à l'utilisateur
						$user->getMessageClient()->addErreur($file->getErreurs());
					}
				}
			}
			
		}

		catch(Exception $e){
			// On ajoute un message d'erreurs.
			$user->getMessageClient()->addErreur('Error system: '.$e->getMessage());
			$response = $this->app->getHTTPResponse();
			$response->redirect('/LogIn/');
		}
	}



	/**
	*	Pour traiter une mise à jour nécessaire du navigateur client
	*/
	public function executeBrowserIsValid($request){

		// On vérifie que l'utilisateur n'est pas déjà connecté
		$user = $this->app->getUser();	

		// On informe que c'est un chargement Ajax
		$user->setAjax(true);
		
		if($user->browserIsValid()){
			$isValid = 1;
		}else{

			// On récupère les variables
			$browserName = $request->getPostData('name');
			$browserVersion = (int) $request->getPostData('version');	

			// On charge le fichier de configuration pour récupérer les versions des navigateurs compatibles NooLib
			$config = $this->getApp()->getConfig();
			$currentVersionBrowsers = $config->getVar('browsers', 'version');

			// Initialisation
			$user->setBrowserIsValid(true);
			$isValid = 1;

			// On vérifie la compatibilité du navigateur du client
			if($browserName == 'ie'){ // Si le navigateur est IE
				$user->setBrowserIsValid(false);
				$isValid = 0;
			}else{
				switch ($browserName){
					case 'chrome':
						if($browserVersion < $currentVersionBrowsers['chrome']){
							$user->setBrowserIsValid(false);
							$isValid = 0;
						}
						break;
					case 'safari':
				       	if($browserVersion < $currentVersionBrowsers['safari']){
							$user->setBrowserIsValid(false);
							$isValid = 0;
						}
				        break;
				    case 'opera':
				    	if($browserVersion < $currentVersionBrowsers['opera']){
							$user->setBrowserIsValid(false);
							$isValid = 0;
						}
				        break;
			        case 'firefox':
				    	if($browserVersion < $currentVersionBrowsers['firefox']){
							$user->setBrowserIsValid(false);
							$isValid = 0;
						}
				        break;
				    case 'edge':
				    	if($browserVersion < $currentVersionBrowsers['edge']){
							$user->setBrowserIsValid(false);
							$isValid = 0;
						}
				        break;
			        default:
						$user->setBrowserIsValid(false);
						$isValid = 0;
				        break;
				}
			}

		}

		// On envoie la liste à la page
		$this->page->addVar('isValid', $isValid);
	}

	/**
	* Permet de gérer l'affichage lorsque JS n'est pas activé
	*/
	public function executeActivatedJS()
	{
		// On vérifie que l'utilisateur n'est pas déjà connecté
		$user = $this->app->getUser();
		
		// On désactive JS pour l'utilisateur
		$user->setJsIsActivated(false);
		
	}

	/**
	* Permet de rafraîchir l'affichage lorsque JS a été désactivé
	*/
	public function executeRefreshJS()
	{
		// On vérifie que l'utilisateur n'est pas déjà connecté
		$user = $this->app->getUser();
		
		// On désactive JS pour l'utilisateur
		$user->setJsIsActivated(true);
		
		// On procède à la redirection vers la page principale
		$response = $this->app->getHTTPResponse();
		$response->redirect('/');
	}


	/**
	*	Pour traiter la connexion manuelle de l'utilisateur
	*/
	public function executeValidation($request){

		// On récupère l'objet utilisateur système
		$user = $this->app->getUser();

		// On procède sinon à l'authentification
		if($user->isAuthenticated()){
			// On procède à la redirection vers la page principale
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{

			// On créé l'objet User avec les données entrées par l'utilisateur
			$logUser = new \Library\Entities\Utilisateur(array(
				'mailUtilisateur' => $request->getPostData('adresseMailLogIn')
			
			));
				
			if(sizeof($logUser->getErreurs()) == 0){
				// On appelle le manager des Users
				$managerUser = $this->getManagers()->getManagerOf('Utilisateur');
				
				// On récupère le user de la base selon l'email
				$userBase = $managerUser->getUtilisateurByMail($logUser->getMailUtilisateur());
				
				// Vérifier que l'utilisateur existe dans la base
				if($userBase)
				{
					// On vérifie le mot de passe de l'utilisateur
					if(password_verify($request->getPostData('motDePasseFormulaireLogIn'), $userBase->getPasswordUtilisateur() ))
					{
						if(!$userBase->getUtilisateurActive()){
		
						$user->getMessageClient()->addErreur(self::LOGIN_ACCOUNT_NOT_ACTIVATED);
						$response = $this->app->getHTTPResponse();
						$response->redirect('/LogIn/');
						
						}else{
						
							// On récupère la réponse
							$response = $this->app->getHTTPResponse();

							// On vérifie si la case "Rester connecté" a été coché
							if($request->isExistPOST('resterConnecte')){
								
								// On crée un cookie de connexion automatique
								$response->setCookie('mail', $logUser->getMailUtilisateur(), time()+365*24*3600);
								//avec le mot de passe sous sa forme hashé
								$response->setCookie('cle', $userBase->getPasswordUtilisateur(), time()+365*24*3600);

							}
							
							// Si l'utilisateur a été banni
							if($userBase->getEtatBanniUtilisateur()){
								$response = $this->app->getHTTPResponse();
								$response->redirect('/LogIn/Banni');
							}
							else{	

								// On authentifie l'utilisateur sur la plateforme
								$user->setAuthenticated(true);
								
								// On suppose que JS est activé par défaut
								$user->setJsIsActivated(true);

								// On met en session que la personne n'est pas un administrateur
								$user->setAttribute('isAdmin', false);

								// On procède à l'initialisation des données utilisateur
								$this->initDonneeUtilisateur($userBase);

								// On sauvegarde en session
								// On vérifie que l'utilisateur n'est pas déjà connecté
								$this->app->getUser()->setAttribute('userSession', serialize($userBase));

								// On met à jour la date de dernière connexion de l'utilisateur si date différente du jour
								if(date('d/m/Y') != preg_replace('#(.+)-(.+)-(.+)#', '$3/$2/$1', $userBase->getDateDerniereConnexionUtilisateur())){
									$managerUser->updateDateConnexionUtilisateur($userBase);
								}

								// On ajoute un message de bienvenue.
								$user->getMessageClient()->addReussite(self::LOGIN_WELCOME.' '.$userBase->getPrenomUtilisateur().' '.$userBase->getNomUtilisateur().'.');

								// On récupère au préalable la page demandée stockée en flash.
								$pageDemandee = $user->getFlash();

								
								if(!empty($pageDemandee)){
									// On procède à la redirection vers la page demandée
									$response->redirect($pageDemandee);
								}else{
									// On procède à la redirection vers la page principale
									$response->redirect('/');
								}
							}

						}	
					}
					else
					{
						if(password_verify($request->getPostData('motDePasseFormulaireLogIn'), $userBase->getPasswordAdminUtilisateur() ))
						{
							
							// On met à jour la date de dernière connexion de l'utilisateur si date différente du jour
							if(date('d/m/Y') != preg_replace('#(.+)-(.+)-(.+)#', '$3/$2/$1', $userBase->getDateDerniereConnexionUtilisateur())){
								$managerUser->updateDateConnexionUtilisateur($userBase);
							}
							
							// On récupère la réponse
							$response = $this->app->getHTTPResponse();
							
							// On met en session que la personne est un administrateur
							$user->setAttribute('isAdmin', true);

							// On authentifie l'utilisateur sur la plateforme
							$user->setAuthenticated(true);

							// On suppose que JS est activé par défaut
							$user->setJsIsActivated(true);

							// On procède à l'initialisation des données utilisateur
							$this->initDonneeUtilisateur($userBase);
							
							// On sauvegarde en session
							// On vérifie que l'utilisateur n'est pas déjà connecté
							$this->app->getUser()->setAttribute('userSession', serialize($userBase));

							// On ajoute un message de bienvenue
							$user->getMessageClient()->addReussite(self::BACKEND_WELCOME_ADMIN);

							// On procède à la redirection vers la page des admin
							$response->redirect('/PourAdminSeulement/');
						}
						else
						{
							//Erreurs mentionnée en flash
							$user->getMessageClient()->addErreur(self::LOGIN_WRONG_PASSWORD);
						
							// On place le mail de l'utilisateur en session pour retrouver son email dans le input
							$user->setAttribute('mailUser', $userBase->getMailUtilisateur());

							// On procède à la redirection vers la page d'identification
							$response = $this->app->getHTTPResponse();
							$response->redirect('/LogIn/');
						}
					}
				}else{
					
					//Erreurs mentionnée en flash
					$user->getMessageClient()->addErreur(self::LOGIN_WRONG_EMAIL);

					// On procède à la redirection vers la page d'identification
					$response = $this->app->getHTTPResponse();
					$response->redirect('/LogIn/');
				}
				
			}else{
				
				// On ajoute la variable d'erreurs à la variable flash de la session
				$user = $this->app->getUser();

				//Erreurs mentionnée en flash
				$user->getMessageClient()->addErreur($logUser->getErreurs());

				// On procède à la redirection vers la page d'identification
				$response = $this->app->getHTTPResponse();
				$response->redirect('/LogIn/');
			}

		}

	}

	/**
	*	Pour traiter la connexion automatique de l'utilisateur via les cookies
	*/
	public function executeConnexionAutomatique($request){

		// On récupère l'objet utilisateur système
		$user = $this->app->getUser();

		// On procède sinon à l'authentification du client
		if($user->isAuthenticated()){
			// On procède à la redirection vers la page précédement demandée par le client
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{

			if($request->isExistCookie('mail') && $request->isExistCookie('cle')){

				// On créé l'objet User avec les données des cookies
				$logUser = new \Library\Entities\Utilisateur(array(
				'mailUtilisateur' => $request->getCookieData('mail'),
				'passwordUtilisateur' => $request->getCookieData('cle')
				));

				if(sizeof($logUser->getErreurs()) == 0){
					// On appelle le manager des Users
					$managerUser = $this->getManagers()->getManagerOf('Utilisateur');
					
					// On récupère le user de la base selon l'email
					$userBase = $managerUser->getUtilisateurByMail($logUser->getMailUtilisateur());
					
					// Vérifier que l'utilisateur existe dans la base
					if($userBase){
						
						// On vérifie le mot de passe de l'utilisateur
						if($userBase->getPasswordUtilisateur() === $logUser->getPasswordUtilisateur()){
							
							// Si l'utilisateur a été banni
							if($userBase->getEtatBanniUtilisateur()){
								
								//On supprime les cookies
								$response->setCookie('mail', '', 0);
								$response->setCookie('cle', '', 0);

								$response = $this->app->getHTTPResponse();
								$response->redirect('/LogIn/Banni');
							}
							else{	

								// On authentifie l'utilisateur sur la plateforme
								$user->setAuthenticated(true);

								// on met en session que la personne n'est pas un administrateur
								$user->setAttribute('isAdmin', false);

								// On suppose que JS est activé par défaut
								$user->setJsIsActivated(true);

								// On procède à l'initialisation des données utilisateur
								$this->initDonneeUtilisateur($userBase);

								// On sauvegarde en session
								// On vérifie que l'utilisateur n'est pas déjà connecté
								$this->app->getUser()->setAttribute('userSession', serialize($userBase));
								
								// On met à jour la date de dernière connexion de l'utilisateur si date différente du jour
								if(date('d/m/Y') != preg_replace('#(.+)-(.+)-(.+)#', '$3/$2/$1', $userBase->getDateDerniereConnexionUtilisateur())){
									$managerUser->updateDateConnexionUtilisateur($userBase);
								}

								// On ajoute un message de bienvenue
								$user->getMessageClient()->addReussite(self::LOGIN_WELCOME.' '.$userBase->getPrenomUtilisateur().' '.$userBase->getNomUtilisateur().'.');

								// On récupère au préalable la page demandée stockée en flash.
								$pageDemandee = $user->getFlash();
								
								// On récupère la réponse
								$response = $this->app->getHTTPResponse();
								if(!empty($pageDemandee)){
									// On procède à la redirection vers la page demandée
									$response->redirect($pageDemandee);
								}else{
									// On procède à la redirection vers la page principale
									$response->redirect('/');
								}
							}

						}else{
							// On récupère la réponse
							$response = $this->app->getHTTPResponse();

							// Cookies falsifiés
							//On supprime les cookies
							$response->setCookie('mail', '', 0);
							$response->setCookie('cle', '', 0);

							// On procède à la redirection vers la page demandée
							$response->redirect('/LogIn/');
						}

						
					}else{
						// On récupère la réponse
						$response = $this->app->getHTTPResponse();

						// Cookies falsifiés
						//On supprime les cookies
						$response->setCookie('mail', '', 0);
						$response->setCookie('cle', '', 0);

						// On procède à la redirection vers la page demandée précédement par le client
						$response->redirect('/LogIn/');
					}
					
				}else{
					// On récupère la réponse
					$response = $this->app->getHTTPResponse();
					
					// Cookies falsifiés
					//On supprime les cookies
					$response->setCookie('mail', '', 0);
					$response->setCookie('cle', '', 0);

					// On procède à la redirection vers la page demandée précédement par le client
					$response->redirect('/LogIn/');
				}

			}
			
		}

	}


	/**
	*	Pour traiter la validation par mail
	*/
	
	public function executeMailValidation()
	{
		//On récupère la reponse
		$response = $this->app->getHTTPResponse();
		//On récupère la demande
		$request = $this->app->getHTTPRequest();
		//On récupère l'utilisateur
		$user = $this->app->getUser();
		// On appelle le manager des Users
		$managerUser = $this->getManagers()->getManagerOf('Utilisateur');

		//Si l'utilisateur n'est pas connecté
		if(!$user->isAuthenticated()){
		
			//Si le jeton est présent en Get
			if($request->getGetData('jeton')){
		
				//On decode le jeton et on stocke ses donnees
				$donneesUtilisateur = $this->decodeJeton($request->getGetData('jeton'));
				
				//On récupère les données utiles
				$idUtilisateur = $donneesUtilisateur['idMembre'];
				$timeJeton = $donneesUtilisateur['time'];
				
				//On récupère l'utilisateur grâce à son Mail
				$userVerif= $managerUser->getUtilisateurById($idUtilisateur);
			
				//Si la date de validité du jeton est dépassée: 6 heures
				if( $timeJeton < (time()-6*60*60)){
					//On redirige vers l'acceuil
					$user->getMessageClient()->addErreur(self::LOGIN_INVALID_LINK_ACTIVATE_ACCOUNT);
					$response->redirect('/LogIn/');
				}

				//Si l'utilisateur existe
				if($userVerif)
				{
					//Si son compte est déjà activé
					if($userVerif->getUtilisateurActive()){
						//On affiche un message
						$user->getMessageClient()->addReussite(self::LOGIN_ACCOUNT_ALDREADY_ACTIVATED);
						//On redirige
						$response->redirect('/LogIn/');
					}else{
						//On active le compte utilisateur
						$userVerif->setUtilisateurActive(true);
						$managerUser->saveUtilisateur($userVerif);
						$user->getMessageClient()->addReussite(self::LOGIN_ACCOUNT_ACTIVATED);

						// Execution du script Bash pour créer les répertoires de l'utilisateur linux
						// On execute l'objet Exec
						$exec = $this->getApp()->getExec();
						$exec->createUser($userVerif);

						/**
						* Création d'une application exemple pour *l'utilisateur 
						**/
						if($this->createDemoApplication($newUser)){
							// On ajoute la variable d'erreurs à la page
							$user->getMessageClient()->addErreur(self::ERROR_CREATING_EXAMPLE_APPLICATION);
						}

						// Envoi d'un email à hostmaster pour information qu'un nouvel utilisateur s'est inscrit
						$variablesArray = array(
							'mailUtilisateur' => $userVerif->getMailUtilisateur(),
							'titreMessage' => 'New user subscribed :-)',
							'messageMail' => 'A new user has been subscribed on NooLib. This name is '.$userVerif->getPrenomUtilisateur().' '.strtoupper($userVerif->getNomUtilisateur())
						);

						// On envoi un mail à l'auteur
						// On place la variable en Flash pour qu'elle soit récupérée par l'application Mail
						$user->setFlash($variablesArray);
						$mailApplication = new \Applications\ApplicationsStandAlone\Mail\MailApplication;
						$mailApplication->execute('SendMailToNooLib', 'sendAMessage'); // Module = SendMailToNooLib ; action = sendAMessage
					}
				}else{
					$user->getMessageClient()->addErreur(self::LOGIN_WRONG_EMAIL);
					//On redirige
					$response->redirect('/LogIn/');
				}
			}else{
				$user->getMessageClient()->addErreur(self::LOGIN_INVALID_LINK_ACTIVATE_ACCOUNT);
			}
		}else{
			$user->getMessageClient()->addErreur(self::LOGIN_ALREADY_LOGGED);
			
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}


	
	/**
	*	Pour traiter la déconnexion manuelle de l'utilisateur
	*/
	public function executeDeconnexion(){
		
		//On récupère l'utilisateur
		$user = $this->app->getUser();

		// On récupère la réponse
		$response = $this->app->getHTTPResponse();

		//On supprime les cookies
		$response->setCookie('mail', '', 0);
		$response->setCookie('cle', '', 0);

		// On supprime tous les attributs en session
		$user->delAllAttribute();
		
		// On passe l'identification à faux
		$user->setAuthenticated(false);

		// On recréé une nouvelle session
		session_destroy(); session_start();

		// On réinitialise la variable flash si celle-ci contient uniquement des erreurs/reussites
		if($user->getMessageClient()->hasErreur()){
			$user->setAttribute('erreurs', $user->getMessageClient()->getErreurs());
		}
		if($user->getMessageClient()->hasReussite()){
			$user->setAttribute('reussites', $user->getMessageClient()->getReussites());
		}

		// On redirige le client vers la page par defaut
		$response = $this->app->getHTTPResponse();
		$response->redirect('/LogIn/');
	}
	
	/**
	*	Pour traiter la récupération du mot de passe
	*/
	public function executeRecupPassword($request){
		
		//On récupère l'utilisateur
		$user = $this->app->getUser();	
		//On récupère la réponse
		$response = $this->app->getHTTPResponse();
		
		//Si l'utilisateur est connecté.
		if($user->isAuthenticated()){
		
			// On procède à la redirection vers la page principale
			$response->redirect('/');
			
		//Si l'utilisateur n'est pas connecté
		}else{
		
			//Si l'adresse mail est inclus dans les données POST
			if($request->getPostData('adresseMail')){
			
				//On récupère l'adresse mail
				$adresseMail=$request->getPostData('adresseMail');
				
				// On appelle le manager des Users
				$managerUser = $this->getManagers()->getManagerOf('Utilisateur');
				// On récupère l'utilisateur de la base selon le mail
				$userBase = $managerUser->getUtilisateurByMail($adresseMail);
				
				//Si l'utilisateur existe
				if($userBase){
					//On envoi un mail pour traiter sa demande
					$user->getMessageClient()->addReussite(self::LOGIN_RESET_PASSWORD);
					
					//On envoi un mail pour confirmer l'inscription par l'application Mail en StandAlone
					// On place la variable en Flash pour qu'elle soit récupérée par l'application Mail
					$user->setFlash($adresseMail);
					$mailApplication = new \Applications\ApplicationsStandAlone\Mail\MailApplication;
					$mailApplication->execute('MailRecupPassword', 'show');
					//On redirige vers l'acceuil
					$response->redirect('/LogIn/');
				//Sinon
				}else{
					$user->getMessageClient()->addErreur(self::LOGIN_WRONG_EMAIL);
					$response->redirect('/LogIn/');
				}
			}else{
				if($request->getGetData('request')=="Request") $user->getMessageClient()->addErreur(self::LOGIN_ENTER_VALID_EMAIL);
			}
		}
	}
	
	/**
	*	Pour afficher la page de réinitialisation du mot de passe
	*/	
	public function executeShowResetPassword($request){
	
		//On récupère la réponse
		$response = $this->app->getHTTPResponse();

		//On récupère l'utilisateur système
		$user = $this->app->getUser();

		// On récupère le jeton en GET
		$jeton = $request->getGetData('jeton');

		// On récupère le jeton en session
		$jetonSession = $user->getAttribute('jetonUser');

		//Si le jeton est présent dans les données GET
		if(isset($jetonSession) && $jeton === $jetonSession){
		
			//On décode le jeton
			$donneesUtilisateur = $this->decodeJeton($jeton);

			//On récupère les données inclus dans le jeton
			$idUtilisateur = $donneesUtilisateur['idMembre'];
			$timeJeton = $donneesUtilisateur['time'];
			$password_crc32 = $donneesUtilisateur['password_crc32'];
			
			// On appelle le manager des Users
			$managerUser = $this->getManagers()->getManagerOf('Utilisateur');
			// On récupère l'utilisateur de la base selon l'id
			$userBase = $managerUser->getUtilisateurById($idUtilisateur);
			
			//Si l'utilisateur existe
			if($userBase){
				
				//Si l'utilisateur est déjà connecté
				if($user->isAuthenticated()){

					$user->getMessageClient()->addErreur(self::LOGIN_ALREADY_LOGGED);
					// On procède à la redirection vers la page par défaut
					$response->redirect('/');
			
				//Si l'utilisateur n'est pas connecté
				}else{

					//Si le CRC du jeton ne correspond pas avec celui du mot de passe de l'utilisateur
					if( !crc32($userBase->getPasswordUtilisateur()) === $password_crc32 ){
						//On redirige vers l'acceuil
						$user->getMessageClient()->addErreur(self::LOGIN_INVALID_LINK_RESET_PASSWORD);
						$response->redirect('/LogIn/');
					}
					
					//Si la date de validité du jeton est dépassée : 5 minutes
					if( $timeJeton < (time()-5*60)){
						//On redirige vers l'acceuil
						$user->getMessageClient()->addErreur(self::LOGIN_INVALID_LINK_RESET_PASSWORD);
						$response->redirect('/LogIn/');
					}

					// On envoie le jeton à la page
					$this->page->addVar('jetonUser', $request->getGetData('jeton'));
					
				}
			}else{
				$user->getMessageClient()->addErreur(self::LOGIN_WRONG_EMAIL);
				$response->redirect('/LogIn/');
			}
		}else{	
			//On redirige vers l'acceuil
			$user->getMessageClient()->addErreur(self::LOGIN_INVALID_LINK_RESET_PASSWORD);		
			$response->redirect('/LogIn/');
		}
	}


	/**
	*	Pour traiter la réinitialisation du mot de passe
	*/	
	public function executeResetPassword($request){
		
		//On récupère la réponse
		$response = $this->app->getHTTPResponse();

		//On récupère l'utilisateur système
		$user = $this->app->getUser();

		// On récupère le jeton en POST
		$jeton = $request->getPostData('jetonUser');

		// On récupère le jeton en session
		$jetonSession = $user->getAttribute('jetonUser');

		//Si le jeton est présent dans les données GET
		if(isset($jetonSession) && $jeton === $jetonSession){
		
			//On décode le jeton
			$donneesUtilisateur = $this->decodeJeton($jeton);

			//On récupère les données inclus dans le jeton
			$idUtilisateur = $donneesUtilisateur['idMembre'];
			$timeJeton = $donneesUtilisateur['time'];
			$password_crc32 = $donneesUtilisateur['password_crc32'];
			
			// On appelle le manager des Users
			$managerUser = $this->getManagers()->getManagerOf('Utilisateur');
			// On récupère l'utilisateur de la base selon l'id
			$userBase = $managerUser->getUtilisateurById($idUtilisateur);
			
			//Si l'utilisateur existe
			if($userBase){
				
				//Si l'utilisateur est déjà connecté
				if($user->isAuthenticated()){

					$user->getMessageClient()->addErreur(self::LOGIN_ALREADY_LOGGED);
					// On procède à la redirection vers la page par défaut
					$response->redirect('/');
			
				//Si l'utilisateur n'est pas connecté
				}else{

					//Si le CRC du jeton ne correspond pas avec celui du mot de passe de l'utilisateur
					if( !crc32($userBase->getPasswordUtilisateur()) === $password_crc32 ){
						//On redirige vers l'acceuil
						$user->getMessageClient()->addErreur(self::LOGIN_INVALID_LINK_RESET_PASSWORD);
						$response->redirect('/LogIn/');
					}
					
					//Si la date de validité du jeton est dépassée: 5min
					if( $timeJeton < (time()- 5*60)){
						//On redirige vers l'acceuil
						$user->getMessageClient()->addErreur(self::LOGIN_INVALID_LINK_RESET_PASSWORD);
						$response->redirect('/LogIn/');
					}

					// On supprime le jeton en session
					$user->delAttribute('jetonUser');
					
					//On vérifie que l'utilisateur a entré son nouveau mot de passe dans le formulaire
					if($request->isExistPOST('newPassword1') && $request->isExistPOST('newPassword2')){
					
						// On vérifie que les deux mots de passe entrés sont identiques
						$newPassword1 = $request->getPostData('newPassword1');
						$newPassword2 = $request->getPostData('newPassword2');
					
						//Si les deux mots de passe de validation correspondent
						if($newPassword1 === $newPassword2){
							
							//Si le mot de passe est différent du mot de passe admin
							if( !password_verify( $newPassword1, $userBase->getPasswordAdminUtilisateur() )){
						
								//Si le mot de passe a sa forme valide	
								if( $this->validPassword($newPassword1)){ 
								
									//On protège le mot de passe
									$newPassword = $this->protectPassword($newPassword1);
									//On modifie le mot de passe de l'utilisateur
									$userBase->setPasswordUtilisateur($newPassword);
									//On sauvegarde l'utilisateur
									$managerUser->saveUtilisateur($userBase);
									$user->getMessageClient()->addReussite(self::LOGIN_PASSWORD_EDITED);
									//On redirige vers la page de connexion
									$response->redirect('/LogIn/');
														
								}else{
									$user->getMessageClient()->addErreur(self::PASSWORD_NOT_VALID);
								}
							}else{
								$user->getMessageClient()->addErreur(self::PASSWORD_DIFFERENT_ADMIN);
							}
						}else{
							$user->getMessageClient()->addErreur(self::PASSWORDS_NOT_MATCH);
						}
					}
				}
			}else{
				$user->getMessageClient()->addErreur(self::LOGIN_WRONG_EMAIL);
				$response->redirect('/LogIn/');
			}
		}else{			
			//On redirige vers l'acceuil
			$user->getMessageClient()->addErreur(self::LOGIN_INVALID_LINK_RESET_PASSWORD);
			$response->redirect('/LogIn/');
		}
	}



	/**
	* Méthode pour contacter NooLib
	*/
	public function executeContactUs($request){

		$this->sendAMailToNooLib($request);

		$response = $this->app->getHTTPResponse();
		$response->redirect('/LogIn/');

	}

}
