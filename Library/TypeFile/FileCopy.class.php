<?php
namespace Library\TypeFile;

// +----------------------------------------------------------------------+
// | PHP Version 7                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib                                            |
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
	
	/**
	* Permet de copier la dernière version d'une application d'un utilisateur dans un nouveau dossier d'un autre utilisateur
	**/
	public function copyApplication($application, $newUser, $newVariableFixe){
		if($application instanceof \Library\Entities\Application){

			// On récupère la dernière version
			$lastVersion = $application->getVersions()[count($application->getVersions())-1];

			// On charge le fichier de configuration
			$config = $this->getApp()->getConfig();
			$pathRoot = $config->getVar('application', 'source', 'filePath');
			$pathLogo = $config->getVar('application', 'logo', 'filePath');
			
			// On créé les chemins
			$pathOldUser = $application->getCreateur()->getVariableFixeUtilisateur();
			$pathNewUser = $newUser->getVariableFixeUtilisateur();
			$pathOldApplication = $application->getVariableFixeApplication();
			$pathNewApplication = $newVariableFixe;
			$pathVersion = $this->cleanFileName($lastVersion->getNumVersion());
			$oldVersionPath = $pathRoot.$pathOldUser.'/'.$pathOldApplication.'/'.$pathVersion.'/';
			$newVersionPath = $pathRoot.$pathNewUser.'/'.$pathNewApplication.'/'.$pathVersion.'/';
			$oldLogoPath = $pathLogo.$pathOldUser.'/'.$pathOldApplication.'/';
			$newLogoPath = $pathLogo.$pathNewUser.'/'.$pathNewApplication.'/';
			
			// On copie
			$this->copy_dir($oldVersionPath, $newVersionPath);
			$this->copy_dir($oldLogoPath, $newLogoPath);

			// On retourne les chemins créés
			$paths = [
				'oldVersionPath' => $oldVersionPath,
				'newVersionPath' => $newVersionPath,
				'oldLogoPath' => $oldLogoPath,
				'newLogoPath' => $newLogoPath
			];
			return $paths;
		}else{
			$this->setErreurs('FILECOPY :: No application or user as parameter of the method.');
			return false;
		}
	}	




	/**
	* Permet de copier la dernière version d'une application dans un nouveau dossier (nom de la nouvelle version)
	**/
	public function copyLastVersionApplication($application, $numNewVersion){
		if($application instanceof \Library\Entities\Application){

			// On récupère la dernière version
			$lastVersion = $application->getVersions()[count($application->getVersions())-1];

			// On charge le fichier de configuration
			$config = $this->getApp()->getConfig();
			$pathRoot = $config->getVar('application', 'source', 'filePath');

			
			// On créé les chemins
			$pathUser = $application->getCreateur()->getVariableFixeUtilisateur();
			$pathApplication = $application->getVariableFixeApplication();
			$pathOldVersion = $this->cleanFileName($lastVersion->getNumVersion());
			$pathNewVersion = $this->cleanFileName($numNewVersion);
			$oldPath = $pathRoot.$pathUser.'/'.$pathApplication.'/'.$pathOldVersion.'/';
			$newPath = $pathRoot.$pathUser.'/'.$pathApplication.'/'.$pathNewVersion.'/';
			
			// On copie
			$this->copy_dir($oldPath, $newPath);
			
			// On retourne les chemins créés
			$paths = [
				'oldPath' => $oldPath,
				'newPath' => $newPath
			];
			return $paths;
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



