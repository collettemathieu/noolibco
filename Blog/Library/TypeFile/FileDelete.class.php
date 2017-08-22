<?php
namespace Library\TypeFile;

// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP FileDelete pour gérer la suppression de fichiers/dossiers.|
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe FileDelete
 * @access: public
 * @version: 1
 */

class FileDelete extends \Library\File{
	
	use \Library\Traits\FonctionsUniverselles;
	
	/* Définition des attributs*/
	
	/**
	* Permet de supprimer tous les fichiers d'une application
	**/
	public function deleteApplicationFile($application){
		if($application instanceof \Library\Entities\Application){
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On récupère l'utilisateur en session
			$utilisateur = unserialize($user->getAttribute('userSession'));

			// On charge le fichier de configuration
			$config = $this->getApp()->getConfig();

			$logo = $config->getVar('application','logo');
			$source = $config->getVar('application','source');

			$nomAppProtegee = $this->cleanFileName($application->getVariableFixeApplication());
			$mailUserProtege = $this->cleanFileName($utilisateur->getVariableFixeUtilisateur());
			$repertoireFichierImages = $logo['filePath'].$mailUserProtege.'/'.$nomAppProtegee.'/';
			$repertoireFichierSources = $source['filePath'].$mailUserProtege.'/'.$nomAppProtegee.'/';

			return $this->deleteFolder(array(
				$repertoireFichierSources, 
				$repertoireFichierImages
			));
			
		}else{
			$this->setErreurs('FILEDELETE :: No application mentioned in the parameter of the method.');
		}
	}

	/**
	* Permet de supprimer tous les fichiers d'un utilisateur
	**/
	public function deleteUtilisateurFile($utilisateur){
		if($utilisateur instanceof \Library\Entities\Utilisateur){
			
			// On charge le fichier de configuration
			$config = $this->getApp()->getConfig();

			$profil = $config->getVar('utilisateur','profil');
			$background = $config->getVar('utilisateur','background');
			$donneeUtilisateur = $config->getVar('donneeUtilisateur','source');
			$donneeUtilisateurTemp = $config->getVar('donneeTemporaire','source');
			$logo = $config->getVar('application', 'logo');
			$source = $config->getVar('application', 'source');

			//On détermine le chemin des données de l'utiliateur
			$mailUserProtege = $this->cleanFileName($utilisateur->getVariableFixeUtilisateur());
			$repertoireImageApp = $logo['filePath'].$mailUserProtege.'/';
			$repertoireUrlPhotoProfilUser = $profil['filePath'].$mailUserProtege.'/';
			//$repertoireDonneeUtilisateur = $donneeUtilisateur['filePath'].$mailUserProtege.'/';
			//$repertoireDonneeUtilisateurTemp = $donneeUtilisateurTemp['filePath'].$mailUserProtege.'/';
			//$repertoireFichiersApplication = $source['filePath'].$mailUserProtege.'/';
			$repertoireUrlImageBackgroundUser = $background['filePath'].$mailUserProtege.'/';

			return $this->deleteFolder(array(
				$repertoireImageApp, 
				$repertoireUrlPhotoProfilUser,
				$repertoireUrlImageBackgroundUser
			));
			
		}else{
			$this->setErreurs('FILEDELETE :: No user mentioned in the parameter of the method.');
		}	
	}


	/**
	* Permet de supprimer les fichiers des fonctions d'une tâche d'une application
	**/
	public function deleteFonctionApplicationFile($fonctions){
		if(is_array($fonctions)){
			$chemins = array();
			foreach($fonctions as $fonction){
				if($fonction instanceof \Library\Entities\Fonction){
					array_push($chemins, $fonction->getUrlFonction());
				}else{
					$this->setErreurs('FILEDELETE :: No function mentioned in the parameter of the method.');
				}
			}
			return $this->deleteFile($chemins);
		}else{
			if($fonctions instanceof \Library\Entities\Fonction){
				return $this->deleteFile($fonctions->getUrlFonction());
			}else{
				$this->setErreurs('FILEDELETE :: No function mentioned in the parameter of the method.');
			}
		}	
	}

