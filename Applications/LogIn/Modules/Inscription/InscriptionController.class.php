<?php
// +----------------------------------------------------------------------+
// | PHP Version 7 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP comme controleur du module d'inscription des utilisateurs.|
// | Ce controleur permet d'inscrire l'utilisateur sur le site et de 	  |
// | contrôler son compte.												  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// | Auteurs : Steve Despres  <stevedespres@noolib.com>				      |
// +----------------------------------------------------------------------+

/**
 * @name: controleur de la rubrique inscription des utilisateurs
 * @access: public
 * @version: 1
 */	

namespace Applications\LogIn\Modules\Inscription;

class InscriptionController extends \Library\BackController{

	use \Library\Traits\MethodeUtilisateurControleur;
	use \Library\Traits\FonctionsUniverselles;

	/**
	*	Récupérer la liste des statuts pour la page formulaire d'inscription
	*/
	public function executeGetStatut($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if($request->isAjaxRequest()){

			// On vérifie que l'utilisateur n'est pas déjà identifié
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			if(!$user->isAuthenticated()){
				// On récupère les différents statut de l'utilisateur
				// On appelle le manager des Statut Utilisateur
				$managerStatutUtilisateur = $this->getManagers()->getManagerOf('StatutUtilisateur');
				$statuts = $managerStatutUtilisateur->getAllStatuts();
				// On créé le tableau des types de publication
				$statutAAfficher = array();
				foreach($statuts as $id=>$statut){
					$typeStatut = array(
						'id' => $id,
						'nameType' => $statut->getNomStatut()
						);
					array_push($statutAAfficher, $typeStatut);
				}
				// On ajoute la variable à la page
				$this->page->addVar('statutAAfficher', $statutAAfficher);
			}else{
				// On ajoute la variable d'erreurs à la page
				$user->getMessageClient()->addErreur(self::INSCRIPTION_USER_ALREADY_LOGGED);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	
	}



	/**
	*	Pour traiter la validation d'un nouveau utilisateur
	*/
	public function executeValiderInscription($request){
		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()){

			// On récupère l'utilisateur
			$user = $this->app->getUser();
			
			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			if(!$user->isAuthenticated()){

				// On vérifie que les deux mots de passe entrés sont identiques
				$motDePasseFormulaire = $request->getPostData('motDePasseFormulaire');
				$motDePasseConfirme = $request->getPostData('motDePasseConfirme');
				$adresseMail = $request->getPostData('adresseMail');
				
				// On charge le fichier de configuration
				$config = $this->getApp()->getConfig();
				
				// On vérifie le mot de passe renseigné par l'utilisateur
				if($request->isExistPOST('motDePasseFormulaire') && $request->isExistPOST('motDePasseConfirme')){
					//Si les deux mots de passe de validation correspondent
					if($request->getPostData('motDePasseFormulaire') === $request->getPostData('motDePasseConfirme')){
						//Si le mot de passe a sa forme valide	
						if( $this->validPassword( $request->getPostData('motDePasseFormulaire'))){ 
							//On le protège 
							$passwordUser = $this->protectPassword($request->getPostData('motDePasseFormulaire'));
							
						}else{
							//Sinon sa valeur est vide.
							$passwordUser = '';
						}
					}else{
						// Si les 2 mots de passe ne correspondent pas, sa valeur est nulle
						$passwordUser = null;
					} 
				}else{
					$passwordUser = '';
				}
				

				// On vérifie que l'adresse électronique de l'utilisateur n'est pas une adresse jetable
				if($config->validMail($adresseMail) == true){
					$mailUtilisateur = trim($adresseMail);
				}else{
					$mailUtilisateur = false;
				}

				// On appelle le manager des Statut Utilisateur
				$managerStatutUtilisateur = $this->getManagers()->getManagerOf('StatutUtilisateur');
		
				// On créé l'objet User avec les données entrées par l'utilisateur
				//On bloque l'utilisateur tant qu'il n'a pas validé son inscription
				$newUser = new \Library\Entities\Utilisateur(array(
					'nomUtilisateur' => trim($request->getPostData('nom')),
					'prenomUtilisateur' => trim($request->getPostData('prenom')),
					'mailUtilisateur' => $mailUtilisateur,
					'passwordUtilisateur' => $passwordUser,
					'statut' => $managerStatutUtilisateur->getStatutByNom($request->getPostData('statutUtilisateur')),
					'urlPhotoUtilisateur' => $config->getVar('divers', 'divers', 'photoProfilDefault'),
					'descriptionUtilisateur' => '',
					'lienPagePersoUtilisateur' => '',
					'passwordAdminUtilisateur' => '', 
					'etatBanniUtilisateur' => false, 
					'urlBackgroundUtilisateur' => $this->getApp()->getConfig()->getVar('divers', 'divers', 'defaultUserBackground'),
					'utilisateurActive' => false
					));

				if(sizeof($newUser->getErreurs()) == 0) {
					// On appelle le manager des Utilisateur
					$managerUser = $this->getManagers()->getManagerOf('Utilisateur');
					
					// Vérifier que l'utilisateur n'existe pas déjà dans la base
					if(!$managerUser->getUtilisateurByMail($newUser->getMailUtilisateur())){
						
						// On créé la variable fixe de l'utilisateur basée sur son mail
						// On créé un nombre aléatoire
						$nombre = rand(0,10000000);
						$variableFixeUtilisateur = $this->cleanFileName($newUser->getMailUtilisateur()).$nombre;
						// On charge le fichier de configuration
						$repertoireDestination = $config->getVar('divers','divers','safeWorkSpace');
						$variableFixeUtilisateur = substr($variableFixeUtilisateur, 0, 31); // Pour pouvoir créer des utilisateurs linux
						$newUser->hydrate(array(
							'variableFixeUtilisateur' => $variableFixeUtilisateur,
							'workSpaceFolderUtilisateur' => $repertoireDestination.$variableFixeUtilisateur
						));

						if(sizeof($newUser->getErreurs()) == 0) {

							// On ajoute l'utilisateur à la base de données
							$managerUser->addUtilisateur($newUser);
							
							//On envoi un mail pour confirmer l'inscription par l'application Mail en StandAlone
							// On place la variable en Flash pour qu'elle soit récupérée par l'application Mail
							$user->setFlash($newUser->getMailUtilisateur());
							$mailApplication = new \Applications\ApplicationsStandAlone\Mail\MailApplication;
							$mailApplication->execute('MailInscription', 'show'); // Module = MailInscription ; action = show
							
							// On ajoute la variable de confirmation à la page
							$user->getMessageClient()->addReussite(self::INSCRIPTION_REGISTRATION_SUCCESSFUL); 
						}else{
							// On ajoute la variable d'erreurs à la page
							$user->getMessageClient()->addErreur($newUser->getErreurs());
						}
					}else{
						// On ajoute la variable d'erreurs à la page
						$user->getMessageClient()->addErreur(self::INSCRIPTION_ALREADY_REGISTRED);
					}
				}else{
					
					// On ajoute la variable d'erreurs à la page
					$user->getMessageClient()->addErreur($newUser->getErreurs());
				}
			}else{
				// On ajoute la variable d'erreurs à la page
				$user->getMessageClient()->addErreur(self::INSCRIPTION_USER_ALREADY_LOGGED);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}		
}