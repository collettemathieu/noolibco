<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP pour les entités des applications constituant   |
// | l'architecture de la plateforme ScienceAPart. 						  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
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
	const FORMAT_ARTICLE = "The parameter passed to the function is not in /Library/Entities/Article format.";
	const FORMAT_COMMENTAIRE = "The parameter passed to the function is not in /Library/Entities/Commentaire format.";
	const FORMAT_CATEGORIE = "The parameter passed to the function is not in /Library/Entities/Categorie format.";
	const FORMAT_COURS = "The parameter passed to the function is not in /Library/Entities/Cours format.";
	const FORMAT_COURS_GLOBAL = "The parameter passed to the function is not in /Library/Entities/CoursGlobal format.";
	const FORMAT_MOT_CLE = "The parameter passed to the function is not in /Library/Entities/MotCle format.";
	const FORMAT_MEDIA = "The parameter passed to the function is not in /Library/Entities/Media format.";
	const FORMAT_TYPE_LOG = "The parameter passed to the function is not in /Library/Entities/TypeLog format.";
	const FORMAT_UTILISATEUR = "The parameter passed to the function is not in /Library/Entities/Utilisateur format.";
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






