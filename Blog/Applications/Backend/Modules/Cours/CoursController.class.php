<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Ce controleur permet d'afficher, modifier ou créer de nouveaux		  |
// | cours		   			  										  	  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: controleur des cours
 * @access: public
 * @version: 1
 */	


namespace Applications\Backend\Modules\Cours;

class CoursController extends \Library\BackController{

	use \Library\Traits\FonctionsUniverselles;

	
	// Page principal de la gestion des cours
	public function executeShow($request){
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$response = $this->app->getHTTPResponse();
			$response->redirect('/ForAdminOnly/');
		}
		else{
			
			//On appelle les managers
			$managerCours = $this->getManagers()->getManagerOf('Cours');
			
			// On récupère la liste de tous les cours
			$cours = $managerCours->getAllCours();

			// On envoie la liste à la page
			$this->page->addVar('cours', $cours);

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

			// On récupère les différentes cours globaux
			// On appelle le manager
			$managerCoursGlobal = $this->getManagers()->getManagerOf('CoursGlobal');
			$coursGlobaux = $managerCoursGlobal->getAllCoursGlobal();
			// On créé la variable d'affichage à insérer dans la page.
			$coursGlobauxAAfficher='<option value="0">Aucun</option>';

			foreach($coursGlobaux as $cours){
			
				$coursGlobauxAAfficher.='<option value="'.$cours->getIdCoursGlobal().'">'.$cours->getTitreCoursGlobal().'</option>';
				
			}

			// On ajoute la variable coursGlobauxAAfficher à la page
			$this->page->addVar('coursGlobauxAAfficher', $coursGlobauxAAfficher);

		}
	}

	// Page d'édition d'un cours
	public function executeShowCours($request){
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$response = $this->app->getHTTPResponse();
			$response->redirect('/ForAdminOnly/');
		}
		else{
			
			//On appelle le manager des Utilisateurs
			$managerCours = $this->getManagers()->getManagerOf('Cours');

			// Cours demandé
			$idCours = (int) $request->getGetData('idCours');

			// On récupère le cours demandé
			$cours = $managerCours->getCoursById($idCours);
			$cours = $managerCours->putCoursGlobalInCours($cours);

			if($cours instanceof \Library\Entities\Cours){

				// On envoie la liste à la page
				$this->page->addVar('cours', $cours);

				// On récupère les différentes catégories des applications
				// On appelle le manager des Catégories
				$managerCategorie = $this->getManagers()->getManagerOf('Categorie');
				$categories = $managerCategorie->getAllCategories();
				// On créé la variable d'affichage à insérer dans la page.
				$categoriesAAfficher='';

				foreach($categories as $categorie){
					if($categorie->getIdCategorie() === $cours->getCategorie()->getIdCategorie()){
						$categoriesAAfficher.='<option selected value="'.$categorie->getNomCategorie().'">'.$categorie->getNomCategorie().'</option>';
					}else{
						$categoriesAAfficher.='<option value="'.$categorie->getNomCategorie().'">'.$categorie->getNomCategorie().'</option>';
					}
				}

				// On ajoute la variable flèche menu à la page
				$this->page->addVar('categoriesAAfficher', $categoriesAAfficher);

				// On récupère les différentes cours globaux
				// On appelle le manager
				$managerCoursGlobal = $this->getManagers()->getManagerOf('CoursGlobal');
				$coursGlobaux = $managerCoursGlobal->getAllCoursGlobal();
				// On créé la variable d'affichage à insérer dans la page.
				$coursGlobauxAAfficher='<option value="0">Aucun</option>';

				foreach($coursGlobaux as $coursGlobal){
					
					if($cours->getCoursGlobal() instanceof \Library\Entities\CoursGlobal && $cours->getCoursGlobal()->getIdCoursGlobal() === $coursGlobal->getIdCoursGlobal()){
						$coursGlobauxAAfficher.='<option selected value="'.$coursGlobal->getIdCoursGlobal().'">'.$coursGlobal->getTitreCoursGlobal().'</option>';
					}else{
						$coursGlobauxAAfficher.='<option value="'.$coursGlobal->getIdCoursGlobal().'">'.$coursGlobal->getTitreCoursGlobal().'</option>';
					}
				}

				// On ajoute la variable coursGlobauxAAfficher à la page
				$this->page->addVar('coursGlobauxAAfficher', $coursGlobauxAAfficher);
			}else{
				$user->getMessageClient()->addErreur('Le cours que vous souhaitez consulter n\'existe pas.');
				// On retourne à la page des cours
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Cours/');
			}
		}
	}
	
	// Méthode pour créer un cours
	public function executeCreerCours($request){
		
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
			$categorie = $managerCategorie->getCatgeorieByName($request->getPostData('categorieCours'));

			/***************************/
			/* CONTROLE DES MOTS-CLES  */
			/***************************/
			// On charge le fichier de configuration
			$config = $this->getApp()->getConfig();
			// On contrôle les mots-clés entrés par le cours			
			// On appelle la fonction multiexplode pour les mots-clés entrés par le cours
			$delimitateursRecherches = explode('|', $config->getVar('divers', 'divers', 'delimitateurMotsCles')); //Tableau des délimiteurs autorisés
			$motsClesEntreUtilisateur = $config->multiexplode($delimitateursRecherches,$request->getPostData('motClesCours'));
			
			$tableauMotCles = array();
			foreach($motsClesEntreUtilisateur as $motcle){
				array_push($tableauMotCles, new \Library\Entities\MotCle(array('nomMotCle' => $motcle)));
			}

			// On récupère le chemin url par defaut de l'image du cours
			$pathImageDefault = $config->getVar('cours', 'parametresImage', 'filePathDefault');

			$newCours = new \Library\Entities\Cours(array(
				'titreCours' => trim($request->getPostData('titreCours')),
				'descriptionCours' => trim($request->getPostData('descriptionCours')),
				'texteCours' => 'Votre texte ici...',
				'referencesCours' => '',
				'auteur' => $auteur,
				'noteCours' => (float) 0,
				'nbreVoteCours' => 0,
				'nbreVueCours' => 0,
				'motCles' => $tableauMotCles,
				'categorie' => $categorie,
				'urlImageCours' => $pathImageDefault,
				'enLigneCours' => false
			));
			
			if(sizeof($newCours->getErreurs()) != 0){
				$user->getMessageClient()->addErreur($newCours->getErreurs());
				$response->redirect('/ForAdminOnly/Cours/');
			}else{
				// On appelle les managers
				$managerMotCle = $this->getManagers()->getManagerOf('MotCle');
				$managerCours = $this->getManagers()->getManagerOf('Cours');

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
				$newCours->hydrate(array(
					'motCles' => $tableauMotClesWithId
					));
				
				$cours = $managerCours->addCours($newCours);

				if($request->getPostData('idCoursGlobal') != 0){
					$managerCoursGlobal = $this->getManagers()->getManagerOf('CoursGlobal');
					$coursGlobal = $managerCoursGlobal->getCoursGlobalById((int) $request->getPostData('idCoursGlobal'));
					if($coursGlobal instanceof \Library\Entities\CoursGlobal){
						$newCoursCoursGlobal = new \Library\Entities\CoursCoursGlobal(array(
							'cours' => $cours,
							'coursGlobal' => $coursGlobal
						));
						if(sizeof($newCoursCoursGlobal->getErreurs()) != 0){
							$user->getMessageClient()->addErreur($newCoursCoursGlobal->getErreurs());
						}else{
							$managerCoursCoursGlobal = $this->getManagers()->getManagerOf('CoursCoursGlobal');
							$managerCoursCoursGlobal->addCoursCoursGlobal($newCoursCoursGlobal);

							$user->getMessageClient()->addReussite('Le cours a bien été créé et a été rattaché au cours global.');
						}

					}else{
						$user->getMessageClient()->addErreur('Le cours global que vous demandez n\'existe pas');
					}
				}else{
					$user->getMessageClient()->addReussite('Le cours a bien été créé.');
				}
				
				$response->redirect('/ForAdminOnly/Cours/');
			}
			
		}
	}


	// Méthode pour mettre à jour le sommaire et les numéros de figure du cours
	public function executeMAJSommaireFigureCours($request){

		//On récupère la requête du client
		$user = $this->app->getUser();

		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des cours
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idCours = (int) $request->getGetData('idCours');
			//On appelle le manager des Utilisateurs
			$managerCours = $this->getManagers()->getManagerOf('Cours');
			//On récupère le cours à administrer
			$cours = $managerCours->getCoursById($idCours);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($cours === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des cours
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Cours/');
			}
			else{
				$texte = $cours->getTexteCours();
				$tmp = get_html_translation_table(HTML_ENTITIES);
				$tmp = array_flip ($tmp);
				$texte = strtr ($texte, $tmp);
				
				// On met à jour les titres du sommaire
				$compteur = 0;
				$texte = preg_replace_callback('#<h1( id=".+"){0,1}>([0-9]. ){0,1}(.+)</h1>#', function($matches){
					global $compteur;
					++$compteur;
					$id = $this->cleanTitreSommaire($matches[3]);
					return '<h1 id="'.$id.'">'.$compteur.'. '.$matches[3].'</h1>';
				}, $texte);

				$sommaire = '';
				preg_match_all('#<h1 id="(.+)">(.+)</h1>#', $texte, $matches);
				$ids = $matches[1];
				$titres = $matches[2];
				foreach ($titres as $key => $titre) {
					$sommaire.= '<a href="#'.$ids[$key].'">'.$titre.'</a><br/>';
				}

				// On met à jour les numéros des figures
				$compteurFigure = 0;
				$texte = preg_replace_callback('#{C}(Figure [0-9]+)#', function($matches){
					global $compteurFigure;
					++$compteurFigure;
					return '{C}Figure '.$compteurFigure;
				}, $texte);

				$cours->hydrate(array(
					'texteCours' => $texte,
					'sommaireCours' => $sommaire
				));

				if(sizeof($cours->getErreurs()) === 0){
					//On procède à la mise à jour dans la BDD du cours
					$managerCours->saveSommaireCours($cours);
					$user->getMessageClient()->addReussite('Le sommaire et les figures du cours ont bien été mises à jour.');
				}else{
					$user->getMessageClient()->addErreur($cours->getErreurs());
				}
				// On retourne à la page des cours
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Cours/id='.$cours->getIdCours());		
			}
		}
	}

	
	// Méthode pour publier/dépbulier un cours
	public function executePublierDepublierCours($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		

		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des cours
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idCours = (int) $request->getPostData('idCours');
			//On appelle le manager des Utilisateurs
			$managerCours = $this->getManagers()->getManagerOf('Cours');
			//On récupère le cours à administrer
			$cours = $managerCours->getCoursById($idCours);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($cours === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des cours
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Cours/');
			}
			else{
				if($cours->getEnLigneCours()){
					$cours->hydrate(array(
						'enLigneCours' => false,
						));
				}else{
					$cours->hydrate(array(
						'enLigneCours' => true,
						));
				}

				//On procède à la mise à jour dans la BDD du cours
				$managerCours->publishCours($cours);
				if($cours->getEnLigneCours()){
					$user->getMessageClient()->addReussite('Le cours a bien été publié.');
				}else{
					$user->getMessageClient()->addReussite('Le cours a bien été retiré.');
				}

				// On retourne à la page des cours
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Cours/id='.$cours->getIdCours());
				
			}
		}
	}

	// Méthode pour modifier le titre du cours
	public function executeModifierTitreCours($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des cours
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idCours = (int) $request->getPostData('idCours');
			//On appelle le manager des Utilisateurs
			$managerCours = $this->getManagers()->getManagerOf('Cours');
			//On récupère le cours à administrer
			$cours = $managerCours->getCoursById($idCours);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($cours === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des cours
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Cours/');
			}
			else{

				$nouveauTitre = $request->getPostData('nouveauTitre');
				
				$cours->hydrate(array(
					'titreCours' => $nouveauTitre,
					));

				if(sizeof($cours->getErreurs()) === 0){

					//On procède à la mise à jour dans la BDD du cours
					$managerCours->saveCours($cours);
					$user->getMessageClient()->addReussite('Le titre du cours a bien été modifié.');
					
					// On retourne à la page du cours
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Cours/id='.$cours->getIdCours());
				}else{
					$user->getMessageClient()->addErreur($cours->getErreurs());
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Cours/id='.$cours->getIdCours());
				}
			}
		}
	}


	// Méthode pour modifier le texte du cours
	public function executeModifierTexteCours($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des cours
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idCours = (int) $request->getPostData('idCours');
			//On appelle le manager des Utilisateurs
			$managerCours = $this->getManagers()->getManagerOf('Cours');
			//On récupère le cours à administrer
			$cours = $managerCours->getCoursById($idCours);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($cours === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des cours
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Cours/');
			}
			else{

				$nouveauTexte = $request->getPostData('nouveauTexte');
				
				$cours->hydrate(array(
					'texteCours' => $nouveauTexte,
					));

				if(sizeof($cours->getErreurs()) === 0){

					//On procède à la mise à jour dans la BDD du cours
					$managerCours->saveCours($cours);
					$user->getMessageClient()->addReussite('Le texte du cours a bien été modifié.');
					
					// On retourne à la page du cours
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Cours/id='.$cours->getIdCours());
				}else{
					$user->getMessageClient()->addErreur($cours->getErreurs());
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Cours/id='.$cours->getIdCours());
				}
			}
		}
	}


	// Méthode pour mettre à jour les références du cours
	public function executeModifierReferencesCours($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des cours
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idCours = (int) $request->getPostData('idCours');
			//On appelle le manager des Utilisateurs
			$managerCours = $this->getManagers()->getManagerOf('Cours');
			//On récupère le cours à administrer
			$cours = $managerCours->getCoursById($idCours);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($cours === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des cours
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Cours/');
			}
			else{

				$nouvellesReferences = $request->getPostData('nouvellesReferences');

				$cours->hydrate(array(
					'referencesCours' => $nouvellesReferences,
					));

				if(sizeof($cours->getErreurs()) === 0){

					//On procède à la mise à jour dans la BDD du cours
					$managerCours->saveCours($cours);
					$user->getMessageClient()->addReussite('Les references du cours ont bien été mises à jour.');
					
					// On retourne à la page du cours
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Cours/id='.$cours->getIdCours());
				}else{
					$user->getMessageClient()->addErreur($cours->getErreurs());
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Cours/id='.$cours->getIdCours());
				}
			}
		}
	}


	// Méthode pour sauvegarder avec CMD+S
	public function executeSaveJSCours($request){

		//On récupère la requête du client
		$user = $this->app->getUser();

		// On informe que c'est un chargement Ajax
		$user->setAjax(true);
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
		}else{
			$idCours = (int) $request->getPostData('idCours');
			//On appelle le manager des Utilisateurs
			$managerCours = $this->getManagers()->getManagerOf('Cours');
			//On récupère le cours à administrer
			$cours = $managerCours->getCoursById($idCours);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($cours === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
			}
			else{

				$nouveauTexte = $request->getPostData('nouveauTexte');
				$nouvellesReferences = $request->getPostData('nouvellesReferences');
				
				$cours->hydrate(array(
					'texteCours' => $nouveauTexte,
					'referencesCours' => $nouvellesReferences
					));

				if(sizeof($cours->getErreurs()) === 0){

					//On procède à la mise à jour dans la BDD du cours
					$managerCours->saveCours($cours);
					$user->getMessageClient()->addReussite('Le cours a bien été mis à jour.');
					
				}else{
					$user->getMessageClient()->addErreur($cours->getErreurs());
				}
			}
		}
	}




	// Méthode pour modifier l'image de présentation du cours
	public function executeModifierImageCours($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On informe que c'est un chargement Ajax
		$user->setAjax(true);

		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
		
		}else{
			$idCours = (int) $request->getPostData('idCours');
			//On appelle le manager des Utilisateurs
			$managerCours = $this->getManagers()->getManagerOf('Cours');
			//On récupère le cours à administrer
			$cours = $managerCours->getCoursById($idCours);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($cours === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
			
			}
			else{

				/*************************/
				/* CONTROLE DE L'IMAGE   */
				/*************************/
				// On charge l'objet File avec la configuration de l'image du cours
				
				$tagName= array( 'categorie' => 'cours', 'sousCategorie' => 'parametresImage');
				$file = $this->getApp()->getFileUpload('imageCours', $tagName);
				
				if(count($file->getErreurs()) == 0){
					
					$file->validFileUpload();

					if(count($file->getErreurs()) == 0){

						// On charge le fichier de configuration
						$config = $this->getApp()->getConfig();
						$filePathMiniature = $config->getVar('cours', 'parametresImage', 'filePathMiniature');
						// On sauvegarde l'ancienne image
						$urlAncienneImage = $cours->getUrlImageCours();
						$urlAncienneImageMiniature = $cours->getUrlImageMiniatureCours();
						// On met à jour l'objet Cours avec la nouvelle image
						$cours->hydrate(array(
							'urlImageCours' => $file->getFilePath(),
							'urlImageMiniatureCours' => $filePathMiniature.$file->getFileName()
							));
						
						if(sizeof($cours->getErreurs()) === 0){


							// On supprime la précédente image du cours si ce n'est pas celle par defaut
							// On récupère le chemin url par defaut de l'image du cours
							$pathImageDefault = $config->getVar('cours', 'parametresImage', 'filePathDefault');
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
									$fileSize = $config->getVar('cours', 'parametresImage', 'fileSize');
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

									$fileSizeMiniature = $config->getVar('cours', 'parametresImage', 'fileSizeMiniature');
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
								
									//On procède à la mise à jour dans la BDD du cours
									$managerCours->saveCours($cours);
									$user->getMessageClient()->addReussite('Le image de présentation du cours a bien été modifiée.');
									
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
							$user->getMessageClient()->addErreur($cours->getErreurs());
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

	// Méthode pour modifier la catégorie du cours
	public function executeModifierCategorieCours($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des cours
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idCours = (int) $request->getPostData('idCours');
			//On appelle le manager des Utilisateurs
			$managerCours = $this->getManagers()->getManagerOf('Cours');
			//On récupère le cours à administrer
			$cours = $managerCours->getCoursById($idCours);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($cours === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des cours
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Cours/');
			}
			else{

				// On crée l'objet Categorie à partir de la base de données. Si celui-ci n'existe pas, on le créé dans la base de données.
				$managerCategorie = $this->getManagers()->getManagerOf('Categorie');
				$categorie = $managerCategorie->getCatgeorieByName($request->getPostData('nouvelleCategorieCours'));


				$cours->hydrate(array(
					'categorie' => $categorie,
					));

				if(sizeof($cours->getErreurs()) === 0){

					//On procède à la mise à jour dans la BDD du cours
					$managerCours->saveCours($cours);
					$user->getMessageClient()->addReussite('La catégorie du cours a bien été modifiée.');
					
					// On retourne à la page du cours
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Cours/id='.$cours->getIdCours());
				}else{
					$user->getMessageClient()->addErreur($cours->getErreurs());
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Cours/id='.$cours->getIdCours());
				}
			}
		}
	}


	// Méthode pour modifier le cours global du cours
	public function executeModifierCoursGlobalCours($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des cours
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idCours = (int) $request->getPostData('idCours');
			//On appelle le manager des Utilisateurs
			$managerCours = $this->getManagers()->getManagerOf('Cours');
			//On récupère le cours à administrer
			$cours = $managerCours->getCoursById($idCours);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($cours === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des cours
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Cours/');
			}
			else{

				$nouveauCoursGlobal = (int) $request->getPostData('nouveauCoursGlobal');
				$managerCoursGlobal = $this->getManagers()->getManagerOf('CoursGlobal');
				$managerCoursCoursGlobal = $this->getManagers()->getManagerOf('CoursCoursGlobal');

				if($nouveauCoursGlobal === 0){
					// On supprime le précédent lien s'il y avait

					$ancienCoursGlobal = $managerCoursCoursGlobal->getCoursGlobalFromCours($cours->getIdCours());
					if($ancienCoursGlobal){
						$managerCoursCoursGlobal->deleteCoursCoursGlobal(new \Library\Entities\CoursCoursGlobal(array(
							'cours' => $cours,
							'coursGlobal' => $ancienCoursGlobal
						)));
					}
				}else{
					$newCoursGlobal = $managerCoursGlobal->getCoursGlobalById($request->getPostData('nouveauCoursGlobal'));
					if($newCoursGlobal instanceof \Library\Entities\CoursGlobal){
						$newCoursCoursGlobal = new \Library\Entities\CoursCoursGlobal(array(
							'cours' => $cours,
							'coursGlobal' => $newCoursGlobal
						));
						if(sizeof($newCoursCoursGlobal->getErreurs()) != 0){
							$user->getMessageClient()->addErreur($newCoursCoursGlobal->getErreurs());
						}else{
							// On supprime le précédent lien s'il y avait
							$ancienCoursGlobal = $managerCoursCoursGlobal->getCoursGlobalFromCours($cours->getIdCours());
							if($ancienCoursGlobal){
								$managerCoursCoursGlobal->deleteCoursCoursGlobal(new \Library\Entities\CoursCoursGlobal(array(
									'cours' => $cours,
									'coursGlobal' => $ancienCoursGlobal
								)));
							}
							// On créé le nouveau lien
							$managerCoursCoursGlobal->addCoursCoursGlobal($newCoursCoursGlobal);

							$user->getMessageClient()->addReussite('Le cours a bien été rattaché à un nouveau cours global.');
						}

					}else{
						$user->getMessageClient()->addErreur('Le cours global que vous demandez n\'existe pas.');
					}
				}

				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Cours/id='.$cours->getIdCours());
			}
		}
	}


	// Méthode pour modifier la description du cours
	public function executeModifierDescriptionCours($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des cours
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idCours = (int) $request->getPostData('idCours');
			//On appelle le manager des Utilisateurs
			$managerCours = $this->getManagers()->getManagerOf('Cours');
			//On récupère le cours à administrer
			$cours = $managerCours->getCoursById($idCours);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($cours === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des cours
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Cours/');
			}
			else{

				$nouvelleDescription = $request->getPostData('nouvelleDescription');
				
				$cours->hydrate(array(
					'descriptionCours' => $nouvelleDescription,
					));

				if(sizeof($cours->getErreurs()) === 0){

					//On procède à la mise à jour dans la BDD du cours
					$managerCours->saveCours($cours);
					$user->getMessageClient()->addReussite('La description du cours a bien été modifiée.');
					
					// On retourne à la page du cours
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Cours/id='.$cours->getIdCours());
				}else{
					$user->getMessageClient()->addErreur($cours->getErreurs());
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Cours/id='.$cours->getIdCours());
				}
			}
		}
	}

	// Méthode pour modifier les mots-clés du cours
	public function executeModifierMotClesCours($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		
		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des cours
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idCours = (int) $request->getPostData('idCours');
			//On appelle le manager des Utilisateurs
			$managerCours = $this->getManagers()->getManagerOf('Cours');
			//On récupère le cours à administrer
			$cours = $managerCours->getCoursById($idCours);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($cours === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des cours
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Cours/');
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
				$managerCoursMotCle = $this->getManagers()->getManagerOf('CoursMotCle');
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

				// On supprime les mots-clés qui ne sont plus en lien avec le cours
				foreach($cours->getMotCles() as $motcle){
					if(!in_array($motcle->getNomMotCle(), $motsClesEntreUtilisateur)){
						$CoursMotCle = new \Library\Entities\CoursMotCle(array(
							'motCle' => $motcle,
							'cours' => $cours
							));
						$managerCoursMotCle->deleteCoursMotCle($CoursMotCle);
					}
				}

				$cours->hydrate(array(
					'motCles' => $tableauMotClesWithId,
					));

				if(sizeof($cours->getErreurs()) === 0){

					//On procède à la mise à jour dans la BDD du cours
					$managerCours->saveCours($cours);
					$user->getMessageClient()->addReussite('Les mots-clés du cours a bien été modifiés.');
					
					// On retourne à la page du cours
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Cours/id='.$cours->getIdCours());
				}else{
					$user->getMessageClient()->addErreur($cours->getErreurs());
					$response = $this->app->getHTTPResponse();
					$response->redirect('/ForAdminOnly/Cours/id='.$cours->getIdCours());
				}
			}
		}
	}
	
	// Méthode pour supprimer un cours de la base
	public function executeSupprimerCours($request){

		//On récupère la requête du client
		$user = $this->app->getUser();
		

		// On vérifie que l'utilisateur est administrateur
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$user->getMessageClient()->addErreur('Vous n\'êtes pas autorisé à administrer cette plateforme.');
			
			// On retourne à la page des cours
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}else{
			$idCours = (int) $request->getPostData('idCours');
			//On appelle le manager des Utilisateurs
			$managerCours = $this->getManagers()->getManagerOf('Cours');
			//On récupère le cours à administrer
			$cours = $managerCours->getCoursById($idCours);
			
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($cours === false){
				
				$user->getMessageClient()->addErreur('La méthode employée n\'est pas prise en compte par la plateforme.');
				// On retourne à la page des cours
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Cours/');
			}
			else{

				// On supprime la précédente image du cours si ce n'est pas celle par defaut
				// On récupère le chemin url par defaut de l'image du cours
				// On charge le fichier de configuration
				$config = $this->getApp()->getConfig();
				$pathImageDefault = $config->getVar('cours', 'parametresImage', 'filePathDefault');
				$fileDelete = $this->getApp()->getFileDelete();
				if($cours->getUrlImageCours() != $pathImageDefault){
					$fileDelete->deleteFile($cours->getUrlImageCours());
					$fileDelete->deleteFile($cours->getUrlImageMiniatureCours());
				}

				//On procède à la suppression dans la BDD du cours
				$managerCours->deleteCours($cours);
				$user->getMessageClient()->addReussite('Le cours a bien été supprimé.');

				// On retourne à la page des cours
				$response = $this->app->getHTTPResponse();
				$response->redirect('/ForAdminOnly/Cours/');
				
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
	* Retourne l'id pour être lisible en html.
	**/
	private function cleanTitreSommaire($texte){
			//  Supprimer les espaces et les accents
		    $texte=trim($texte);
		    $table = array(
		        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
		        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
		        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
		        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
		        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
		        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
		        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
		        'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
		    );
		    $texte= strtr($texte, $table);
		    
		    //  Supprime et remplace les caracètres spéciaux (autres que lettres et chiffres)
		    $texte = preg_replace('#([^a-z0-9]+)#i', '-', $texte);
		    
	    	return $texte;
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

			//On récupère l'id du cours
			$idCours = (int) $request->getGetData('idCours');
			
			//On appelle le manager des Cours
			$managerCours = $this->getManagers()->getManagerOf('Cours');

			$cours = $managerCours->getCoursById($idCours);
			
			//Si le cours existe
			if($cours instanceof \Library\Entities\Cours){

				$user->setFlash($cours);

	            $socialMediaApplication = new \Applications\ApplicationsStandAlone\SocialMedia\SocialMediaApplication;
	            $socialMediaApplication->execute('AutoPost', 'login'); // Module = AutoPost ; action = login
	        	
	        }
        }
    }
}
