<?php
namespace Library;

// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP Config pour gérer les fichiers de configuration des   	  |
// | applications constituant l'architecture de la plateforme NooLib.     |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>  			  |
// | 		  Guénaël DEQUEKER <dequekerguenael@noolib.com>               |
// | 		  Steve DESPRES	   <despressteve@noolib.com>                  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe Config
 * @access: public
 * @version: 1
 */

class Config extends ApplicationComponent{
	
	protected $vars = array();
	
	/**
	* Permet de récupérer une variable du fichier de configuration
	*/
	
	public function getVar($categorie, $sousCategorie='', $variable=''){

		//Si le tableau des variables est vide
		if(empty($this->vars)){
			//On récupère les variables
			$this->putVars();
		}

		//Si la catégorie n'est pas renseigné
		if( empty($categorie)){
		
			return null;
		//Si la catégorie est renseignée	
		}else{
			//Et que la sous catégorie n'est pas renseignée
			if( empty($sousCategorie)){
			
				//Si la variable n'est pas renseignée
				if(empty($variable)){
			
					//Si la categorie existe
					if(isset($this->vars[$categorie])){
						
						//On retourne la tableau de la catégorie
						return $this->vars[$categorie];
			
					}else{
				
						return null;
					}			
				}else{
				
					return null;
				}
			//Et que la sous catégorie est aussi renseignée
			}else{
					//Si la variable n'est pas renseignée
					if(empty($variable)){
						//Si la categorie existe
						if(isset($this->vars[$categorie])){
							
							$donneesCategorie = $this->vars[$categorie];
							//Si la sous categorie existe
							if(isset($donneesCategorie[$sousCategorie])){
								//On retourne la sous catégorie
								return $donneesCategorie[$sousCategorie];
							
							}else{
								return null;
							}
								
						}else{
							return null;
						}
					//Si la variable est renseignée
					}else{
						//Si la catégorie existe				
						if(isset($this->vars[$categorie])){
						
							$donneesCategorie = $this->vars[$categorie];
							//Si la sous categorie existe
							if(isset($donneesCategorie[$sousCategorie])){
							
								$donneesSousCategorie = $donneesCategorie[$sousCategorie];
								//Si la variable existe
								if(isset($donneesSousCategorie[$variable])){
									//On retourne la variable
									return $donneesSousCategorie[$variable];
								
								}else{
									return null;
								}
							}else{
								return null;
							}
						}else{
							return null;
						}
					
					}
			}
		}
	}
	
	/**
	* Permet de récupérer toutes les variable du fichier de configuration
	*/
	public function getVars(){

		if(empty($this->vars)){
			$this->putVars();
		}
		
		return $this->vars;
	}

	
	/**
	* Permet de créer un lien avec le fichier de configuration
	*/
	public function getSimpleXmlFile() {
	
		$xml = simplexml_load_file('../ConfigSystem/fichierDeConfiguration.xml');
		return $xml;
		
		}
	
	/**
	* Permet de sauver le fichier de configuration
	*/
	private function save($xml) {
	
		$xml->asXML('../ConfigSystem/fichierDeConfiguration.xml');
	}
	
	/**
	* Permet d'ajouter les variables de configuration dans le tableau de l'objet Config
	*/

