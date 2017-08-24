<?php
namespace Library;

// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe PHP MessageClient pour l'affichage de messages au client.     |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe MessageClient
 * @access: public
 * @version: 1
 */

class MessageClient
{
	/* Définition des attributs */

	protected $erreurs = array(),
			  $reussites = array();

	/* Constructeur de la classe */

	public function __construct(){
		if(isset($_SESSION['erreurs'])){
			$this->erreurs = $_SESSION['erreurs'];
		}
		if(isset($_SESSION['reussites'])){
			$this->reussites = $_SESSION['reussites'];
		}
	}

	/**
	* Ajoute une nouvelle erreur
	**/
	public function addErreur($erreur){
		if(isset($erreur) && !empty($erreur)){
			if(is_array($erreur)){
				$this->erreurs = array_merge($this->erreurs, $erreur);
			}else{
				array_push($this->erreurs, $erreur);
			}
			$_SESSION['erreurs'] = $this->erreurs;
		}
	}

	/**
	* Ajoute une nouvelle réussite
	**/
	public function addReussite($reussite){
		if(isset($reussite) && !empty($reussite)){
			if(is_array($reussite)){
				$this->reussites = array_merge($this->reussites, $reussite);
			}else{
				array_push($this->reussites, $reussite);
			}
			$_SESSION['reussites'] = $this->reussites;
		}
	}

	/**
	* Retourne si une erreur existe
	**/
	public function hasErreur(){
		return !empty($this->erreurs);
	}


	/**
	* Retourne si une réussite existe
	**/
	public function hasReussite(){
		return !empty($this->reussites);
	}
	

	/* GETTERS */

	public function getErreurs(){
		if(!empty($this->erreurs)){
			unset($_SESSION['erreurs']);
			$erreurs= $this->erreurs;
			$this->erreurs = array();
			return $erreurs;
		}else{
			return null;
		}
	}

	public function getReussites(){
		if(!empty($this->reussites)){
			unset($_SESSION['reussites']);
			$reussites= $this->reussites;
			$this->reussites = array();
			return $reussites;
		}else{
			return null;
		}		
	}

}
