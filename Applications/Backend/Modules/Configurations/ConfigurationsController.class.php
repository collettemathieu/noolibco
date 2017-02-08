<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP comme controleur pour modifier le fichier XML de config   |
// +----------------------------------------------------------------------+
// | Auteurs : Guénaël DEQUEKER <dequekerguenael@noolib.com> 		      |
// | 		   Steve DESPRES	   <despressteve@noolib.com>              |
// +----------------------------------------------------------------------+

/**
 * @name: controleur des options de l'utilisateur pour le Frontend
 * @access: public
 * @version: 1
 */	


namespace Applications\Backend\Modules\Configurations;

class ConfigurationsController extends \Library\BackController
{
	private function access() {
		
		$user = $this->app->getUser();
		if(!$user->getAttribute('isAdmin')) {
			// On procède à la redirection
			$this->app->getHTTPResponse()->redirect('/PourAdminSeulement/');
			
			return false;
		} else {
			return true;
		}
	}
	
	public function executeShow() {

		if($this->access()) {
			// si l'utilisateur à acces
			$config = $this->getApp()->getConfig();
			$this->page->addVar('vars', $config->getVars());
		}
	}
	
	public function executeCreerConfigurations($request) {
		
		if($this->access()) {
		
			// si l'utilisateur à acces
			$user = $this->app->getUser();
			
			$var = $request->getPostData('var');
			$value = $request->getPostData('value');
			$categorie = $request->getPostData('categorie');
			$sousCategorie = $request->getPostData('sousCategorie');
			

			
			if(!isset($var) || !isset($value) || $var=='' || $value=='') {
			// si les données envoyé par post ne sont pas viables
				$user->getMessageClient()->addErreur('Les champs vides ne sont pas autorisés.');
				
			} else {
			
			
				if(!isset($categorie) || !isset($categorie) || $categorie=='' || $categorie==''){
				
				
				
						if(!isset($sousCategorie) || !isset($sousCategorie) || $sousCategorie=='' || $sousCategorie==''){
						
								
							// sinon
							$config = $this->getApp()->getConfig();
							
							if($config->getVar($categorie, $sousCategorie, $var) != null) {
							// si la varialbe existe deja dans le fichier de configuration
								$user->getMessageClient()->addErreur('La variable existe déjà.');
							} else {
							// sinon
								//on met le fichier de configue à jour :
								$config->addVar($var, $value);
								$user->getMessageClient()->addReussite('La variable a bien été créée.');
							}

						}else{
						
								$user->getMessageClient()->addErreur('Un problème est survenu.');
						
						}
				
				}else{
				
				
					if(!isset($sousCategorie) || !isset($sousCategorie) || $sousCategorie=='' || $sousCategorie==''){
					
							// sinon
							$config = $this->getApp()->getConfig();
							
							if($config->getVar($categorie, $sousCategorie, $var) != null) {
							// si la varialbe existe deja dans le fichier de configuration
								$user->getMessageClient()->addErreur('La variable existe déjà.');
							} else {
							// sinon
								//on met le fichier de configue à jour :
								$config->addVar($var, $value, $categorie);
								$user->getMessageClient()->addReussite('La variable a bien été créée.');
							}
					
					
					}else{
					
							// sinon
							$config = $this->getApp()->getConfig();
							
							if($config->getVar($categorie, $sousCategorie, $var) != null) {
							// si la varialbe existe deja dans le fichier de configuration
								$user->getMessageClient()->addErreur('La variable existe déjà.');
							} else {
							// sinon
								//on met le fichier de configue à jour :
								$config->addVar($var, $value, $categorie, $sousCategorie);
								$user->getMessageClient()->addReussite('La variable a bien été créée.');
							}
					
					
					}
				
				}
			}
			
			$this->app->getHTTPResponse()->redirect('/PourAdminSeulement/Configurations/');
		}
	}
	