	public function putVars(){
	
		//On récupère le document XML
		$xml = $this->getSimpleXmlFile();
		
		//On déclare 2 tableaux
		$varsCategorie = array();
		$varsSousCategorie = array();

			foreach( $xml as $categorie){
		
			//Pour chaque sous categorie
			foreach( $categorie as $sousCat){
				if( !empty($varsSousCategorie)){ $varsSousCategorie=null; }
				//Pour chaque definitions
				foreach($sousCat->define as $donneeSousCategorie){
				
					 //On récupère les attributs
					 $attributsSousCategorie = $donneeSousCategorie->attributes();
				
					 //On remplit le tabeau
					 $varsSousCategorie[(string)$attributsSousCategorie['var']] = (string) $attributsSousCategorie['value'];

					 $varsCategorieParent[$sousCat->getName()] = (array) $varsSousCategorie;
					 	
						$this->vars[$categorie->getName()] = $varsCategorieParent;
		
				}
	
			}
	
		unset($varsCategorieParent);
		}
		
		//Pour chaque catégorie
		foreach ( $xml as $cat){
			//Pour chaque definitions 
			foreach( $cat->define as $donneeCategorie){
				
				//On récupère les attributs
				$attributsCategorie = $donneeCategorie->attributes();
				if($attributsCategorie){
				//On remplit le tabeau
				$varsCategorie[(string) $attributsCategorie['var']] = (string) $attributsCategorie['value'];
				$this->vars[$cat->getName()] = (array) $varsCategorie;

			}}
			unset($varsCategorie);
		}
		
		//Pour chaque définitions à la racine du document xml
		foreach( $xml->define as $def){

				//On récupère les attributs
				$attributs = $def->attributes();
				//On les stocks dans un tableau de variables
				$this->vars[(string)$attributs['var']] = (string) $attributs['value'];
		
		}
		
	}
	
	/**
	* Permet d'ajouter une variable dans le fichier de configuration
	*/
	public function addVar($var, $value, $categorie='', $sousCategorie='') {
		
		//On récupère le document XML
		$xml = $this->getSimpleXmlFile();

		//Si le tableau des variables est vide
		if(empty($this->vars)){
			//On récupère les variables
			$this->putVars();
		}
		
		//Si la categorie n'est pas renseignée
		if(empty($categorie)){

			//Ni la sous catégorie
			if(empty($sousCategorie)){
				//On créer un nouveau define 
				$define = $xml->addChild('define');
				$define->addAttribute('var', $var);
				$define->addAttribute('value', $value);
			
			}else{			
				return null;
			}
		
		//Si la catégorie est renseignée
		}else{
			//Si la catégorie existe
			if(isset($this->vars[$categorie])){			
				//On récupère le tableau de variables de la catégorie
				$donneesCategorie = $this->vars[$categorie];
				
				//Si la sous catégorie n'est pas renseignée
				if(empty($sousCategorie)){
					//On créer un nouveau define 
					$define = $xml->$categorie->addChild('define');
					$define->addAttribute('var', $var);
					$define->addAttribute('value', $value);
				//Sinon	
				}else{
					
					//Si la sous catégorie existe
					if(isset($donneesCategorie[$sousCategorie])){
						//On créer un nouveau define
						$define = $xml->$categorie->$sousCategorie->addChild('define');
						$define->addAttribute('var', $var);
						$define->addAttribute('value', $value);
					

					}else{
						return null;
					}
				}		
			}else{
				return null;
			}
				
		}
		$this->save($xml);			
	}
	
