<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour le gestionnaire de données.			  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe DataManagerController
 * @access: public
 * @version: 1
 */	

namespace Applications\HandleData\Modules\DataManager;
use Library\Entities\DonneeUtilisateur;
use Library\Entities\UtilisateurDonneeUtilisateur;

class DataManagerController extends \Library\BackController
{

	use \Library\Traits\MethodeDonneeUtilisateurControleur;

	/**
	* Méthode pour afficher le gestionnaire de données
	*/
	public function executeShowGestionnaireDonnee($request){
		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur connecté
			$utilisateur = unserialize($user->getAttribute('userSession'));

			// On affiche l'espace disponible des données
			$statDonneeUtilisateur = $this->executeEspaceDisponibleDonneeUtilisateur($utilisateur);
			$this->page->addVar('tailleMaxDonneesUtilisateur', $statDonneeUtilisateur['tailleMaxDonneesUtilisateur']);
			$this->page->addVar('tailleMoDonneesUtilisateur', $statDonneeUtilisateur['tailleMoDonneesUtilisateur']);
			$this->page->addVar('progressionPourcent', $statDonneeUtilisateur['progressionPourcent']);

			// On envoi l'utilisateur à la page
			$this->page->addVar('utilisateur', $utilisateur);

			// On charge le fichier de configuration
			$config = $this->getApp()->getConfig();
			$delaySaveDataUser = $config->getVar('divers','divers','delaySaveDataUser');
			// On envoi la variable à la page
			$this->page->addVar('delaySaveDataUser', $delaySaveDataUser);

		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}

	/**
	* Méthode pour afficher les données non sélectionnées par l'utilisateur (colonnes tableau ou images)
	*/
	public function executeShowData($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);
			
			// On récupère la donnée utilisateur
			$idDonneeUtilisateur = $request->getPostData('idDonneeUtilisateur');

			// On appelle le manager de DonneeUtilisateur
			$managerDonneeUtilisateur = $this->getManagers()->getManagerOf('DonneeUtilisateur');

			$donneeUtilisateur = $managerDonneeUtilisateur->getDonneeUtilisateurById($idDonneeUtilisateur);

			if($donneeUtilisateur){

				if(file_exists($donneeUtilisateur->getUrlDonneeUtilisateur())){
					// On récupère l'utilisateur connecté
					$utilisateur = unserialize($user->getAttribute('userSession'));

					// On récupère le manager des UtilisateurDonneeUtilisateur
					$managerUtilisateurDonneeUtilisateur = $this->getManagers()->getManagerOf('UtilisateurDonneeUtilisateur');
					$utilisateurDonneeUtilisateur = $managerUtilisateurDonneeUtilisateur->getUtilisateurDonneeUtilisateurById($utilisateur->getIdUtilisateur(), $idDonneeUtilisateur);
					
					// On vérifie que la donnée utilisateur appartient bien à l'utilisateur en session
					if($utilisateurDonneeUtilisateur){
						$typeDonnee = $donneeUtilisateur->getTypeDonneeUtilisateur()->getExtensionTypeDonneeUtilisateur();
						$this->getPage()->addVar('idDonneeUtilisateur', $idDonneeUtilisateur);
						
						// On charge le fichier de configuration
						$config = $this->getApp()->getConfig();
						$typesImage = explode(',', $config->getVar('donneeUtilisateur', 'typeImage', 'type'));

						if(in_array($typeDonnee, $typesImage)){ // Si c'est une image
							$image = file_get_contents($donneeUtilisateur->getUrlDonneeUtilisateur());
							$this->getPage()->addVar('image', $image);
							$this->getPage()->addVar('typeDonnee', $typeDonnee);
						}elseif($typeDonnee === 'xml'){
							$dataFile = file_get_contents($donneeUtilisateur->getUrlDonneeUtilisateur());
							$this->getPage()->addVar('dataFile', $dataFile);
							$this->getPage()->addVar('ext', $typeDonnee);
						}
					}else{
						$user->getMessageClient()->addErreur(self::DENY_HANDLE_DATA);
					}
				}else{
					$user->getMessageClient()->addErreur(self::NO_DATA_ANYMORE);
				}
			}else{
				$user->getMessageClient()->addErreur(self::NO_DATA);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}


