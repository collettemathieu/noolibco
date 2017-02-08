<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Ce controleur permet d'afficher, modifier ou crer de nouveau		  |
// | catégorie, surcatégorie, satuts                                      |
// +----------------------------------------------------------------------+
// | Auteurs : Guénaël DEQUEKER <dequekerguenael@noolib.com> 		      |
// | Auteurs : Steve Despres  <stevedespres@noolib.com>				      |
// +----------------------------------------------------------------------+

/**
 * @name: controleur des options de l'utilisateur pour le Frontend
 * @access: public
 * @version: 1
 */	


namespace Applications\Backend\Modules\Attributs;

class AttributsController extends \Library\BackController
{
	public function executeShow()
	{
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin')){
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');
		}
	}
	
	//partie statut utilisateur
	
	public function executeEditerStatutUtilisateur($request)
	{
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin')){
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			// On récupère les différents statut utilisateur à la vue
			$managerStatutUtilisateur = $this->getManagers()->getManagerOf('StatutUtilisateur');
			$statuts = $managerStatutUtilisateur->getAllStatuts();
			$this->page->addVar('statuts', $statuts);
			
			
			$idStatutUtilisateur = $request->getPostData('idStatutUtilisateur');
			
			if(isset($idStatutUtilisateur))
			{
				//on passe l'id en get et on redirige
				$response = $this->app->getHTTPResponse();
				$response->redirect('/PourAdminSeulement/Attributs/EditerStatutUtilisateur/idStatutUtilisateur='.$idStatutUtilisateur);
			}
			else
			{
				$idStatutUtilisateur = $request->getGetData('idStatutUtilisateur');
				
				if(isset($idStatutUtilisateur))
				{
					$statutUtilisateur = $managerStatutUtilisateur->getStatutById($idStatutUtilisateur);
					
					if($statutUtilisateur)
					{
						$this->page->addVar('statutUtilisateur', $statutUtilisateur);
					}
				}
			}
		}
	}
	
	public function executeAjouterStatutUtilisateur($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$nomStatut = $request->getPostData('nomStatut');
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($nomStatut === null || $nomStatut == '')
			{
				$user->getMessageClient()->addErreur('Attention, tous les champs sont obligatoires.');
				// si non, on procède à la redirection
				$response->redirect('/PourAdminSeulement/Attributs/EditerStatutUtilisateur/');
			}
			else
			{
				$managerStatutUtilisateur = $this->getManagers()->getManagerOf('StatutUtilisateur');
				$statuts = $managerStatutUtilisateur->getAllStatuts();
				//on vérifie si le statut n'existe pas déjà
				$bool = false;
				foreach($statuts as $statut)
				{
					$bool = $bool || ($statut->getNomStatut() == $nomStatut);
				}
				//si il y a déjà un statut du même nom
				if($bool)
				{
					//on fait s'écrire l'erreur
					$user->getMessageClient()->addErreur('Le statut créé existe déjà.');
					
					//on redirige
					$response->redirect('/PourAdminSeulement/Attributs/EditerStatutUtilisateur/');
				}
				else
				{
					//on créé un nouveau statut
					$newStatut = new \Library\Entities\StatutUtilisateur(array(
					'nomStatut' => $nomStatut
					));
					
					//si il y a des erreurs
					if(count($newStatut->getErreurs()) != 0)
					{
						$user->getMessageClient()->addErreur($newStatut->getErreurs());
						$response->redirect('/PourAdminSeulement/Attributs/EditerStatutUtilisateur/');
					}
					else
					{
						$statutManager = $this->getManagers()->getManagerOf('StatutUtilisateur');
						//on enregistre
						$statutManager->addStatut($newStatut);
						$user->getMessageClient()->addReussite('Le statut a bien été créé.');
						$response->redirect('/PourAdminSeulement/Attributs/EditerStatutUtilisateur/');
					}
				}
			}
		}
	}
	
	public function executeSupprimerStatutUtilisateur($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idStatutUtilisateur = $request->getPostData('idStatutUtilisateur');
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($idStatutUtilisateur === null)
			{
				// si non, on procède à la redirection
				$response->redirect('/PourAdminSeulement/');
			}
			else
			{
				$managerStatutUtilisateur = $this->getManagers()->getManagerOf('StatutUtilisateur');
				$statutASupprimer = $managerStatutUtilisateur->getStatutById($idStatutUtilisateur);
				//on interdit de supprimer le statut "Aucun"
				if($statutASupprimer === false || $statutASupprimer->getIdStatut() == 1)
				{
					$user->getMessageClient()->addErreur('Vous ne pouvez pas supprimer ce statut.');
				}
				else
				{
					//on supprime le statut de la base
					$managerStatutUtilisateur->deleteStatut($statutASupprimer);
					$user->getMessageClient()->addReussite('Le statut a bien été supprimé.');
				}
				$response->redirect('/PourAdminSeulement/Attributs/EditerStatutUtilisateur/');
			}
		}
	}
	
	public function executeModifierStatutUtilisateur($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idStatutUtilisateur = $request->getPostData('idStatutUtilisateur');
			$newNom = $request->getPostData('newNom');
			//on vérifie que les données ont bien été envoyé par post
			if($idStatutUtilisateur === null || $newNom === null || $newNom =='')
			{
				$user->getMessageClient()->addErreur('Attention, tous les champs sont obligatoires.');
				$response->redirect('/PourAdminSeulement/Attributs/EditerStatutUtilisateur/idStatutUtilisateur='.$idStatutUtilisateur);
			}
			else
			{
				$statutUtilisateur = $this->getManagers()->getManagerOf('StatutUtilisateur')->getStatutById($idStatutUtilisateur);
				if($statutUtilisateur === false)
				{
					$response->redirect('/PourAdminSeulement/Attributs/EditerStatutUtilisateur/');
				}
				else
				{
					$nomDejaPris = $this->getManagers()->getManagerOf('StatutUtilisateur')->getStatutByNom($newNom);
					if($nomDejaPris && $nomDejaPris->getIdStatut() != $statutUtilisateur->getIdStatut())
					{
						$user->getMessageClient()->addErreur('Un statut porte déjà le même nom.');
						$response->redirect('/PourAdminSeulement/Attributs/EditerStatutUtilisateur/');
					}
					else
					{
						//on change les infos
						$statutUtilisateur->setNomStatut($newNom);
						//on met la base à joure
						$this->getManagers()->getManagerOf('StatutUtilisateur')->saveStatut($statutUtilisateur);
						$user->getMessageClient()->addReussite('Les informations ont été mises à jour.');
						//on redirige
						$response->redirect('/PourAdminSeulement/Attributs/EditerStatutUtilisateur/idStatutUtilisateur='.$statutUtilisateur->getIdStatut());
					}
				}
			}
		}
	}
	
	//partie statut application
	
	public function executeEditerStatutApplication($request)
	{
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			// On récupère les différents statut utilisateur à la vue
			$managerStatutApplication = $this->getManagers()->getManagerOf('StatutApplication');
			$statuts = $managerStatutApplication->getAllStatuts();
			$this->page->addVar('statuts', $statuts);
			
			
			$idStatutApplication = $request->getPostData('idStatutApplication');
			
			if(isset($idStatutApplication))
			{
				//on passe l'id en get et on redirige
				$response = $this->app->getHTTPResponse();
				$response->redirect('/PourAdminSeulement/Attributs/EditerStatutApplication/idStatutApplication='.$idStatutApplication);
			}
			else
			{
				$idStatutApplication = $request->getGetData('idStatutApplication');
				
				if(isset($idStatutApplication))
				{
					$statutApplication = $managerStatutApplication->getStatutById($idStatutApplication);
					
					if($statutApplication)
					{
						$this->page->addVar('statutApplication', $statutApplication);
					}
				}
			}
		}
	}
	
	public function executeAjouterStatutApplication($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$nomStatut = $request->getPostData('nomStatut');
			$couleurStatut = $request->getPostData('couleurStatut');
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($nomStatut === null || $couleurStatut === null || $nomStatut =='')
			{
				$user->getMessageClient()->addErreur('Attention, tous les champs sont obligatoires.');
				// si non, on procède à la redirection
				$response->redirect('/PourAdminSeulement/Attributs/EditerStatutApplication/');
			}
			else
			{
				$managerStatutApplication = $this->getManagers()->getManagerOf('StatutApplication');
				$statuts = $managerStatutApplication->getAllStatuts();
				//on vérifi si le statut n'existe pas déjà
				$bool = false;
				foreach($statuts as $statut)
				{
					$bool = $bool || ($statut->getNomStatut() == $nomStatut);
				}
				//si il y a déjà un statut du même nom
				if($bool)
				{
					//on fait s'écrire l'erreur
					$user->getMessageClient()->addErreur('Le statut créé existe déjà.');
					
					//on redirige
					$response->redirect('/PourAdminSeulement/Attributs/EditerStatutApplication/');
				}
				else
				{
					if($this->getManagers()->getManagerOf('StatutApplication')->getStatutByNom($nomStatut))
					{
						$user->getMessageClient()->addErreur('Le nom créé existe déjà.');
						$response->redirect('/PourAdminSeulement/Attributs/EditerStatutApplication/');
					}
					else
					{
						//on créé un nouveau statut
						$newStatut = new \Library\Entities\StatutApplication(array(
							'nomStatut' => $nomStatut, 
							'couleurStatut' => $couleurStatut
							));
						
						//si il y a des erreurs
						if(count($newStatut->getErreurs()) != 0)
						{
							$user->getMessageClient()->addErreur($newStatut->getErreurs());
							$response->redirect('/PourAdminSeulement/Attributs/EditerStatutApplication/');
						}
						else
						{
							$statutManager = $this->getManagers()->getManagerOf('StatutApplication');
							//on enregistre
							$statutManager->addStatut($newStatut);
							$user->getMessageClient()->addReussite('Le statut a bien été créé.');
							$response->redirect('/PourAdminSeulement/Attributs/EditerStatutApplication/');
						}
					}
				}
			}
		}
	}
	
	public function executeSupprimerStatutApplication($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idStatutApplication = $request->getPostData('idStatutApplication');
			//on vérifie que les données ont bien été envoyé par post
			if($idStatutApplication === null)
			{
				$response->redirect('/PourAdminSeulement/Attributs/EditerStatutApplication/');
			}
			else
			{
				$statutApplication = $this->getManagers()->getManagerOf('StatutApplication')->getStatutById($idStatutApplication);
				$this->getManagers()->getManagerOf('StatutApplication')->deleteStatut($statutApplication);
				$user->getMessageClient()->addReussite('Le statut application a bien été supprimé.');
				$response->redirect('/PourAdminSeulement/Attributs/EditerStatutApplication/');
			}
		}
	}
	
	public function executeModifierStatutApplication($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idStatutApplication = $request->getPostData('idStatutApplication');
			$newNom = $request->getPostData('newNom');
			$newCouleurStatut = $request->getPostData('newCouleurStatut');
			//on vérifie que les données ont bien été envoyé par post
			if($idStatutApplication === null || $newNom === null || $newCouleurStatut === null || $newNom =='')
			{
				$user->getMessageClient()->addErreur('Attention, tous les champs sont obligatoires.');
				$response->redirect('/PourAdminSeulement/Attributs/EditerStatutApplication/idStatutApplication='.$idStatutApplication);
			}
			else
			{
				$statutApplication = $this->getManagers()->getManagerOf('StatutApplication')->getStatutById($idStatutApplication);
				if($statutApplication === false)
				{
					$response->redirect('/PourAdminSeulement/Attributs/EditerStatutApplication/');
				}
				else
				{
					$nomDejaPris = $this->getManagers()->getManagerOf('StatutApplication')->getStatutByNom($newNom);
					if($nomDejaPris && $nomDejaPris->getIdStatut() != $statutApplication->getIdStatut())
					{
						$user->getMessageClient()->addErreur('Le nom créé existe déjà.');
						$response->redirect('/PourAdminSeulement/Attributs/EditerStatutApplication/');
					}
					else
					{
						//on change les infos
						$statutApplication->setNomStatut($newNom);
						$statutApplication->setCouleurStatut($newCouleurStatut);
						//on met la base à joure
						$this->getManagers()->getManagerOf('StatutApplication')->saveStatut($statutApplication);
						$user->getMessageClient()->addReussite('Les informations ont bien été mises à jour.');
						//on redirige
						$response->redirect('/PourAdminSeulement/Attributs/EditerStatutApplication/idStatutApplication='.$statutApplication->getIdStatut());
					}
				}
			}
		}
	}
	
	
	//partie categorie application
	
	public function executeEditerCategorie($request)
	{
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			// On récupère les différents statut utilisateur à la vue
			$managerSurcategorie = $this->getManagers()->getManagerOf('Surcategorie');
			$this->page->addVar('surcategories', $managerSurcategorie->getAllSurcategories());
			
			$idCategorie = $request->getPostData('idCategorie');
			
			if(isset($idCategorie))
			{
				//on passe l'id en get et on redirige
				$response = $this->app->getHTTPResponse();
				$response->redirect('/PourAdminSeulement/Attributs/EditerCategorie/idCategorie='.$idCategorie);
			}
			else
			{
				$idSurcategorie = $request->getPostData('idSurcategorie');
				
				if(!isset($idSurcategorie))
				{
					$idSurcategorie = 14;
				}
				$this->page->addVar('idSurcategorie', $idSurcategorie);
				
				$surcategorie = $managerSurcategorie->getSurcategorieById($idSurcategorie);
				$managerSurcategorie->putCategoriesInSurcategorie($surcategorie);
				$this->page->addVar('categories', $surcategorie->getCategories());
				
				$idCategorie = $request->getGetData('idCategorie');
				
				if(isset($idCategorie))
				{
					$managerCategorie = $this->getManagers()->getManagerOf('Categorie');
					
					$categorie = $managerCategorie->getCategorieById($idCategorie);
					
					if($categorie)
					{
						$this->page->addVar('categorie', $categorie);
					}
				}
			}
		}
	}
	
	public function executeAjouterCategorie($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$nom = $request->getPostData('nom');
			$description = $request->getPostData('description');
			$idSurcategorie = $request->getPostData('idSurcategorie');
			$surcategorie = $this->getManagers()->getManagerOf('Surcategorie')->getSurcategorieById($idSurcategorie);;
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($nom === null || $nom === '' || $description === null || $description === '' || $idSurcategorie === null || $surcategorie === null)
			{
				// si non, on procède à la redirection
				$user->getMessageClient()->addErreur('Attention, tous les champs sont obligatoires.');
				$response->redirect('/PourAdminSeulement/Attributs/EditerCategorie/');
			}
			else
			{
				$managerCategorie = $this->getManagers()->getManagerOf('Categorie');
				//on vérifi si le statut n'existe pas déjà
				$bool = false;
				foreach($managerCategorie->getAllCategories() as $cat)
				{
					$bool = $bool || ($cat->getNomCategorie() == $nom);
				}
				//si il y a déjà un statut du même nom
				if($bool)
				{
					//on fait s'écrire l'erreur
					$user->getMessageClient()->addErreur('La catégorie créée existe déjà.');
					
					//on redirige
					$response->redirect('/PourAdminSeulement/Attributs/EditerCategorie/');
				}
				else
				{
					if($this->getManagers()->getManagerOf('Categorie')->getCategorieByNom($nom))
					{
						$user->getMessageClient()->addErreur('Une catégorie porte déjà le même nom.');
						$response->redirect('/PourAdminSeulement/Attributs/EditerCategorie/');
					}
					else
					{
						//on créé un nouveau statut
						$newCategorie = new \Library\Entities\Categorie(array(
							'nomCategorie' => $nom, 
							'descriptionCategorie' => $description, 
							'surcategorie' => $surcategorie
							));
						
						//si il y a des erreurs
						if(count($newCategorie->getErreurs()) != 0)
						{
							$user->getMessageClient()->addErreur('$newCategorie->getErreurs()');
							$response->redirect('/PourAdminSeulement/Attributs/EditerCategorie/');
						}
						else
						{
							$statutManager = $this->getManagers()->getManagerOf('Categorie');
							//on enregistre
							$statutManager->addCategorie($newCategorie);
							$user->getMessageClient()->addReussite('La catégorie a bien été créée.');
							$response->redirect('/PourAdminSeulement/Attributs/EditerCategorie/idCategorie='.$newCategorie->getIdCategorie());
						}
					}
				}
			}
		}
	}
	
	public function executeModifierCategorie($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$nom = $request->getPostData('nom');
			$idCategorie = $request->getPostData('idCategorie');
			$description = $request->getPostData('description');
			$idSurcategorie = $request->getPostData('idSurcategorie');
			$surcategorie = $this->getManagers()->getManagerOf('Surcategorie')->getSurcategorieById($idSurcategorie);;
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($nom === null || $nom === '' || $idCategorie === null || $description === null || $description === '' || $idSurcategorie === null || $surcategorie === null)
			{
				// si non, on procède à la redirection
				$user->getMessageClient()->addErreur('Attention, tous les champs sont obligatoires.');
				$response->redirect('/PourAdminSeulement/Attributs/EditerCategorie/idCategorie='.$idCategorie);
			}
			else
			{
				$managerCategorie = $this->getManagers()->getManagerOf('Categorie');
				//on vérifi si le statut n'existe pas déjà
				
				$categorie = $managerCategorie->getCategorieById($idCategorie);
				
				if($categorie)
				{
					$nomDejaPris = $this->getManagers()->getManagerOf('Categorie')->getCategorieByNom($nom);
					if($nomDejaPris && $nomDejaPris->getIdCategorie() != $categorie->getIdCategorie())
					{
						$user->getMessageClient()->addErreur('Le nom créé existe déjà.');
						$response->redirect('/PourAdminSeulement/Attributs/EditerCategorie/');
					}
					else
					{
						$categorie->setNomCategorie($nom);
						$categorie->setDescriptionCategorie($description);
						$categorie->setSurcategorie($surcategorie);
						
						if(count($categorie->getErreurs()) != 0)
						{
							$user->getMessageClient()->addErreur($categorie->getErreurs());
						}
						else
						{
							$managerCategorie->saveCategorie($categorie);
							$user->getMessageClient()->addReussite('Les informations ont bien été mises à jour.');
						}
					}
				}
				$response->redirect('/PourAdminSeulement/Attributs/EditerCategorie/idCategorie='.$idCategorie);
			}
		}
	}
	
	public function executeSupprimerCategorie($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		

		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idCategorie = $request->getPostData('idCategorie');
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($idCategorie === null)
			{
				// si non, on procède à la redirection
				$response->redirect('/PourAdminSeulement/');
				$user->getMessageClient()->addErreur('Cette catégorie n\'existe pas.');
			}
			else
			{
				$managerCategorie = $this->getManagers()->getManagerOf('Categorie');
				//on vérifi si le statut n'existe pas déjà
				
				$categorie = $managerCategorie->deleteCategorie($managerCategorie->getCategorieById($idCategorie));
				$user->getMessageClient()->addReussite('La catégorie a bien été supprimée.');
				$response->redirect('/PourAdminSeulement/Attributs/EditerCategorie/');
			}
		}
	}
	
	//partie surcategorie application
	
	public function executeEditerSurcategorie($request)
	{
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			// On récupère les différents statut utilisateur à la vue
			$managerSurcategorie = $this->getManagers()->getManagerOf('Surcategorie');
			$surcategories = $managerSurcategorie->getAllSurcategories();
			$this->page->addVar('surcategories', $surcategories);
			
			
			$idSurcategorie = $request->getPostData('idSurcategorie');
			
			if(isset($idSurcategorie))
			{
				//on passe l'id en get et on redirige
				$response = $this->app->getHTTPResponse();
				$response->redirect('/PourAdminSeulement/Attributs/EditerSurcategorie/idSurcategorie='.$idSurcategorie);
			}
			else
			{
				$idSurcategorie = $request->getGetData('idSurcategorie');
				
				if(isset($idSurcategorie))
				{
					$surcategorie = $managerSurcategorie->getSurcategorieById($idSurcategorie);
					
					if($surcategorie)
					{
						$this->page->addVar('surcategorie', $surcategorie);
					}
				}
			}
		}
	}
	
	public function executeAjouterSurcategorie($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$nom = $request->getPostData('nom');
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($nom === null || $nom == '')
			{
				// si non, on procède à la redirection
				$user->getMessageClient()->addErreur('Attention, tous les champs sont obligatoires.');
				$response->redirect('/PourAdminSeulement/Attributs/EditerSurcategorie/');
			}
			else
			{
				$managerSurcategorie = $this->getManagers()->getManagerOf('Surcategorie');
				//on vérifi si le statut n'existe pas déjà
				$bool = false;
				foreach($managerSurcategorie->getAllSurcategories() as $cat)
				{
					$bool = $bool || ($cat->getNomSurcategorie() == $nom);
				}
				//si il y a déjà un statut du même nom
				if($bool)
				{
					//on fait s'écrire l'erreur
					$user->getMessageClient()->addErreur('Cette surcategorie existe déjà.');
					
					//on redirige
					$response->redirect('/PourAdminSeulement/Attributs/EditerSurcategorie/');
				}
				else
				{
					if($this->getManagers()->getManagerOf('Surcategorie')->getSurcategorieByNom($nom))
					{
						$user->getMessageClient()->addErreur('Cette surcategorie existe déjà.');
						$response->redirect('/PourAdminSeulement/Attributs/EditerSurcategorie/');
					}
					else
					{
						//on créé un nouveau statut
						$newSurcategorie = new \Library\Entities\Surcategorie(array(
							'nomSurcategorie' => $nom
							));
						
						//si il y a des erreurs
						if(count($newSurcategorie->getErreurs()) != 0)
						{
							$user->getMessageClient()->addErreur($newSurcategorie->getErreurs());
							$response->redirect('/PourAdminSeulement/Attributs/EditerSurcategorie/');
						}
						else
						{
							$statutManager = $this->getManagers()->getManagerOf('Surcategorie');
							//on enregistre
							$statutManager->addSurcategorie($newSurcategorie);
							$user->getMessageClient()->addReussite('La surcatégorie a bien été créée.');
							$response->redirect('/PourAdminSeulement/Attributs/EditerSurcategorie/');
						}
					}
				}
			}
		}
	}
	
	public function executeModifierSurcategorie($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$nom = $request->getPostData('nom');
			$idSurcategorie = $request->getPostData('idSurcategorie');
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($nom === null || $idSurcategorie === null || $nom == '')
			{
				// si non, on procède à la redirection
				$user->getMessageClient()->addErreur('Attention, tous les champs sont obligatoires.');
				$response->redirect('/PourAdminSeulement/Attributs/EditerSurcategorie/idSurcategorie='.$idSurcategorie);
			}
			else
			{
				$managerSurcategorie = $this->getManagers()->getManagerOf('Surcategorie');
				//on vérifi si le statut n'existe pas déjà
				
				$surcategorie = $managerSurcategorie->getSurcategorieById($idSurcategorie);
				
				if($surcategorie)
				{
					$nomDejaPris = $this->getManagers()->getManagerOf('Surcategorie')->getSurcategorieByNom($nom);
					if($nomDejaPris && $nomDejaPris->getIdSurcategorie() != $surcategorie->getIdSurcategorie())
					{
						$user->getMessageClient()->addErreur('Attention, cette surcatégorie existe déjà.');
						$response->redirect('/PourAdminSeulement/Attributs/EditerSurcategorie/');
					}
					else
					{
						$surcategorie->setNomSurcategorie($nom);
						
						if(count($surcategorie->getErreurs()) != 0)
						{
							$user->getMessageClient()->addErreur($surcategorie->getErreurs());
						}
						else
						{
							$managerSurcategorie->saveSurcategorie($surcategorie);
							$user->getMessageClient()->addReussite('Les informations ont bien été mises à jour.');
						}
					}
				}
				$response->redirect('/PourAdminSeulement/Attributs/EditerSurcategorie/idSurcategorie='.$idSurcategorie);
			}
		}
	}
	
	public function executeSupprimerSurcategorie($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idSurcategorie = $request->getPostData('idSurcategorie');
			//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
			if($idSurcategorie === null)
			{
				// si non, on procède à la redirection
				$response->redirect('/PourAdminSeulement/');
				$user->getMessageClient()->addErreur('Cette surcatégorie n\'existe pas.');
			}
			else
			{
				$managerSurcategorie = $this->getManagers()->getManagerOf('Surcategorie');
				
				$surcategorie = $managerSurcategorie->deleteSurcategorie($managerSurcategorie->getSurcategorieById($idSurcategorie));
				$user->getMessageClient()->addReussite('La surcatégorie a bien été supprimée.');
				$response->redirect('/PourAdminSeulement/Attributs/EditerSurcategorie/');
			}
		}
	}
	
	public function executeEditerTypeDonneeUtilisateur($request){
	
	
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
		
			$managerTypeDonneeUtilisateur = $this->getManagers()->getManagerOf('TypeDonneeUtilisateur');
			$allTypeDonneeUtilisateur = $managerTypeDonneeUtilisateur->getAllTypeDonneeUtilisateurs();
			
			$this->page->addVar('allTypeDonneeUtilisateur', $allTypeDonneeUtilisateur);
		
		}
	
	}
	
	public function executeModifierTypeDonneeUtilisateur($request){
	
	
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
		
			if($request->getPostData('idTypeDonneeUtilisateur') && $request->getPostData('extensionTypeDonneeUtilisateur')){
			
				$managerTypeDonneeUtilisateur = $this->getManagers()->getManagerOf('TypeDonneeUtilisateur');
				$typeParametre = $managerTypeDonneeUtilisateur->getTypeDonneeUtilisateurById($request->getPostData('idTypeDonneeUtilisateur'));
				$typeParametre->setExtensionTypeDonneeUtilisateur($request->getPostData('extensionTypeDonneeUtilisateur'));
				$managerTypeDonneeUtilisateur->saveTypeDonneeUtilisateur($typeParametre);
				
				$user->getMessageClient()->addReussite('Le type de paramètre a bien été modifié.');
				
				$response->redirect('/PourAdminSeulement/Attributs/EditerTypeDonneeUtilisateur/');
				
			}else{
				
				$user->getMessageClient()->addErreur('Entrez une extension');
					
			}
		}
	}
	
	public function executeSupprimerTypeDonneeUtilisateur($request){
	
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			if($request->getPostData('idTypeDonneeUtilisateur')){
				$managerTypeDonneeUtilisateur = $this->getManagers()->getManagerOf('TypeDonneeUtilisateur');
				$typeParametre = $managerTypeDonneeUtilisateur->getTypeDonneeUtilisateurById($request->getPostData('idTypeDonneeUtilisateur'));
				
				$managerTypeDonneeUtilisateur->deleteTypeDonneeUtilisateur($typeParametre);
				
				$user->getMessageClient()->addReussite('Le type de paramètre a bien été supprimé.');
				
				$response->redirect('/PourAdminSeulement/Attributs/EditerTypeDonneeUtilisateur/');
			}else{
				$user->getMessageClient()->addErreur('Une erreur est survenue');
				$response->redirect('/PourAdminSeulement/Attributs/EditerTypeDonneeUtilisateur/');
			}
		
		
		}
	
	}
	
	public function executeAjouterTypeDonneeUtilisateur($request){
	
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			if($request->getPostData('nomTypeDonneeUtilisateur') && $request->getPostData('extensionTypeDonneeUtilisateur')){
				
				$managerTypeDonneeUtilisateur = $this->getManagers()->getManagerOf('TypeDonneeUtilisateur');
					
				// On crée l'objet VersionTache
				$typeParametre = new \Library\Entities\TypeDonneeUtilisateur(array(
					'nomTypeDonneeUtilisateur' => $request->getPostData('nomTypeDonneeUtilisateur'),
					'extensionTypeDonneeUtilisateur' => $request->getPostData('extensionTypeDonneeUtilisateur')
					));
				
				if(sizeof($typeParametre->getErreurs()) == 0){
				
					$managerTypeDonneeUtilisateur->addTypeDonneeUtilisateur($typeParametre);
						
					$user->getMessageClient()->addReussite('Le type de paramètre a bien été créé.');
					$response->redirect('/PourAdminSeulement/Attributs/EditerTypeDonneeUtilisateur/');
						
				}else{
				
					// On ajoute la variable d'erreurs à la page
					$user->getMessageClient()->addErreur($typeParametre->getErreurs());
					$response->redirect('/PourAdminSeulement/Attributs/EditerTypeDonneeUtilisateur/');
				}
			}else{
				$user->getMessageClient()->addErreur('Complétez les champs requis pour ajouter une type de paramètre.');
			
				$response->redirect('/PourAdminSeulement/Attributs/EditerTypeDonneeUtilisateur/');
			}
		}
	}
}