	/**
	* Permet de modifier une variable dans le fichier de configuration
	*/
	public function changerVar($var, $value, $categorie='', $sousCategorie='') {
	
		//On récupère le document XML
			$xml = $this->getSimpleXmlFile();
			//Si le tableau des variables est vide
			if(empty($this->vars)){
				//On récupère les variables
				$this->putVars();
			}
						
			//Si la catégorie est renseignée
			if($categorie!=''){
				//Si la sous categorie est renseignée
				if($sousCategorie!=''){
					//Si la categorie existe
					if(isset($this->vars[$categorie])){
						$varsCategorie = $this->vars[$categorie];
							//Si la sous catégorie existe
							if(isset($varsCategorie[$sousCategorie])){
								//On reset l'identifiant de la définition
									$id=0;
									
									//Pour chaque definitions
									foreach($xml->$categorie->$sousCategorie->define as $donneeSousCategorie){
					
										 //On récupère les attributs
										 $attributsSousCategorie = $donneeSousCategorie->attributes();

										//Si c'est la variable recherchée
										if((string)$attributsSousCategorie['var'] == $var){
											//On modifie sa valeur
											$attributsSousCategorie['value']=$value;
											$this->save($xml);	
											$this->putVars();
											break;	
										}
											$id++;
									}	
							}else{							
								$user->getMessageClient()->addErreur('La sous catégorie n\'existe pas');							
							}							
					}else{				
						$user->getMessageClient()->addErreur('La catégorie n\'existe pas');
					}
				//Sinon
				}else{	
				
					if(isset($this->vars[$categorie])){
					//On reset l'identifiant de la définition
					$id=0;
					foreach($xml->$categorie->define as $donneeCategorie){
							 //On récupère les attributs
							 $attributsCategorie = $donneeCategorie->attributes();
						
							//Si c'est la variable recherchée
							if((string)$attributsCategorie['var'] == $var){
							
								//On modifie sa valeur
								$attributsCategorie['value']=$value;
								$this->save($xml);
								$this->putVars();
								break;
							}
						$id++;
				
				}
				}else{
					$user->getMessageClient()->addErreur('La catégorie n\'existe pas');
				}
				}
				
				
			//Si la catégorie n'est pas renseigné
			}else{
			
				//Si la sous categorie est renseignée
				if($sousCategorie!='' ){
					//Erreur
					$user->getMessageClient()->addErreur('Une erreur s\'est produite');
				//Sinon
				}else{
						//On reset l'identifiant de la définition
						$id=0;
						//Pour chaque définitions à la racine du document xml
						foreach( $xml->define as $def){
							//On récupère les attributs
							$attributs = $def->attributes();
						
							//Si le nom de la variable correspond à celui de celle a supprimé
							if($attributs['var'] == $var){

								//On modifie sa valeur
								$attributs['value']=$value;
								$this->save($xml);
								$this->putVars();
								break;
							
							}
							//On incrémente l'identifiant de la définition
							$id++;
						}
				}
		}
		
		$this->save($xml);
			
		}

