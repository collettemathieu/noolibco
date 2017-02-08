<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour le dépôt des applications. Le dépôt se |
// | réalise en plusieurs étapes avant d'être validé. Ici Etape 2.		  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe Step2Controller - Etape 2 vers Etape 3
 * @access: public
 * @version: 1
 */	


namespace Applications\SubmitAnApplication\Modules\Step2;
	
use Library\Entities\MotCle;
use Library\Entities\ApplicationMotCle;

class Step2Controller extends \Library\BackController
{

	/**
	* Méthode pour valider le dépôt d'une application de l'étape 2 et accéder à l'étape 3
	*/
	public function executeValidStep2($request){

		if ($request->isAjaxRequest()) {

			// On vérifie que l'utilisateur est bien identifié
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'application en cours de dépôt si elle existe
			$newApp = unserialize($user->getAttribute('newApp'));

			// On vérifie que le bon contrôleur est appelé
			if($newApp && $newApp->getStatut()->getNomStatut() === 'Step2Deposit'){

				// On charge le fichier de configuration
				$config = $this->getApp()->getConfig();

				/***************************/
				/* CONTROLE DES MOTS-CLES   */
				/***************************/
				// On contrôle les mots-clés entrés par l'utilisateur			
				// On appelle la fonction multiexplode pour les mots-clés entrés par l'utilisateur
				$delimitateursRecherches = explode('|', $config->getVar('divers', 'divers', 'delimitateurMotsCles')); //Tableau des délimiteurs autorisés
				$motsClesEntreUtilisateur = $config->multiexplode($delimitateursRecherches,$request->getPostData('motsClesApp'));
				
				/***************************/
				/* CONTROLE DU LOGO        */
				/***************************/
				// On charge l'objet File avec la configuration du logo de l'application
				
				$tagName= array( 'categorie' => 'application', 'sousCategorie' => 'logo');
				$file = $this->getApp()->getFileUpload('logoApp', $tagName);
				
				if(count($file->getErreurs()) == 0){
					
					// En paramètre on ne renseigne par l'utilisateur mais que le sous-dossier de l'application
					$file->validFileUpload(null, $newApp->getVariableFixeApplication());

					if(count($file->getErreurs()) == 0){

						// On met à jour l'objet App avec les nouvelles données entrées par l'utilisateur
						$newApp->hydrate(array(
							'urlLogoApplication' => $file->getFilePath(),
							'motCles' => $motsClesEntreUtilisateur
							));
						
						// On contrôle qu'il n'y a pas eu d'erreurs
						if(sizeof($newApp->getErreurs()) == 0){
							
							// S'il n'y a pas d'erreur, on enregistre les fichiers sources sur le serveur
							if($file->depositFileUpload()){
							
								/***************************/
								/* GESTION DES MOTS-CLES   */
								/***************************/

								// On appelle les managers
								$managerMotCle = $this->getManagers()->getManagerOf('MotCle');
								$managerApplicationMotCle = $this->getManagers()->getManagerOf('ApplicationMotCle');
								//Creation des mots clés que l'on insère dans la BDD s'il n'existe pas encore
								foreach($motsClesEntreUtilisateur as $motcle){
									//tentative de récuperation du mot cle dans la base par son Nom
									$motCleBDD = $managerMotCle->getMotCleByName($motcle);
									//s'il n'existe pas, creation d'un nouveau mot cle que l'on ajoute ensuite directement dans la BDD
									if (!$motCleBDD){
										$motCleBDD = new MotCle(array('nomMotCle' => $motcle));
										$managerMotCle->addMotCle($motCleBDD);
									}

									// On associe ensuite le mot-clé à l'application s'il n'est pas déjà présent dans la BDD
									if(!$managerApplicationMotCle->getApplicationMotCleById($newApp->getIdApplication(), $motCleBDD->getIdMotCle())){
										$ApplicationMotCle = new ApplicationMotCle(array(
											'motCle' => $motCleBDD,
											'application' => $newApp
											));
										$managerApplicationMotCle->addApplicationMotCle($ApplicationMotCle);
									}
								}

								/**************************************************/
								/* GESTION DU LOGO pour une réduction de taille   */
								/**************************************************/
								
								/*On crée une image miniature 128*128px de l'image icône*/
								$source = imagecreatefrompng($file->getFilePath());
								$destination = imagecreatetruecolor(128,128);
								
								$largeur_source = imagesx($source);
								$hauteur_source = imagesy($source);
								
								$largeur_destination = imagesx($destination);
								$hauteur_destination = imagesy($destination);
								
								/*On préserve la transparence*/
								imagealphablending($destination, false);
								imagesavealpha($destination, true);

								/*On crée la miniature de 128*128*/
								imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);
								
								/*On écrase l'image précédente par la miniature de 128*128*/
								imagepng($destination, $file->getFilePath());
							
								// On modifie le statut de l'application
								$managerStatut = $this->getManagers()->getManagerOf('StatutApplication');
								$newApp->setStatut($managerStatut->getStatutByNom('Step3Deposit'));
								// On sauvegarde dans la BDD
								$managerApplication = $this->getManagers()->getManagerOf('Application');
								$managerApplication->saveStep2DepositApplication($newApp);
								// On place l'objet newApp en Session
								$user->setAttribute('newApp', serialize($newApp));

								// On met à jour la session de l'utilisateur
								$utilisateur = unserialize($user->getAttribute('userSession'));
								$utilisateur->updateApplication($newApp);
								$user->setAttribute('userSession', serialize($utilisateur));

								// On ajoute la variable de réussite
								$user->getMessageClient()->addReussite(true);

							}else{
								// On ajoute la variable d'erreurs
								$user->getMessageClient()->addErreur($file->getErreurs());
							}
						}else{
							// Sinon on revoit l'utilisateur à l'étape 2 avec les messages d'erreur.
							// On ajoute la variable d'erreurs à la variable flash de la session
							$user->getMessageClient()->addErreur($newApp->getErreurs());
						}
					}else{
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur($file->getErreurs());
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur($file->getErreurs());
				}
			}else{
				// On ajoute la variable de réussites
				$user->getMessageClient()->addErreur(self::SUBMITAPPLICATION_ERROR_STEP_APPLICATION);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}
}