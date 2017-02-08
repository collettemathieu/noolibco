<?php
namespace Library\TypeFile;

// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP FileUploadLocalData pour gérer les données temporaires de |
// | l'utilisateur.		      											  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe FileUploadLocalData
 * @access: public
 * @version: 1
 */

class FileUploadLocalData extends \Library\File{
	
	use \Library\Traits\FonctionsUniverselles;
	
	/* Définition des attributs*/
	protected $restraintsFile;
			 
	/**
	* Permet de valider un fichier temporaire avant son enregistrement définitif sur la plateforme.
	**/
	public function validFileUploadLocalData($extensionFichier, $nomFichier, $tailleDonneeUtilisateur, $utilisateur = null){
		
		if(count($this->restraintsFile) != 0){

			if($tailleDonneeUtilisateur < $this->restraintsFile['fileWeight']){

				// On récupère les extensions autorisées
				$extensionFichierAutorisees = explode(',', $this->restraintsFile['fileExtension']); //Tableau des extensions autorisées
				
				if(in_array($extensionFichier, $extensionFichierAutorisees)){
					
					$this->setFileExtension($extensionFichier);

					// On créé un nombre aléatoire pour le nom du fichier
					$nombre = rand(0,10000000);
					$nouveauNomFichier = $this->cleanFileName(substr($nomFichier, 0, 3).$nombre.'.'.$extensionFichier);
					$this->setFileName(substr($nomFichier, 0, 3).$nombre);

					// On récupère l'utilisateur connecté ou on conserve l'utilisateur entré en paramètre
					if($utilisateur === null || ! $utilisateur instanceof \Library\Entities\Utilisateur){
						$utilisateur = unserialize($this->app->getUser()->getAttribute('userSession'));
					}

					$mailUserProtege = $utilisateur->getVariableFixeUtilisateur();
					
					// On créé le chemin du fichier temporaire
					$this->setFileFolder($this->restraintsFile['filePath'].$mailUserProtege.'/');
					
					$this->setFilePath($this->fileFolder.$nouveauNomFichier);

				}else{
					$this->setErreurs(self::FILE_EXTENSION);
				}
			}else{
				$this->setErreurs(self::FILE_SIZE_EXCEEDED);
			}
		}else{
			$this->setErreurs(self::NO_SYSTEM_VARIABLE);
		}
	}

	/**
	* Permet d'enregistrer un fichier uploadé - retourne true sinon false avec un tableau d'erreur
	**/
	public function depositFileUploadLocalData($data){
		
		if(!empty($data)){
			if(!empty($this->fileFolder) && !empty($this->filePath)){
				/*Création du répertoire désirée
				  Pour créer une stucture imbriquée, le paramètre $recursive 
				  doit être spécifié. 0777 donne accès à tous les droits*/
				// Si le dossier n'existe pas, on le crée
				if(!is_dir($this->fileFolder)){
					mkdir($this->fileFolder, 0770, true);
				}

				if(($fichierDataTemp = fopen($this->filePath, 'w')) !== false){
					// On execute les traitements des données en fonction de l'extension des données
					switch($this->fileExtension){
						case 'csv':
							if(is_array($data)){
								foreach($data as $line){
									fputcsv($fichierDataTemp, $line, ',');
								}
								fclose($fichierDataTemp);
								chmod($this->filePath, 0770);
								return true;
							}else{
								$this->setErreurs(self::FILE_UPLOAD_ARRAY_FORMAT);
								fclose($fichierDataTemp);
								return false;
							}
						break;
						
						default:
							fputs($fichierDataTemp, $data);
							// On ferme le fichier de données
							fclose($fichierDataTemp);
							// On modifie les droits du fichier
							chmod($this->filePath, 0770);
							return true;
					}

					
				}else{
					$this->setErreurs(self::ERROR_FILE_OPEN);
					return false;
				}
			}else{
				$this->setErreurs(self::NO_FOLDER_OR_FILE);
				return false;
			}
		}else{
			$this->setErreurs(self::DATA_REQUIRED);
			return false;
		}
	}


	/* SETTERS */

	public function setRestraintsFile(){
		
		// On charge le fichier de configuration
		$config = $this->getApp()->getConfig();
		$contraintes = $config->getVar('donneeUtilisateur', 'source');

		if(count($contraintes) != 0){
			$this->restraintsFile = $contraintes;
		}else{
			$this->setErreurs(self::NO_NODE_MATCHES);
		}
		
	}
	
}



