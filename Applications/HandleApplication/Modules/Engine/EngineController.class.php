<?php
// +----------------------------------------------------------------------+
// | PHP Version 7								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour exécuter les applications.			  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe EngineController
 * @access: public
 * @version: 1
 */

namespace Applications\HandleApplication\Modules\Engine;
	
class EngineController extends \Library\BackController
{
	
	/**
	* Permet d'executer la mule d'une application
	**/
	public function executeRunTheMule($request)
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

			if($application != false){

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
				// sauf si celui-ci est admin ainsi que les
				// développeurs qui souhaitent tester l'application
				$abonnementUser = true; // --->>> A modifier par la suite
				if($abonnementUser || $user->getAttribute('isAdmin')){

					// On vérifie que l'application est bien activée
					// L'admin a le droit de faire fonctionner une application non activée ainsi que les
					// développeurs qui souhaitent tester l'application
					if($application->getStatut()->getIdStatut() > 4 || $user->getAttribute('isAdmin') || in_array($utilisateur->getIdUtilisateur(), $idAuteurs)){

						// On récupère toutes les données envoyées et on les contrôle
						$i=0; $j=0; $tabDonneeUtilisateur = array(); $noError = true;
						// On appelle le manager des données
						$managerDonneeUtilisateur = $this->getManagers()->getManagerOf('DonneeUtilisateur');
						// On récupère le manager des UtilisateurDonneeUtilisateur
						$managerUtilisateurDonneeUtilisateur = $this->getManagers()->getManagerOf('UtilisateurDonneeUtilisateur');
						
						while($request->isExistPost('tache'.$i.'data'.$j)){
							
							// On récupère la donnée utilisateur en question si elle existe avec tous ses attributs emplis
							$idData = $request->getPostData('tache'.$i.'data'.$j);
							
							if(strpos($idData, 'noolibData_') !== false){ // Si c'est une donnéeUtilisateur
								$idData = str_replace('noolibData_', '', $idData); // On supprime le caractère $
								$donneeUtilisateur = $managerDonneeUtilisateur->getDonneeUtilisateurById((int) $idData);
								if($donneeUtilisateur != false){
									$utilisateurDonneeUtilisateur = $managerUtilisateurDonneeUtilisateur->getUtilisateurDonneeUtilisateurById($utilisateur->getIdUtilisateur(), $donneeUtilisateur->getIdDonneeUtilisateur());
									// On vérifie que la donnée utilisateur appartient bien à l'utilisateur en session
									if($utilisateurDonneeUtilisateur){
										array_push($tabDonneeUtilisateur, $donneeUtilisateur);
									}else{
										$noError = false;
										$user->getMessageClient()->addErreur(self::DENY_HANDLE_DATA);
										break;
									}
								}else{
									$noError = false;
									$user->getMessageClient()->addErreur(self::ENGINE_NO_DATA);
									break;
								}
							}else{
								
								// On appelle le manager des type de données
								$managerTypeDonneeUtilisateur = $this->getManagers()->getManagerOf('TypeDonneeUtilisateur');
								$typeDonneeUtilisateur = $managerTypeDonneeUtilisateur->getTypeDonneeUtilisateurByExtension('input.txt');

								$inputData = new \Library\Entities\InputDonneeUtilisateur(array(
										'valeurInputDonneeUtilisateur' => $idData,
										'typeDonneeUtilisateur' => $typeDonneeUtilisateur
									));

								array_push($tabDonneeUtilisateur, $inputData);
							}
							++$j;
							if(!$request->isExistPost('tache'.$i.'data'.$j)){
								++$i;$j=0;
							}
						}
						
						if($noError){

							// Récupération des url des données autres que les paramètres ou les inputsText de la tâche
							$tabUrlDestinationDonneeUtilisateur = array();
							foreach($tabDonneeUtilisateur as $donneeUtilisateur){
								if($donneeUtilisateur instanceof \Library\Entities\DonneeUtilisateur && ! $donneeUtilisateur instanceof \Library\Entities\InputDonneeUtilisateur){
									array_push($tabUrlDestinationDonneeUtilisateur, $donneeUtilisateur->getUrlDonneeUtilisateur());
								}else{
									array_push($tabUrlDestinationDonneeUtilisateur, false);
								}
							}


							// On récupère la version demandée si admin/auteurs ou la dernière version active de l'application
							$idVersion = (int) $request->getPostData('idVersion');
							if(!empty($idVersion) && in_array($utilisateur->getIdUtilisateur(), $idAuteurs)){
								
								// On vérifie que l'id de la version existe pour l'application
								foreach($application->getVersions() as $versionApp){
									if($versionApp->getIdVersion() === $idVersion){
										$version = $versionApp;
										break;
									}
								}
							}else{
								// On prend la dernière version active de l'application
								foreach($application->getVersions() as $versionApp){
									if($versionApp->getActiveVersion()){
										$version = $versionApp;
										break;
									}
								}
							}

							if(isset($version)){
								// On récupère toutes les données envoyées et on les contrôle
								$i=0; $tabTaches = array(); $noError = true; $offset = 0;
								// On récupère les tâches de l'application demandée par l'utilisateur
								$taches = $version->getTaches();
								while($request->isExistPost('tache'.$i)){
									$nomTacheApplication = $request->getPostData('tache'.$i);
									// On vérifie que cette tâche existe dans l'application
									foreach($taches as $tache){
										if($tache->getNomTache() === $nomTacheApplication){
											$tacheDemandee = $tache;
											break;
										}
									}

									if(isset($tacheDemandee)){

										$nombreDeDonnee = count($tacheDemandee->getTacheTypeDonneeUtilisateurs());

										$outputData = $this->executeRun($request, $application, $version->getNumVersion(), $tacheDemandee, array_slice($tabDonneeUtilisateur, $offset, $nombreDeDonnee), array_slice($tabUrlDestinationDonneeUtilisateur, $offset, $nombreDeDonnee));
										$offset = $offset + $nombreDeDonnee;
										// On retourne le résultat à la page ou un message d'erreur
										if($outputData != false){
											$user->getMessageClient()->addReussite($outputData);
										}else if(count($this->getApp()->getExec()->getErreurs()) != 0){
											$user->getMessageClient()->addErreur($this->getApp()->getExec()->getErreurs());
										}else if(!$user->getMessageClient()->hasErreur()){
											$user->getMessageClient()->addErreur($nomTacheApplication.': '.self::ERROR_RUNNING_APPLICATION);
										}
										unset($tacheDemandee);
									}else{
										$user->getMessageClient()->addErreur(self::NO_TASK);
									}
									++$i;
								}

								// Execution du script Bash pour vider le dossier User linux
								// On execute l'objet Exec
								$exec = $this->getApp()->getExec();
								$exec->delFolderInProd($utilisateur);
							}else{
								$user->getMessageClient()->addErreur(self::TREE_VERSION_NOT_ACTIVATED);
							}
						}else{
							$user->getMessageClient()->addErreur(self::ENGINE_NO_DATA_MULE);
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


	/**
	* Permet d'executer une tâche d'une application
	**/
	private function executeRun($request, $applicationRunning, $numVersionRunning, $tacheDemandee, $tabDonneeUtilisateur, $filePaths){
		
		// On récupère l'utilisateur système
		$user = $this->app->getUser();

		// On informe que c'est un chargement Ajax
		$user->setAjax(true);

		// On récupère l'utilisateur connecté
		$utilisateur = unserialize($user->getAttribute('userSession'));

		// On récupère le création de l'application
		$createur = $applicationRunning->getCreateur();

		if($tacheDemandee instanceof \Library\Entities\Tache){

			if(count($tacheDemandee->getTacheTypeDonneeUtilisateurs()) === count($tabDonneeUtilisateur) && count($tabDonneeUtilisateur) === count($filePaths)){
				// On vérifie la pertinence des données envoyées par l'utilisateur
				$i = 0;
				foreach($tacheDemandee->getTacheTypeDonneeUtilisateurs() as $tacheData){
					
					$donnee = $tabDonneeUtilisateur[$i];
					$typeDonnee = $donnee->getTypeDonneeUtilisateur()->getExtensionTypeDonneeUtilisateur();
					$typeAttendu = $tacheData->getTypeDonneeUtilisateur()->getExtensionTypeDonneeUtilisateur();
					
					if($typeAttendu != 'all'){
						// On charge le fichier de configuration
						$config = $this->getApp()->getConfig();
						$typeImageAutorises = $config->getVar('donneeUtilisateur','typeImage','type');
						// On récupère les extensions autorisées
						$extensionsImageAutorisees = explode(',', $typeImageAutorises); //Tableau des extensions autorisées
						
						if($typeAttendu != 'all.image'){
														
							if($typeAttendu != 'all.image.without.dicom'){

								if($typeDonnee != $typeAttendu){
									$user->getMessageClient()->addErreur($tacheDemandee->getNomTache().': '.self::ENGINE_NO_MATCH_DATA);
									return false;
								}
							}else{
								// On retire l'élément 'dcm' du tableau des extensions
								unset($extensionsImageAutorisees[array_search('dcm', $extensionsImageAutorisees)]);
								unset($extensionsImageAutorisees[array_search('DCM', $extensionsImageAutorisees)]);
								$extensionsImageAutorisees = array_values($extensionsImageAutorisees); // Pour supprimer les cases vides
								if(!in_array($typeDonnee, $extensionsImageAutorisees)){
									$user->getMessageClient()->addErreur($tacheDemandee->getNomTache().': '.self::ENGINE_NO_MATCH_DATA);
									return false;
								}
							}
						}else{
							if(!in_array($typeDonnee, $extensionsImageAutorisees)){
								$user->getMessageClient()->addErreur($tacheDemandee->getNomTache().': '.self::ENGINE_NO_MATCH_DATA);
								return false;
							}
						}
					}
					++$i;
				}
			}else{
				$user->getMessageClient()->addErreur($tacheDemandee->getNomTache().': '.self::ENGINE_NO_MATCH_DATA);
				return false;
			}
			
			// On récupère la liste des fonctions à exécuter par rapport à la tâche demandée
			// par l'utilisateur.
			$fonctions = $tacheDemandee->getFonctions();

			if($fonctions != NULL){
				// On exécute chacune des fonctions dans l'ordre approprié
				// On parcourt chaque traitement jusqu'à trouvé celui demandé par l'utilisateur
			
				//Ajouter un & à la fin de la ligne de commande permet de lancer l'application en tâche de fond et permet de ne pas attendre le résultat.
				foreach($fonctions as $fonction){
					
					// On construit le tableau d'arguments à faire passer aux fonctions
					$args = array();
					$params = array();
					foreach($filePaths as $i=>$file){
						if(empty($file)){
							array_push($args, $tabDonneeUtilisateur[$i]->getValeurInputDonneeUtilisateur());
						}else{
							array_push($args, substr(strrchr($file,'/'),1));
						}
					}
					
					if(!isset($outputData) || empty($outputData)){
						$outputData = 'undefined';
					}else{
						$outputData = escapeshellarg($outputData); // Permettre de passer les arguments dans le shell_exec()
					}

					// On récupère les paramètres de la fonction modifiés (ou non) par l'utilisateur
					foreach($fonction->getParametres() as $parametre){
						if($request->isExistPOST($parametre->getIdParametre()) && $parametre->getStatutPublicParametre()){
							$valueParam = (float) $request->getPostData($parametre->getIdParametre());
							if($valueParam <= $parametre->getValeurMaxParametre() && $valueParam >= $parametre->getValeurMinParametre()){
								$params[$parametre->getNomParametre()] = $valueParam;
							}else{
								$params[$parametre->getNomParametre()] = $parametre->getValeurDefautParametre();
							}
						}else{
							$params[$parametre->getNomParametre()] = $parametre->getValeurDefautParametre();
						}
					}

					// On transforme le tableau d'arguments en chaine de caratères
					$args = implode('§', $args);
					$params = implode('§', $params);
					if(!empty($params)){
						$args = $args.'§'.$params.'§'.$outputData; // Exemple nomData1§nomData2§param1§param2§param3§previousOutput 
					}else{
						$args = $args.'§'.$outputData; // Exemple nomData1§nomData2§previousOutput 
					}
					
					// Execution du script Bash pour executer une fonction de l'application
					// On execute l'objet Exec
					$exec = $this->getApp()->getExec();
					$outputData = $exec->execFct($createur, $utilisateur, $applicationRunning, $numVersionRunning, $fonction, $args);
				
					// On contrôle la sortie - si le résultat n'est pas en JSON on retourne l'erreur	
					$result = trim($outputData);
					$result = str_replace( array( '<br>', '<br />', "\n", "\r" ), array( '', '', '', '' ), $result );
					$result = utf8_encode($result);
					
					if(json_decode($result, true) === null){
						$outputData = '{"erreurs":"'.$outputData.'"}';
						break;
					}
				}
				
				if($outputData != null && !empty($outputData)){
					return $outputData;
				}else{
					return false;
				}
			}else{
				$user->getMessageClient()->addErreur(self::ENGINE_NO_ACTION_FOR_TASK);
			}
		}else{
			$user->getMessageClient()->addErreur(self::FORMAT_TACHE);
		}
	}
}