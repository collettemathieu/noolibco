<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Ce controleur permet d'afficher, moditier ou crée de nouveaux		  |
// | laboratoires, établissements, équipes, ville, pays                   |
// +----------------------------------------------------------------------+
// | Auteurs : Guénaël DEQUEKER <dequekerguenael@noolib.com> 		      |
// +----------------------------------------------------------------------+

/**
 * @name: controleur des options de l'utilisateur pour le Frontend
 * @access: public
 * @version: 1
 */	


namespace Applications\Backend\Modules\Groupes;

class GroupesController extends \Library\BackController
{
	public function executeShow()
	{
		$user = $this->app->getUser();
		// On vérifie que l'utilisateur est bien identifié et administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');
		}
	}
	
	
	
	
	// partie Etablissement
	
	public function executeShowEtablissement($request)
	{
		$user = $this->app->getUser();
		// On vérifie que l'utilisateur est bien identifié et administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$managerEtablissement = $this->getManagers()->getManagerOf('Etablissement');
			
			$this->page->addVar('allEtablissements', $managerEtablissement->getAllEtablissements());
			
			//on récuper l'id passé en post
			$idEtablissement = $request->getPostData('idEtablissement');
			//si id passé en post
			if(isset($idEtablissement))
			{
				//on passe l'id en get et on redirige
				$response = $this->app->getHTTPResponse();
				$response->redirect('/PourAdminSeulement/Groupes/Etablissement/idEtablissement='.$idEtablissement);
			}
			else
			{
				$managerPays = $this->getManagers()->getManagerOf('Pays');
				
				$this->page->addVar('listePays', $managerPays->getAllPays());
				
				$idPays = $request->getPostData('idPays');
				
				if(!isset($idPays))
				{
					$idPays = 65;
				}
				$this->page->addVar('idPays', $idPays);
				
				$pays = $managerPays->getPaysById($idPays);
				$managerPays->putVillesInPays($pays);
				$this->page->addVar('villes', $pays->getVilles());
				
				
				
				$idEtablissement = $request->getGetData('idEtablissement');
				//si un id a été envoyé à la page en get
				if(isset($idEtablissement))
				{
					$etablissement = $managerEtablissement->getEtablissementById($idEtablissement);
					//si un établissement à été récuperé
					if($etablissement)
					{
						//on ajoute l'établissement à afficher à la vue
						$this->page->addVar('etablissement', $etablissement);
						
						$managerVille = $this->getManagers()->getManagerOf('Ville');
						$this->page->addVar('villes', $managerVille->getAllVilles());
					}
				}
			}
		}
	}
	
	public function executeCreerEtablissement($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		// On vérifie que l'utilisateur est bien identifié et administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$newNom = $request->getPostData('newNom');
			$newAdresse = $request->getPostData('newAdresse');
			$newIdVille = $request->getPostData('newIdVille');
			//on vérifie que les données ont bien été envoyé par post
			if($newNom === null || $newAdresse === null || $newIdVille === null || $newNom == '' || $newAdresse == '')
			{
				$user->getMessageClient()->addErreur('Tous les champs doivent être renseignés pour pouvoir créer un établissement.');
			}
			else
			{
				$ville = $this->getManagers()->getManagerOf('Ville')->getVilleById($newIdVille);
				if($ville === false)
				{	
					$user->getMessageClient()->addErreur('Veuillez sélectionner une ville.');
				}
				else
				{
					if($this->getManagers()->getManagerOf('Etablissement')->getEtablissementByNom($newNom))
					{
						$user->getMessageClient()->addErreur('Vous ne pouvez pas créer cet établissement. Celui-ci existe déjà.');
					}
					else
					{
						//on créé un instance Etablissement
						$newEtablissement = new \Library\Entities\Etablissement(array(
							'nomEtablissement' => $newNom, 
							'adresseEtablissement' => $newAdresse, 
							'ville' => $ville
							));
						//si la création à échouée
						if(count($newEtablissement->getErreurs()) != 0)
						{
							$user->getMessageClient()->addErreur($newEtablissement->getErreurs());
						}
						else
						{
							//si un établissement à déjà ce nom
							if($this->getManagers()->getManagerOf('Etablissement')->getEtablissementByNom($newNom))
							{
								$user->getMessageClient()->addErreur('Un établissement de ce nom existe déjà.');
							}
							else
							{
								$this->getManagers()->getManagerOf('Etablissement')->addEtablissement($newEtablissement);
								$user->getMessageClient()->addReussite('L\'établissement a bien été créé.');
							}
						}
					}
				}
			}
			$response->redirect('/PourAdminSeulement/Groupes/Etablissement/');
		}
	}
	
	public function executeSupprimerEtablissement($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		// On vérifie que l'utilisateur est bien identifié et administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idEtablissement = $request->getPostData('idEtablissement');
			//on vérifie que les données ont bien été envoyé par post
			if($idEtablissement === null)
			{
				$user->getMessageClient()->addErreur('Aucune donnée n\'a été transmise.');
				$response->redirect('/PourAdminSeulement/Groupes/Etablissement/');
			}
			else
			{
				$etablissement = $this->getManagers()->getManagerOf('Etablissement')->getEtablissementById($idEtablissement);
				$this->getManagers()->getManagerOf('Etablissement')->deleteEtablissement($etablissement);
				$user->getMessageClient()->addReussite('L\'établissement a bien été supprimé.');
				
				$response->redirect('/PourAdminSeulement/Groupes/Etablissement/');
			}
		}
	}
	
	public function executeChangerInfoEtablissement($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		// On vérifie que l'utilisateur est bien identifié et administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idEtablissement = $request->getPostData('idEtablissement');
			$newNom = $request->getPostData('newNom');
			$newAdresse = $request->getPostData('newAdresse');
			$newIdVille = $request->getPostData('newIdVille');
			//on vérifie que les données ont bien été envoyé par post
			if($idEtablissement === null || $newNom === null || $newNom === '' || $newAdresse === null || $newAdresse === '' || $newIdVille === null)
			{
				$user->getMessageClient()->addErreur('Attention, tous les champs sont obligatoires.');
				$response->redirect('/PourAdminSeulement/Groupes/Etablissement/idEtablissement='.$idEtablissement);
			}
			else
			{
				$etablissement = $this->getManagers()->getManagerOf('Etablissement')->getEtablissementById($idEtablissement);
				if($etablissement === false)
				{	
					$user->getMessageClient()->addErreur('Attention, cet établissement n\'existe plus.');
					$response->redirect('/PourAdminSeulement/Groupes/Etablissement/');
				}
				else
				{
					$newVille = $this->getManagers()->getManagerOf('Ville')->getVilleById($newIdVille);
					if($newVille === false)
					{
						$user->getMessageClient()->addErreur('Veuillez selectionner une ville.');
						$response->redirect('/PourAdminSeulement/Groupes/Etablissement/');
					}
					else
					{
						$nomDejaPris = $this->getManagers()->getManagerOf('Etablissement')->getEtablissementByNom($newNom);
						if($nomDejaPris && $nomDejaPris->getIdEtablissement() != $etablissement->getIdEtablissement())
						{
							$user->getMessageClient()->addErreur('Cet établissement existe déjà.');
						}
						else
						{
							//on change les infos
							$etablissement->setNomEtablissement($newNom);
							$etablissement->setAdresseEtablissement($newAdresse);
							$etablissement->setVille($newVille);
							//on met la base à joure
							$this->getManagers()->getManagerOf('Etablissement')->saveEtablissement($etablissement);
							$user->getMessageClient()->addReussite('Les informations ont été mises à jour.');
							//on redirige
						}
						$response->redirect('/PourAdminSeulement/Groupes/Etablissement/idEtablissement='.$etablissement->getIdEtablissement());
					}
				}
			}
		}
	}
	
	
	//partie laboratoire
	
	
	public function executeShowLaboratoire($request)
	{
		$user = $this->app->getUser();
		// On vérifie que l'utilisateur est bien identifié et administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			//on ajoute les etablissements à la vue
			$this->page->addVar('etablissements', $this->getManagers()->getManagerOf('Etablissement')->getAllEtablissements());
			
			
			$managerLaboratoire = $this->getManagers()->getManagerOf('Laboratoire');
			
			$this->page->addVar('allLaboratoires', $managerLaboratoire->getAllLaboratoires());
			
			//on récuper l'id passé en post
			$idLaboratoire = $request->getPostData('idLaboratoire');
			//si id passé en post
			if(isset($idLaboratoire))
			{
				//on passe l'id en get et on redirige
				$response = $this->app->getHTTPResponse();
				$response->redirect('/PourAdminSeulement/Groupes/Laboratoire/idLaboratoire='.$idLaboratoire);
			}
			else
			{
				//pour le choix des laboratoire
				
				$idEtablissement = $request->getPostData('idEtablissement');
				
				$managerEtablissement = $this->getManagers()->getManagerOf('Etablissement');
				$this->page->addVar('listeEtablissement', $managerEtablissement->getAllEtablissements());
				
				if(isset($idEtablissement))
				{
					$this->page->addVar('idEtablissement', $idEtablissement);
					
					$etablissement = $managerEtablissement->getEtablissementById($idEtablissement);
					$managerEtablissement->putLaboratoiresInEtablissement($etablissement);
					$this->page->addVar('listeLaboratoire', $etablissement->getLaboratoires());
				}
				
				
				$idLaboratoire = $request->getGetData('idLaboratoire');
				//si un id a été envoyé à la page en get
				if(isset($idLaboratoire))
				{
					$laboratoire = $managerLaboratoire->getLaboratoireById($idLaboratoire);
					//si un laboratoire à été récuperé
					if($laboratoire)
					{
						$this->page->addVar('laboratoire', $laboratoire);
					}
				}
			}
		}
	}
	
	public function executeCreerLaboratoire($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		// On vérifie que l'utilisateur est bien identifié et administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$newNom = $request->getPostData('newNom');
			$newUrl = $request->getPostData('newUrl');
			$newIdEtablissement = $request->getPostData('newIdEtablissement');
			//on vérifie que les données ont bien été envoyé par post
			if($newNom === null || $newUrl === null || $newIdEtablissement === null || $newNom == '' || $newUrl == '')
			{
				$user->getMessageClient()->addErreur('Attention, tous les champs sont obligatoires.');
				$response->redirect('/PourAdminSeulement/Groupes/Laboratoire/');
			}
			else
			{
				$etablissement = $this->getManagers()->getManagerOf('Etablissement')->getEtablissementById($newIdEtablissement);
				if($etablissement === false)
				{
					$user->getMessageClient()->addErreur('Veuillez selectionner un etablissement.');
					$response->redirect('/PourAdminSeulement/Groupes/Laboratoire/');
				}
				else
				{
					if($this->getManagers()->getManagerOf('Laboratoire')->getLaboratoireByNom($newNom))
					{
						$user->getMessageClient()->addErreur('Ce laboratoire existe déjà.');
						$response->redirect('/PourAdminSeulement/Groupes/Laboratoire/');
					}
					else
					{
						//on créé un instance Laboratoire
						$newLaboratoire = new \Library\Entities\Laboratoire(array(
							'nomLaboratoire' => $newNom, 
							'urlLaboratoire' => $newUrl, 
							'etablissement' => $etablissement
							));
						//si la création à échouée
						if(count($newLaboratoire->getErreurs()) != 0)
						{
							$user->getMessageClient()->addErreur($newLaboratoire->getErreurs());
							$response->redirect('/PourAdminSeulement/Groupes/Laboratoire/');
						}
						else
						{
							$this->getManagers()->getManagerOf('Laboratoire')->addLaboratoire($newLaboratoire);
							$user->getMessageClient()->addReussite('Le laboratoire a bien été créé.');
							$response->redirect('/PourAdminSeulement/Groupes/Laboratoire/');
						}
					}
				}
			}
		}
	}
	
	public function executeSupprimerLaboratoire($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		// On vérifie que l'utilisateur est bien identifié et administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idLaboratoire = $request->getPostData('idLaboratoire');
			//on vérifie que les données ont bien été envoyé par post
			if($idLaboratoire === null)
			{
				$user->getMessageClient()->addErreur('Attention, aucune donnée n\'a été transmise.');
				$response->redirect('/PourAdminSeulement/Groupes/Laboratoire/');
			}
			else
			{
				$laboratoire = $this->getManagers()->getManagerOf('Laboratoire')->getLaboratoireById($idLaboratoire);
				$this->getManagers()->getManagerOf('Laboratoire')->deleteLaboratoire($laboratoire);
				$user->getMessageClient()->addReussite('Le laboratoire a bien été supprimé.');
				$response->redirect('/PourAdminSeulement/Groupes/Laboratoire/');
			}
		}
	}
	
	public function executeChangerInfoLaboratoire($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		// On vérifie que l'utilisateur est bien identifié et administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idLaboratoire = $request->getPostData('idLaboratoire');
			$newNom = $request->getPostData('newNom');
			$newUrl = $request->getPostData('newUrl');
			$newIdEtablissement = $request->getPostData('newIdEtablissement');
			//on vérifie que les données ont bien été envoyé par post
			if($idLaboratoire === null || $newNom === null || $newUrl === null || $newIdEtablissement === null || $newNom == '' || $newUrl == '')
			{
				$user->getMessageClient()->addErreur('Attention, tous les champs sont obligatoires.');
				$response->redirect('/PourAdminSeulement/Groupes/Laboratoire/idLaboratoire='.$idLaboratoire);
			}
			else
			{
				$laboratoire = $this->getManagers()->getManagerOf('Laboratoire')->getLaboratoireById($idLaboratoire);
				if($laboratoire === false)
				{
					$response->redirect('/PourAdminSeulement/Groupes/Laboratoire/');
				}
				else
				{
					$newEtablissement = $this->getManagers()->getManagerOf('Etablissement')->getEtablissementById($newIdEtablissement);
					if($newEtablissement === false)
					{
						$user->getMessageClient()->addErreur('Veuillez selectionner un établissement.');
						$response->redirect('/PourAdminSeulement/Groupes/Laboratoire/');
					}
					else
					{
						$nomDejaPris = $this->getManagers()->getManagerOf('Laboratoire')->getLaboratoireByNom($newNom);
						if($nomDejaPris && $nomDejaPris->getIdLaboratoire() != $laboratoire->getIdLaboratoire())
						{
							$user->getMessageClient()->addErreur('Ce laboratoire existe déjà.');
							$response->redirect('/PourAdminSeulement/Groupes/Laboratoire/');
						}
						else
						{
							//on change les infos
							$laboratoire->setNomLaboratoire($newNom);
							$laboratoire->setUrlLaboratoire($newUrl);
							$laboratoire->setEtablissement($newEtablissement);
							//on met la base à joure
							$this->getManagers()->getManagerOf('Laboratoire')->saveLaboratoire($laboratoire);
							$user->getMessageClient()->addReussite('Les informations ont bien été mises à jour.');
							//on redirige
							$response->redirect('/PourAdminSeulement/Groupes/Laboratoire/idLaboratoire='.$laboratoire->getIdLaboratoire());
						}
					}
				}
			}
		}
	}
	
	
	
	//partie équipe
	
	
	public function executeShowEquipe($request)
	{
		$user = $this->app->getUser();
		// On vérifie que l'utilisateur est bien identifié et administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			//on ajoute les laboratoires à la vue
			$this->page->addVar('laboratoires', $this->getManagers()->getManagerOf('Laboratoire')->getAllLaboratoires());
			
			
			$managerEquipe = $this->getManagers()->getManagerOf('Equipe');
			
			$this->page->addVar('allEquipes', $managerEquipe->getAllEquipes());
			
			//on récuper l'id passé en post
			$idEquipe = $request->getPostData('idEquipe');
			//si id passé en post
			if(isset($idEquipe))
			{
				//on passe l'id en get et on redirige
				$response = $this->app->getHTTPResponse();
				$response->redirect('/PourAdminSeulement/Groupes/Equipe/idEquipe='.$idEquipe);
			}
			else
			{
				//pour le choix des équipes
					
				$idEtablissement = $request->getPostData('idEtablissement');
				$idLaboratoire = $request->getPostData('idLaboratoire');
				
				$managerEtablissement = $this->getManagers()->getManagerOf('Etablissement');
				$this->page->addVar('listeEtablissement', $managerEtablissement->getAllEtablissements());
				
				if(isset($idEtablissement))
				{
					$this->page->addVar('idEtablissement', $idEtablissement);
					
					$etablissement = $managerEtablissement->getEtablissementById($idEtablissement);
					$managerEtablissement->putLaboratoiresInEtablissement($etablissement);
					$this->page->addVar('listeLaboratoire', $etablissement->getLaboratoires());
					
					if(isset($idLaboratoire))
					{
						$this->page->addVar('idLaboratoire', $idLaboratoire);
						
						$managerLaboratoire = $this->getManagers()->getManagerOf('Laboratoire');
						$laboratoire = $managerLaboratoire->getLaboratoireById($idLaboratoire);
						$managerLaboratoire->putEquipesInLaboratoire($laboratoire);
						$this->page->addVar('listeEquipe', $laboratoire->getEquipes());
					}
				}
				
				$idEquipe = $request->getGetData('idEquipe');
				//si un id a été envoyé à la page en get
				if(isset($idEquipe))
				{
					$equipe = $managerEquipe->getEquipeById($idEquipe);
					//si un laboratoire à été récuperé
					if($equipe)
					{
						$this->page->addVar('equipe', $equipe);
					}
				}
			}
		}
	}
	
	public function executeCreerEquipe($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		// On vérifie que l'utilisateur est bien identifié et administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$newNom = $request->getPostData('newNom');
			$newIdLaboratoire = $request->getPostData('newIdLaboratoire');
			//on vérifie que les données ont bien été envoyé par post
			if($newNom === null || $newIdLaboratoire === null || $newNom == '')
			{
				$user->getMessageClient()->addErreur('Attention, tous les champs sont obligatoires.');
				$response->redirect('/PourAdminSeulement/Groupes/Equipe/');
			}
			else
			{
				$laboratoire = $this->getManagers()->getManagerOf('Laboratoire')->getLaboratoireById($newIdLaboratoire);
				if($laboratoire === false)
				{
					$user->getMessageClient()->addErreur('Veuillez selectionner un laboratoire.');
					$response->redirect('/PourAdminSeulement/Groupes/Equipe/');
				}
				else
				{
					if($this->getManagers()->getManagerOf('Equipe')->getEquipeByNom($newNom))
					{
						$user->getMessageClient()->addErreur('Cette équipe existe déjà.');
						$response->redirect('/PourAdminSeulement/Groupes/Equipe/');
					}
					else
					{
						//on créé un instance Equipe
						$newEquipe = new \Library\Entities\Equipe(array(
							'nomEquipe' => $newNom, 
							'laboratoire' => $laboratoire
							));
						//si la création à échouée
						if(count($newEquipe->getErreurs()) != 0)
						{
							$user->getMessageClient()->addErreur($newEquipe->getErreurs());
							$response->redirect('/PourAdminSeulement/Groupes/Equipe/');
						}
						else
						{
							$this->getManagers()->getManagerOf('Equipe')->addEquipe($newEquipe);
							$user->getMessageClient()->addReussite('L\'équipe a bien été créée.');
							$response->redirect('/PourAdminSeulement/Groupes/Equipe/');
						}
					}
				}
			}
		}
	}
	
	public function executeSupprimerEquipe($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		// On vérifie que l'utilisateur est bien identifié et administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idEquipe = $request->getPostData('idEquipe');
			//on vérifie que les données ont bien été envoyé par post
			if($idEquipe === null)
			{
				$user->getMessageClient()->addErreur('Attention, aucune donnée n\'a été transmise.');
				$response->redirect('/PourAdminSeulement/Groupes/Equipe/');
			}
			else
			{
				$equipe = $this->getManagers()->getManagerOf('Equipe')->getEquipeById($idEquipe);
				$this->getManagers()->getManagerOf('Equipe')->deleteEquipe($equipe);
				$user->getMessageClient()->addReussite('L\'équipe a bien été supprimée.');
				$response->redirect('/PourAdminSeulement/Groupes/Equipe/');
			}
		}
	}
	
	public function executeChangerInfoEquipe($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		// On vérifie que l'utilisateur est bien identifié et administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idEquipe = $request->getPostData('idEquipe');
			$newNom = $request->getPostData('newNom');
			$newIdLaboratoire = $request->getPostData('newIdLaboratoire');
			//on vérifie que les données ont bien été envoyé par post
			if($idEquipe === null || $newNom === null || $newIdLaboratoire === null || $newNom == '')
			{
				$user->getMessageClient()->addErreur('Attention, tous les champs sont obligatoires.');
				$response->redirect('/PourAdminSeulement/Groupes/Equipe/idEquipe='.$idEquipe);
			}
			else
			{
				$equipe = $this->getManagers()->getManagerOf('Equipe')->getEquipeById($idEquipe);
				if($equipe === false)
				{
					$response->redirect('/PourAdminSeulement/Groupes/Equipe/');
				}
				else
				{
					$newLaboratoire = $this->getManagers()->getManagerOf('Laboratoire')->getLaboratoireById($newIdLaboratoire);
					if($newLaboratoire === false)
					{
						$user->getMessageClient()->addErreur('Veuillez selectionner un laboratoire.');
						$response->redirect('/PourAdminSeulement/Groupes/Equipe/');
					}
					else
					{
						$nomDejaPris = $this->getManagers()->getManagerOf('Equipe')->getEquipeByNom($newNom);
						if($nomDejaPris && $nomDejaPris->getIdEquipe() != $equipe->getIdEquipe())
						{
							$user->getMessageClient()->addErreur('Cette équipe existe déjà.');
							$response->redirect('/PourAdminSeulement/Groupes/Equipe/');
						}
						else
						{
							//on change les infos
							$equipe->setNomEquipe($newNom);
							$equipe->setLaboratoire($newLaboratoire);
							//on met la base à joure
							$this->getManagers()->getManagerOf('Equipe')->saveEquipe($equipe);
							$user->getMessageClient()->addReussite('Les informations ont bien été mises à jour.');
							//on redirige
							$response->redirect('/PourAdminSeulement/Groupes/Equipe/idEquipe='.$equipe->getIdEquipe());
						}
					}
				}
			}
		}
	}
	
	
	//partie ville
	
	
	public function executeShowVille($request)
	{
		$user = $this->app->getUser();
		// On vérifie que l'utilisateur est bien identifié et administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			//on récuper l'id passé en post
			$idVille = $request->getPostData('idVille');
			//si id passé en post
			if(isset($idVille))
			{
				//on passe l'id en get et on redirige
				$response = $this->app->getHTTPResponse();
				$response->redirect('/PourAdminSeulement/Groupes/Ville/idVille='.$idVille);
			}
			else
			{
				$this->page->addVar('allPays', $this->getManagers()->getManagerOf('Pays')->getAllPays());
				
				$idPays = $request->getPostData('idPays');
				if(!isset($idPays))
				{
					// par défaut sur la france (id = 65)
					$idPays = 65;
				}
				$this->page->addVar('idPays', $idPays);
				
				$pays = $this->getManagers()->getManagerOf('Pays')->getPaysById($idPays);
				$this->getManagers()->getManagerOf('Pays')->putVillesInPays($pays);
				
				$this->page->addVar('listeVille', $pays->getVilles());
				
				$idVille = $request->getGetData('idVille');
				
				if(isset($idVille))
				{
					$ville = $this->getManagers()->getManagerOf('Ville')->getVilleById($idVille);
					
					if(!$ville)
					{
						$user->getMessageClient()->addErreur('Attention, cette ville n\'existe pas.');
					}
					else
					{
						$this->page->addVar('ville', $ville);
					}
				}
			}
		}
	}
	
	public function executeChangerInfoVille($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		// On vérifie que l'utilisateur est bien identifié et administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idVille = $request->getPostData('idVille');
			$ville = $this->getManagers()->getManagerOf('Ville')->getVilleById($idVille);
			$nom = $request->getPostData('nom');
			$idPays = $request->getPostData('idPays');
			$pays = $this->getManagers()->getManagerOf('Pays')->getPaysById($idPays);
			
			if(!isset($idVille) || !isset($ville) || !isset($nom) || !isset($idPays) || !isset($pays) || $nom == '')
			{
				$user->getMessageClient()->addErreur('Attention, tous les champs sont obligatoires.');
				$response->redirect('/PourAdminSeulement/Groupes/Ville/idVille='.$idVille);
			}
			else
			{
				$nomDejaPris = $this->getManagers()->getManagerOf('Ville')->getVilleByNom($nom);
				if($nomDejaPris && $nomDejaPris->getIdVille() != $ville->getIdVille())
				{
					$user->getMessageClient()->addErreur('Attention, cette ville existe déjà.');
					$response->redirect('/PourAdminSeulement/Groupes/Ville/');
				}
				else
				{
					$ville->setNomVille($nom);
					$ville->setPays($pays);
					
					if(count($ville->getErreurs()) != 0)
					{
						$user->getMessageClient()->addErreur($ville->getErreurs());
						$response->redirect('/PourAdminSeulement/Groupes/Ville/');
					}
					else
					{
						$this->getManagers()->getManagerOf('Ville')->saveVille($ville);
						$user->getMessageClient()->addReussite('Les informations ont bien été mises à jour.');
						$response->redirect('/PourAdminSeulement/Groupes/Ville/idVille='.$ville->getIdVille());
					}
				}
			}
		}
	}
	
	public function executeCreerVille($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		// On vérifie que l'utilisateur est bien identifié et administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$nom = $request->getPostData('nom');
			$idPays = $request->getPostData('idPays');
			$pays = $this->getManagers()->getManagerOf('Pays')->getPaysById($idPays);
			
			if(!isset($nom) || !isset($idPays) || !isset($pays) || $nom == '')
			{
				$user->getMessageClient()->addErreur('Attention, tous les champs sont obligatoires.');
				$response->redirect('/PourAdminSeulement/Groupes/Ville/');
			}
			else
			{
				if($this->getManagers()->getManagerOf('Ville')->getVilleByNom($nom))
				{
					$user->getMessageClient()->addErreur('Cette ville existe déjà.');
					$response->redirect('/PourAdminSeulement/Groupes/Ville/');
				}
				else
				{
					$ville = new \Library\Entities\Ville(array(
						'nomVille' => $nom, 
						));
					
					$ville->setPays($pays);
					
					if(count($ville->getErreurs()) != 0)
					{
						$user->getMessageClient()->addErreur($ville->getErreurs());
						$response->redirect('/PourAdminSeulement/Groupes/Ville/');
					}
					else
					{
						$this->getManagers()->getManagerOf('Ville')->addVille($ville);
						$user->getMessageClient()->addReussite('La ville a bien été créée.');
						$response->redirect('/PourAdminSeulement/Groupes/Ville/idVille='.$ville->getIdVille());
					}
				}
			}
		}
	}
	
	public function executeSupprimerVille($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		// On vérifie que l'utilisateur est bien identifié et administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idVille = $request->getPostData('idVille');
			$ville = $this->getManagers()->getManagerOf('Ville')->getVilleById($idVille);
			
			if(!isset($idVille) || !isset($ville))
			{
				$user->getMessageClient()->addErreur('Attention, aucune donnée n\'a été transmise.');
				$response->redirect('/PourAdminSeulement/');
			}
			else
			{
				$this->getManagers()->getManagerOf('Ville')->deleteVille($ville);
				$user->getMessageClient()->addReussite('La ville a bien été supprimée.');
				$response->redirect('/PourAdminSeulement/Groupes/Ville/');
			}
		}
	}
	
	
	//section pays
	
	
	public function executeShowPays($request)
	{
		$user = $this->app->getUser();
		// On vérifie que l'utilisateur est bien identifié et administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			//on récuper l'id passé en post
			$idPays = $request->getPostData('idPays');
			//si id passé en post
			if(isset($idPays))
			{
				//on passe l'id en get et on redirige
				$response = $this->app->getHTTPResponse();
				$response->redirect('/PourAdminSeulement/Groupes/Pays/idPays='.$idPays);
			}
			else
			{
				$this->page->addVar('allPays', $this->getManagers()->getManagerOf('Pays')->getAllPays());
				
				$idPays = $request->getGetData('idPays');
				
				if(isset($idPays))
				{
					$pays = $this->getManagers()->getManagerOf('Pays')->getPaysById($idPays);
					
					if(!$pays)
					{
						$user->getMessageClient()->addErreur('Attention, ce pays n\'existe pas.');
					}
					else
					{
						$this->page->addVar('pays', $pays);
					}
				}
			}
		}
	}
	
	public function executeChangerInfoPays($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		// On vérifie que l'utilisateur est bien identifié et administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idPays = $request->getPostData('idPays');
			$pays = $this->getManagers()->getManagerOf('Pays')->getPaysById($idPays);
			$nom = $request->getPostData('nom');
			
			if(!isset($idPays) || !isset($pays) || !isset($nom) || $nom == '')
			{
				$user->getMessageClient()->addErreur('Attention, tous les champs sont obligatoires.');
				$response->redirect('/PourAdminSeulement/Groupes/Pays/idPays='.$idPays);
			}
			else
			{
				$nomDejaPris = $this->getManagers()->getManagerOf('Pays')->getPaysByNom($nom);
				if($nomDejaPris && $nomDejaPris->getIdPays() != $pays->getIdPays())
				{
					$user->getMessageClient()->addErreur('Ce pays existe déjà.');
					$response->redirect('/PourAdminSeulement/Groupes/Pays/');
				}
				else
				{
					$pays->setNomPays($nom);
					
					if(count($pays->getErreurs()) != 0)
					{
						$user->getMessageClient()->addErreur($pays->getErreurs());
						$response->redirect('/PourAdminSeulement/Groupes/Pays/');
					}
					else
					{
						$this->getManagers()->getManagerOf('Pays')->savePays($pays);
						$user->getMessageClient()->addReussite('Les informations ont bien été mises à jour.');
						$response->redirect('/PourAdminSeulement/Groupes/Pays/idPays='.$pays->getIdPays());
					}
				}
			}
		}
	}
	
	public function executeCreerPays($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		// On vérifie que l'utilisateur est bien identifié et administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$nom = $request->getPostData('nom');
			
			if(!isset($nom) || $nom == '')
			{
				$user->getMessageClient()->addErreur('Attention, tous les champs sont obligatoires.');
				$response->redirect('/PourAdminSeulement/Groupes/Pays/');
			}
			else
			{
				if($this->getManagers()->getManagerOf('Pays')->getPaysByNom($nom))
				{
					$user->getMessageClient()->addErreur('Ce pays existe déjà.');
					$response->redirect('/PourAdminSeulement/Groupes/Pays/');
				}
				else
				{
					$pays = new \Library\Entities\Pays(array(
							'nomPays' => $nom, 
							));
					
					if(count($pays->getErreurs()) != 0)
					{
						$user->getMessageClient()->addErreur($pays->getErreurs());
						$response->redirect('/PourAdminSeulement/Groupes/Pays/');
					}
					else
					{
						$this->getManagers()->getManagerOf('Pays')->addPays($pays);
						$user->getMessageClient()->addReussite('Le pays a bien été créé.');
						$response->redirect('/PourAdminSeulement/Groupes/Pays/idPays='.$this->getManagers()->getManagerOf('Pays')->getPaysByNom($nom)->getIdPays());
					}
				}
			}
		}
	}
	
	public function executeSupprimerPays($request)
	{
		$user = $this->app->getUser();
		$response = $this->app->getHTTPResponse();
		// On vérifie que l'utilisateur est bien identifié et administrateur
		if(!$user->getAttribute('isAdmin'))
		{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');
		}
		else
		{
			$idPays = $request->getPostData('idPays');
			$pays = $this->getManagers()->getManagerOf('Pays')->getPaysById($idPays);
			
			if(!isset($idPays) || !isset($pays))
			{
				$user->getMessageClient()->addErreur('Attention, aucun pays n\'a été mentionné.');
				$response->redirect('/PourAdminSeulement/');
			}
			else
			{
				$this->getManagers()->getManagerOf('Pays')->deletePays($pays);
				$user->getMessageClient()->addReussite('Le pays a bien été supprimé.');
				$response->redirect('/PourAdminSeulement/Groupes/Pays/');
			}
		}
	}
}