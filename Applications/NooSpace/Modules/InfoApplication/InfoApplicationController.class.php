<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2015 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour la mule.								  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe InfoApplicationController
 * @access: public
 * @version: 1
 */

namespace Applications\NooSpace\Modules\InfoApplication;
	
class InfoApplicationController extends \Library\BackController
{
	/**
	* Permet de retourner la mule de l'application
	**/
	public function executeShow($request)
	{
		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {

			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur connecté
			$utilisateur = unserialize($user->getAttribute('userSession'));
			
			// On récupère l'id de l'application que le client veut ajouter au Dock
			$idApplication = (int) $request->getPostData('idApplication');

			// On appelle le manager des applications
			$managerApplication = $this->getManagers()->getManagerOf('Application');
			// On récupère l'application en question si elle existe avec tous ses attributs emplis
			$application = $managerApplication->getApplicationByIdWithAllParameters($idApplication);

			if ($application != false){

				// On appelle le manager des utilisateurs
				$managerUtilisateur = $this->getManagers()->getManagerOf('Utilisateur');

				// On récupère les auteurs/développeurs de l'application
				$auteurs = $application->getAuteurs();
				$idAuteurs = array();
				foreach($auteurs as $auteur){
					$mailAuteur = $auteur->getMailAuteur();
					if(isset($mailAuteur)){
						$utilisateurAuteur = $managerUtilisateur->getUtilisateurByMail($mailAuteur);
						if($utilisateurAuteur !=false){
							array_push($idAuteurs, $utilisateurAuteur->getIdUtilisateur());
						}
					}
				}

				// On ajoute le créateur de l'application
				array_push($idAuteurs, $application->getCreateur()->getIdUtilisateur());
				
				// On vérifie que l'utilisateur a les droits d'accès sur cette application
				// si par exemple son abonnement est valide
				// sauf si celui-ci est admin
				$abonnementUser = true; // --->>> A modifier par la suite
				if($abonnementUser || $user->getAttribute('isAdmin')){

					// On vérifie que l'application est bien activée
					// L'admin a le droit de faire fonctionner une application non activée
					if($application->getStatut()->getIdStatut() > 4 || $user->getAttribute('isAdmin') || in_array($utilisateur->getIdUtilisateur(), $idAuteurs)){

						// On récupère la dernière version de l'application
						$version = $application->getVersions()[count($application->getVersions()) - 1];

						// On récupère les tâche de l'application
						$taches = $version->getTaches();


						if(count($taches) != 0){
							$listeTacheAAfficher = '';
							$listeTypeDonnee = array();
							$tacheAvecParametre = array();
							$tacheParametre=array();
							$tabParametres = array();
							foreach($taches as $tache){

								$listeTacheAAfficher.='<option value="'.$tache->getNomTache().'">'.$tache->getNomTache().'</option>';
								foreach($tache->getTacheTypeDonneeUtilisateurs() as $tacheData){
									$typeDonneeUtilisateur = array(
										'nomTache' => $tache->getNomTache(),
										'description' => $tacheData->getDescription(),
										'nomTypeDonnee' => $tacheData->getTypeDonneeUtilisateur()->getNomTypeDonneeUtilisateur(),
										'ext' => $tacheData->getTypeDonneeUtilisateur()->getExtensionTypeDonneeUtilisateur(),
										);
									array_push($listeTypeDonnee, $typeDonneeUtilisateur);
								}
								
										foreach($tache->getFonctions() as $fonction){
											if(count($fonction->getParametres()) != 0){
												foreach($fonction->getParametres() as $parametre){
													if($parametre->getStatutPublicParametre()){
														$parametres = array('nomTache' => $tache->getNomTache(),
																'idParams' => $parametre->getIdParametre(),
																'nomParams' => $parametre->getNomParametre(),
																'defaultVal' => $parametre->getValeurDefautParametre(),
																'minVal' => $parametre->getValeurMinParametre(),
																'maxVal' => $parametre->getValeurMaxParametre(),
																'pasVal' => $parametre->getValeurPasParametre() );
														array_push($tabParametres, $parametres);
													}
													
												}
											}

										}
							}
							if(count($listeTypeDonnee) != 0){
								// On ajoute les variables à la page
								$this->page->addVar('listeTypeDonnee', $listeTypeDonnee);
								$this->page->addVar('listeTacheAAfficher', $listeTacheAAfficher);
								$this->page->addVar('listeParams', $tabParametres);
								
							}else{
								$user->getMessageClient()->addErreur(self::APPLICATION_HAS_NO_MULE);
							}
						}else{
							$user->getMessageClient()->addErreur(self::APPLICATION_HAS_NO_MULE);
						}
					}else{
						$user->getMessageClient()->addErreur(self::APPLICATION_NOT_ACTIVATED);
					}
				}else{
					$user->getMessageClient()->addErreur(self::DENY_USE_APPLICATION);
				}
			}else{
				$user->getMessageClient()->addErreur(self::NO_APPLICATION);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}
}