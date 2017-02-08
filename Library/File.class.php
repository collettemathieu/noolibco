<?php
namespace Library;

// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP FILE pour la gestion des fichiers. 		      |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe File
 * @access: public
 * @version: 1
 */

class File extends ApplicationComponent
{
	use \Library\Traits\FonctionsUniverselles;

	/* Définition des constantes */
	const DATA_REQUIRED = 'User data is required as parameter for this function.';
	const ALL_FIELDS_REQUIRED = 'You need to fill in all the fields required.';
	const ERROR_FILE_OPEN = 'An technical error has occurred while opening file.';
	const NO_FOLDER_OR_FILE = 'No folder or file found.';
	const FILE_SIZE_EXCEEDED = 'The file size exceeds the limit authorized.';
	const FILE_EXTENSION = 'This file extension is not authorized.';
	const NO_SYSTEM_VARIABLE = 'The system variables do not exist.';

	/* FileUploadLocalData controller */
	const FILE_UPLOAD_ARRAY_FORMAT = 'CSV data are not in array format';

	/* Fichier de configuation */
	const NO_NODE_MATCHES = 'No node matches in the config file.';
	
	/* Définition des attributs */
	protected $fileFolder = '',
			  $fileName = '',
			  $filePath,
			  $fileExtension = '',
			  $erreurs = array();
	
	/**
	* Permet de valider un fichier présent sur la plateforme
	**/
	public function validFile($cheminFichier){

		$infoFile = new SplFileInfo($cheminFichier);
	}

	/**
	* Méthode pour calculer la taille en octet d'un dossier à partir des tagName
	*/
	public function calculerTailleDossier($tagName, $chemin = ''){
		// On charge le fichier de configuration
		$config = $this->getApp()->getConfig();
		$contraintes = $config->getVar($tagName['categorie'], $tagName['sousCategorie']);
		if(count($contraintes) != 0){
			$repertoire = $contraintes['filePath'];

			//Si le chemin existe
			$chemin = $this->cleanFileName($chemin);
			$path = $repertoire.$chemin;

			return $this->sizeFolder($path);

		}else{
			$this->setErreurs('FILE :: The node entered does not match with the config file.');
		}
	}

	/**
	* Méthode pour calculer la taille en octet d'un dossier
	*/
	public function sizeFolder($path){
		// On vérifie que le chemin existe
		if(file_exists($path)){
			//On ouvre son repertoire de données
			$Racine=opendir($path);
			//On initialise la taille 
			$Taille=0;
			//On parcour le dossier
			while( false!==($Dossier = readdir($Racine))){
				//Si le dossier contient des fichiers
				if($Dossier != '..' And $Dossier !='.')
				{	//Si le fichier est un dossier 
					if(is_dir($path.'/'.$Dossier)){
						//On ajoute la taille du sous dossier
						$Taille += $this->sizeFolder($path.'/'.$Dossier); 
					}else{
						//On ajoute la taille du fichier
						$Taille += filesize($path.'/'.$Dossier); 
					}
				}
			}
			//On ferme le repertoire
			closedir($Racine);
			//On retourne la taille
			return $Taille;
		}else{
			return 0;
		}
	}

	/* SETTERS */

	protected function setFileFolder($repertoire){
		if(isset($repertoire) && !empty($repertoire)){
			$this->fileFolder = $repertoire;
		}
	}

	protected function setFilePath($cheminFichier){
		if(isset($cheminFichier) && !empty($cheminFichier)){
			$this->filePath = $cheminFichier;
		}
	}

	protected function setFileName($nomFichier){
		if(isset($nomFichier) && !empty($nomFichier)){
			$this->fileName = $nomFichier;
		}
	}

	protected function setFileExtension($extension){
		if(isset($extension) && !empty($extension)){
			$this->fileExtension = $extension;
		}
	}

	protected function setErreurs($erreur){
		if(isset($erreur) && !empty($erreur)){
			array_push($this->erreurs, $erreur);
		}
	}


	/* GETTERS */

	public function getErreurs(){
		return $this->erreurs;
	}

	public function getFileFolder(){
		return $this->fileFolder;
	}

	public function getFileName(){
		return $this->fileName;
	}

	public function getFilePath(){
		return $this->filePath;
	}

	public function getFileExtension(){
		return $this->fileExtension;
	}

}



