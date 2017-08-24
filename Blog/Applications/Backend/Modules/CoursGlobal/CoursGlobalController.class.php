<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Ce controleur permet d'afficher, modifier ou créer de nouveaux		  |
// | coursGlobal			  										  	  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: controleur des coursGlobal
 * @access: public
 * @version: 1
 */	


namespace Applications\Backend\Modules\CoursGlobal;

class CoursGlobalController extends \Library\BackController{

	
	// Page principal de la gestion des coursGlobal
	public function executeShow($request){
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$response = $this->app->getHTTPResponse();
			$response->redirect('/ForAdminOnly/');
		}
		else{
			
			//On appelle le manager des CoursGlobal
			$managerCoursGlobal = $this->getManagers()->getManagerOf('CoursGlobal');

			// On récupère la liste de tous les coursGlobal
			$coursGlobal = $managerCoursGlobal->getAllCoursGlobal();

			// On envoie la liste à la page
			$this->page->addVar('coursGlobal', $coursGlobal);

			// On récupère les différentes catégories des applications
			// On appelle le manager des Catégories
			$managerCategorie = $this->getManagers()->getManagerOf('Categorie');
			$categories = $managerCategorie->getAllCategories();
			// On créé la variable d'affichage à insérer dans la page.
			$categoriesAAfficher='';

			foreach($categories as $categorie){
			
				$categoriesAAfficher.='<option value="'.$categorie->getNomCategorie().'">'.$categorie->getNomCategorie().'</option>';
				
			}

			// On ajoute la variable categoriesAAfficher à la page
			$this->page->addVar('categoriesAAfficher', $categoriesAAfficher);

		}
	}

	// Page d'édition d'un coursGlobal
	public function executeShowCoursGlobal($request){
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$response = $this->app->getHTTPResponse();
			$response->redirect('/ForAdminOnly/');
		}
		else{
			
			//On appelle le manager des Utilisateurs
			$managerCoursGlobal = $this->getManagers()->getManagerOf('CoursGlobal');

			// CoursGlobal demandé
			$idCoursGlobal = (int) $request->getGetData('idCoursGlobal');

			// On récupère le coursGlobal demandé
			$coursGlobal = $managerCoursGlobal->getCoursGlobalById($idCoursGlobal);

			if($coursGlobal instanceof \Library\Entities\CoursGlobal){

				// On envoie la liste à la page
				$this->page->addVar('coursGlobal', $coursGlobal);

				// On récupère les différentes catégories des applications
				// On appelle le manager des Catégories
				$managerCategorie = $this->getManagers()->getManagerOf('Categorie');
				$categories = $managerCategorie->getAllCategories();
				// On créé la variable d'affichage à insérer dans la page.
				$categoriesAAfficher='';

				foreach($categories as $categorie){
				
					if($categorie->getIdCategorie() === $coursGlobal->getCategorie()->getIdCategorie()){
						$categoriesAAfficher.='<option selected value="'.$categorie->getNomCategorie().'">'.$categorie->getNomCategorie().'</option>';
					}else{
						$categoriesAAfficher.='<option value="'.$categorie->getNomCategorie().'">'.$categorie->getNomCategorie().'</option>';
					}
				}

				// On ajoute la variable flèche menu à la page
				$this->page->addVar('categoriesAAfficher', $categoriesAAfficher);
			}else{
				$user->getMessageClient()->addErreur('Le cours global que vous souhaitez consulter n\'existe pas.');
				// On retourne à la page des coursGlobal
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/CoursGlobal/');
			}
		}
	}
	
	// Méthode pour créer un coursGlobal
	public function executeCreerCoursGlobal($request){
		
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		//On récupère la réponse
		$response = $this->app->getHTTPResponse();

		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$response->redirect('/ForAdminOnly/');
		}else{
			
			// On crée l'auteur
			$managerUser = $this->getManagers()->getManagerOf('Utilisateur');
			$auteur = $managerUser->getUtilisateurByMail('collettemathieu@scienceapart.com');
		
			// On crée l'objet Categorie à partir de la base de données. Si celui-ci n'existe pas, on le créé dans la base de données.
			$managerCategorie = $this->getManagers()->getManagerOf('Categorie');
			$categorie = $managerCategorie->getCatgeorieByName($request->getPostData('categorieCoursGlobal'));

			// On charge le fichier de configuration
			$config = $this->getApp()->getConfig();
			// On récupère le chemin url par defaut de l'image du coursGlobal
			$pathImageDefault = $config->getVar('coursGlobal', 'parametresImage', 'filePathDefault');

			$newCoursGlobal = new \Library\Entities\CoursGlobal(array(
				'titreCoursGlobal' => trim($request->getPostData('titreCoursGlobal')),
				'descriptionCoursGlobal' => trim($request->getPostData('descriptionCoursGlobal')),
				'auteur' => $auteur,
				'noteCoursGlobal' => (float) 0,
				'nbreVoteCoursGlobal' => 0,
				'nbreVueCoursGlobal' => 0,
				'categorie' => $categorie,
				'urlImageCoursGlobal' => $pathImageDefault,
				'enLigneCoursGlobal' => false
			));
			
			if(sizeof($newCoursGlobal->getErreurs()) != 0){
				$user->getMessageClient()->addErreur($newCoursGlobal->getErreurs());
				$response->redirect('/ForAdminOnly/CoursGlobal/');
			}else{
				// On appelle les managers
				$managerCoursGlobal = $this->getManagers()->getManagerOf('CoursGlobal');

				$managerCoursGlobal->addCoursGlobal($newCoursGlobal);
				$user->getMessageClient()->addReussite('Le cours global a bien été créé.');
				
				$response->redirect('/ForAdminOnly/CoursGlobal/');
			}
			
		}
	}

	
	// Méthode pour publier/dépublier un coursGlobal
	public function executePublierDepublierCoursGlobal($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		

		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des coursGlobal
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idCoursGlobal = (int) $request->getPostData('idCoursGlobal');
			//On appelle le manager des Utilisateurs
			$managerCoursGlobal = $this->getManagers()->getManagerOf('CoursGlobal');
			//On récupère le coursGlobal à administrer
			$coursGlobal = $managerCoursGlobal->getCoursGlobalById($idCoursGlobal);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($coursGlobal === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des coursGlobal
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/CoursGlobal/');
			}
			else{
				if($coursGlobal->getEnLigneCoursGlobal()){
					$coursGlobal->hydrate(array(
						'enLigneCoursGlobal' => false,
						));
				}else{
					$coursGlobal->hydrate(array(
						'enLigneCoursGlobal' => true,
						));
				}

				//On procède à la mise à jour dans la BDD du coursGlobal
				$managerCoursGlobal->publishCoursGlobal($coursGlobal);
				if($coursGlobal->getEnLigneCoursGlobal()){
					$user->getMessageClient()->addReussite('Le cours global a bien été publié.');
				}else{
					$user->getMessageClient()->addReussite('Le cours global a bien été retiré.');
				}

				// On retourne à la page des coursGlobal
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/CoursGlobal/id='.$coursGlobal->getIdCoursGlobal());
				
			}
		}
	}

	// Méthode pour modifier le titre du coursGlobal
	public function executeModifierTitreCoursGlobal($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des coursGlobal
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idCoursGlobal = (int) $request->getPostData('idCoursGlobal');
			//On appelle le manager des Utilisateurs
			$managerCoursGlobal = $this->getManagers()->getManagerOf('CoursGlobal');
			//On récupère le coursGlobal à administrer
			$coursGlobal = $managerCoursGlobal->getCoursGlobalById($idCoursGlobal);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($coursGlobal === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des coursGlobal
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/CoursGlobal/');
			}
			else{

				$nouveauTitre = $request->getPostData('nouveauTitre');
				
				$coursGlobal->hydrate(array(
					'titreCoursGlobal' => $nouveauTitre,
					));

				if(sizeof($coursGlobal->getErreurs()) === 0){

					//On procède à la mise à jour dans la BDD du coursGlobal
					$managerCoursGlobal->saveCoursGlobal($coursGlobal);
					$user->getMessageClient()->addReussite('Le titre du cours global a bien été modifié.');
					
					// On retourne à la page du coursGlobal
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/CoursGlobal/id='.$coursGlobal->getIdCoursGlobal());
				}else{
					$user->getMessageClient()->addErreur($coursGlobal->getErreurs());
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/CoursGlobal/id='.$coursGlobal->getIdCoursGlobal());
				}
			}
		}
	}


	// Méthode pour modifier l'image de présentation du coursGlobal
	public function executeModifierImageCoursGlobal($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On informe que c'est un chargement Ajax
		$user->setAjax(true);

		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
		
		}else{
			$idCoursGlobal = (int) $request->getPostData('idCoursGlobal');
			//On appelle le manager des Utilisateurs
			$managerCoursGlobal = $this->getManagers()->getManagerOf('CoursGlobal');
			//On récupère le coursGlobal à administrer
			$coursGlobal = $managerCoursGlobal->getCoursGlobalById($idCoursGlobal);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($coursGlobal === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
			
			}
			else{

				/*************************/
				/* CONTROLE DE L'IMAGE   */
				/*************************/
				// On charge l'objet File avec la configuration de l'image du coursGlobal
				
				$tagName= array( 'categorie' => 'coursGlobal', 'sousCategorie' => 'parametresImage');
				$file = $this->getApp()->getFileUpload('imageCoursGlobal', $tagName);
				
				if(count($file->getErreurs()) == 0){
					
					$file->validFileUpload();

					if(count($file->getErreurs()) == 0){

						// On charge le fichier de configuration
						$config = $this->getApp()->getConfig();
						$filePathMiniature = $config->getVar('coursGlobal', 'parametresImage', 'filePathMiniature');
						// On sauvegarde l'ancienne image
						$urlAncienneImage = $coursGlobal->getUrlImageCoursGlobal();
						$urlAncienneImageMiniature = $coursGlobal->getUrlImageMiniatureCoursGlobal();
						// On met à jour l'objet CoursGlobal avec la nouvelle image
						$coursGlobal->hydrate(array(
							'urlImageCoursGlobal' => $file->getFilePath(),
							'urlImageMiniatureCoursGlobal' => $filePathMiniature.$file->getFileName()
							));
						
						if(sizeof($coursGlobal->getErreurs()) === 0){


							// On supprime la précédente image du coursGlobal si ce n'est pas celle par defaut
							// On récupère le chemin url par defaut de l'image du coursGlobal
							$pathImageDefault = $config->getVar('coursGlobal', 'parametresImage', 'filePathDefault');
							$fileDelete = $this->getApp()->getFileDelete();
							if($urlAncienneImage != $pathImageDefault){
								$fileDelete->deleteFile($urlAncienneImage);
								$fileDelete->deleteFile($urlAncienneImageMiniature);
							}

							// S'il n'y a pas d'erreur, on enregistre les fichiers sources sur le serveur
							if(count($fileDelete->getErreurs()) === 0){
								if($file->depositFileUpload()){

									$extensionFichier = strtolower(pathinfo($file->getFileName(), PATHINFO_EXTENSION));
									/**
									* Compression et modification de la résolution pour l'image principale
									*/
									$fileSize = $config->getVar('coursGlobal', 'parametresImage', 'fileSize');
									if(in_array($extensionFichier, array('png', 'PNG'))){
										$source = imagecreatefrompng($file->getFilePath());
									}else{
										$source = imagecreatefromjpeg($file->getFilePath());
									}
									
									$largeur_source = imagesx($source);
									$hauteur_source = imagesy($source);
									
									$largeur_destination = $fileSize;
									$hauteur_destination = $hauteur_source*$fileSize/$largeur_source;

									$destination = imagecreatetruecolor($largeur_destination,$hauteur_destination);
									
									/*On préserve la transparence*/
									imagealphablending($destination, false);
									imagesavealpha($destination, true);

									/*On diminue la résolution*/
									imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);
									
									//Si c'est une image jpeg, on compresse l'image
									if(!in_array($extensionFichier, array('png', 'PNG'))){
										imagejpeg($destination, $file->getFilePath(), $config->getVar('divers', 'divers', 'userBackgroundPixelcompression'));
									}else{
										imagepng($destination, $file->getFilePath());
									}

									/**
									* On crée une image miniature 256*xxx de l'image principale
									*/

									$fileSizeMiniature = $config->getVar('coursGlobal', 'parametresImage', 'fileSizeMiniature');
									if(in_array($extensionFichier, array('png', 'PNG'))){
										$source = imagecreatefrompng($file->getFilePath());
									}else{
										$source = imagecreatefromjpeg($file->getFilePath());
									}
									
									$largeur_source = imagesx($source);
									$hauteur_source = imagesy($source);
									
									$largeur_destination = $fileSizeMiniature;
									$hauteur_destination = $hauteur_source*$fileSizeMiniature/$largeur_source;

									$destination = imagecreatetruecolor($largeur_destination,$hauteur_destination);
									
									/*On préserve la transparence*/
									imagealphablending($destination, false);
									imagesavealpha($destination, true);

									/*On crée la miniature*/
									imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);
									
									//Si c'est une image jpeg, on compresse l'image
									if(!in_array($extensionFichier, array('png', 'PNG'))){
										imagejpeg($destination, $filePathMiniature.$file->getFileName());
									}else{
										imagepng($destination, $filePathMiniature.$file->getFileName());
									}
								
									//On procède à la mise à jour dans la BDD du coursGlobal
									$managerCoursGlobal->saveCoursGlobal($coursGlobal);
									$user->getMessageClient()->addReussite('Le image de présentation du cours global a bien été modifiée.');
									
								}else{
									// On ajoute la variable d'erreurs
									$user->getMessageClient()->addErreur($file->getErreurs());
									
								}
							}else{
								// On ajoute la variable d'erreurs
								$user->getMessageClient()->addErreur($fileDelete->getErreurs());
							}
						}else{
							$user->getMessageClient()->addErreur($fileDelete->getErreurs());
							$user->getMessageClient()->addErreur($coursGlobal->getErreurs());
						}
					}else{
						// On ajoute la variable d'erreurs
						$user->getMessageClient()->addErreur($file->getErreurs());
					}
				}else{
					// On ajoute la variable d'erreurs
					$user->getMessageClient()->addErreur($file->getErreurs());
				}
			}
		}
	}

	// Méthode pour modifier la catégorie du coursGlobal
	public function executeModifierCategorieCoursGlobal($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des coursGlobal
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idCoursGlobal = (int) $request->getPostData('idCoursGlobal');
			//On appelle le manager des Utilisateurs
			$managerCoursGlobal = $this->getManagers()->getManagerOf('CoursGlobal');
			//On récupère le coursGlobal à administrer
			$coursGlobal = $managerCoursGlobal->getCoursGlobalById($idCoursGlobal);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($coursGlobal === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des coursGlobal
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/CoursGlobal/');
			}
			else{

				// On crée l'objet Categorie à partir de la base de données. Si celui-ci n'existe pas, on le créé dans la base de données.
				$managerCategorie = $this->getManagers()->getManagerOf('Categorie');
				$categorie = $managerCategorie->getCatgeorieByName($request->getPostData('nouvelleCategorieCoursGlobal'));


				$coursGlobal->hydrate(array(
					'categorie' => $categorie,
					));

				if(sizeof($coursGlobal->getErreurs()) === 0){

					//On procède à la mise à jour dans la BDD du coursGlobal
					$managerCoursGlobal->saveCoursGlobal($coursGlobal);
					$user->getMessageClient()->addReussite('La catégorie du cours global a bien été modifiée.');
					
					// On retourne à la page du coursGlobal
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/CoursGlobal/id='.$coursGlobal->getIdCoursGlobal());
				}else{
					$user->getMessageClient()->addErreur($coursGlobal->getErreurs());
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/CoursGlobal/id='.$coursGlobal->getIdCoursGlobal());
				}
			}
		}
	}


	// Méthode pour modifier la description du coursGlobal
	public function executeModifierDescriptionCoursGlobal($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des coursGlobal
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idCoursGlobal = (int) $request->getPostData('idCoursGlobal');
			//On appelle le manager des Utilisateurs
			$managerCoursGlobal = $this->getManagers()->getManagerOf('CoursGlobal');
			//On récupère le coursGlobal à administrer
			$coursGlobal = $managerCoursGlobal->getCoursGlobalById($idCoursGlobal);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($coursGlobal === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des coursGlobal
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/CoursGlobal/');
			}
			else{

				$nouvelleDescription = $request->getPostData('nouvelleDescription');
				
				$coursGlobal->hydrate(array(
					'descriptionCoursGlobal' => $nouvelleDescription,
					));

				if(sizeof($coursGlobal->getErreurs()) === 0){

					//On procède à la mise à jour dans la BDD du coursGlobal
					$managerCoursGlobal->saveCoursGlobal($coursGlobal);
					$user->getMessageClient()->addReussite('La description du cours global a bien été modifiée.');
					
					// On retourne à la page du coursGlobal
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/CoursGlobal/id='.$coursGlobal->getIdCoursGlobal());
				}else{
					$user->getMessageClient()->addErreur($coursGlobal->getErreurs());
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/CoursGlobal/id='.$coursGlobal->getIdCoursGlobal());
				}
			}
		}
	}

	
	// Méthode pour supprimer un coursGlobal de la base
	public function executeSupprimerCoursGlobal($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		

		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des coursGlobal
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idCoursGlobal = (int) $request->getPostData('idCoursGlobal');
			//On appelle le manager des Utilisateurs
			$managerCoursGlobal = $this->getManagers()->getManagerOf('CoursGlobal');
			//On récupère le coursGlobal à administrer
			$coursGlobal = $managerCoursGlobal->getCoursGlobalById($idCoursGlobal);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($coursGlobal === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des coursGlobal
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/CoursGlobal/');
			}
			else{

				// On supprime la précédente image du coursGlobal si ce n'est pas celle par defaut
				// On récupère le chemin url par defaut de l'image du coursGlobal
				// On charge le fichier de configuration
				$config = $this->getApp()->getConfig();
				$pathImageDefault = $config->getVar('coursGlobal', 'parametresImage', 'filePathDefault');
				$fileDelete = $this->getApp()->getFileDelete();
				if($coursGlobal->getUrlImageCoursGlobal() != $pathImageDefault){
					$fileDelete->deleteFile($coursGlobal->getUrlImageCoursGlobal());
				}

				//On procède à la suppression dans la BDD du coursGlobal
				$managerCoursGlobal->deleteCoursGlobal($coursGlobal);
				$user->getMessageClient()->addReussite('Le cours global a bien été supprimé.');

				// On retourne à la page des coursGlobal
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/CoursGlobal/');
				
			}
		}
	}
}
