<?php

namespace Library\Traits;

// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Trait pour les methodes des entités Utilisateurs                     |
// +----------------------------------------------------------------------+
// | Auteurs : Guénaël DEQUEKER <dequekerguenael@noolib.com> 			  |
// | 		   Mathieu COLLETTE <collettemathieu@noolib.com>			  |
// | 		   Steve DESPRES <despressteve@noolib.com> 		     		  |
// +----------------------------------------------------------------------+

/**
 * @access: public
 * @version: 1
 */	

trait MethodeUtilisateurControleur
{
	/**
	* Permet de supprimer un utilisateur - retourne true ou false avec un tableau d'erreur.
	**/
	private function supprimerUtilisateur($utilisateurASupprimer)
	{
		if($utilisateurASupprimer instanceof \Library\Entities\Utilisateur)
		{
			// On supprime toutes les données utilisateurs
			$file = $this->getApp()->getFileDelete();
			
			if($file->deleteUtilisateurFile($utilisateurASupprimer)){
				//On appelle le manager des Utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
				//On procède à la suppression dans la BDD de l'utilisateur
				$managerUtilisateur->deleteUtilisateur($utilisateurASupprimer);

				// Execution du script Bash pour supprimer un utilisateur linux
				// On execute l'objet Exec
				$exec = $this->getApp()->getExec();
				$exec->delUser($utilisateurASupprimer);
				
				return true;
			
			}else{
				// On retourne le tableau d'erreurs
				return $file->getErreurs();
			}

		}
		else
		{
			return false;
		}
	}
	

	/**
	* Permet de modifier la photo du profil de l'utilisateur
	*/
	private function changerPhotoProfil($utilisateur){
		
		if($utilisateur instanceof \Library\Entities\Utilisateur){
		
			$user = $this->app->getUser();
			$config = $this->getApp()->getConfig();

			$tagName = array( 'categorie' => 'utilisateur', 'sousCategorie' => 'profil');
			// On charge l'objet File avec la configuration de la photo de profil de l'utilisateur
			$file = $this->getApp()->getFileUpload('photo', $tagName);

			if(count($file->getErreurs()) == 0){
				
				// En paramètre on renseigne aucun sous-dossier, juste le nom de l'utilisateur
				$file->validFileUpload($utilisateur);

				if(count($file->getErreurs()) == 0){

					if($file->depositFileUpload()){
						//on récuper l'image dans une variable
						if ($file->getFileExtension() == 'jpg' || $file->getFileExtension() == 'jpeg')
						{
							$ImageInitiale = imagecreatefromjpeg($file->getFilePath());
						}
						else
						{
							$ImageInitiale = imagecreatefrompng($file->getFilePath());
						}
						$largeur_source = imagesx($ImageInitiale);
						$Hauteur_source = imagesy($ImageInitiale);
						
						//on calcule de facteur de redimentionnement
						$rate = min($config->getVar('divers', 'divers', 'userProfilPicturePixelSizeMax')/$largeur_source, $config->getVar('divers', 'divers', 'userProfilPicturePixelSizeMax')/$Hauteur_source);
						$largeur_destination = $largeur_source*$rate;
						$Hauteur_Destination = $Hauteur_source*$rate;
						
						//on créé une nouvelle image
						$ImageFinale = imagecreatetruecolor($largeur_destination, $Hauteur_Destination);
						imagecopyresampled($ImageFinale, $ImageInitiale, 0, 0, 0, 0, imagesx($ImageFinale), imagesy($ImageFinale), $largeur_source, $Hauteur_source);
						
						//on écrase l'ancienne image avec la nouvelle redimentionnée
						imagejpeg($ImageFinale, $file->getFilePath(), (int) $config->getVar('divers', 'divers', 'userProfileCompression'));
						imagedestroy($ImageInitiale);
						
						//on supprime l'ancienne photo si celle-ci n'est pas la photo par défaut
						if($utilisateur->getUrlPhotoUtilisateur() != '../Files/Images/User/profil_defaut.png')
						{
							// On charge l'objet de suppression de fichier
							$fileDelete = $this->getApp()->getFileDelete();
							if(!$fileDelete->deleteFile($utilisateur->getUrlPhotoUtilisateur())){
								// On affiche une erreur.
								$user->setFlash(array('erreurs' => $fileDelete->getErreurs()));
								return false;	
							}

						}
						//on met à jour la base de donnée
						$utilisateur->setUrlPhotoUtilisateur($file->getFilePath());
						$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');
						$managerUtilisateur->saveUtilisateur($utilisateur);

						// Opération réussie mentionnée en flash
						$user->getMessageClient()->addReussite('The picture profile has been well edited.');

						return true;
					}else{
						// On affiche une erreur.
						$user->getMessageClient()->addErreur($file->getErreurs());
						return false;
					}
				}else{
					// On affiche une erreur.
					$user->getMessageClient()->addErreur($file->getErreurs());
					return false;
				}
			}else{
				// On affiche une erreur.
				$user->getMessageClient()->addErreur($file->getErreurs());
				return false;
			}
		}else{
			return false;
		}
	}

