<?php

namespace Library\Traits;

// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Trait pour fonctions universelles utilisées par différents 		  |
// | contrôleurs.       												  |
// +----------------------------------------------------------------------+
// | Auteur :  Mathieu COLLETTE <collettemathieu@noolib.com>     		  |
// +----------------------------------------------------------------------+

/**
 * @access: public
 * @version: 1
 */	

trait FonctionsUniverselles
{
	/**
	* Retourne un nom ou dossier de fichier nettoyé pour un enregistrement sur DD.
	**/
	private function cleanFileName($nomFichier){
		if(isset($nomFichier) && !empty($nomFichier)){
			
			//  Supprimer les espaces et les accents
		    $nomFichier=trim($nomFichier);
		    $nomFichier= strtr($nomFichier,"ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ","aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn");
		 
		    //  Supprime et remplace les caracètres spéciaux (autres que lettres et chiffres)
		    $nomFichier = preg_replace('#([^.a-z0-9]+)#i', '_', $nomFichier);
		    
	    	return $nomFichier;
		}else{
			return false;
		}
	}

	/**
	* Méthode pour rechercher une publication
	*/
	public function executeRequestPublication($reqPublication){

		// On récupère l'utilisateur système
		$user = $this->app->getUser();
		
		if(is_string($reqPublication) && preg_match('#([0-9]{2}.[0-9]{4,5}/.+)#', $reqPublication, $matches)){
			
			$reqPublication = $matches[1];
			// Création d'une requête externe avec la librairie CURL -> activer "allow_url_fopen = On;" et extension=php_curl.dll dans php.ini 
			// On utilise le site crossref.org afin de récupérer les informations.
			$url = 'http://doi.crossref.org/search/doi?pid=collettemathieu%40noolib.com&format=unixsd&doi='.$reqPublication;
			$options = array(
		        CURLOPT_RETURNTRANSFER => true,     // return web page
		        CURLOPT_HEADER         => false,    // don't return headers
		        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
		        CURLOPT_ENCODING       => "",       // handle all encodings
		        CURLOPT_USERAGENT      => "NooLib", // who am i
		        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
		        CURLOPT_CONNECTTIMEOUT => 10,      // timeout on connect
		        CURLOPT_TIMEOUT        => 10,      // timeout on response
		        CURLOPT_MAXREDIRS      => 3,       // stop after 10 redirects
		        CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
	        );

			// On initialise la connexion
			$curl = curl_init($url);
			// On initialise les options de connexion
			curl_setopt_array($curl, $options);
			// On récupère les résultats
			$results = curl_exec($curl);
			// On ferme la connexion
			curl_close($curl);
			// On traite les résultats via le DOM XML
			$dom = new \DomDocument();
			$dom->loadXML($results);

			$query = $dom->getElementsByTagName('query');
			if($query->item(0)->hasAttribute('status')){
				if($query->item(0)->getAttribute('status') === 'resolved'){
					$titleJournal = $dom->getElementsByTagName('full_title')->item(0)->firstChild->nodeValue;
					$titleArticle = $dom->getElementsByTagName('title')->item(0)->firstChild->nodeValue;
					$auteurs = $dom->getElementsByTagName('person_name');
					$yearPublication = $dom->getElementsByTagName('publication_date')->item(0)->getElementsByTagName('year')->item(0)->firstChild->nodeValue;
					$urlRessource = $dom->getElementsByTagName('resource')->item(0)->firstChild->nodeValue;
					$listeAuteurs = '';
					
					foreach($auteurs as $auteur){
						$nameAuteur = $auteur->getElementsByTagName('given_name')->item(0);
						$surnameAuteur = $auteur->getElementsByTagName('surname')->item(0);
						$listeAuteurs .= $nameAuteur->firstChild->nodeValue.' '.$surnameAuteur->firstChild->nodeValue.', ';
					}

					$results = array(
						'titleArticle' => $titleArticle,
						'listeAuteurs' => $listeAuteurs,
						'yearPublication' => $yearPublication,
						'titleJournal' => $titleJournal,
						'urlRessource' => $urlRessource
						);
					
					return $results;

				}else{
					$user->getMessageClient()->addErreur(self::TREE_DOI_NOT_FOUND);
					return false;
				}
			}else{
				$user->getMessageClient()->addErreur(self::TREE_DOI_NOT_FOUND);
				return false;
			}
		}else{
			$user->getMessageClient()->addErreur(self::TREE_ADD_PUBLICATION_ARG_EMPTY);
			return false;
		}
	}
	
}

