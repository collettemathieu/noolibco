<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Ce controleur permet d'afficher, modifier ou créer de nouvelles	  |
// | actualités		   			  										  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: controleur des actualités
 * @access: public
 * @version: 1
 */	


namespace Applications\Backend\Modules\Actualites;

class ActualitesController extends \Library\BackController{
	
	// Page principal de la gestion des actualites
	public function executeShow($request){
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$response = $this->app->getHTTPResponse();
			$response->redirect('/ForAdminOnly/');
		}
		else{
			
			//On appelle le manager des Actualités
			$managerActualite = $this->getManagers()->getManagerOf('Actualite');

			// On récupère la liste de tous les actualites
			$actualites = $managerActualite->getAllActualites();

			// On envoie la liste à la page
			$this->page->addVar('actualites', $actualites);

		}
	}

	// Page d'édition d'une actualité
	public function executeShowActualite($request){
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$response = $this->app->getHTTPResponse();
			$response->redirect('/ForAdminOnly/');
		}
		else{
			
			//On appelle le manager des Actualités
			$managerAcutalite = $this->getManagers()->getManagerOf('Actualite');

			// Actualité demandée
			$idActualite = (int) $request->getGetData('idActualite');

			// On récupère l'actualite demandé
			$actualite = $managerAcutalite->getActualiteById($idActualite);

			if($actualite instanceof \Library\Entities\Actualite){

				// On envoie la liste à la page
				$this->page->addVar('actualite', $actualite);

			}else{
				$user->getMessageClient()->addErreur('L\'actualité que vous souhaitez consulter n\'existe pas.');
				// On retourne à la page des actualites
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Actualites/');
			}
		}
	}
	
	// Méthode pour créer une actualité
	public function executeCreerActualite($request){
		
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		//On récupère la réponse
		$response = $this->app->getHTTPResponse();

		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$response->redirect('/ForAdminOnly/');
		}else{

			// On charge le fichier de configuration
			$config = $this->getApp()->getConfig();
			// On récupère le chemin url par defaut de l'image de l'article
			$pathImageDefault = $config->getVar('actualite', 'parametresImage', 'filePathDefault');

			$newActualite = new \Library\Entities\Actualite(array(
				'titreActualite' => trim($request->getPostData('titreActualite')),
				'texteActualite' => trim($request->getPostData('texteActualite')),
				'urlLienActualite' => trim($request->getPostData('urlLienActualite')),
				'urlImageActualite' => $pathImageDefault,
				'enLigneActualite' => false
			));
			
			if(sizeof($newActualite->getErreurs()) != 0){
				$user->getMessageClient()->addErreur($newActualite->getErreurs());
				$response->redirect('/ForAdminOnly/Actualites/');
			}else{
					
				// On appelle les managers
				$managerActualite = $this->getManagers()->getManagerOf('Actualite');

				$managerActualite->addActualite($newActualite);
				$user->getMessageClient()->addReussite('L\'actualité a bien été créée.');
				
				$response->redirect('/ForAdminOnly/Actualites/');
			}
			
		}
	}

	
	// Méthode pour publier/dépbulier une actualité
	public function executePublierDepublierActualite($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		

		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des actualites
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idActualite = (int) $request->getPostData('idActualite');
			//On appelle le manager des Utilisateurs
			$managerAcutalite = $this->getManagers()->getManagerOf('Actualite');
			//On récupère l'actualite à administrer
			$actualite = $managerAcutalite->getActualiteById($idActualite);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($actualite === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des actualites
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Actualites/');
			}
			else{
				if($actualite->getEnLigneActualite()){
					$actualite->hydrate(array(
						'enLigneActualite' => false,
						));
				}else{
					$actualite->hydrate(array(
						'enLigneActualite' => true,
						));
				}

				//On procède à la mise à jour dans la BDD de l'actualite
				$managerAcutalite->publishActualite($actualite);
				if($actualite->getEnLigneActualite()){
					$user->getMessageClient()->addReussite('L\'actualité a bien été publiée.');
				}else{
					$user->getMessageClient()->addReussite('L\'actualité a bien été retirée.');
				}

				// On retourne à la page des actualites
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Actualites/id='.$actualite->getIdActualite());
				
			}
		}
	}

	// Méthode pour modifier le titre de l'actualité
	public function executeModifierTitreActualite($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des actualites
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idActualite = (int) $request->getPostData('idActualite');
			//On appelle le manager des Utilisateurs
			$managerActualite = $this->getManagers()->getManagerOf('Actualite');
			//On récupère l'actualite à administrer
			$actualite = $managerActualite->getActualiteById($idActualite);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($actualite === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des actualites
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Actualites/');
			}
			else{

				$nouveauTitre = $request->getPostData('nouveauTitre');
				
				$actualite->hydrate(array(
					'titreActualite' => $nouveauTitre,
					));

				if(sizeof($actualite->getErreurs()) === 0){

					//On procède à la mise à jour dans la BDD de l'actualite
					$managerActualite->saveActualite($actualite);
					$user->getMessageClient()->addReussite('Le titre de l\'actualité a bien été modifiée.');
					
					// On retourne à la page de l'actualite
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Actualites/id='.$actualite->getIdActualite());
				}else{
					$user->getMessageClient()->addErreur($actualite->getErreurs());
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Actualites/id='.$actualite->getIdActualite());
				}
			}
		}
	}


	// Méthode pour modifier le texte de l'actualité
	public function executeModifierTexteActualite($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des actualites
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idActualite = (int) $request->getPostData('idActualite');
			//On appelle le manager des Utilisateurs
			$managerActualite = $this->getManagers()->getManagerOf('Actualite');
			//On récupère l'actualite à administrer
			$actualite = $managerActualite->getActualiteById($idActualite);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($actualite === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des actualites
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Actualites/');
			}
			else{

				$nouveauTexte = $request->getPostData('nouveauTexte');
				
				$actualite->hydrate(array(
					'texteActualite' => $nouveauTexte,
					));

				if(sizeof($actualite->getErreurs()) === 0){

					//On procède à la mise à jour dans la BDD de l'actualite
					$managerActualite->saveActualite($actualite);
					$user->getMessageClient()->addReussite('Le texte de l\'actualité a bien été modifié.');
					
					// On retourne à la page de l'actualite
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Actualites/id='.$actualite->getIdActualite());
				}else{
					$user->getMessageClient()->addErreur($actualite->getErreurs());
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Actualites/id='.$actualite->getIdActualite());
				}
			}
		}
	}


	// Méthode pour modifier l'url du lien de l'actualité
	public function executeModifierUrlLienActualite($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des actualites
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idActualite = (int) $request->getPostData('idActualite');
			//On appelle le manager des Utilisateurs
			$managerActualite = $this->getManagers()->getManagerOf('Actualite');
			//On récupère l'actualite à administrer
			$actualite = $managerActualite->getActualiteById($idActualite);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($actualite === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des actualites
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Actualites/');
			}
			else{

				$nouveauLienUrl = $request->getPostData('nouveauLienUrl');
				
				$actualite->hydrate(array(
					'urlLienActualite' => $nouveauLienUrl,
					));

				if(sizeof($actualite->getErreurs()) === 0){

					//On procède à la mise à jour dans la BDD de l'actualite
					$managerActualite->saveActualite($actualite);
					$user->getMessageClient()->addReussite('L\'url du lien de l\'actualité a bien été modifié.');
					
					// On retourne à la page de l'actualite
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Actualites/id='.$actualite->getIdActualite());
				}else{
					$user->getMessageClient()->addErreur($actualite->getErreurs());
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Actualites/id='.$actualite->getIdActualite());
				}
			}
		}
	}


	// Méthode pour modifier l'image de présentation de l'actualite
	public function executeModifierImageActualite($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On informe que c'est un chargement Ajax
		$user->setAjax(true);
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
		}else{
			$idActualite = (int) $request->getPostData('idActualite');
			//On appelle le manager des Utilisateurs
			$managerActualite = $this->getManagers()->getManagerOf('Actualite');
			//On récupère l'actualite à administrer
			$actualite = $managerActualite->getActualiteById($idActualite);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($actualite === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				
			}
			else{

				/*************************/
				/* CONTROLE DE L'IMAGE   */
				/*************************/
				// On charge l'objet File avec la configuration de l'image de l'actualite
				
				$tagName= array( 'categorie' => 'actualite', 'sousCategorie' => 'parametresImage');
				$file = $this->getApp()->getFileUpload('imageActualite', $tagName);
				
				if(count($file->getErreurs()) == 0){
					
					$file->validFileUpload();

					if(count($file->getErreurs()) == 0){
						
						// On met à jour l'objet App avec le nouveau logo de l'application
						$urlAnciennceImage = $actualite->getUrlImageActualite();
						$actualite->hydrate(array(
							'urlImageActualite' => substr($file->getFilePath(), 14)
							));
						
						if(sizeof($actualite->getErreurs()) === 0){

							$fileDelete = $this->getApp()->getFileDelete();
							// On supprime la précédente image de l'actualité si ce n'est pas celle par defaut
							// On récupère le chemin url par defaut de l'image de l'actualité
							// On charge le fichier de configuration
							$config = $this->getApp()->getConfig();
							$pathImageDefault = $config->getVar('actualite', 'parametresImage', 'filePathDefault');
							$fileDelete = $this->getApp()->getFileDelete();
							if($urlAnciennceImage != $pathImageDefault){
								$fileDelete->deleteFile('../public_html'.$urlAnciennceImage);
							}


							if(count($fileDelete->getErreurs()) === 0){
								// S'il n'y a pas d'erreur, on enregistre les fichiers sources sur le serveur
								if($file->depositFileUpload(755)){

									//On compresse l'image
									$image = imagecreatefromjpeg($file->getFilePath());
									imagejpeg($image, $file->getFilePath(), $config->getVar('divers', 'divers', 'userBackgroundPixelcompression'));
									
									//On procède à la mise à jour dans la BDD de l'actualite
									$managerActualite->saveActualite($actualite);
									$user->getMessageClient()->addReussite('L\'image de présentation de l\'actualité a bien été modifiée.');
									
								}else{
									// On ajoute la variable d'erreurs
									$user->getMessageClient()->addErreur($file->getErreurs());	
								}
							}else{
								// On ajoute la variable d'erreurs
								$user->getMessageClient()->addErreur($fileDelete->getErreurs());
							}
						}else{
							$user->getMessageClient()->addErreur($actualite->getErreurs());
							
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

	
	// Méthode pour supprimer une actualité de la base
	public function executeSupprimerActualite($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		

		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des actualites
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idActualite = (int) $request->getPostData('idActualite');
			//On appelle le manager des Utilisateurs
			$managerActualite = $this->getManagers()->getManagerOf('Actualite');
			//On récupère l'actualite à administrer
			$actualite = $managerActualite->getActualiteById($idActualite);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($actualite === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des actualites
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Actualites/');
			}
			else{

				$fileDelete = $this->getApp()->getFileDelete();
				// On supprime la précédente image de l'actualité si ce n'est pas celle par defaut
				// On récupère le chemin url par defaut de l'image de l'actualité
				// On charge le fichier de configuration
				$config = $this->getApp()->getConfig();
				$pathImageDefault = $config->getVar('actualite', 'parametresImage', 'filePathDefault');
				$fileDelete = $this->getApp()->getFileDelete();
				if($actualite->getUrlImageActualite() != $pathImageDefault){
					$fileDelete->deleteFile('../public_html'.$actualite->getUrlImageActualite());
				}

				//On procède à la suppression dans la BDD de l'actualité
				$managerActualite->deleteActualite($actualite);
				$user->getMessageClient()->addReussite('L\'actualité a bien été supprimée.');

				// On retourne à la page des actualités
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Actualites/');
				
			}
		}
	}

}
