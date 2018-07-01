<?php

// +----------------------------------------------------------------------+
// | PHP Version 7                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2018 NooLib                                            |
// +----------------------------------------------------------------------+
// | Fonction permettant de charger les classes et les traits 			  |
// | automatiquement à l'aide de la pile PHP spl_autoload_register   	  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Fonction autoloadClasseEtTrait
 * @access: public
 * @version: 1
 */

/**
* Fonction permettant de charger les classes et les traits automatiquement à l'aide de la pile PHP spl_autoload_register
*/
function autoloadClasseEtTrait($nomClasse)
{
	/* On remplace les \ qui proviennent des namespace par des / pour spécifier le chemin réel*/
	$nomClasse = str_replace('\\', '/', $nomClasse);
	$fichierClasseDemande =  '../'.$nomClasse.'.class.php';
	$fichierTraitDemande =  '../'.$nomClasse.'.trait.php';

	/* On test si le fichier existe sinon on affiche une boîte de dialogue d'erreur*/
	if(file_exists($fichierClasseDemande)){
		require $fichierClasseDemande;
	}elseif(file_exists($fichierTraitDemande)){
		require $fichierTraitDemande;
	}
}

/* On place la fonction autoload dans la pile des fichiers chargés automatiquement par PHP pour notre framework*/
spl_autoload_register('autoloadClasseEtTrait');

/* Chargement des classes pour Composer */
if(file_exists('../vendor/autoload.php')){
	require_once '../vendor/autoload.php';	
}