	/**
	* Permet de supprimer une variable dans le fichier de configuration
	*/
	public function supprimerVar($var, $categorie='', $sousCategorie='') {

				//On récupère le document XML
				$xml = $this->getSimpleXmlFile();
				
				
				//Si le tableau des variables est vide
				if(empty($this->vars)){
					//On récupère les variables
					$this->putVars();
				}
				
				//Si la catégorie est renseignée
				if($categorie!=''){
					//Si la sous categorie est renseignée
					if($sousCategorie!=''){
						//Si la categorie existe
						if(isset($this->vars[$categorie])){
							$varsCategorie = $this->vars[$categorie];
								//Si la sous categorie existe
								if(isset($varsCategorie[$sousCategorie])){
								
									//On reset l'identifiant de la définition
									$id=0;
						
									//Pour chaque definitions
									foreach($xml->$categorie->$sousCategorie->define as $donneeSousCategorie){

										 //On récupère les attributs
										 $attributsSousCategorie = $donneeSousCategorie->attributes();

										//Si c'est la variable recherchée
										if((string)$attributsSousCategorie['var'] == $var){
										
											unset($xml->$categorie->$sousCategorie->define[$id]);
											$this->save($xml);	
											$this->putVars();
											break;
											
										}
											$id++;
									}
		
								}else{
									$user->getMessageClient()->addErreur('La sous catégorie n\'existe pas');								
								}
							}else{
								$user->getMessageClient()->addErreur('La catégorie n\'existe pas');
							}
					//Sinon
					}else{
					
						if(isset($this->vars[$categorie])){
							//On reset l'identifiant de la définition
							$id=0;
							foreach($xml->$categorie->define as $donneeCategorie){

									 //On récupère les attributs
									 $attributsCategorie = $donneeCategorie->attributes();
								
									//Si c'est la variable recherchée
									if((string)$attributsCategorie['var'] == $var){
									
										//On supprime sa définition
										Unset($xml->$categorie->define[$id]);
										$this->save($xml);
										$this->putVars();
										break;
									}
								$id++;
							}
						}else{						
							$user->getMessageClient()->addErreur('La catégorie n\'existe pas');
						}
					}
				//Si la catégorie n'est pas renseigné
				}else{
				
					//Si la sous categorie est renseignée
					if($sousCategorie!='' ){
						//Erreur
						$user->getMessageClient()->addErreur('Une erreur s\'est produite');
					//Sinon
					}else{
							//On reset l'identifiant de la définition
							$id=0;
							//Pour chaque définitions à la racine du document xml
							foreach( $xml->define as $def){

								//On récupère les attributs
								$attributs = $def->attributes();
								//Si le nom de la variable correspond à celui de celle a supprimé
								if($attributs['var'] == $var){
									//On supprime la définition
									Unset($xml->define[$id]);
									$this->save($xml);
									$this->putVars();
									break;
								}
								//On incrémente l'identifiant de la définition
								$id++;
							}
					}
				}
			$this->save($xml);
	}
			
	
		/**
	* Permet d'ajouter une catégorie
	*/
	public function supprimerCat($categorie) {
		
			//On récupère le document XML
				$xml = $this->getSimpleXmlFile();
				
				
				//Si le tableau des variables est vide
				if(empty($this->vars)){
					//On récupère les variables
					$this->putVars();
				}
				
				//Si la catégorie est renseignée
				if($categorie!=''){
					//Si la categorie existe
					if(isset($this->vars[$categorie])){

						unset($xml->$categorie);
					
					}else{
					
					$user->getMessageClient()->addErreur('La catégorie à supprimer n\'existe pas');
				}

				//Si la catégorie n'est pas renseigné
				}else{
				
					$user->getMessageClient()->addErreur('Entrer le nom de la catégorie à supprimer');
				}
				
			$this->save($xml);
	
		}
	
	
		/**
	* Permet d'ajouter une catégorie
	*/
	public function modifierCat($categorie, $nouveauNomCategorie) {	
	
	
			//On récupère le document XML
			$xml = $this->getSimpleXmlFile();
			
			$user = $this->app->getUser();
		
			//On récupère les variables
			$this->putVars();
			
			$etatSuppression = false;
			
				//Si la catégorie est renseignée
				if($categorie!=''){
					//Si son nouveau nom est renseigné
					if($nouveauNomCategorie!=''){
						//Si la catégorie existe
						if(isset($this->vars[$categorie])){
							//Si la nouvelle catégorie n'existe pas déjà
							if(!isset($this->vars[$nouveauNomCategorie])){
							
								$sousCats = $xml->$categorie->count();
							
								$xml->addChild($nouveauNomCategorie) ;
								
								
									foreach($xml->$categorie->children() as $sousCategorie){
									
										$xml->$nouveauNomCategorie->addChild($sousCategorie->getName());
									
										
										foreach($sousCategorie as $def){
										
											$nomSousCat = $sousCategorie->getName();
											$attributs = $def->attributes();
										
											$define = $xml->$nouveauNomCategorie->$nomSousCat->addChild('define');
											$define -> addAttribute('var', $attributs['var']);
											$define -> addAttribute('value', $attributs['value']);
											
											$etatSuppression = true;
										}
									}
							}else{
								$user->getMessageClient()->addErreur('Ce nom de catégorie existe déjà');
							}
						}else{
							$user->getMessageClient()->addErreur('Cette catégorie n\'existe pas');
						
						}
					
				//Si la catégorie n'est pas renseigné
				}else{
				
					$user->getMessageClient()->addErreur('La catégorie à supprimer n\'existe pas');
				}
				
				}else{
				
					$user->getMessageClient()->addErreur('La catégorie à supprimer n\'est pas renseignée');
				
				}
			if($etatSuppression){
				unset($xml->$categorie);	
			}
			$this->save($xml);
			
			
		}
	
	
	
	
		/**
	* Permet d'ajouter une catégorie
	*/
	public function ajouterSousCat($nomSousCategorie, $categorie, $var, $value) {	
	
				//On récupère le document XML
				$xml = $this->getSimpleXmlFile();
				
				$user = $this->app->getUser();
				
				//Si le tableau des variables est vide
				if(empty($this->vars)){
					//On récupère les variables
					$this->putVars();
				}
				
				//Si la catégorie est renseignée
				if($categorie!=''){
					//Si la sous categorie est renseignée
					if($nomSousCategorie!=''){
						//SI la catégorie existe
						if(isset($this->vars[$categorie])){
							$varsCategorie = $this->vars[$categorie];
							//Si la sous catégorie n'existe pas déjà
							if(!isset($varsCategorie[$nomSousCategorie])){
								//On ajoute la sous catégorie
								$xml->$categorie->addChild($nomSousCategorie);
								
								$define = $xml->$categorie->$nomSousCategorie->addChild('define');
								$define -> addAttribute('var', $var);
								$define -> addAttribute('value', $value);
								
							}else{								
								$user->getMessageClient()->addErreur('Cette sous catégorie existe déjà');
							}
						}else{
							$user->getMessageClient()->addErreur('La catégorie n\'existe pas');						
						}
					}else{
						$user->getMessageClient()->addErreur('Entrez le nom de l sous catégorie');
					}
				}else{
				
					$user->getMessageClient()->addErreur('La categorie n\'est pas renseignée');
				}
			$this->save($xml);
	}
	
