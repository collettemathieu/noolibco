<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 														  |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib 											  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour les entités des applications constituant   |
// | l'architecture de la plateforme NooLib. 							  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE et <collettemathieu@noolib.com> 			  |
// | 			Corentin CHEVALLIER <ChevallierCorentin@noolib.com> 	  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe Entity
 * @access : public
 * @version : 1
 */
namespace Library;

abstract class Entity implements \ArrayAccess
{
	/* Définition des attributs */
	protected $erreurs = array();

	const FORMAT_STRING = "The parameter passed to the function is not in STRING format.";
	const FORMAT_INT = "The parameter passed to the function is not in INT format.";
	const FORMAT_FLOAT = "The parameter passed to the function is not in FLOAT format.";
	const FORMAT_ARRAY = "The parameter passed to the function is not in ARRAY format.";
	const FORMAT_BOOLEAN = "The parameter passed to the function is not in BOOLEAN format.";
	const FORMAT_APPLICATION = "The parameter passed to the function is not in /Library/Entities/Application format.";
	const FORMAT_AUTEUR = "The parameter passed to the function is not in /Library/Entities/Auteur format.";
	const FORMAT_CATEGORIE = "The parameter passed to the function is not in /Library/Entities/Categorie format.";
	const FORMAT_DONNEE_UTILISATEUR = "The parameter passed to the function is not in /Library/Entities/DonneeUtilisateur format.";
	const FORMAT_EQUIPE = "The parameter passed to the function is not in /Library/Entities/Equipe format.";
	const FORMAT_ETABLISSEMENT = "The parameter passed to the function is not in /Library/Entities/Etablissement format.";
	const FORMAT_FONCTION = "The parameter passed to the function is not in /Library/Entities/Fonction format.";
	const FORMAT_LABORATOIRE = "The parameter passed to the function is not in /Library/Entities/Laboratoire format.";
	const FORMAT_LOG = "The parameter passed to the function is not in /Library/Entities/Log format.";
	const FORMAT_MOT_CLE = "The parameter passed to the function is not in /Library/Entities/MotCle format.";
	const FORMAT_ORDRE = "The parameter passed to the function is not in /Library/Entities/Ordre format.";
	const FORMAT_PARAMETRE = "The parameter passed to the function is not in /Library/Entities/Parametre format.";
	const FORMAT_PAYS = "The parameter passed to the function is not in /Library/Entities/Pays format.";
	const FORMAT_PUBLICATION = "The parameter passed to the function is not in /Library/Entities/Publication format.";
	const FORMAT_STATUT = "The parameter passed to the function is not in /Library/Entities/Statut format.";
	const FORMAT_TACHE = "The parameter passed to the function is not in /Library/Entities/Tache format.";
	const FORMAT_TYPE_LOG = "The parameter passed to the function is not in /Library/Entities/TypeLog format.";
	const FORMAT_TYPE_DONNEE_UTILISATEUR = "The parameter passed to the function is not in /Library/Entities/TypeParametre format.";
	const FORMAT_TACHE_TYPE_DONNEE_UTILISATEUR = "The parameter passed to the function is not in /Library/Entities/TacheTypeParametre format.";
	const FORMAT_TYPE_PUBLICATION = "The parameter passed to the function is not in /Library/Entities/TypePublication format.";
	const FORMAT_TYPE_SORTIE = "The parameter passed to the function is not in /Library/Entities/TypeSortie format.";
	const FORMAT_TYPE_AFFICHAGE_PARAMETRE = "The parameter passed to the function is not in /Library/Entities/TypeAffichageParametre format.";
	const FORMAT_UNITE_PARAMETRE = "The parameter passed to the function is not in /Library/Entities/UniteParametre format.";
	const FORMAT_UTILISATEUR = "The parameter passed to the function is not in /Library/Entities/Utilisateur format.";
	const FORMAT_VERSION = "The parameter passed to the function is not in /Library/Entities/Version format.";
	const FORMAT_VILLE = "The parameter passed to the function is not in /Library/Entities/Ville format.";
	const FORMAT_EMPTY = "The parameter passed to the function is null or empty.";

	/* Constructeur de la classe */
	
	public function __construct(array $donnees)
	{
		$this->hydrate($donnees);
	}


	/**
	* Permet d'hydrater l'objet.
	*/
	public function hydrate(array $donnees)
	{
		if(isset($donnees) && !empty($donnees))
		{
			foreach($donnees as $cle => $valeur)
			{
				$methode = 'set'.ucfirst($cle);
				if(method_exists($this, $methode))
				{
					$this->$methode($valeur);
				}
			}
		}
	}


	/**
	* Implementation des fonctions de ArrayAccess
	*/ 

	public function offsetGet($var)
	{
		if(isset($this->$var) && is_callable(array($this, $var)))
		{
			return $this->$var();
		}
	}

	public function offsetSet($methode, $value)
	{
		$method = 'set'.ucfirst($methode);
		if(method_exists($this, $method))
		{
			$this->$method($value);
		}
	}

	public function offsetExists($var)
	{
		return isset($this->$var) && is_callable(array($this, $var));
	}

	public function offsetUnset($var)
	{
		throw new \Exception('Unset value is forbidden !');
	}


	/* SETTERS */

	public function setErreurs($erreur){
		if(is_string($erreur) && !empty($erreur)){
			$this->erreurs[] = $erreur;
		}
	}


	/* GETTERS */
	
	public function getErreurs()
	{
		return $this->erreurs;
	}

}






