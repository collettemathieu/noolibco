<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP comme controleur des paramètres des utilisateurs. 	  	  |
// | Ce controleur permet d'afficher le profil des utilisateurs et de     |
// | permettre leur modification par ces derniers. 		  				  |
// +----------------------------------------------------------------------+
// | Auteurs : Guénaël DEQUEKER <dequekerguenael@noolib.com> 		      |
// | 		   Steve DESPRES    <despressteve@noolib.com> 		     	  |
// +----------------------------------------------------------------------+

/**
 * @name: controleur des paramètres de NooLib pour l'utilisateur
 * @access: public
 * @version: 1
 */	

namespace Applications\Settings\Modules\Parametres;
	
class ParametresController extends \Library\BackController
{
	use \Library\Traits\MethodeUtilisateurControleur;
	
	public function executeShow(){}
	
	public function executeParametresCompte(){}
	
	public function executeSupprimerUtilisateur($requete){
		$user = $this->app->getUser();
		$userSession = unserialize($user->getAttribute('userSession'));
		$response = $this->app->getHTTPResponse();
		
		$password = $requete->getPostData('password');
		
		// On verifie si l'utilisateur n'a pas accédé à la methode via l'url
		if($password === null){
			$user->getMessageClient()->addErreur('Your are not authorized to remove this account user.');

			$response->redirect('/');

		}
		else{
			// On charge le fichier de configuration
			$config = $this->getApp()->getConfig();
			
			if( !password_verify( $password, $userSession->getPasswordUtilisateur()))
			{
				$user->getMessageClient()->addErreur('The password entered is incorrect.');
				
				$response->redirect('/Settings/ManageYourAccount');
			}
			else{
				
				// On récupère l'utilisateur complet
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				//on recuper l'utilisateur à administrer
				$utilisateurASupprimer = $managerUtilisateur->getUtilisateurByIdWithAllData($userSession->getIdUtilisateur());
				$reponse = $this->supprimerUtilisateur($utilisateurASupprimer);
				if($reponse === true){
					$user->getMessageClient()->addReussite('Your account has been well removed. See you soon !');
					// On supprime l'utilisateur en session par une deconnexion
					$response->redirect('/LogIn/Deconnexion');
				}else{
					$user->getMessageClient()->addErreur($reponse);
					// On renvoie l'utilisateur à la page paramètres de son compte
					$response->redirect('/Settings/ManageYourAccount');
				}	
			}
		}

	}
	
	public function executeParametreFond(){}
	
	public function executeChangerFond(){
		
		$user = $this->app->getUser();
		$utilisateur = unserialize($user->getAttribute('userSession'));
		$config = $this->getApp()->getConfig();
		$tagName = array( 'categorie' => 'utilisateur', 'sousCategorie' => 'background');
		// On charge l'objet File avec la configuration du background de l'utilisateur
		$file = $this->getApp()->getFileUpload('photo', $tagName);

		if(count($file->getErreurs()) == 0){
			
			// En paramètre on renseigne un dossier supplémentaire à ajouter
			$file->validFileUpload();

			if(count($file->getErreurs()) == 0){

				if($file->depositFileUpload(755)){
					if ($file->getFileExtension() == 'jpg' || $file->getFileExtension() == 'jpeg')
						{
							$ImageInitial = imagecreatefromjpeg($file->getFilePath());
						}
						else
						{
							$ImageInitial = imagecreatefrompng($file->getFilePath());
						}
						$largeur_source = imagesx($ImageInitial);
						$Hauteur_source = imagesy($ImageInitial);
						//on calcule de facteur de redimentionnement
						$rate = min($config->getVar('divers', 'divers', 'userBackgroundPixelSizeMaxX')/$largeur_source, $config->getVar('divers', 'divers', 'userBackgroundPixelSizeMaxY')/$Hauteur_source);
						if($rate < 1)
						{
							$largeur_destination = $largeur_source*$rate;
							$Hauteur_Destination = $Hauteur_source*$rate;
						}
						else
						{
							$largeur_destination = $largeur_source;
							$Hauteur_Destination = $Hauteur_source;
						}
						//on créé une nouvelle image
						$ImageFinal = imagecreatetruecolor($largeur_destination, $Hauteur_Destination);
						imagecopyresampled($ImageFinal, $ImageInitial, 0, 0, 0, 0, imagesx($ImageFinal), imagesy($ImageFinal), $largeur_source, $Hauteur_source);
						//on écrase l'ancienne image avec la nouvelle redimentionnée
						imagejpeg($ImageFinal, $file->getFilePath(), $config->getVar('divers', 'divers', 'userBackgroundPixelcompression'));
						imagedestroy($ImageInitial);
						
						$filePathDefaultImage = $config->getVar('utilisateur', 'background', 'filePathDefault');
						if($utilisateur->getUrlBackgroundUtilisateur() != $filePathDefaultImage)
						{
							// On charge l'objet de suppression de fichier
							$fileDelete = $this->getApp()->getFileDelete();
							if(!$fileDelete->deleteFile('../public_html'.$utilisateur->getUrlBackgroundUtilisateur())){
								// On affiche une erreur.
								$user->getMessageClient()->addErreur($fileDelete->getErreurs());
							}
						}
						//on met à jour la base de donnée
						$cheminFinal = preg_replace('#\.\./public_html(.+)#', '$1', $file->getFilePath());
						$utilisateur->setUrlBackgroundUtilisateur($cheminFinal);
						$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
						$managerUtilisateur->saveUtilisateur($utilisateur);
						
						// On sauvegarde l'utilisateur en session
						$user->setAttribute('userSession', serialize($utilisateur));

						// On affiche un message de confirmation.
						$user->getMessageClient()->addReussite('The wallpaper has been well edited.');
				}else{
					// On affiche une erreur.
					$user->getMessageClient()->addErreur($file->getErreurs());
				}
			}else{
				// On affiche une erreur.
				$user->getMessageClient()->addErreur($file->getErreurs());
			}
		}else{
			// On affiche une erreur.
			$user->getMessageClient()->addErreur($file->getErreurs());
		}

		$response = $this->app->getHTTPResponse();
		$response->redirect('/Settings/EditYourWallpaper');
	}
	
	public function executeFondParDefaut()
	{
		$user = $this->app->getUser();
		$utilisateur = unserialize($user->getAttribute('userSession'));
		$config = $this->getApp()->getConfig();
		$filePathDefaultImage = $config->getVar('utilisateur', 'background', 'filePathDefault');
		
		if($utilisateur->getUrlBackgroundUtilisateur() != $filePathDefaultImage)
		{
			
			// On charge l'objet de suppression de fichier
			$fileDelete = $this->getApp()->getFileDelete();
			if(!$fileDelete->deleteFile('../public_html'.$utilisateur->getUrlBackgroundUtilisateur())){
				// On affiche une erreur.
				$user->getMessageClient()->addErreur($fileDelete->getErreurs());	
			}
			
			$utilisateur->setUrlBackgroundUtilisateur($filePathDefaultImage);
			$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
			$managerUtilisateur->saveUtilisateur($utilisateur);
		
			$user->setAttribute('userSession', serialize($utilisateur));

			// On affiche un message de confirmation
			$user->getMessageClient()->addReussite('The default wallpaper has been restored.');

		}else{
			// On affiche une erreur.
			$user->getMessageClient()->addErreur('The default wallpaper is already restored.');
		}
		
		$response = $this->app->getHTTPResponse();
		$response->redirect('/Settings/EditYourWallpaper');
	}
}