		/**
	* Permet d'ajouter une catégorie
	*/
	public function supprimerSousCat($sousCategorie, $categorie) {	
	
	
	
			//On récupère le document XML
			$xml = $this->getSimpleXmlFile();
			
			
			//Si le tableau des variables est vide
			if(empty($this->vars)){
				//On récupère les variables
				$this->putVars();
			}
			
			//Si la catégorie est renseignée
			if($categorie!=''){
				//Si la sous categorie est renseignée
				if($sousCategorie!=''){
					//Si la categorie existe
					if(isset($this->vars[$categorie])){
						$varsCategorie = $this->vars[$categorie];
						//Si la sous categorie existe
						if($varsCategorie[$sousCategorie]){
							//on la détruit
							unset($xml->$categorie->$sousCategorie);
							
						}else{
							$user->getMessageClient()->addErreur('La sous catégorie à supprimer n\'existe pas');
						}
					}else{
						$user->getMessageClient()->addErreur('Lacatégorie à supprimer n\'existe pas');
					}
				}else{
					$user->getMessageClient()->addErreur('La sous catégorie n\'est pas renseignée');
				}
			//Si la catégorie n'est pas renseigné
			}else{
				$user->getMessageClient()->addErreur('La catégorie n\'est pas renseignée');
			}
			
		$this->save($xml);
	}
	
	

