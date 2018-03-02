<?php
namespace Library\TypeFile;

// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP FileUpload pour gérer les fichiers Uploadés.		      |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe FileUpload
 * @access: public
 * @version: 1
 */

class FileUpload extends \Library\File{
	
	use \Library\Traits\FonctionsUniverselles;

	/* Définition des attributs*/
	protected $tableServerFile,
			  $restraintsFile;
			 
	/**
	* Permet de valider un fichier avant son enregistrement définitif sur la plateforme.
	* Retourne le chemin complet du fichier validé ou une erreur.
	**/
	public function validFileUpload($utilisateur = null, $sousDossier = '', $sousSousDossier = ''){

		if(count($this->tableServerFile) != 0 && count($this->restraintsFile) != 0){

			if($this->tableServerFile['size'] < $this->restraintsFile['fileWeight']){

				// On récupère les extensions autorisées
				$extensionFichierAutorisees = explode(',', $this->restraintsFile['fileExtension']); //Tableau des extensions autorisées
				
				$extensionFichier = strtolower(pathinfo($this->fileName, PATHINFO_EXTENSION));
				
				if(in_array($extensionFichier, $extensionFichierAutorisees)){
					
					$this->setFileExtension($extensionFichier);

					// On créé un nombre aléatoire pour le nom du fichier
					$nombre = rand(0,10000000);
					$this->setFileName($this->cleanFileName(substr(basename($this->fileName), 0, 3).$nombre.'.'.$extensionFichier));

					// On récupère l'utilisateur connecté ou on conserve l'utilisateur entré en paramètre
					if($utilisateur === null || ! $utilisateur instanceof \Library\Entities\Utilisateur){
						$utilisateur = unserialize($this->app->getUser()->getAttribute('userSession'));
					}

					$mailUserProtege = $utilisateur->getVariableFixeUtilisateur();
					
					if(!empty($sousDossier)){
						$sousDossier = '/'.$this->cleanFileName($sousDossier);
					}
					if(!empty($sousSousDossier)){
						$sousSousDossier = '/'.$this->cleanFileName($sousSousDossier);
					}
					$this->setFileFolder($this->restraintsFile['filePath'].$mailUserProtege.$sousDossier.$sousSousDossier.'/');
					$this->setFilePath($this->fileFolder.$this->getFileName());

				}else{
					$this->setErreurs('This file extension is not authorized.');
				}
			}else{
				$this->setErreurs('The file size exceeds the limit authorized.');
			}
		}else{
			$this->setErreurs('FILEUPLOAD :: The system variables do not exist.');
		}
	}

	/**
	* Permet d'enregistrer un fichier uploadé - retourne true sinon false avec un tableau d'erreur
	**/
	public function depositFileUpload($permission = 770){
		
		if(count($this->tableServerFile) != 0){
			if(!empty($this->fileFolder) && !empty($this->filePath)){
				/*Création du répertoire désirée
				  Pour créer une stucture imbriquée, le paramètre $recursive 
				  doit être spécifié. 0777 donne accès à tous les droits*/
				// Si le dossier n'existe pas, on le crée
				if(!is_dir($this->fileFolder)){
					mkdir($this->fileFolder, octdec($permission), true);
				}

					if(move_uploaded_file($this->tableServerFile['tmp_name'], $this->filePath)){
						
						// On modifie les droits du fichier
						chmod($this->filePath, octdec($permission));
						return true;
						
					}else{
						$this->setErreurs('An technical error has occurred. No file saved.');
						return false;
					}
			}else{
				$this->setErreurs('FILEUPLOAD :: The folder or file is empty.');
				return false;
			}
		}else{
			$this->setErreurs('FILEUPLOAD :: The variable server is empty.');
			return false;
		}
	}


	/* SETTERS */

	public function setTableServerFile($nomVariableServer){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			if(isset($_FILES[$nomVariableServer])){
				if ($_FILES[$nomVariableServer]['error'] == 0){
					$this->tableServerFile = $_FILES[$nomVariableServer];
					$this->fileName = $this->tableServerFile['name'];
				}else{
					$this->setErreurs('An system error has occured during the file transfer.');
				}
			}else{
				$this->setErreurs('FILEUPLOAD :: The variable server is empty.');
			}
		}else{
			$this->setErreurs('FILEUPLOAD :: The request method is not valid.');
		}
		
	}

	public function setRestraintsFile($tagName = ''){
		if(!empty($tagName)){
		
			if(isset($tagName['categorie'])){
					if(isset($tagName['sousCategorie'])){
						// On charge le fichier de configuration
						$config = $this->getApp()->getConfig();
						$contraintes = $config->getVar($tagName['categorie'], $tagName['sousCategorie'] );
					}else{
						$this->setErreurs('FILEUPLOAD :: The variable server is empty.');
					}
			}else{
				$this->setErreurs('FILEUPLOAD :: No node matches with the config file.');
			}
		}else{
			$this->setErreurs('FILEUPLOAD :: No tagname has been identified in the config file.');
		}
		
		if(count($contraintes) != 0){
				$this->restraintsFile = $contraintes;
			}else{
				$this->setErreurs('FILEUPLOAD :: No node matches with the config file.');
			}
		
	}
	
}