	/**
	* Méthode pour récupérer la liste des institutions de recherche
	*/
	public function executeGetAllInstitutions(){
		
		// On récupère l'utilisateur système
		$user = $this->app->getUser();

		// On informe que c'est un chargement Ajax
		$user->setAjax(true);

		// On récupère la liste des établissements
		$managerEtablissement = $this->getManagers()->getManagerOf('Etablissement');
		$listeEtablissements = $managerEtablissement->getAllEtablissements();

		// On créé le tableau des types de publication
		$listeEtablissementsAAfficher = array();
		foreach($listeEtablissements as $id=>$etablissement){
			$typeEtablissement = array(
				'name' => $etablissement->getNomEtablissement(),
				'id' => $etablissement->getIdEtablissement()
				);
			array_push($listeEtablissementsAAfficher, $typeEtablissement);
		}
		// On le résultat
		return $listeEtablissementsAAfficher;
	}


	/**
	* Méthode pour récupérer la liste des laboratoires en fonction de l'établissement
	*/
	public function executeGetAllLaboratories($request){
		
		// On récupère l'utilisateur système
		$user = $this->app->getUser();

		// On informe que c'est un chargement Ajax
		$user->setAjax(true);

		//On récupère l'id de l'établissement
		$idEtablissement = (int) $request->getPostData('idEtablissement');
		// On récupère l'établissement demandé
		$managerEtablissement = $this->getManagers()->getManagerOf('Etablissement');
		$etablissement = $managerEtablissement->getEtablissementById($idEtablissement);
		
		if($etablissement){
			$managerEtablissement->putLaboratoiresInEtablissement($etablissement);
		
			// On créé le tableau des types de publication
			$listeLaboratoiresAAfficher = array();
			foreach($etablissement->getLaboratoires() as $id=>$laboratoire){
				$typeLaboratoire = array(
					'name' => $laboratoire->getNomLaboratoire(),
					'id' => $laboratoire->getIdLaboratoire()
					);
				array_push($listeLaboratoiresAAfficher, $typeLaboratoire);
			}
			// On retourne le résultat
			return $listeLaboratoiresAAfficher;
		}else{
			// On envoie une erreur
			$user->getMessageClient()->addErreur(self::PROFILE_INSTITUTION_NOT_EXISTS);
			return false;
		}


	}

	/**
	* Méthode pour récupérer la liste des équipes en fonction du laboratoire
	*/
	public function executeGetAllTeams($request){
		
		// On récupère l'utilisateur système
		$user = $this->app->getUser();

		// On informe que c'est un chargement Ajax
		$user->setAjax(true);

		//On récupère l'id du laboratoire
		$idLaboratoire = (int) $request->getPostData('idLaboratoire');
		// On récupère le laboratoire demandé
		$managerLaboratoire = $this->getManagers()->getManagerOf('Laboratoire');
		$laboratoire = $managerLaboratoire->getLaboratoireById($idLaboratoire);
		
		if($laboratoire){
			$managerLaboratoire->putEquipesInLaboratoire($laboratoire);

			// On créé le tableau des types de publication
			$listeEquipesAAfficher = array();
			foreach($laboratoire->getEquipes() as $id=>$equipe){
				$typeEquipe = array(
					'name' => $equipe->getNomEquipe(),
					'id' => $equipe->getIdEquipe()
					);
				array_push($listeEquipesAAfficher, $typeEquipe);
			}
			// On retourne les résultat
			return $listeEquipesAAfficher;
		}else{
			// On envoie une erreur
			$user->getMessageClient()->addErreur(self::PROFILE_LABORATORY_NOT_EXISTS);
			return false;
		}
	}


