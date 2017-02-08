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
	
}

