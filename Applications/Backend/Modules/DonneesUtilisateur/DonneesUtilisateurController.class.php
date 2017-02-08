<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Ce controleur permet d'afficher, moditier ou crée de nouveaux		  |
// | utilisateurs                                                         |
// +----------------------------------------------------------------------+
// | Auteurs :  Steve DESPRES    <despressteve@noolib.com> 		     	  |
// +----------------------------------------------------------------------+

/**
 * @name: controleur des données utilisateur
 * @access: public
 * @version: 1
 */	


namespace Applications\Backend\Modules\DonneesUtilisateur;

class DonneesUtilisateurController extends \Library\BackController
{
	use \Library\Traits\MethodeDonneeUtilisateurControleur;
	
	
	/**
	* Méthode pour afficher les données utilisateur
	*/
	
	public function executeShow($request)
	{
	
	
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin')){
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');
		
		}else{
		
		
			//On appelle le manager des Utilisateurs
			$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
			//On récupère tout les utilisateurs
			$utilisateurs = $managerUtilisateur->getAllUtilisateurs();
			
			//On passe tout les utilisateurs à la page 
			$this->page->addVar('utilisateurs', $utilisateurs);
			$ShowDonneesUtilisateur = false;
	
			//Si l'id en get
			if($request->getGetData('idUtilisateur')){
				
				//Si l'utilisateur existe
				if($managerUtilisateur->getUtilisateurById($request->getGetData('idUtilisateur'))){
					//On récupère l'utilisateur
					$utilisateurAAdministrer = $managerUtilisateur->getUtilisateurByIdWithAllData($request->getGetData('idUtilisateur'));
					
					// On calcul la taille des données de l'utilisateur
					// On affiche l'espace disponible des données
					$statDonneeUtilisateur = $this->executeEspaceDisponibleDonneeUtilisateur($utilisateurAAdministrer);
					
					//On récupère ses données 
					$donneesUtilisateur = $utilisateurAAdministrer->getDonneesUtilisateur();
					
					// On affiche les données utilisateur
					$this->page->addVar('ShowDonneesUtilisateur', true);
					$this->page->addVar('donneesUtilisateur', $donneesUtilisateur);

					// Si il y a des données utilisateur
					if(!sizeof($donneesUtilisateur)==0){
						
						//On passe toutes les variables utiles à la page 
						$this->page->addVar('utilisateurAAdministrer', $utilisateurAAdministrer);
						$this->page->addVar('tailleMaxDonneesUtilisateur', $statDonneeUtilisateur['tailleMaxDonneesUtilisateur']);
						$this->page->addVar('tailleMoDonneesUtilisateur', $statDonneeUtilisateur['tailleMoDonneesUtilisateur']);
						$this->page->addVar('progressionPourcent', $statDonneeUtilisateur['progressionPourcent']);
					}else{

					}
				}else{
					// On masque les données utilisateur
					$this->page->addVar('ShowDonneesUtilisateur', false);
				}
			}else{
				// On masque les données utilisateur
				$this->page->addVar('ShowDonneesUtilisateur', false);
			}
		}
	}
	
	/**
	* Méthode pour supprimer une donnée utilisateur
	*/
	public function executeSupprimerDonneeUtilisateur($request){

		//On récupère l'utilisateur
		$user = $this->app->getUser();
		//Si il n'est pas en Admin
		if(!$user->getAttribute('isAdmin')){
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/PourAdminSeulement/');
		//Si il est Admin
		}else{
		
			//Si l'id est en post
			if($request->getPostData('idDonneeUtilisateur') && $request->getPostData('idUtilisateur')){
			
			
				$idUtilisateur = $request->getPostData('idUtilisateur');
				//On supprime la donnée avec la méthode du trait
				$etatSuppressionDonneeUtilisateur = $this->executeDeleteDonneeUtilisateur($request);
				
				$response = $this->app->getHTTPResponse();
				$response->redirect('/PourAdminSeulement/DonneesUtilisateur/idUtilisateur='.$idUtilisateur);
				
			}else{
				$user->getMessageClient()->addErreur('La donnée que vous souhaitez supprimer n\'existe pas.');
				// On procède à la redirection
				$response = $this->app->getHTTPResponse();
				$response->redirect('/PourAdminSeulement/DonneesUtilisateur/');
			}
		}
	}
	
}