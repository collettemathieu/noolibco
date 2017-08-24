<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Ce controleur permet d'afficher, modifier ou créer de nouveaux		  |
// | articles		   			  										  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: controleur des articles
 * @access: public
 * @version: 1
 */	


namespace Applications\Backend\Modules\Articles;

class ArticlesController extends \Library\BackController{

	use \Library\Traits\FonctionsUniverselles;
	
	// Page principal de la gestion des articles
	public function executeShow($request){
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$response = $this->app->getHTTPResponse();
			$response->redirect('/ForAdminOnly/');
		}
		else{
			
			//On appelle le manager des Articles
			$managerArticle = $this->getManagers()->getManagerOf('Article');

			// On récupère la liste de tous les articles
			$articles = $managerArticle->getAllArticles();

			// On envoie la liste à la page
			$this->page->addVar('articles', $articles);

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

	// Page d'édition d'un article
	public function executeShowArticle($request){
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$response = $this->app->getHTTPResponse();
			$response->redirect('/ForAdminOnly/');
		}
		else{
			
			//On appelle le manager des Utilisateurs
			$managerArticle = $this->getManagers()->getManagerOf('Article');

			// Article demandé
			$idArticle = (int) $request->getGetData('idArticle');

			// On récupère l'article demandé
			$article = $managerArticle->getArticleById($idArticle);

			if($article instanceof \Library\Entities\Article){

				// On envoie la liste à la page
				$this->page->addVar('article', $article);

				// On récupère les différentes catégories des applications
				// On appelle le manager des Catégories
				$managerCategorie = $this->getManagers()->getManagerOf('Categorie');
				$categories = $managerCategorie->getAllCategories();
				// On créé la variable d'affichage à insérer dans la page.
				$categoriesAAfficher='';

				foreach($categories as $categorie){
				
					if($categorie->getIdCategorie() === $article->getCategorie()->getIdCategorie()){
						$categoriesAAfficher.='<option selected value="'.$categorie->getNomCategorie().'">'.$categorie->getNomCategorie().'</option>';
					}else{
						$categoriesAAfficher.='<option value="'.$categorie->getNomCategorie().'">'.$categorie->getNomCategorie().'</option>';
					}
				}

				// On ajoute la variable flèche menu à la page
				$this->page->addVar('categoriesAAfficher', $categoriesAAfficher);
			}else{
				$user->getMessageClient()->addErreur('L\'article que vous souhaitez consulter n\'existe pas.');
				// On retourne à la page des articles
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Articles/');
			}
		}
	}
	