	/**
	* Permet de supprimer toutes les données d'un utilisateur
	**/
	public function deleteAllData($utilisateur){
		if($utilisateur instanceof \Library\Entities\Utilisateur){
			
			// On charge le fichier de configuration
			$config = $this->getApp()->getConfig();

			$donneeTemporaire = $config->getVar('donneeUtilisateur', 'source');

			//On détermine le chemin des données de l'utiliateur
			$mailUserProtege = $this->cleanFileName($utilisateur->getVariableFixeUtilisateur());
			$repertoireDonneeTemporaire = $donneeTemporaire['filePath'].$mailUserProtege.'/';

			return $this->deleteFolder(array(
				$repertoireDonneeTemporaire
			));
			
		}else{
			$this->setErreurs('FILEDELETE :: No user mentioned in the parameter of the method.');
		}	
	}


	/**
	* Permet de supprimer un ensemble de fichiers
	**/
	public function deleteFile($chemins = ''){
		if(!empty($chemins)){
			if(is_array($chemins)){
				$nombreEchec = 0;
				foreach($chemins as $chemin){
					if(file_exists($chemin)){
						if(!unlink($chemin)){
							$nombreEchec = $nombreEchec + 1;
						}
					}
				}
				if($nombreEchec == 1){
					$this->setErreurs('FILEDELETE :: One file has not been deleted.');
					return false;
				}else if($nombreEchec >1){
					$this->setErreurs('FILENAME :: '.$nombreEchec.' files have not been deleted.');
					return false;
				}else{
					return true;
				}
			}else{
				if(file_exists($chemins)){
					if(!unlink($chemins)){
						$this->setErreurs('FILEDELETE :: One file has not been deleted.');
					}else{
						return true;
					}
				}else{
					$this->setErreurs('FILEDELETE :: The pathname of the data does not exist.');
					return false;
				}
			}
		}else{
			$this->setErreurs('FILEDELETE :: No file mentioned.');
			return false;
		}
		
		
	}

	/**
	* Permet de supprimer un ensemble de dossiers et tous ce qu'ils contiennent
	**/
	public function deleteFolder($dossiers = ''){
		
		if(!empty($dossiers)){
			if(is_array($dossiers)){
				$nombreEchec = 0;
				foreach($dossiers as $dossier){
					if(is_dir($dossier)){
						if(!$this->clearDir($dossier)){
							$nombreEchec = $nombreEchec + 1;
						}
					}
				}
				if($nombreEchec == 1){
					$this->setErreurs('FILEDELETE :: One folder has not been deleted.');
					return false;
				}else if($nombreEchec >1){
					$this->setErreurs('FILENAME :: '.$nombreEchec.' folders have not been deleted.');
					return false;
				}else{
					return true;
				}
			}else{
				if(is_dir($dossiers)){
					if($this->clearDir($dossiers)){
						return true;
					}else{
						$this->setErreurs('FILENAME :: The folder cannot be deleted.');
						return false;	
					}
				}
			}
		}else{
			$this->setErreurs('FILEDELETE :: No folder mentioned.');
			return false;
		}
	}


	/**
	* Méthode pour supprimer définitivement un répertoire ainsi que ses fichiers
	**/
	private function clearDir($dossier) {
		$ouverture=@opendir($dossier);
		if (!$ouverture) return;
		while($fichier=readdir($ouverture)) {
			if ($fichier == '.' || $fichier == '..') continue;
				if (is_dir($dossier."/".$fichier)) {
					$r=$this->clearDir($dossier."/".$fichier);
					if (!$r) return false;
				}
				else {
					$r=@unlink($dossier."/".$fichier);
					if (!$r) return false;
				}
		}

		closedir($ouverture);
		$r=@rmdir($dossier);
		if (!$r) return false;
			return true;
	}
}



