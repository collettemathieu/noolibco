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

