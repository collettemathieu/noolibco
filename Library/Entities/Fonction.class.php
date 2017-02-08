<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Fonctions. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe Fonction
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe Fonction
 */
class Fonction extends \Library\Entity {
protected $idFonction, $nomFonction, $urlFonction, $extensionFonction, $parametres = array(), $taches = array();

const FORMAT_NOM_FONCTION = 'The name of the function must contain at least 3 letters and be less than 20 letters in length.';

	/**
	 * ******setters******
	 */

	public function setIdFonction($idFonction) {
		if (ctype_digit($idFonction) || is_int($idFonction)) {
			$this->idFonction = $idFonction;
		} else {
			$this->setErreurs("Fonction setIdFonction " . self::FORMAT_INT);
		}
	}
	public function setNomFonction($nomFonction) {
		if (is_string ( $nomFonction )){
			if(strlen ( $nomFonction ) > 3 && strlen ( $nomFonction ) < 21) {
				$this->nomFonction = $nomFonction;
			}else{
				$this->setErreurs(self::FORMAT_NOM_FONCTION);
			}
		} else {
			$this->setErreurs("Fonction setNomFonction " . self::FORMAT_STRING);
		}
	}	
	public function setUrlFonction($urlFonction) {
		if (is_string ( $urlFonction )) {
			$this->urlFonction = $urlFonction;
		} else {
			$this->setErreurs("Fonction setUrlFonction " . self::FORMAT_STRING);
		}
	}
	public function setExtensionFonction($extensionFonction) {
		if (is_string ( $extensionFonction )) {
			$this->extensionFonction = $extensionFonction;
		} else {
			$this->setErreurs("Fonction setExtensionFonction " . self::FORMAT_STRING);
		}
	}
	
	public function setParametres($parametres){
		if (is_array($parametres)) {
			$this->parametres = $parametres;
		} else {
			$this->setErreurs("Fonction setParametres " . self::FORMAT_ARRAY);
		}
	}	
	public function setTaches($taches){
		if (is_array($taches)) {
			$this->taches = $taches;
		} else {
			$this->setErreurs("Fonction setTaches " . self::FORMAT_ARRAY);
		}
	}
	
	/**
	 * *******getters*****
	 */
	public function getIdFonction() {
		return $this->idFonction;
	}
	public function getNomFonction() {
		return $this->nomFonction;
	}
	public function getUrlFonction() {
		return $this->urlFonction;
	}
	public function getExtensionFonction() {
		return $this->extensionFonction;
	}
	public function getParametres(){
		return $this->parametres;
	}
	public function getTaches(){
		return $this->taches;
	}
	
	public function getParametreFromParametres(int $idParametre){
		$parametreReturn = null;
		if (ctype_digit($idParametre) || is_int($idParametre)) {
			foreach ($this->parametres as $parametre){
				if ($parametre->getIdParametre() == $idParametre){
					$parametreReturn = $parametre;
				}
			}
		}
		else {
			$this->setErreurs("Fonction getParametreFromParametres " . self::FORMAT_INT);
		}
		return $parametreReturn;
	
	}
	public function getTacheFromTaches(int $idTache){
		$tacheReturn = null;
		if (ctype_digit($idTache) || is_int($idTache)) {
			foreach ($this->taches as $tache){
				if ($tache->getIdTache() == $idTache){
					$tacheReturn = $tache;
				}
			}
		}
		else {
			$this->setErreurs("Fonction getTacheFromTaches " . self::FORMAT_INT);
		}
		return $tacheReturn;
	
	}
	/**
	 *
	 * adders
	 *
	 */
	public function addParametre($parametre){
		if ($parametre instanceof Parametre){
			array_push($this->parametres, $parametre);
		}
		else{
			$this->setErreurs("Fonction addParametre " . self::FORMAT_PARAMETRE);
		}
	}
	public function addTache($tache){
		if ($tache instanceof Tache){
			array_push($this->taches, $tache);
		}
		else{
			$this->setErreurs("Fonction addTache " . self::FORMAT_TACHE);
		}
	}
	public function addAllParametres(Array $parametres){
		if (is_array($parametres)){
			foreach ($parametres as $parametre){
				if ($parametre instanceof Parametre){
					array_push($this->parametres, $parametre);
				}
				else{
					$this->setErreurs("Fonction addAllParametres " . self::FORMAT_PARAMETRE);
				}
			}
		}
		else{
			$this->setErreurs("Fonction addAllParametres " . self::FORMAT_ARRAY);
		}
	}
	public function addAllTaches(Array $taches){
		if (is_array($taches)){
			foreach ($taches as $tache){
				if ($tache instanceof Tache){
					array_push($this->taches, $tache);
				}
				else{
					$this->setErreurs("Fonction addAllTaches " . self::FORMAT_TACHE);
				}
			}
		}
		else{
			$this->setErreurs("Fonction addAllTaches " . self::FORMAT_ARRAY);
		}
	}
}
