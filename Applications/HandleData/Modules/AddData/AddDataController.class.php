<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour la gestion par Ajax des formulaires    |
// | lors de l'ajout/modification d'une donnée (data) client.			  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe AddDataController
 * @access: public
 * @version: 1
 */	


namespace Applications\HandleData\Modules\AddData;
use Library\Entities\DonneeUtilisateur;
use Library\Entities\UtilisateurDonneeUtilisateur;


class AddDataController extends \Library\BackController
{

	use \Library\Traits\MethodeDonneeUtilisateurControleur;

	/**
	* Méthode pour ajouter une donnée locale (cad temporairement) sur le serveur NooLib
	*/
	public function executeAddLocalData($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {

			echo $this->app->getNomApplication();exit();
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			if(!$user->isAjaxRequestAlreadyRunning()){

				// On récupère l'utilisateur connecté
				$utilisateur = unserialize($user->getAttribute('userSession'));

				// On augmente artificiellement l'allocation mémoire
				ini_set('memory_limit','512M');

				// On contrôle les données présentes
				$extensionFichier = $request->getPostData('ext');
				$nomFichier = substr($request->getPostData('nomFichier'), 0, -4);
				$dataSent = $request->getPostData('donneeUtilisateur');
				$sampleRateDonneeUtilisateur = (int) $request->getPostData('sampleRateDonneeUtilisateur');
				$tailleDonneeUtilisateur = (int) $request->getPostData('tailleDonneeUtilisateur');
				$tempsMinimumDonneeUtilisateur = (int) $request->getPostData('tempsMinimumDonneeUtilisateur');

				try{
					if(!empty($extensionFichier) && !empty($nomFichier) && !empty($dataSent) && isset($sampleRateDonneeUtilisateur) && isset($tailleDonneeUtilisateur)){

						// On créé un TypeDonneeUtilisateur associé à la donnée
						// On appelle le manager des TypeDonneeUtilisateur
						$managerTypeDonneeUtilisateur = $this->getManagers()->getManagerOf('TypeDonneeUtilisateur');

						$typeDonneeUtilisateur = $managerTypeDonneeUtilisateur->getTypeDonneeUtilisateurByExtension($extensionFichier);
						
						if($typeDonneeUtilisateur){
							
							if($extensionFichier === 'edf' || $extensionFichier === 'edf+' || $extensionFichier === 'txt'){
								$typeDonneeUtilisateur = $managerTypeDonneeUtilisateur->getTypeDonneeUtilisateurByExtension('csv');
								$extensionFichier = 'csv';	
							}

							// On charge l'objet des données temporaires
							$file = $this->getApp()->getFileUploadLocalData();
							$file->validFileUploadLocalData($extensionFichier, $nomFichier, $tailleDonneeUtilisateur);

							if(count($file->getErreurs()) == 0){

								// On crée une nouvelle donnée utilisateur
								$nouvelleDonneeUtilisateur = new DonneeUtilisateur(array(
									'urlDonneeUtilisateur' => $file->getFilePath(),
									'nomDonneeUtilisateur' => substr($nomFichier, 0, 34),
									'sampleRateDonneeUtilisateur' => $sampleRateDonneeUtilisateur,
									'tailleDonneeUtilisateur' => $tailleDonneeUtilisateur,
									'tempsMinimumDonneeUtilisateur' => $tempsMinimumDonneeUtilisateur,
									'typeDonneeUtilisateur' => $typeDonneeUtilisateur,
									'datePublicationDonneeUtilisateur' => date('Y-m-d'),
									'isInCache' => true
									));

								if(sizeof($nouvelleDonneeUtilisateur->getErreurs()) == 0){

									// On execute les traitements des données en fonction de l'extension des données
									switch($extensionFichier){
										case 'csv':
											// On convertit les données JSON en tableau
											$data = json_decode(html_entity_decode($dataSent));

										break;
										
										default:

											// On décode le format dataURL de JS
											list($type, $data) = explode(';', $dataSent);
											list(, $data) = explode(',', $data);
											$data = base64_decode($data);
									}
									if($data != null && $data != false){
										if($file->depositFileUploadLocalData($data)){

											// On appelle le manager de donneeUtilisateur et de UtilisateurDonneeUtilisateur
											$managerDonneeUtilisateur = $this->getManagers()->getManagerOf('DonneeUtilisateur');
											$managerUtilisateurDonneeUtilisateur = $this->getManagers()->getManagerOf('UtilisateurDonneeUtilisateur');

											// On créé l'objet UtilisateurDonneeUtilisateur pour le lien entre l'utilisateur et sa donnée
											$utilisateurDonneeUtilisateur = new UtilisateurDonneeUtilisateur(array(
												'donneeUtilisateur' => $nouvelleDonneeUtilisateur, 
												'utilisateur' => $utilisateur
												));

											if(count($utilisateurDonneeUtilisateur->getErreurs()) == 0){

												// On crée une miniature de l'image pour l'affichage dans le data manager
												if($extensionFichier === 'jpg' || $extensionFichier === 'png' || $extensionFichier === 'tif'){
													if($extensionFichier === 'png'){
														$ImageInitiale = imagecreatefrompng($file->getFilePath());
													}else{
														$ImageInitiale = imagecreatefromjpeg($file->getFilePath());
													}
													
													$largeur_source = imagesx($ImageInitiale);
													$Hauteur_source = imagesy($ImageInitiale);
													
													// On calcule de facteur de redimentionnement de 64 px
													$rate = min(64/$largeur_source, 64/$Hauteur_source);
													$largeur_destination = $largeur_source*$rate;
													$Hauteur_Destination = $Hauteur_source*$rate;
													
													// On créé une nouvelle image
													$ImageFinale = imagecreatetruecolor($largeur_destination, $Hauteur_Destination);
													imagealphablending($ImageFinale, false); // setting alpha blending on
													imagesavealpha($ImageFinale, true); // save alphablending setting (important)
													imagecopyresampled($ImageFinale, $ImageInitiale, 0, 0, 0, 0, imagesx($ImageFinale), imagesy($ImageFinale), $largeur_source, $Hauteur_source);
													
													$pathMiniatureImage = $file->getFileFolder().'Miniatures/'.$file->getFileName().'.png';

													// Si le dossier n'existe pas, on le crée
													if(!is_dir($file->getFileFolder().'Miniatures/')){
														mkdir($file->getFileFolder().'Miniatures/', octdec(770), true);
													}
													// On enregistre l'ancienne image avec la nouvelle redimentionnée
													imagepng($ImageFinale, $pathMiniatureImage);

													// On met à jour la donnée utilisateur
													$nouvelleDonneeUtilisateur->hydrate(array(
														'urlMiniatureDonneeUtilisateur' => $pathMiniatureImage
													));
												}else{
													$nouvelleDonneeUtilisateur->hydrate(array(
														'urlMiniatureDonneeUtilisateur' => ''
													));
												}

												// On place la donnée en BDD
												$donneeUtilisateur = $managerDonneeUtilisateur->addDonneeUtilisateur($nouvelleDonneeUtilisateur);
												$managerUtilisateurDonneeUtilisateur->addUtilisateurDonneeUtilisateur($utilisateurDonneeUtilisateur);

												// On appelle le manager des Utilisateur pour insérer les nouvelles données de l'utilisateur
												$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
												
												// On ajoute la nouvelle donnée à l'utilisateur en session
												$utilisateur->addDonneeUtilisateur($nouvelleDonneeUtilisateur);

												// On sauvegarde l'utilisateur en session
												$user->setAttribute('userSession', serialize($utilisateur));

												// On affiche le nouvel espace disponible des données
												$statDonneeUtilisateur = $this->executeEspaceDisponibleDonneeUtilisateur($utilisateur);

												$this->page->addVar('tailleMaxDonneesUtilisateur', $statDonneeUtilisateur['tailleMaxDonneesUtilisateur']);
												$this->page->addVar('tailleMoDonneesUtilisateur', $statDonneeUtilisateur['tailleMoDonneesUtilisateur']);
												$this->page->addVar('progressionPourcent', $statDonneeUtilisateur['progressionPourcent']);

												// On place l'utilisateur dans la page
												$this->getPage()->addVar('utilisateur', $utilisateur);

												// On charge le fichier de configuration
												$config = $this->getApp()->getConfig();
												$delaySaveDataUser = $config->getVar('divers','divers','delaySaveDataUser');
												// On envoi la variable à la page
												$this->page->addVar('delaySaveDataUser', $delaySaveDataUser);
												
												// On tente de libérer l'espace mémoire
												unset($dataSent);unset($data);unset($nouvelleDonneeUtilisateur);unset($utilisateurDonneeUtilisateur);
												
												$user->getMessageClient()->addReussite(self::ADD_DATA_LOADED_DATA);

											}else{
												// On ajoute la variable d'erreurs à la page
												$user->getMessageClient()->addErreur($utilisateurDonneeUtilisateur->getErreurs());
											}
										}else{
											// On ajoute la variable d'erreurs à la page
											$user->getMessageClient()->addErreur($file->getErreurs());
										}
									}else{
										// On ajoute la variable d'erreurs à la page
										$user->getMessageClient()->addErreur(self::ADD_DATA_ERROR_ENCODING);
									}
								}else{
									// On ajoute la variable d'erreurs à la page
									$user->getMessageClient()->addErreur($nouvelleDonneeUtilisateur->getErreurs());
								}
							}else{
								// On ajoute la variable d'erreurs à la page
								$user->getMessageClient()->addErreur($file->getErreurs());
							}
						}else{
							// On ajoute la variable d'erreurs à la page
							$user->getMessageClient()->addErreur(self::ADD_DATA_DATA_NOT_TAKEN_INTO_ACCOUNT);
						}
					}else{
						// On ajoute la variable d'erreurs à la page
						$user->getMessageClient()->addErreur(self::ADD_DATA_NO_DATA_SENT);
					}
				}
				catch(Exception $e){
					// On ajoute la variable d'erreurs à la page
					$user->getMessageClient()->addErreur(self::ADD_DATA_MEMORY_EXCEEDED);
				}
			}else{
				// On ajoute la variable d'erreurs à la page
				$user->getMessageClient()->addErreur(self::ADD_DATA_DATA_ALREADY_LOADED);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}

}