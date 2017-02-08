<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib			         				          |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour afficher la page par defaut de la 	  |
// | librairie.							  			  					  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>    			  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe DefautController
 * @access: public
 * @version: 1
 */

namespace Applications\Librairie\Modules\Defaut;
	
class DefautController extends \Library\BackController
{
	public function executeShow()
	{
		// On charge le fichier de configuration
		$config = $this->getApp()->getConfig();
		
		// On récupère les applications des utilisateurs
		// On appelle le manager des Apps
		$managerApp = $this->getManagers()->getManagerOf('Application');
		$listeApps = $managerApp->getAllActiveApplications();

		//On ajoute la liste des applications à la page
		$this->page->addVar('apps', $listeApps);
		
		// On récupère les différentes catégories des applications
		// On appelle le manager des Catégories
		$managerCategorie = $this->getManagers()->getManagerOf('Categorie');
		$categories = $managerCategorie->getAllCategories();
		// On créé la variable d'affichage à insérer dans la page.

		$surCategorie = $categories[0]->getSurcategorie()->getNomSurcategorie();
		$categoriesAAfficher='<optgroup label="'.$surCategorie.'">';

		foreach($categories as $categorie){
		
			if($categorie->getSurcategorie()->getNomSurcategorie() === $surCategorie){
			
				$categoriesAAfficher.='<option title="'.$categorie->getDescriptionCategorie().'" value="'.$categorie->getIdCategorie().'">'.$categorie->getNomCategorie().'</option>';
			
			}else{
				$categoriesAAfficher .= '</optgroup>';
				$surCategorie = $categorie->getSurcategorie()->getNomSurcategorie();
				$categoriesAAfficher.='<optgroup label="'.$surCategorie.'">';
				$categoriesAAfficher.='<option title="'.$categorie->getDescriptionCategorie().'" value="'.$categorie->getIdCategorie().'">'.$categorie->getNomCategorie().'</option>';

			}
		}

		$categoriesAAfficher .= '</optgroup>';
		// On ajoute la variable flèche menu à la page
		$this->page->addVar('categoriesAAfficher', $categoriesAAfficher);
	}
}