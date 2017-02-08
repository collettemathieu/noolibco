<?php

namespace Library\Traits;

// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Trait pour les methodes des entités DonneeUtilisateur                |
// +----------------------------------------------------------------------+
// | Auteurs : 															  |
// | 		   Mathieu COLLETTE <collettemathieu@noolib.com>			  |
// | 		   Steve DESPRES <despressteve@noolib.com> 		     		  |
// +----------------------------------------------------------------------+

/**
 * @access: public
 * @version: 1
 */	

trait MethodeDonneeUtilisateurControleur
{
	
	/**
	* Méthode pour supprimer une donnée utilisateur
	*/
	private function executeSaveDonneeUtilisateur($request, $donneeUtilisateur = null){

		// On récupère l'utilisateur système
		$user = $this->app->getUser();

		// On appelle le manager de DonneeUtilisateur
		$managerDonneeUtilisateur = $this->getManagers()->getManagerOf('DonneeUtilisateur');

		if($donneeUtilisateur == null){
			// On récupère la donnée utilisateur
			$idDonneeUtilisateur = $request->getPostData('idDonneeUtilisateur');

			$donneeUtilisateur = $managerDonneeUtilisateur->getDonneeUtilisateurById($idDonneeUtilisateur);
		}
	
		if($donneeUtilisateur){

			// On recupère l'utilisateur en session
			$utilisateur = unserialize($user->getAttribute('userSession'));

			// On met à jour la donnée utilisateur
			$donneeUtilisateur->setIsInCache(false);

			// On met à jour la donnée de la BDD
			$managerDonneeUtilisateur->saveDonneeUtilisateur($donneeUtilisateur);
			
			// On met à jour la donnée de l'objet utilisateur
			$utilisateur->updateDonneeUtilisateur($donneeUtilisateur);

			// On place le profil de l'utilisateur en session
			$user->setAttribute('userSession', serialize($utilisateur));
			
			// On ajoute une message au client
			$user->getMessageClient()->addReussite('Your data is now saved in NooLib for 30 days.');
			return true;
			
		}else{
			$user->getMessageClient()->addErreur($file->getErreurs());
			return false;
		}
	}

	/**
	* Méthode pour supprimer une donnée utilisateur (l'originale et sa miniature pour une image)
	*/
	private function executeDeleteDonneeUtilisateur($request, $donneeUtilisateur = null){

		// On récupère l'utilisateur système
		$user = $this->app->getUser();

		// On appelle le manager de DonneeUtilisateur
		$managerDonneeUtilisateur = $this->getManagers()->getManagerOf('DonneeUtilisateur');

		if($donneeUtilisateur == null){
			// On récupère la donnée utilisateur
			$idDonneeUtilisateur = $request->getPostData('idDonneeUtilisateur');

			$donneeUtilisateur = $managerDonneeUtilisateur->getDonneeUtilisateurById($idDonneeUtilisateur);
		}
	
		if($donneeUtilisateur){

			// On recupère l'utilisateur en session
			$utilisateur = unserialize($user->getAttribute('userSession'));

			// On appelle le manager de UtilisateurDonneeUtilisateur
			$managerUtilisateurDonneeUtilisateur = $this->getManagers()->getManagerOf('UtilisateurDonneeUtilisateur');
			
			// On vérifie que la donnée utilisateur appartient à l'utilisateur sinon c'est un admin et on ne fait rien
			if($managerUtilisateurDonneeUtilisateur->getUtilisateurDonneeUtilisateurById($utilisateur->getIdUtilisateur(), $donneeUtilisateur->getIdDonneeUtilisateur())){
				// On supprime la donnée de l'objet utilisateur
				$utilisateur->removeDonneeUtilisateur($donneeUtilisateur);

				// On place le profil de l'utilisateur en session
				$user->setAttribute('userSession', serialize($utilisateur));
			}

			// On supprime la donnée de la BDD
			$managerDonneeUtilisateur->deleteDonneeUtilisateur($donneeUtilisateur);

			// On supprime les fichiers de données physiquement si il existe
			$file = $this->getApp()->getFileDelete();
			if($file->deleteFile(array($donneeUtilisateur->getUrlDonneeUtilisateur(), $donneeUtilisateur->getUrlMiniatureDonneeUtilisateur()))){
				// On ajoute une message au client
				$user->getMessageClient()->addReussite('The data has been well deleted.');
			}else{
				$user->getMessageClient()->addErreur($file->getErreurs());
			}

			return true;
		}else{
			$user->getMessageClient()->addErreur('The data you want to remove does not exist.');
			return false;
		}
	}

	/**
	* Méthode pour calculer l'espace disponible des données utilisateur
	*/
	private function executeEspaceDisponibleDonneeUtilisateur($utilisateur){
		if($utilisateur instanceof \Library\Entities\Utilisateur){
			// On calcul la taille des données de l'utilisateur
			// On appelle l'objet File
			$file = $this->getApp()->getFile();
			$tagNameUtilisateur = array( 'categorie' => 'donneeUtilisateur', 'sousCategorie' => 'source');
			$tailleOctetsDonneesUtilisateur = $file->calculerTailleDossier( $tagNameUtilisateur, $utilisateur->getVariableFixeUtilisateur());

			//On convertit la taille totale en Mo avec 2 chiffres après la virgule
			$tailleMoDonneesUtilisateur = number_format($tailleOctetsDonneesUtilisateur/(1024*1024),2);
			//On définit la taille maximum que peuvent avoir les données
			// On charge le fichier de configuration
			$config = $this->getApp()->getConfig();
			$tailleMaxDonneesUtilisateur = (int) $config->getVar('donneeUtilisateur', 'folderData', 'tailleMax');
			
			//On calcul la progression en pourcentage
			$progressionPourcent = number_format(($tailleMoDonneesUtilisateur/$tailleMaxDonneesUtilisateur)*100,1);

			return array(
				'tailleMoDonneesUtilisateur' => $tailleMoDonneesUtilisateur,
				'tailleMaxDonneesUtilisateur' => $tailleMaxDonneesUtilisateur,
				'progressionPourcent' => $progressionPourcent
			);

		}else{
			return false;
		}
		
	}
}

