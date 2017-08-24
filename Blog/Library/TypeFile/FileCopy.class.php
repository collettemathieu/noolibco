<?php
namespace Library\TypeFile;

// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP FileCopy pour gérer la copie de fichiers/dossiers.		  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe FileCopy
 * @access: public
 * @version: 1
 */

class FileCopy extends \Library\File{
	
	use \Library\Traits\FonctionsUniverselles;
	
	/* Définition des attributs*/
	
	/**
	* Permet de copier une donnée utilisateur dans son espace protégé en droits.
	**/
	public function copyForUsingInSafeWorkspace($tabDonneeUtilisateur, $application){
		if($application instanceof \Library\Entities\Application){
			// On récupère l'utilisateur système
			$user = $this->app->getUser();
			// On récupère l'utilisateur en session
			$utilisateur = unserialize($user->getAttribute('userSession'));

			// On récupère l'auteur de l'application
			$auteur = $application->getCreateur();

			// On charge le fichier de configuration
			$config = $this->getApp()->getConfig();

			// On créé un nombre aléatoire pour l'associer au répertoire
			$nombre = rand(0,10000000);

			// On récupère le dossier workspace de l'utilisateur
			$folderUserSafeWorkspace = $utilisateur->getWorkSpaceFolderUtilisateur();
			/*Création du répertoire désirée
			  Pour créer une stucture imbriquée, le paramètre $recursive 
			  doit être spécifié. 0777 donne accès à tous les droits*/
			// Si le dossier n'existe pas, on le crée
			if(!is_dir($folderUserSafeWorkspace)){
				mkdir($folderUserSafeWorkspace, 0777, true);
			}

			
			// On construit le repertoire DonnéeUtilisateur
			$repertoireDonneeUtilisateur = $folderUserSafeWorkspace.'/Data/';

			/*Création du répertoire désirée
			  Pour créer une stucture imbriquée, le paramètre $recursive 
			  doit être spécifié. 0777 donne accès à tous les droits*/
			// Si le dossier n'existe pas, on le crée
			if(!is_dir($repertoireDonneeUtilisateur)){
				mkdir($repertoireDonneeUtilisateur, 0777, true);
			}
			
			$tabUrlDestinationDonneeUtilisateur = array();
			foreach($tabDonneeUtilisateur as $donneeUtilisateur){
				if($donneeUtilisateur instanceof \Library\Entities\DonneeUtilisateur && ! $donneeUtilisateur instanceof \Library\Entities\InputDonneeUtilisateur){
					// On tente de copier les données dans le répertoire temporaire
					$nombre = rand(0,10000000);
					$nouveauNomDonneeUtilisateur = $this->cleanFileName(substr($donneeUtilisateur->getNomDonneeUtilisateur(), 0, 3).$nombre.'.'.$donneeUtilisateur->getTypeDonneeUtilisateur()->getExtensionTypeDonneeUtilisateur());
					$urlDestinationDonneeUtilisateur = $repertoireDonneeUtilisateur.$nouveauNomDonneeUtilisateur;
					if(copy($donneeUtilisateur->getUrlDonneeUtilisateur(), $urlDestinationDonneeUtilisateur)){
						array_push($tabUrlDestinationDonneeUtilisateur, $urlDestinationDonneeUtilisateur);
					}else{
						$this->setErreurs('FILECOPY :: A system error has occurred while copying file.');
						return false;
					}
				}else{
					array_push($tabUrlDestinationDonneeUtilisateur, false);
				}
			}
			$this->setFilePath($tabUrlDestinationDonneeUtilisateur);

			// On copie l'application dans le safeWorkspace
			$source = $config->getVar('application','source');
			$mailAuteurProtege = $this->cleanFileName($auteur->getVariableFixeUtilisateur());
			$mailUserProtege = $this->cleanFileName($utilisateur->getVariableFixeUtilisateur());
			$nomAppProtegee = $this->cleanFileName($application->getVariableFixeApplication());
			$repertoireSourceApp = $source['filePath'].$mailAuteurProtege.'/'.$nomAppProtegee.'/';
			$repertoireDestinationApp = $folderUserSafeWorkspace.'/Sources/'.$nomAppProtegee.'/';
			
			// On copie les fichiers sources de l'application
			$this->copy_dir($repertoireSourceApp, $repertoireDestinationApp);

			// On modifie les droits
			//exec('chmod -R 777 '.$folderUserSafeWorkspace);

			// On modifie l'url des fichiers sources de l'objet application
			// On récupère la dernière version de l'application
			$version = $application->getVersions()[count($application->getVersions()) - 1];
			// On parcours les tâches de l'application
			$taches = $version->getTaches();
			foreach($taches as $tache){
				// On parcours les fonctions de la tâche
				$fonctions = $tache->getFonctions();
				foreach($fonctions as $fonction){
					$urlSource = $fonction->getUrlFonction();
					$filename = substr(strrchr($urlSource, "/"), 1);
					$fonction->setUrlFonction($repertoireDestinationApp.$filename);
				}
			}
			
			return $application;
		}else{
			$this->setErreurs('FILECOPY :: No application as parameter of the method.');
			return false;
		}
	}


	/**
	* Permet de copier un dossier avec ses sous-dossiers et fichier
	**/
	public function copy_dir($dir2copy,$dir_paste){
		if(!empty($dir2copy) && !empty($dir_paste)){
			// On vérifie si $dir2copy est un dossier
			if (is_dir($dir2copy)) {

				// Si oui, on l'ouvre
				if ($dh = opendir($dir2copy)) {     
					// On liste les dossiers et fichiers de $dir2copy
					while (($file = readdir($dh)) !== false) {
					    // Si le dossier dans lequel on veut coller n'existe pas, on le créé
					    if (!is_dir($dir_paste)){
					    	mkdir ($dir_paste, 0777, true);
					    }

						// S'il s'agit d'un dossier, on relance la fonction récursive
						if(is_dir($dir2copy.$file) && $file != '..'  && $file != '.'){
							copy_dir($dir2copy.$file.'/',$dir_paste.$file.'/');
						}elseif($file != '..'  && $file != '.'){ // S'il sagit d'un fichier, on le copie simplement
							copy($dir2copy.$file,$dir_paste.$file);
						}                                      
					}
				  // On ferme $dir2copy
				  closedir($dh);

				}

			}
		}
	}

}