		/**
	* Permet d'ajouter une catégorie
	*/
	public function modifierSousCat($sousCategorie, $categorie, $nouveauNomSousCategorie) {

			//On récupère le document XML
			$xml = $this->getSimpleXmlFile();
			$user = $this->app->getUser();
			//Si le tableau des variables est vide
			if(empty($this->vars)){
				//On récupère les variables
				$this->putVars();
			}
			
			$etatSuppression = false;
			
			//Si la catégorie est renseignée
			if($categorie!=''){
			
				//Si la sous catégorie est renseignée
				if($sousCategorie!=''){
			
					//Si son nouveau nom est renseigné
					if($nouveauNomSousCategorie!=''){
					
						//Si la catégorie existe
						if(isset($this->vars[$categorie])){
							$varsCategorie = $this->vars[$categorie];
							
							//Si la sous categorie existe
							if(isset($varsCategorie[$sousCategorie])){
						
								//Si la nouvelle catégorie n'existe pas déjà
								if(!isset($varsCategorie[$nouveauNomSousCategorie])){
								
									//On créer la nouvelle sous catégorie
									$xml->$categorie->addChild($nouveauNomSousCategorie);
									//Pour chaque definitions
									foreach($xml->$categorie->$sousCategorie->children() as $def){
									
										$attributs = $def->attributes();
										//On créé les définitions
										$define = $xml->$categorie->$nouveauNomSousCategorie->addChild('define');
										$define->addAttribute('var', $attributs['var']);
										$define->addAttribute('value', $attributs['value']);
										$etatSuppression = true;
									}
									
								}else{
									
									$user->getMessageClient()->addErreur('Ce nom de catégorie existe déjà');
								}
							}else{
								$user->getMessageClient()->addErreur('Cette sous catégorie n\'existe pas');
							}
						}else{
							$user->getMessageClient()->addErreur('Cette catégorie n\'existe pas');
						
						}

					}else{
			
						$user->getMessageClient()->addErreur('Le nouveau nom de la sous catégorie n\'est pas renseigné');
					}
			
				}else{
					$user->getMessageClient()->addErreur('La sous catégorie n\'est pas renseignée');
			
				}
			}else{
				$user->getMessageClient()->addErreur('La catégorie n\'est pas renseignée');
			}
			
			if($etatSuppression){
				unset($xml->$categorie->$sousCategorie);
			}
			$this->save($xml);
		}

		/**
	* Permet d'ajouter une catégorie
	*/
	public function ajouterElements($var, $value, $categorie, $sousCategorie) {

			//On récupère le document XML
			$xml = $this->getSimpleXmlFile();
			// si l'utilisateur à acces
			$user = $this->app->getUser();
			
			//On récupère les variables
			$this->putVars();
		
			//Si la catégorie est renseignée
			if($categorie!=''){
			
				//Si la sous catégorie est renseignée
				if($sousCategorie!=''){					
						//Si la catégorie existe
						if(!isset($this->vars[$categorie])){
							//Si la variable est entré
							if( isset($var) && $var!=''){
								//Si la valeur est entrée
								if( isset($value) && $value!=''){
								
									//On ajoute la nouvelle catégorie
									$xml->addChild($categorie);
									//Elle récupère les données de la catégorie à renommée
									$xml->$categorie->addChild($sousCategorie);
									//On ajoute la variable
									$define = $xml->$categorie->$sousCategorie->addChild('define');
									$define->addAttribute('var', $var);
									$define->addAttribute('value', $value);
									
								}else{
									$user->getMessageClient()->addErreur('Entrez la valeur de la variable');
								}	
								
							}else{
								$user->getMessageClient()->addErreur('Entrez le nom de la variable');
							}

							
						}else{
							$user->getMessageClient()->addErreur('Cette catégorie existe déjà');
						
						}
			
				}else{
					$user->getMessageClient()->addErreur('La sous catégorie n\'est pas renseignée');
			
				}
			}else{
				$user->getMessageClient()->addErreur('La catégorie n\'est pas renseignée');
			}
				
			$this->save($xml);
		}
	
	
	/**
	* Permet de convertir une chaine de caractères en un tableau de données 
	* avec plusieurs délimiteurs
	*/
	public function multiexplode($delimiters, $string){
    
	    $ready = str_replace($delimiters, $delimiters[0], $string);
	    $launch = explode($delimiters[0], $ready);
	    return  $launch;
	}

	/**
	* Permet de valider le format d'une adresse mail
	*/
	
	public function validMail($mailUtilisateur) {
		
		if(!empty($mailUtilisateur)){
			$emailNonValides= explode(', ', $this->getVar('divers', 'divers', 'emailNonValides'));
		
			//On extrait le domaine du mail de l'utilisateur
			$donneesMailUtilisateur = explode('@', $mailUtilisateur);
			$domaineUtilisateur = $donneesMailUtilisateur[1];
			//On vérifie que son nom de domaine ne correspond pas à la liste d'emails jetables
			if(!in_array($domaineUtilisateur, $emailNonValides)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	

			
}