	/**
	* Permet d'ajouter l'utilisateur à une équipe de recherche
	*/
	private function ajouterEquipe(\Library\Entities\Utilisateur $utilisateur, $idEquipe)
	{
		//on verifie si l'utilisateur n'a pas accédé à la methode via l'url
		$user = $this->app->getUser();
		
		if($idEquipe === null)
		{
			$user->getMessageClient()->addErreur('No data sent.');
		}
		else
		{
			//on vérifie si il n'est pas déjà dans cette équipe
			$dejaDansEquipe = false;
			foreach($utilisateur->getEquipes() as $equipe)
			{
				if($equipe->getIdEquipe() == $idEquipe)
				{
					$dejaDansEquipe = true;
				}
			}
			//si déja dans cette équipe
			if($dejaDansEquipe)
			{
				//on procède à la redirection
				$user->getMessageClient()->addErreur('You cannot be twice in the same team.');
			}
			else
			{
				$utilisateurEquipe = new \Library\Entities\UtilisateurEquipe(array(
					'utilisateur' => $utilisateur, 
					'equipe' => $this->getManagers()->getManagerOf('Equipe')->getEquipeById($idEquipe)
					));
				
				if(count($utilisateurEquipe->getErreurs()) != 0)
				{
					$user->getMessageClient()->addErreur($utilisateurEquipe->getErreurs());
				}
				else
				{
					$utilisateurEquipeManager = $this->getManagers()->getManagerOf('UtilisateurEquipe');
					//on enregistre
					$utilisateurEquipeManager->addUtilisateurEquipe($utilisateurEquipe);
					$user->getMessageClient()->addReussite('The team has been well added.');
				}
			}
		}
	}
	
	/**
	* Permet de supprimer un utilisateur d'une équipe de recherche
	*/
	private function removeEquipe(\Library\Entities\Utilisateur $utilisateur, $idEquipe)
	{
		$user = $this->app->getUser();
		
		if($idEquipe === null)
		{
			// si non, on procède à la redirection
			$response->redirect('/Profile/');
		}
		else
		{
			//on vérifie si l'utilisateur est dans l'equi dont il veut se retirer
			$dansEquipe = false;
			foreach($utilisateur->getEquipes() as $equipe)
			{
				if($equipe->getIdEquipe() == $idEquipe)
				{
					$dansEquipe = true;
				}
			}
			//si déja dans cette équipe
			if(!$dansEquipe)
			{
				//on procède à la redirection
				$response->redirect('/Profile/');
			}
			else
			{
				$utilisateurEquipe = new \Library\Entities\UtilisateurEquipe(array(
					'utilisateur' => $utilisateur, 
					'equipe' => $this->getManagers()->getManagerOf('Equipe')->getEquipeById($idEquipe)
					));
				
				if(count($utilisateurEquipe->getErreurs()) != 0)
				{
					$user->getMessageClient()->addErreur($utilisateurEquipe->getErreurs());
					$response->redirect('/Profile/');
				}
				else
				{
					$utilisateurEquipeManager = $this->getManagers()->getManagerOf('UtilisateurEquipe');
					//on enregistre
					$utilisateurEquipeManager->deleteUtilisateurEquipe($utilisateurEquipe);
					$user->getMessageClient()->addReussite('The team has been well removed.');
				}
			}
		}
	}


	/**
	* Permet de protéger le mot de passe par hashage et salage.
	*/
	private function protectPassword($password){
		if (isset($password) && !empty($password)){
			$password = password_hash($password, PASSWORD_DEFAULT);
			return $password;
		}
		else{
			return false;
		}
	}