	public function executeModifierConfigurations($request) {
		
		if($this->access()) {
		// si l'utilisateur à acces
			$user = $this->app->getUser();
			
			$var = $request->getPostData('var');
			$value = $request->getPostData('value');
			$categorie = $request->getPostData('categorie');
			$sousCategorie = $request->getPostData('sousCategorie');
			
			
			
			if(!isset($var) || !isset($value) || $var=='' || $value=='') {
			// si les données envoyé par post ne sont pas viables
				$user->getMessageClient()->addErreur('Les champs vides ne sont pas autorisés.');
			} else {
			
			
				if(!isset($categorie) || !isset($categorie) || $categorie=='' || $categorie==''){
				
				
				
						if(!isset($sousCategorie) || !isset($sousCategorie) || $sousCategorie=='' || $sousCategorie==''){
						
							// sinon
							$config = $this->getApp()->getConfig();
							//on met le fichier de configue à jour :
							$config->changerVar($var, $value);
							$user->getMessageClient()->addReussite('La variable a bien été modifiée.');
						

						}else{
							$user->getMessageClient()->addErreur('Une erreur est survenue');
						}

				}else{
			
						if(!isset($sousCategorie) || !isset($sousCategorie) || $sousCategorie=='' || $sousCategorie==''){
						
							// sinon
							$config = $this->getApp()->getConfig();
							//on met le fichier de configue à jour :
							$config->changerVar($var, $value, $categorie);
							$user->getMessageClient()->addReussite('La variable a bien été modifiée.');

						}else{
						
							// sinon
							$config = $this->getApp()->getConfig();
							//on met le fichier de configue à jour :
							$config->changerVar($var, $value, $categorie, $sousCategorie);
							$user->getMessageClient()->addReussite('La variable a bien été modifiée.');
						
						}
						
			
			
				}
			$this->app->getHTTPResponse()->redirect('/PourAdminSeulement/Configurations/');
		}
	}
	
	}
	
	public function executeSupprimerConfigurations($request) {
		
		if($this->access()) {
		// si l'utilisateur à acces
			$user = $this->app->getUser();
			
			$var = $request->getPostData('var');
			$categorie = $request->getPostData('categorie');
			$sousCategorie = $request->getPostData('sousCategorie');
		
			
			if(isset($var) && $var!='') {
			// si les données envoyé par post sont viables
				$config = $this->getApp()->getConfig();
				
				if( isset($categorie) && $categorie!=''){
				
					if( isset($sousCategorie) && $sousCategorie!=''){
					
						//on met le fichier de configue à jour :
						$config->supprimerVar($var, $categorie, $sousCategorie);
				
						$user->getMessageClient()->addReussite('La variable a bien été supprimée.');
					
					
					
					}else{
					
						//on met le fichier de configue à jour :
						$config->supprimerVar($var, $categorie);
				
						$user->getMessageClient()->addReussite('La variable a bien été supprimée.');
	
					}
		
				}else{
				
					//on met le fichier de configue à jour :
					$config->supprimerVar($var);
				
					$user->getMessageClient()->addReussite('La variable a bien été supprimée.');
				
				}
				
			}
			$this->app->getHTTPResponse()->redirect('/PourAdminSeulement/Configurations/');
		}
	}
	
	
	public function executeSupprimerCategorieConfigurations($request){
	
		if($this->access()) {
			// si l'utilisateur à acces
			$user = $this->app->getUser();
			
			
			$categorie = $request->getPostData('categorie');

		
			if(isset($categorie) && $categorie!='') {
			
						// si les données envoyé par post sont viables
						$config = $this->getApp()->getConfig();
						//on met le fichier de configue à jour :
						$config->supprimerCat($categorie);
				
						$user->getMessageClient()->addReussite('La catégorie à bien été supprimée');
	
			}else{
				$user->getMessageClient()->addErreur('Veuillez entrer le nom de la catégorie à supprimer');
			}
			
			$this->app->getHTTPResponse()->redirect('/PourAdminSeulement/Configurations/');
		}
	}
	
	public function executeModifierCategorieConfigurations($request){
	
		if($this->access()) {
			// si l'utilisateur à acces
			$user = $this->app->getUser();
			
			
			$categorie = $request->getPostData('categorie');
			$nouveauNomCategorie = $request->getPostData('nouveauNomCategorie');

			
		
			if(isset($categorie) && $categorie!='') {
			
				if(isset($nouveauNomCategorie) && $nouveauNomCategorie!='') {
				
					if( $categorie != $nouveauNomCategorie ){
				
						// si les données envoyé par post sont viables
						$config = $this->getApp()->getConfig();
						
						//on met le fichier de configue à jour :
						$config->modifierCat($categorie, $nouveauNomCategorie);
				
						$user->getMessageClient()->addReussite('La catégorie à bien été modifiée');
					}else{
					
						$user->getMessageClient()->addReussite('Le nom de la catégorie n\'a  pas été modifié');
					
					}
					
					
				}else{
					$user->getMessageClient()->addErreur('Veuillez entrer le nouveau nom de la catégorie');
				}
	
			}else{
				$user->getMessageClient()->addErreur('Veuillez choisir la catégorie à modifier');
			}
			
			$this->app->getHTTPResponse()->redirect('/PourAdminSeulement/Configurations/');
		}
	
	}
	