	/**
	* Méthode pour afficher les données graphiques pour la librairie HighStock
	*/
	public function executeSendDataForHighStock($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère la donnée utilisateur
			$idDonneeUtilisateur = $request->getGetData('idDonneeUtilisateur');

			// On appelle le manager de DonneeUtilisateur
			$managerDonneeUtilisateur = $this->getManagers()->getManagerOf('DonneeUtilisateur');

			$donneeUtilisateur = $managerDonneeUtilisateur->getDonneeUtilisateurById($idDonneeUtilisateur);

			if($donneeUtilisateur){
				// On récupère le type de donnée de la donnée
				$typeDonnee = $donneeUtilisateur->getTypeDonneeUtilisateur()->getExtensionTypeDonneeUtilisateur();

				// On traite la donnée si c'est un graphe sinon on ne traite pas.
				if($typeDonnee === 'csv'){

					// On récupère l'utilisateur connecté
					$utilisateur = unserialize($user->getAttribute('userSession'));

					// On récupère le manager des UtilisateurDonneeUtilisateur
					$managerUtilisateurDonneeUtilisateur = $this->getManagers()->getManagerOf('UtilisateurDonneeUtilisateur');
					$utilisateurDonneeUtilisateur = $managerUtilisateurDonneeUtilisateur->getUtilisateurDonneeUtilisateurById($utilisateur->getIdUtilisateur(), $idDonneeUtilisateur);
					
					// On vérifie que la donnée utilisateur appartient bien à l'utilisateur en session
					if($utilisateurDonneeUtilisateur){

						// On ouvre le fichier data selon son url
						if(($fichierData = fopen($donneeUtilisateur->getUrlDonneeUtilisateur(), 'r')) !== false){
							
							// On parcourt l'ensemble du fichier CSV et dans chaque colonne on insère la valeur de la donnée
							$dataObjet = array();
							$data = array();
							$serieNavigator = array();
							$tabSeries = array();
							$legende = fgetcsv($fichierData, 500, ',');
							$sampleRate = $donneeUtilisateur->getSampleRateDonneeUtilisateur();
							$tailleDonneeUtilisateur = $donneeUtilisateur->getTailleDonneeUtilisateur();
							$tempsMinimumDonneeUtilisateur = $donneeUtilisateur->getTempsMinimumDonneeUtilisateur();

							// On supprime la 1ère colonne de la légende qui contient le Temps et n'est pas un signal
							array_shift($legende);
							$dataObjet['legende'] = $legende;
							$numSignal = count($legende);

							// On augmente artificiellement l'allocation mémoire
							ini_set('memory_limit','512M');

							// On créé un tableau de tableaux
							for($i = 0 ; $i<$numSignal ; ++$i){
								$tabSeries[$i] = array();
							}

							// On parcourt le fichier pour récupérer les données souhaitées
							$compteurDeLigne = $tempsMinimumDonneeUtilisateur;
							
							// Dans le cas où un début et une fin spécifiques sont demandés
							if($request->isExistGET('debutDonnee') && $request->isExistGET('finDonnee')){

								$debutDonnee = (int) $request->getGetData('debutDonnee');
								$finDonnee = (int) $request->getGetData('finDonnee');

								while(($line = fgetcsv($fichierData, 500, ',')) !== false){
									
									if($compteurDeLigne >= $debutDonnee && $compteurDeLigne <= $finDonnee){
										for($i = 0 ; $i<$numSignal ; ++$i){

											$M = array((double) $line[0]*1000*$sampleRate, (double) $line[$i+1]);

											array_push($tabSeries[$i], $M);
										}
									}
									++$compteurDeLigne;
									if($compteurDeLigne > $finDonnee){break;} // On sort de la boucle
								}
							// Sinon c'est le premier affichage de la donnée
							}else{
								
								$finDonnee = $tempsMinimumDonneeUtilisateur + 15000; // On n'affiche que les 15000 premiers points

								// On crée le pas d'affichage pour limiter le nombre de points à 1000 points pour le navigateur
								$pas = round($tailleDonneeUtilisateur/1000); // On souhaite un affichage de mille valeurs maximum
								if($pas < 1){
									$pas = 1;
								}

								while(($line = fgetcsv($fichierData, 500, ',')) !== false){
									
									if($compteurDeLigne <= $finDonnee){ 
										for($i = 0 ; $i<$numSignal ; ++$i){

											$M = array((double) $line[0]*1000*$sampleRate, (double) $line[$i+1]);

											array_push($tabSeries[$i], $M);
										}
									}

									if($compteurDeLigne % $pas == 0){
										$M = array((double) $line[0]*1000*$sampleRate, (double) $line[1]);
										array_push($serieNavigator, $M);
									}
									++$compteurDeLigne;
								}
							}

							// On ferme le fichier de donnée
							fclose($fichierData);

							for($i = 0 ; $i<$numSignal ; ++$i){
								array_push($data, $tabSeries[$i]);
							}

							// On enregistre les data dans le tableau des données à transmettre
							$dataObjet['data'] = $data;
							$dataObjet['serieNavigator'] = $serieNavigator;
							$dataObjet['sampleRate'] = $sampleRate;
							$dataObjet['tailleSignal'] = $tailleDonneeUtilisateur;
							$dataObjet['startTime'] = $tempsMinimumDonneeUtilisateur;
							$dataObjet['endTime'] = $finDonnee; // Car on affiche que 30 000 pts

							// Renvoi des données AJAX
							// On renvoie les données choisies par l'utilisateur sous forme d'un tableau de 2 colonnes (X, Y) pour affichage
							$this->page->addVar('dataObjet', $dataObjet);
						}else{
							$user->getMessageClient()->addErreur(self::ERROR_LOADING_DATA);
						}
						
					}else{
						$user->getMessageClient()->addErreur(self::DENY_HANDLE_DATA);
					}
				}else{
					$user->getMessageClient()->addErreur(self::DATA_MANAGER_NOT_DISPLAY_AS_GRAPH);
				}
			}else{
				$user->getMessageClient()->addErreur(self::NO_DATA);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
		
	}


	/**
	* Méthode pour sauvegarder la donnée sélectionnée par l'utilisateur
	*/
	public function executeUpdateData($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			if(!$user->isAjaxRequestAlreadyRunning()){

				// On récupère la donnée utilisateur
				$idDonneeUtilisateur = $request->getPostData('idDonneeUtilisateur');
				$debutDonnee = (int) $request->getPostData('debutDonnee');
				$finDonnee = (int) $request->getPostData('finDonnee');
				$rowSelected = json_decode($request->getPostData('rowSelected'));
				
				// On appelle le manager de DonneeUtilisateur
				$managerDonneeUtilisateur = $this->getManagers()->getManagerOf('DonneeUtilisateur');

				$donneeUtilisateur = $managerDonneeUtilisateur->getDonneeUtilisateurById($idDonneeUtilisateur);

				if($donneeUtilisateur){

					// On récupère le type de donnée de la donnée
					$typeDonnee = $donneeUtilisateur->getTypeDonneeUtilisateur()->getExtensionTypeDonneeUtilisateur();

					// On traite la donnée si c'est un graphe sinon on ne traite pas.
					if($typeDonnee === 'csv'){

						// On récupère l'utilisateur connecté
						$utilisateur = unserialize($user->getAttribute('userSession'));

						// On récupère le manager des UtilisateurDonneeUtilisateur
						$managerUtilisateurDonneeUtilisateur = $this->getManagers()->getManagerOf('UtilisateurDonneeUtilisateur');
						$utilisateurDonneeUtilisateur = $managerUtilisateurDonneeUtilisateur->getUtilisateurDonneeUtilisateurById($utilisateur->getIdUtilisateur(), $idDonneeUtilisateur);
						
						// On vérifie que la donnée utilisateur appartient bien à l'utilisateur en session
						if($utilisateurDonneeUtilisateur){

							// On augmente artificiellement l'allocation mémoire
							ini_set('memory_limit','512M');
							$finDonneeUtilisateur = (int) $donneeUtilisateur->getTempsMinimumDonneeUtilisateur() + $donneeUtilisateur->getTailleDonneeUtilisateur();
							if($debutDonnee >= 0 && $finDonnee > $debutDonnee && $finDonnee < $finDonneeUtilisateur && is_array($rowSelected)){
								// On ouvre le fichier data selon son url
								if(($fichierData = fopen($donneeUtilisateur->getUrlDonneeUtilisateur(), 'r')) !== false){
									
									// On parcourt l'ensemble du fichier CSV et dans chaque colonne on insère la valeur de la donnée
									$tabData = array();
									$tailleRowSelected = count($rowSelected);
									$lineOne = fgetcsv($fichierData, 500, ',');
									$lineTwo = fgetcsv($fichierData, 500, ',');
									$M = array();
									$M[0] = 'Time';
									for($i = 0 ; $i<$tailleRowSelected ; ++$i){
										$M[$i+1] = $lineOne[$rowSelected[$i]+1];
									}
									
									array_push($tabData, $M);
									$sampleRate = $donneeUtilisateur->getSampleRateDonneeUtilisateur();
									$k = $lineTwo[0]*$sampleRate; // On récupère le 1er point de donnée
									// Si celui-ci est supérieur ou égale à debutDonnee, on insère la ligne 2 dans le tableau final
									if($k >= $debutDonnee){
										array_push($tabData, $lineTwo);
									}

									// En fonction du début et de la fin de la donnée, on crée le tableau équivalent
									// Cela évite de parcourir tout le tableau à chaque fois
									while((($line = fgetcsv($fichierData, 500, ',')) !== false) && $k<=$finDonnee){
										
										if($k >= $debutDonnee){
											$M = array();
											$M[0] = (double) $line[0]; // On récupère l'échelle de temps
											for($i = 0 ; $i<$tailleRowSelected ; ++$i){
												$M[$i+1] = (double) $line[$rowSelected[$i]+1];
											}
											
											array_push($tabData, $M);

										}
										if($k > $finDonnee){break;} // On sort de la boucle

										$k++;
										
									}

									// On ferme le fichier de donnée
									fclose($fichierData);

									// On ouvre cette fois le fichier en écriture seulement, les données seront écrasées et remplacées par les nouvelles
									if(($fichierData = fopen($donneeUtilisateur->getUrlDonneeUtilisateur(), 'w')) !== false){
										$tailleTabData = count($tabData);
										for($i = 0; $i < $tailleTabData ; ++$i){
											fputcsv($fichierData, $tabData[$i], ',');
										}

										// On sauvegarde la donnée en BDD
										$donneeUtilisateur->hydrate(array(
											'tailleDonneeUtilisateur' => (int) $tailleTabData,
											'tempsMinimumDonneeUtilisateur' => (int) $debutDonnee,
											));

										if(count($donneeUtilisateur->getErreurs()) == 0){
											$managerDonneeUtilisateur->saveDonneeUtilisateur($donneeUtilisateur);

											$user->getMessageClient()->addReussite(self::DATA_MANAGER_UPDATE_DATA);

											// On affiche l'espace disponible des données
											$statDonneeUtilisateur = $this->executeEspaceDisponibleDonneeUtilisateur($utilisateur);

											$this->page->addVar('tailleMaxDonneesUtilisateur', $statDonneeUtilisateur['tailleMaxDonneesUtilisateur']);
											$this->page->addVar('tailleMoDonneesUtilisateur', $statDonneeUtilisateur['tailleMoDonneesUtilisateur']);
											$this->page->addVar('progressionPourcent', $statDonneeUtilisateur['progressionPourcent']);
										}else{
											$user->getMessageClient()->addErreur($donneeUtilisateur->getErreurs());
										}
									}else{
										$user->getMessageClient()->addErreur(self::ERROR_WRITTING_DATA);
									}

									// On ferme le fichier de donnée
									fclose($fichierData);

								}else{
									$user->getMessageClient()->addErreur(self::ERROR_LOADING_DATA);
								}
							}else{
							
								$user->getMessageClient()->addErreur(self::DATA_MANAGER_INTERVAL_NOT_VALID);
							}
						}else{
							$user->getMessageClient()->addErreur(self::DENY_HANDLE_DATA);
						}
					}else{
						$user->getMessageClient()->addErreur(self::DATA_MANAGER_DATA_NOT_BE_SAVED);	
					}
				}else{
					$user->getMessageClient()->addErreur(self::NO_DATA);
				}
			}else{
				// On ajoute la variable d'erreurs à la page
				$user->getMessageClient()->addErreur(self::ADD_DATA_DATA_ALREADY_LOADED);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}

	/**
	* Méthode pour créer une copie permanente de la donnée sélectionnée par l'utilisateur sur le serveur
	*/
	public function executeSaveDataOnServer($request){
		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur connecté
			$utilisateur = unserialize($user->getAttribute('userSession'));

			// On récupère la donnée utilisateur
			$idDonneeUtilisateur = $request->getPostData('idDonneeUtilisateur');

			// On appelle le manager de DonneeUtilisateur
			$managerDonneeUtilisateur = $this->getManagers()->getManagerOf('DonneeUtilisateur');

			$donneeUtilisateur = $managerDonneeUtilisateur->getDonneeUtilisateurById($idDonneeUtilisateur);

			if($donneeUtilisateur){

				// On récupère le manager des UtilisateurDonneeUtilisateur
				$managerUtilisateurDonneeUtilisateur = $this->getManagers()->getManagerOf('UtilisateurDonneeUtilisateur');
				$utilisateurDonneeUtilisateur = $managerUtilisateurDonneeUtilisateur->getUtilisateurDonneeUtilisateurById($utilisateur->getIdUtilisateur(), $idDonneeUtilisateur);
					
				// On vérifie que la donnée utilisateur appartient bien à l'utilisateur en session
				if($utilisateurDonneeUtilisateur){

					// On vérifie que la donnée est une donnée temporaire
					if($donneeUtilisateur->getIsInCache()){

						// On appelle la méthode des traits DonneeUtilisateur
						$this->executeSaveDonneeUtilisateur($request, $donneeUtilisateur);

					}else{ // Sinon on met la date de publication de la donnée à jour
						$managerDonneeUtilisateur->updateDateDonneeUtilisateur($donneeUtilisateur);
						$donneeUtilisateur = $managerDonneeUtilisateur->getDonneeUtilisateurById($donneeUtilisateur->getIdDonneeUtilisateur());
						// On met à jour la donnée de l'objet utilisateur
						$utilisateur->updateDonneeUtilisateur($donneeUtilisateur);
						// On place le profil de l'utilisateur en session
						$user->setAttribute('userSession', serialize($utilisateur));
						$user->getMessageClient()->addReussite(self::DATA_MANAGER_DATA_UPDATE_DATE);
					}

					// On ajoute l'objet utilisateur à la page
					$this->page->addVar('utilisateur', unserialize($user->getAttribute('userSession')));

					// On charge le fichier de configuration
					$config = $this->getApp()->getConfig();
					$delaySaveDataUser = $config->getVar('divers','divers','delaySaveDataUser');
					// On envoi la variable à la page
					$this->page->addVar('delaySaveDataUser', $delaySaveDataUser);

				}else{
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_DATA);
				}
			}else{
				$user->getMessageClient()->addErreur(self::NO_DATA);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}



	/**
	* Méthode pour supprimer une donnée utilisateur
	*/
	public function executeDeleteData($request){

		// On détecte qu'il sagit bien d'une requête AJAX sinon on ne fait rien.
		if ($request->isAjaxRequest()) {
			// On récupère l'utilisateur système
			$user = $this->app->getUser();

			// On informe que c'est un chargement Ajax
			$user->setAjax(true);

			// On récupère l'utilisateur connecté
			$utilisateur = unserialize($user->getAttribute('userSession'));

			// On récupère la donnée utilisateur
			$idDonneeUtilisateur = $request->getPostData('idDonneeUtilisateur');

			// On appelle le manager de DonneeUtilisateur
			$managerDonneeUtilisateur = $this->getManagers()->getManagerOf('DonneeUtilisateur');

			$donneeUtilisateur = $managerDonneeUtilisateur->getDonneeUtilisateurById($idDonneeUtilisateur);

			if($donneeUtilisateur){
				// On récupère le manager des UtilisateurDonneeUtilisateur
				$managerUtilisateurDonneeUtilisateur = $this->getManagers()->getManagerOf('UtilisateurDonneeUtilisateur');
				$utilisateurDonneeUtilisateur = $managerUtilisateurDonneeUtilisateur->getUtilisateurDonneeUtilisateurById($utilisateur->getIdUtilisateur(), $idDonneeUtilisateur);
				
				// On vérifie que la donnée utilisateur appartient bien à l'utilisateur en session
				if($utilisateurDonneeUtilisateur){

					// On appelle la méthode des traits DonneeUtilisateur
					$this->executeDeleteDonneeUtilisateur($request, $donneeUtilisateur);

					// On affiche l'espace disponible des données
					$statDonneeUtilisateur = $this->executeEspaceDisponibleDonneeUtilisateur($utilisateur);

					$this->page->addVar('tailleMaxDonneesUtilisateur', $statDonneeUtilisateur['tailleMaxDonneesUtilisateur']);
					$this->page->addVar('tailleMoDonneesUtilisateur', $statDonneeUtilisateur['tailleMoDonneesUtilisateur']);
					$this->page->addVar('progressionPourcent', $statDonneeUtilisateur['progressionPourcent']);

				}else{
					$user->getMessageClient()->addErreur(self::DENY_HANDLE_DATA);
				}
			}else{
				$user->getMessageClient()->addErreur(self::NO_DATA);
			}
		}else{
			// On procède à la redirection
			$response = $this->app->getHTTPResponse();
			$response->redirect('/');
		}
	}

}