	/**
	* Permet de vérifier le format d'un mot de passe
	*/
	private function validPassword($passwordUtilisateur)	{
	//Contient  au moins 1 majuscule, 1 minuscule et 1 chiffre
		$regex = '#(?=(.*[A-Z]){1,})'.'(?=(.*[a-z]){1,})'.'(?=(.*[0-9]){1,})#';	
		//Si c'est un type String
		if (is_string( $passwordUtilisateur )){
			//Si il contient plus de 7 caractères
			if ( strlen($passwordUtilisateur) > 7 ){	
				//Utilisation de la Regex
				if(preg_match( $regex, $passwordUtilisateur)){
					return true;
				}else{
					return false;
				}						
			}else{
				return false;
			}		
		}else{
			return false;
		}
	}
	
	/**
	* Permet d'encoder un jeton
	*/
	private function codeJeton($email){
	
		// On appelle le manager des Users
		$managerUser = $this->getManagers()->getManagerOf('Utilisateur');
				
		// On récupère le user de la base selon l'email
		$userBase = $managerUser->getUtilisateurByMail($email);

		if($userBase){
			//On récupère l'identifiant de l'utilisateur
			$idMembre = (int)$userBase->getIdUtilisateur();
			
			//On récupère la date
			$date = date('jmY');
			//On recupère l'heure
			$heure = date('His');
			//On récupère l'heure constante
			$time = time();
			//On calcul le crc du mot de passe de l'utilisateur
			$password_crc32 = crc32($userBase->getPasswordUtilisateur());
			//On génère un nombre aléatoire
			$entropy = mt_rand();		
			
			
			//On compacte les données dans une chaine binaire
			$jeton_binaire= pack('IIIIIS', $idMembre, $date, $heure, $time, $password_crc32, $entropy);
			//On créer une version URL sûre de notre jeton
			$jeton_safe= rtrim(strtr(base64_encode($jeton_binaire),'+/', '-_'), '=');

			return $jeton_safe;
		}else{
			return false;
		}
	}
	
	/**
	* Permet de décoder un jeton
	*/
	private function decodeJeton($jeton){
	
		
		//On décode notre jeton
		$jeton_binaire= base64_decode(str_pad(strtr($jeton, '-_', '+/'), strlen($jeton) % 4, '=', STR_PAD_RIGHT));
		
		//On décompacte les données
		$donneesUtilisateur = @unpack('IidMembre/Idate/Iheure/Itime/Ipassword_crc32/Sentropy', $jeton_binaire);
		
		//On récupère les données
		$idMembre = $donneesUtilisateur['idMembre'];
		$date = $donneesUtilisateur['date'];
		$heure = $donneesUtilisateur['heure'];
		$time = $donneesUtilisateur['time'];
		$entropy = $donneesUtilisateur['entropy'];
		$password_crc32= $donneesUtilisateur['password_crc32'];
		
		return $donneesUtilisateur;
	
	}

	/**
	* Permet d'envoyer un courrier à NooLib
	*/
	private function sendAMailToNooLib($request){
	
		$response = $this->app->getHTTPResponse();
		$user = $this->app->getUser();

		// On appelle le manager des utilisateurs
		$mailUtilisateur = $request->getPostData('emailAddress');

		if(!empty($mailUtilisateur) && preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $mailUtilisateur)){
				
			//On verifie les variables
			$headerMessageMail = $request->getPostData('headerMessageMail');
			$bodyMessageMail = $request->getPostData('bodyMessageMail');
			
			if(!empty($headerMessageMail) && !empty($bodyMessageMail)){
				
					$variablesArray = array(
						'mailUtilisateur' => $mailUtilisateur,
						'titreMessage' => $headerMessageMail,
						'messageMail' => $bodyMessageMail
					);

					// On envoi un mail à l'auteur
					// On place la variable en Flash pour qu'elle soit récupérée par l'application Mail
					$user->setFlash($variablesArray);
					$mailApplication = new \Applications\ApplicationsStandAlone\Mail\MailApplication;
					$mailApplication->execute('SendMailToNooLib', 'sendAMessage'); // Module = SendMailToNooLib ; action = sendAMessage

					// Opération réussie
					$user->getMessageClient()->addReussite(self::MAIL_MESSAGE_SENT);
					return true;
			}
			else{
				// On envoie une erreur
				$user->getMessageClient()->addErreur(self::ALL_FIELDS_REQUIRED);
			}
			return false;
		}else{
			// On envoie une erreur
			$user->getMessageClient()->addErreur(self::LOGIN_MAIL_NOT_VALID);
			return false;
		}
	
	}
	
}