	public function executeAjouterSousCategorieConfigurations($request){
	
		if($this->access()) {
		
			// si l'utilisateur à acces
			$user = $this->app->getUser();
			
			$categorie = $request->getPostData('categorie');
			$nomSousCategorie = $request->getPostData('nomSousCategorie');
			$var = $request->getPostData('var');
			$value = $request->getPostData('value');
			

			if(isset($categorie) && $categorie!='') {

				if( isset($nomSousCategorie) && $nomSousCategorie!=''){
				
					if(isset($var) && $var!=''){
						
							if(isset($value) && $value!=''){
				
								// si les données envoyé par post sont viables
								$config = $this->getApp()->getConfig();
								
								$config->ajouterSousCat($nomSousCategorie, $categorie, $var, $value);
								
							}else{
								$user->getMessageClient()->addErreur('Entrez la valeur de la variable');
							}
						
					}else{
						$user->getMessageClient()->addErreur('Entrez le nom de la variable');
					}
		
				}else{
					$user->getMessageClient()->addErreur('Veuillez entrer le nom de la sous catégorie à créer');
				}
				
			}else{
				$user->getMessageClient()->addErreur('Veuillez entrer le nom de la catégorie');
			}

			$this->app->getHTTPResponse()->redirect('/PourAdminSeulement/Configurations/');
		}
	
	}
	
	public function executeSupprimerSousCategorieConfigurations($request){
	
	
			if($this->access()) {
		
			// si l'utilisateur à acces
			$user = $this->app->getUser();
			
			$categorie = $request->getPostData('categorie');
			$sousCategorie = $request->getPostData('sousCategorie');
		
			if(isset($categorie) && $categorie!='') {

				if( isset($sousCategorie) && $sousCategorie!=''){
				
						// si les données envoyé par post sont viables
						$config = $this->getApp()->getConfig();
						
						$config->supprimerSousCat($sousCategorie, $categorie);
		
				}else{
					$user->getMessageClient()->addErreur('Veuillez entrer le nom de la sous catégorie à supprimer');
				}
				
			}else{
				$user->getMessageClient()->addErreur('Veuillez entrer le nom de la catégorie');
			}
			
			
			$this->app->getHTTPResponse()->redirect('/PourAdminSeulement/Configurations/');
		}
		
	}
	
	
	
	public function executeModifierSousCategorieConfigurations($request){
	
				if($this->access()) {
		
			// si l'utilisateur à acces
			$user = $this->app->getUser();
			
			$categorie = $request->getPostData('categorie');
			$sousCategorie = $request->getPostData('sousCategorie');
			$nouveauNomSousCategorie = $request->getPostData('nouveauNomSousCategorie');
		
			if(isset($categorie) && $categorie!='') {

				if( isset($sousCategorie) && $sousCategorie!=''){
				
					if(isset($nouveauNomSousCategorie) && $nouveauNomSousCategorie!=''){
					
						if($sousCategorie != $nouveauNomSousCategorie){
				
							// si les données envoyé par post sont viables
							$config = $this->getApp()->getConfig();
							
							$config->modifierSousCat($sousCategorie, $categorie, $nouveauNomSousCategorie);
							
						}else{
						
							$user->getMessageClient()->addErreur('Le nom de la sous catégorie n\'a pas été modifié');
						
						}
						
					}else{
						$user->getMessageClient()->addErreur('Veuillez entrer le nom de la sous catégorie à modifier');
					}
				}else{
					$user->getMessageClient()->addErreur('Veuillez entrer le nom de la sous catégorie à créer');
				}
				
			}else{
				$user->getMessageClient()->addErreur('Veuillez entrer le nom de la catégorie');
			}
			
			
			$this->app->getHTTPResponse()->redirect('/PourAdminSeulement/Configurations/');
		}
	}
	
	
	public function executeAjouterElementsConfigurations($request){
	
	
		if($this->access()) {
			// si l'utilisateur à acces
			$user = $this->app->getUser();
			
			$categorie = $request->getPostData('categorie');
			$sousCategorie = $request->getPostData('sousCategorie');
			$var = $request->getPostData('var');
			$value = $request->getPostData('value');
			
			if(isset($categorie) && $categorie!='') {
				if(isset($sousCategorie) && $sousCategorie!=''){
					if(isset($var) && $var!=''){
						if(isset($value) && $value!=''){
							// si les données envoyé par post sont viables
							$config = $this->getApp()->getConfig();
							$config->ajouterElements($var, $value, $categorie, $sousCategorie);
							
							
						}else{
							$user->getMessageClient()->addErreur('Veuillez entrer la valeur de la variable');
						}
					}else{
						$user->getMessageClient()->addErreur('Veuillez entrer le nom de la variable à créer');
					}
				}else{
					$user->getMessageClient()->addErreur('Veuillez entrer le nom de la sous catégorie à créer');
				}
			}else{
				$user->getMessageClient()->addErreur('Veuillez entrer le nom de la catégorie à créer');
			}
			$this->app->getHTTPResponse()->redirect('/PourAdminSeulement/Configurations/');
		}
	}
	
}
