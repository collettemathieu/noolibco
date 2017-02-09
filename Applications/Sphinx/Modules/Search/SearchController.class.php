<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur qui permet de réaliser des recherches, 	  |
// | le tout en requête AJAX.					  						  |
// +----------------------------------------------------------------------+
// | Auteurs : Antoine FAUCHARD <fauchardantoine@noolib.com>			  |
// |		   Guénaël DEQUEKER <dequekerguenael@noolib.com> 			  |
// | 		   Mathieu COLLETTE <collettemathieu@noolib.com>   			  |
// +----------------------------------------------------------------------+

/**
 * @name: Classe SearchController
 * @access: public
 * @version: 1
 */	

namespace Applications\Sphinx\Modules\Search;
	
class SearchController extends \Library\BackController
{
	/**
	* Fonction qui permet de faire une recherche à partir d'un appel AJAX
	*/
	public function executeSearchApplication($request)	{

		// On récupère l'utilisateur système
		$user = $this->app->getUser();

		// On informe que c'est un chargement Ajax
		$user->setAjax(true);
			
		$request = $this->getApp()->getHTTPRequest();
		
		// On charge le fichier de configuration
		$config = $this->getApp()->getConfig();
		
		// On récupère le contenu de cette requête: l'expression à rechercher et la catégorie.
		$motRecherche = $request->getPostData('rechercheApplication');
		$idCategorie = intval($request->getPostData('categorie'));
		echo $motRecherche;exit();
		$this->page->addVar('motRecherche',$motRecherche);
		
		//On appelle le manager Sphinx
		$managerSphinx = $this->getManagers()->getManagerOf('Sphinx');
		
		// On lance la methode pour la recherche
		// Contrôle du contenu de la recherche
		
		$rechercheVide = FALSE;
		$absenceResultat = FALSE;
		
		if(empty($motRecherche)){
			$rechercheVide = TRUE;
			
		} else {
			
			$applications = array();
			$applicationsBis = array();
			
			// On récupère le contenu de cette requête: l'expression à rechercher.
			// On contrôle les mots-clés entrés par l'utilisateur			
			// On appelle la fonction multiexplode pour les mots-clés entrés par l'utilisateur
			$delimitateursRecherches = explode('|', $config->getVar('divers', 'divers', 'delimitateurMotsCles')); //Tableau des délimiteurs autorisés
			$motsClesEntreUtilisateur = $config->multiexplode($delimitateursRecherches, $motRecherche);

			foreach($motsClesEntreUtilisateur as $motCle){
				$applicationsBis = array_merge($applicationsBis, $managerSphinx->searchSphinxApplicationByMotCle($motCle));
				$applicationsBis = array_merge($applicationsBis, $managerSphinx->searchSphinxApplicationByNom($motCle));
			}

			$tailleApplications = count($applicationsBis);
			for($i = 0; $i < $tailleApplications; ++$i){
				$val = false;
				for($j = $i + 1; $j < $tailleApplications; ++$j){
					if($applicationsBis[$i]->getIdApplication() === $applicationsBis[$j]->getIdApplication()){
						$val = true;
					}
				}
				if(!$val){
					$idCategorieApplication = $applicationsBis[$i]->getCategorie()->getIdCategorie();
					if($idCategorieApplication === $idCategorie || $idCategorie === 0){
						if($applicationsBis[$i]->getStatut()->getIdStatut() > 4){
							array_push($applications, $applicationsBis[$i]);
						}
					}
				}
			}

			if(empty($applications)){
				$absenceResultat = TRUE;
			}else{
				$this->page->addVar('applications',$applications);
			}
		}
		
		//On envoie les variables de contrôle à la vue.	
		$this->page->addVar('rechercheVide',$rechercheVide);
		
		$this->page->addVar('absenceResultat',$absenceResultat);
		
	}
	
	
}