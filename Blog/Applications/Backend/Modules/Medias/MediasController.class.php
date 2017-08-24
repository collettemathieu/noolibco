<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Ce controleur permet d'importer, modifier ou supprimer des			  |
// | données multimédias (images, vidéos, ...)							  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: controleur des médias
 * @access: public
 * @version: 1
 */	


namespace Applications\Backend\Modules\Medias;

class MediasController extends \Library\BackController{
	
	// Page principal de la gestion des médias
	public function executeShow($request){
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$response = $this->app->getHTTPResponse();
			$response->redirect('/ForAdminOnly/');
		}
		else{
			//On appelle le manager des Medias
			$managerMedia = $this->getManagers()->getManagerOf('Media');

			// On récupère la liste de tous les actualites
			$medias = $managerMedia->getAllMedias();

			// On envoie la liste à la page
			$this->page->addVar('medias', $medias);
		}
	}

	// Récupérer l'ensemble des médias
	public function executePickUpMedias($request){
		//On récupère la requête du client
		$user = $this->app->getUser();

		// On informe que c'est un chargement Ajax
		$user->setAjax(true);
		
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
		}
		else{
			//On appelle le manager des Medias
			$managerMedia = $this->getManagers()->getManagerOf('Media');

			// On récupère la liste de tous les actualites
			$medias = $managerMedia->getAllMedias();

			// On envoie la liste à la page
			$this->page->addVar('medias', $medias);
		}
	}

	// Page d'édition d'un média
	public function executeShowMedia($request){
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$response = $this->app->getHTTPResponse();
			$response->redirect('/ForAdminOnly/');
		}
		else{
			
			//On appelle le manager des medias
			$managerMedia = $this->getManagers()->getManagerOf('Media');

			// Actualité demandée
			$idMedia = (int) $request->getGetData('idMedia');

			// On récupère le media demandé
			$media = $managerMedia->getMediaById($idMedia);

			if($media instanceof \Library\Entities\Media){

				// On envoie la liste à la page
				$this->page->addVar('media', $media);

			}else{
				$user->getMessageClient()->addErreur('Le media que vous souhaitez consulter n\'existe pas.');
				// On retourne à la page des actualites
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Medias/');
			}
		}
	}
	

	// Méthode pour importer un média
	public function executeImporterMedia($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On informe que c'est un chargement Ajax
		$user->setAjax(true);
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
		}else{
			/***********************/
			/* CONTROLE DU MEDIA   */
			/***********************/

			// On charge le fichier de configuration
			$config = $this->getApp()->getConfig();

			// On charge l'objet File avec la configuration du media
			$tagName= array( 'categorie' => 'media', 'sousCategorie' => 'parametresImage');
			$file = $this->getApp()->getFileUpload('media', $tagName);
			
			if(count($file->getErreurs()) == 0){
				
				$file->validFileUpload();

				if(count($file->getErreurs()) == 0){
					
					// On crée l'objet Media
					$filePathMiniature = $config->getVar('media', 'parametresImage', 'filePathMiniature');
					$media = new \Library\Entities\Media(array(
						'urlMedia' => $file->getFilePath(),
						'urlMediaMiniature' => $filePathMiniature.$file->getFileName()
						));
					
					if(sizeof($media->getErreurs()) === 0){

						// S'il n'y a pas d'erreur, on enregistre les fichiers sources sur le serveur
						if($file->depositFileUpload(755)){

							/**
							* Compression et modification de la résolution pour l'image principale
							*/
							$fileSize = $config->getVar('media', 'parametresImage', 'fileSize');
							
							$source = imagecreatefromjpeg($file->getFilePath());
							
							$largeur_source = imagesx($source);
							$hauteur_source = imagesy($source);
							
							$largeur_destination = $fileSize;
							$hauteur_destination = $hauteur_source*$fileSize/$largeur_source;

							$destination = imagecreatetruecolor($largeur_destination,$hauteur_destination);
							
							/*On préserve la transparence*/
							imagealphablending($destination, false);
							imagesavealpha($destination, true);

							/*On diminue la résolution et on compresse*/
							imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);
							imagejpeg($destination, $file->getFilePath(), $config->getVar('divers', 'divers', 'userBackgroundPixelcompression'));

							/**
							* On crée une image miniature 256*xxx de l'image principale
							*/

							$fileSizeMiniature = $config->getVar('media', 'parametresImage', 'fileSizeMiniature');
							$source = imagecreatefromjpeg($file->getFilePath());
							
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
							
							imagejpeg($destination, $filePathMiniature.$file->getFileName());
							
							// On appelle les managers
							$managerMedia = $this->getManagers()->getManagerOf('Media');
							//On procède à la mise à jour dans la BDD du media
							$managerMedia->addMedia($media);
							$user->getMessageClient()->addReussite('Le média a bien été ajouté.');
							
						}else{
							// On ajoute la variable d'erreurs
							$user->getMessageClient()->addErreur($file->getErreurs());
							
						}
					}else{
						$user->getMessageClient()->addErreur($media->getErreurs());
						
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

	
	// Méthode pour supprimer un média de la base
	public function executeSupprimerMedia($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		

		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des medias
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idMedia = (int) $request->getPostData('idMedia');
			//On appelle le manager des Médias
			$managerMedia = $this->getManagers()->getManagerOf('Media');
			//On récupère le media à administrer
			$media = $managerMedia->getMediaById($idMedia);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($media === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des medias
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Medias/');
			}
			else{

				$fileDelete = $this->getApp()->getFileDelete();
				// On supprime le media et sa miniature
				$fileDelete = $this->getApp()->getFileDelete();
				$fileDelete->deleteFile($media->getUrlMedia());
				$fileDelete->deleteFile($media->getUrlMediaMiniature());

				//On procède à la suppression dans la BDD du media
				$managerMedia->deleteMedia($media);
				$user->getMessageClient()->addReussite('Le media a bien été supprimé.');

				// On retourne à la page des medias
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Medias/');
				
			}
		}
	}

}