	// Méthode pour créer un article
	public function executeCreerArticle($request){
		
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
			$categorie = $managerCategorie->getCatgeorieByName($request->getPostData('categorieArticle'));

			/***************************/
			/* CONTROLE DES MOTS-CLES  */
			/***************************/
			// On charge le fichier de configuration
			$config = $this->getApp()->getConfig();
			// On contrôle les mots-clés entrés par l'article			
			// On appelle la fonction multiexplode pour les mots-clés entrés par l'article
			$delimitateursRecherches = explode('|', $config->getVar('divers', 'divers', 'delimitateurMotsCles')); //Tableau des délimiteurs autorisés
			$motsClesEntreUtilisateur = $config->multiexplode($delimitateursRecherches,$request->getPostData('motClesArticle'));
			
			$tableauMotCles = array();
			foreach($motsClesEntreUtilisateur as $motcle){
				array_push($tableauMotCles, new \Library\Entities\MotCle(array('nomMotCle' => $motcle)));
			}

			// On récupère le chemin url par defaut de l'image de l'article
			$pathImageDefault = $config->getVar('article', 'parametresImage', 'filePathDefault');

			$newArticle = new \Library\Entities\Article(array(
				'titreArticle' => trim($request->getPostData('titreArticle')),
				'descriptionArticle' => trim($request->getPostData('descriptionArticle')),
				'texteArticle' => 'Votre texte ici...',
				'referencesArticle' => '',
				'auteur' => $auteur,
				'noteArticle' => (float) 0,
				'nbreVoteArticle' => 0,
				'nbreVueArticle' => 0,
				'motCles' => $tableauMotCles,
				'categorie' => $categorie,
				'urlImageArticle' => $pathImageDefault,
				'enLigneArticle' => false
			));
			
			if(sizeof($newArticle->getErreurs()) != 0){
				$user->getMessageClient()->addErreur($newArticle->getErreurs());
				$response->redirect('/ForAdminOnly/Articles/');
			}else{
				// On appelle les managers
				$managerMotCle = $this->getManagers()->getManagerOf('MotCle');
				$managerArticle = $this->getManagers()->getManagerOf('Article');

				$tableauMotClesWithId = array();
				foreach($tableauMotCles as $motcle){
					//tentative de récuperation du mot cle dans la base par son Nom
					$motCleBDD = $managerMotCle->getMotCleByName($motcle->getNomMotCle());
					//s'il n'existe pas, creation d'un nouveau mot cle que l'on ajoute ensuite directement dans la BDD
					if (!$motCleBDD){
						$motCleBDD = $managerMotCle->addMotCle($motcle);
					}
					array_push($tableauMotClesWithId, $motCleBDD);
				}
				$newArticle->hydrate(array(
					'motCles' => $tableauMotClesWithId
					));
				
				$managerArticle->addArticle($newArticle);
				$user->getMessageClient()->addReussite('L\'article a bien été créé.');
				
				$response->redirect('/ForAdminOnly/Articles/');
			}
			
		}
	}

	
	// Méthode pour publier/dépbulier un article
	public function executePublierDepublierArticle($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		

		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des articles
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idArticle = (int) $request->getPostData('idArticle');
			//On appelle le manager des Utilisateurs
			$managerArticle = $this->getManagers()->getManagerOf('Article');
			//On récupère l'article à administrer
			$article = $managerArticle->getArticleById($idArticle);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($article === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des articles
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Articles/');
			}
			else{
				if($article->getEnLigneArticle()){
					$article->hydrate(array(
						'enLigneArticle' => false,
						));
				}else{
					$article->hydrate(array(
						'enLigneArticle' => true,
						));
				}

				//On procède à la mise à jour dans la BDD de l'article
				$managerArticle->publishArticle($article);
				if($article->getEnLigneArticle()){
					$user->getMessageClient()->addReussite('L\'article a bien été publié.');
				}else{
					$user->getMessageClient()->addReussite('L\'article a bien été retiré.');
				}

				// On retourne à la page des articles
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Articles/id='.$article->getIdArticle());
				
			}
		}
	}

	// Méthode pour modifier le titre de l'article
	public function executeModifierTitreArticle($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des articles
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idArticle = (int) $request->getPostData('idArticle');
			//On appelle le manager des Utilisateurs
			$managerArticle = $this->getManagers()->getManagerOf('Article');
			//On récupère l'article à administrer
			$article = $managerArticle->getArticleById($idArticle);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($article === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des articles
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Articles/');
			}
			else{

				$nouveauTitre = $request->getPostData('nouveauTitre');
				
				$article->hydrate(array(
					'titreArticle' => $nouveauTitre,
					));

				if(sizeof($article->getErreurs()) === 0){

					//On procède à la mise à jour dans la BDD de l'article
					$managerArticle->saveArticle($article);
					$user->getMessageClient()->addReussite('Le titre de l\'article a bien été modifié.');
					
					// On retourne à la page de l'article
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Articles/id='.$article->getIdArticle());
				}else{
					$user->getMessageClient()->addErreur($article->getErreurs());
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Articles/id='.$article->getIdArticle());
				}
			}
		}
	}


	// Méthode pour modifier le texte de l'article
	public function executeModifierTexteArticle($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des articles
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idArticle = (int) $request->getPostData('idArticle');
			//On appelle le manager des Utilisateurs
			$managerArticle = $this->getManagers()->getManagerOf('Article');
			//On récupère l'article à administrer
			$article = $managerArticle->getArticleById($idArticle);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($article === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des articles
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Articles/');
			}
			else{

				$nouveauTexte = $request->getPostData('nouveauTexte');
				
				$article->hydrate(array(
					'texteArticle' => $nouveauTexte,
					));

				if(sizeof($article->getErreurs()) === 0){

					//On procède à la mise à jour dans la BDD de l'article
					$managerArticle->saveArticle($article);
					$user->getMessageClient()->addReussite('Le texte de l\'article a bien été modifié.');
					
					// On retourne à la page de l'article
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Articles/id='.$article->getIdArticle());
				}else{
					$user->getMessageClient()->addErreur($article->getErreurs());
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Articles/id='.$article->getIdArticle());
				}
			}
		}
	}


	// Méthode pour mettre à jour les références de l'article
	public function executeModifierReferencesArticle($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des articles
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idArticle = (int) $request->getPostData('idArticle');
			//On appelle le manager des Utilisateurs
			$managerArticle = $this->getManagers()->getManagerOf('Article');
			//On récupère l'article à administrer
			$article = $managerArticle->getArticleById($idArticle);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($article === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des articles
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Articles/');
			}
			else{

				$nouvellesReferences = $request->getPostData('nouvellesReferences');

				$article->hydrate(array(
					'referencesArticle' => $nouvellesReferences,
					));

				if(sizeof($article->getErreurs()) === 0){

					//On procède à la mise à jour dans la BDD de l'article
					$managerArticle->saveArticle($article);
					$user->getMessageClient()->addReussite('Les references de l\'article ont bien été mises à jour.');
					
					// On retourne à la page de l'article
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Articles/id='.$article->getIdArticle());
				}else{
					$user->getMessageClient()->addErreur($article->getErreurs());
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Articles/id='.$article->getIdArticle());
				}
			}
		}
	}


	// Méthode pour sauvegarder avec CMD+S
	public function executeSaveJSArticle($request){

		//On récupère la requête du client
		$user = $this->app->getUser();

		// On informe que c'est un chargement Ajax
		$user->setAjax(true);
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
		}else{
			$idArticle = (int) $request->getPostData('idArticle');
			//On appelle le manager des Utilisateurs
			$managerArticle = $this->getManagers()->getManagerOf('Article');
			//On récupère le article à administrer
			$article = $managerArticle->getArticleById($idArticle);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($article === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
			}
			else{

				$nouveauTexte = $request->getPostData('nouveauTexte');
				$nouvellesReferences = $request->getPostData('nouvellesReferences');
				
				$article->hydrate(array(
					'texteArticle' => $nouveauTexte,
					'referencesArticle' => $nouvellesReferences
					));

				if(sizeof($article->getErreurs()) === 0){

					//On procède à la mise à jour dans la BDD de l'article
					$managerArticle->saveArticle($article);
					$user->getMessageClient()->addReussite('L\'article a bien été mis à jour.');
					
				}else{
					$user->getMessageClient()->addErreur($article->getErreurs());
				}
			}
		}
	}


	// Méthode pour modifier l'image de présentation de l'article
	public function executeModifierImageArticle($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On informe que c'est un chargement Ajax
		$user->setAjax(true);

		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
		
		}else{
			$idArticle = (int) $request->getPostData('idArticle');
			//On appelle le manager des Utilisateurs
			$managerArticle = $this->getManagers()->getManagerOf('Article');
			//On récupère l'article à administrer
			$article = $managerArticle->getArticleById($idArticle);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($article === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
			
			}
			else{

				/*************************/
				/* CONTROLE DE L'IMAGE   */
				/*************************/
				// On charge l'objet File avec la configuration de l'image de l'article
				
				$tagName= array( 'categorie' => 'article', 'sousCategorie' => 'parametresImage');
				$file = $this->getApp()->getFileUpload('imageArticle', $tagName);
				
				if(count($file->getErreurs()) == 0){
					
					$file->validFileUpload();

					if(count($file->getErreurs()) == 0){

						// On charge le fichier de configuration
						$config = $this->getApp()->getConfig();
						$filePathMiniature = $config->getVar('article', 'parametresImage', 'filePathMiniature');
						// On sauvegarde l'ancienne image
						$urlAncienneImage = $article->getUrlImageArticle();
						$urlAncienneImageMiniature = $article->getUrlImageMiniatureArticle();
						// On met à jour l'objet Article avec la nouvelle image
						$article->hydrate(array(
							'urlImageArticle' => $file->getFilePath(),
							'urlImageMiniatureArticle' => $filePathMiniature.$file->getFileName()
							));
						
						if(sizeof($article->getErreurs()) === 0){


							// On supprime la précédente image de l'article si ce n'est pas celle par defaut
							// On récupère le chemin url par defaut de l'image de l'article
							$pathImageDefault = $config->getVar('article', 'parametresImage', 'filePathDefault');
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
									$fileSize = $config->getVar('article', 'parametresImage', 'fileSize');
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

									$fileSizeMiniature = $config->getVar('article', 'parametresImage', 'fileSizeMiniature');
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
								
									//On procède à la mise à jour dans la BDD de l'article
									$managerArticle->saveArticle($article);
									$user->getMessageClient()->addReussite('L\'image de présentation de l\'article a bien été modifiée.');
									
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
							$user->getMessageClient()->addErreur($article->getErreurs());
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

	// Méthode pour modifier la catégorie de l'article
	public function executeModifierCategorieArticle($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des articles
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idArticle = (int) $request->getPostData('idArticle');
			//On appelle le manager des Utilisateurs
			$managerArticle = $this->getManagers()->getManagerOf('Article');
			//On récupère l'article à administrer
			$article = $managerArticle->getArticleById($idArticle);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($article === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des articles
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Articles/');
			}
			else{

				// On crée l'objet Categorie à partir de la base de données. Si celui-ci n'existe pas, on le créé dans la base de données.
				$managerCategorie = $this->getManagers()->getManagerOf('Categorie');
				$categorie = $managerCategorie->getCatgeorieByName($request->getPostData('nouvelleCategorieArticle'));


				$article->hydrate(array(
					'categorie' => $categorie,
					));

				if(sizeof($article->getErreurs()) === 0){

					//On procède à la mise à jour dans la BDD de l'article
					$managerArticle->saveArticle($article);
					$user->getMessageClient()->addReussite('La catégorie de l\'article a bien été modifiée.');
					
					// On retourne à la page de l'article
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Articles/id='.$article->getIdArticle());
				}else{
					$user->getMessageClient()->addErreur($article->getErreurs());
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Articles/id='.$article->getIdArticle());
				}
			}
		}
	}


	// Méthode pour modifier la description de l'article
	public function executeModifierDescriptionArticle($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des articles
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idArticle = (int) $request->getPostData('idArticle');
			//On appelle le manager des Utilisateurs
			$managerArticle = $this->getManagers()->getManagerOf('Article');
			//On récupère l'article à administrer
			$article = $managerArticle->getArticleById($idArticle);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($article === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des articles
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Articles/');
			}
			else{

				$nouvelleDescription = $request->getPostData('nouvelleDescription');
				
				$article->hydrate(array(
					'descriptionArticle' => $nouvelleDescription,
					));

				if(sizeof($article->getErreurs()) === 0){

					//On procède à la mise à jour dans la BDD de l'article
					$managerArticle->saveArticle($article);
					$user->getMessageClient()->addReussite('La description de l\'article a bien été modifiée.');
					
					// On retourne à la page de l'article
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Articles/id='.$article->getIdArticle());
				}else{
					$user->getMessageClient()->addErreur($article->getErreurs());
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Articles/id='.$article->getIdArticle());
				}
			}
		}
	}

	// Méthode pour modifier les mots-clés de l'article
	public function executeModifierMotClesArticle($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des articles
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idArticle = (int) $request->getPostData('idArticle');
			//On appelle le manager des Utilisateurs
			$managerArticle = $this->getManagers()->getManagerOf('Article');
			//On récupère l'article à administrer
			$article = $managerArticle->getArticleById($idArticle);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($article === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des articles
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Articles/');
			}
			else{

				// On charge le fichier de configuration
				$config = $this->getApp()->getConfig();

				/***************************/
				/* CONTROLE DES MOTS-CLES   */
				/***************************/
				// On contrôle les mots-clés entrés par l'utilisateur			
				// On appelle la fonction multiexplode pour les mots-clés entrés par l'utilisateur
				$delimitateursRecherches = explode('|', $config->getVar('divers', 'divers', 'delimitateurMotsCles')); //Tableau des délimiteurs autorisés
				$motsClesEntreUtilisateur = $config->multiexplode($delimitateursRecherches,$request->getPostData('nouveauxMotsCles'));

				/***************************/
				/* GESTION DES MOTS-CLES   */
				/***************************/

				// On appelle les managers
				$managerMotCle = $this->getManagers()->getManagerOf('MotCle');
				$managerArticleMotCle = $this->getManagers()->getManagerOf('ArticleMotCle');
				//Creation des mots clés que l'on insère dans la BDD s'il n'existe pas encore
				$tableauMotClesWithId = array();
				foreach($motsClesEntreUtilisateur as $motcle){
					//tentative de récuperation du mot cle dans la base par son Nom
					$motCleBDD = $managerMotCle->getMotCleByName($motcle);
					//s'il n'existe pas, creation d'un nouveau mot cle que l'on ajoute ensuite directement dans la BDD
					if (!$motCleBDD){
						$motCleBDD = new \Library\Entities\MotCle(array('nomMotCle' => $motcle));
						$managerMotCle->addMotCle($motCleBDD);
					}
					array_push($tableauMotClesWithId, $motCleBDD);
				}

				// On supprime les mots-clés qui ne sont plus en lien avec l'article
				foreach($article->getMotCles() as $motcle){
					if(!in_array($motcle->getNomMotCle(), $motsClesEntreUtilisateur)){
						$ArticleMotCle = new \Library\Entities\ArticleMotCle(array(
							'motCle' => $motcle,
							'article' => $article
							));
						$managerArticleMotCle->deleteArticleMotCle($ArticleMotCle);
					}
				}

				$article->hydrate(array(
					'motCles' => $tableauMotClesWithId,
					));

				if(sizeof($article->getErreurs()) === 0){

					//On procède à la mise à jour dans la BDD de l'article
					$managerArticle->saveArticle($article);
					$user->getMessageClient()->addReussite('Les mots-clés de l\'article a bien été modifiés.');
					
					// On retourne à la page de l'article
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Articles/id='.$article->getIdArticle());
				}else{
					$user->getMessageClient()->addErreur($article->getErreurs());
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Articles/id='.$article->getIdArticle());
				}
			}
		}
	}
	
	// Méthode pour supprimer un article de la base
	public function executeSupprimerArticle($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		

		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des articles
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idArticle = (int) $request->getPostData('idArticle');
			//On appelle le manager des Utilisateurs
			$managerArticle = $this->getManagers()->getManagerOf('Article');
			//On récupère l'article à administrer
			$article = $managerArticle->getArticleById($idArticle);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($article === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des articles
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Articles/');
			}
			else{

				// On supprime la précédente image de l'article si ce n'est pas celle par defaut
				// On récupère le chemin url par defaut de l'image de l'article
				// On charge le fichier de configuration
				$config = $this->getApp()->getConfig();
				$pathImageDefault = $config->getVar('article', 'parametresImage', 'filePathDefault');
				$fileDelete = $this->getApp()->getFileDelete();
				if($article->getUrlImageArticle() != $pathImageDefault){
					$fileDelete->deleteFile($article->getUrlImageArticle());
					$fileDelete->deleteFile($article->getUrlImageMiniatureArticle());
				}

				//On procède à la suppression dans la BDD de l'article
				$managerArticle->deleteArticle($article);
				$user->getMessageClient()->addReussite('L\'article a bien été supprimé.');

				// On retourne à la page des articles
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Articles/');
				
			}
		}
	}

	/**
	* Méthode pour rechercher une référence
	*/
	public function executeSeekReference($request){
		
		//On récupère la requête du client
		$user = $this->app->getUser();

		// On informe que c'est un chargement Ajax
		$user->setAjax(true);

		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
		}else{

			// On récupère la recherche
			$reqPublication = $request->getPostData('reqPublication');

			$results = $this->executeRequestPublication($reqPublication);

			if($results){
				// On ajoute la variable results à la page
				$this->page->addVar('results', $results);
			}
		}

	}

	/**
    * Permet de poster sur les réseaux sociaux.
    **/
    public function executePostOnSocialNetworks($request){
        
        $user = $this->app->getUser();
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des articles
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{

			//On récupère l'id de l'article
			$idArticle = (int) $request->getGetData('idArticle');
			
			//On appelle le manager des Articles
			$managerArticle = $this->getManagers()->getManagerOf('Article');

			$article = $managerArticle->getArticleById($idArticle);
			
			//Si l'article existe
			if($article instanceof \Library\Entities\Article){

				$user->setFlash($article);

	            $socialMediaApplication = new \Applications\ApplicationsStandAlone\SocialMedia\SocialMediaApplication;
	            $socialMediaApplication->execute('AutoPost', 'login'); // Module = AutoPost ; action = login
	        	
	        }
        }
    }

	/**
    * Permet d'annoncer l'activation de l'application sur Facebook.
    **/
    public function executeProcessFacebookApplication($request){
        
        $user = $this->app->getUser();
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des articles
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
            $socialMediaApplication = new \Applications\ApplicationsStandAlone\SocialMedia\SocialMediaApplication;
            $socialMediaApplication->execute('AutoPost', 'process');
        }
    }

}
