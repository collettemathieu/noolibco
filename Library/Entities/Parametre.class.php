<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Parametre. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe Parametre
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe Parametre
 */
class Parametre extends \Library\Entity {
	protected $idParametre, $nomParametre, $descriptionParametre, $statutPublicParametre, $typeAffichageParametre, $valeurDefautParametre, $valeurMinParametre, $valeurMaxParametre, $valeurPasParametre, $fonctions = array();
	
	const FORMAT_NOM_PARAMETRE = "The name of the parameter must contain at least 3 letters and be less than 10 letters in length.";
	const FORMAT_DESCRIPTION = "The description of the parameter must contain at least 10 letters and be less than 200 letters in length.";
	const FORMAT_STEP_NULL = "The step of the parameter cannot be equal to zero.";
	/**
	 * ******setters******
	 */
	public function setIdParametre($idParametre) {
		if (ctype_digit($idParametre) || is_int($idParametre)) {
			$this->idParametre = $idParametre;
		} else {
			$this->setErreurs("Parametre setIdParametre " . self::FORMAT_INT);
		}
	}
	public function setNomParametre($nomParametre) {
		if (is_string($nomParametre )) {
			if(strlen($nomParametre)>2 && strlen($nomParametre)<51 ){
				$this->nomParametre = $nomParametre;
			}else{
				$this->setErreurs(self::FORMAT_NOM_PARAMETRE);
			}
		} else {
			$this->setErreurs("Parametre setNomParametre " . self::FORMAT_STRING);
		}
	}
	public function setDescriptionParametre($descriptionParametre) {
		if (is_string ($descriptionParametre)) {
			if(strlen($descriptionParametre)>9 && strlen($descriptionParametre)<201) {
				$this->descriptionParametre = $descriptionParametre;
			}else{
				$this->setErreurs(self::FORMAT_DESCRIPTION);
			}
		} else {
			$this->setErreurs("Parametre setDescriptionParametre " . self::FORMAT_STRING);
		}
	}
	public function setFonctions ($fonctions){
		if(is_array($fonctions)){
			$this->fonctions = $fonctions;
		}
		else{
			$this->setErreurs("Parametre setFonctions " . self::FORMAT_ARRAY);
		}
	}
		
	public function setStatutPublicParametre($statutPublicParametre) {
		if (is_bool($statutPublicParametre)) {
			$this->statutPublicParametre = $statutPublicParametre;
		} else {
			$this->setErreurs("Parametre setStatutPublicParametre " . self::FORMAT_BOOLEAN);
		}
	}
	public function setTypeAffichageParametre($typeAffichageParametre) {
		if ($typeAffichageParametre instanceof TypeAffichageParametre) {
			$this->typeAffichageParametre = $typeAffichageParametre;
		} else {
			$this->setErreurs("Parametre setTypeAffichageParametre " . self::FORMAT_TYPE_AFFICHAGE_PARAMETRE);
		}
	}
	
	public function setValeurDefautParametre($valeurDefautParametre) {
		if (is_float($valeurDefautParametre)){
			$this->valeurDefautParametre = $valeurDefautParametre;
		} else {
			$this->setErreurs("Parametre setValeurDefautParametre " . self::FORMAT_FLOAT);
		}
	}
	
	public function setValeurMinParametre($valeurMinParametre) {
		if (is_float($valeurMinParametre)){
			$this->valeurMinParametre = $valeurMinParametre;
		} else {
			$this->setErreurs("Parametre setValeurMinParametre " . self::FORMAT_FLOAT);
		}
	}
	
	public function setValeurMaxParametre($valeurMaxParametre) {
		if (is_float($valeurMaxParametre)){
			$this->valeurMaxParametre = $valeurMaxParametre;
		} else {
			$this->setErreurs("Parametre setValeurMaxParametre " . self::FORMAT_FLOAT);
		}
	}
	
	public function setValeurPasParametre($valeurPasParametre) {
		if (is_float($valeurPasParametre)){
			if($valeurPasParametre != 0){
				$this->valeurPasParametre = $valeurPasParametre;
			}else{
				$this->setErreurs(self::FORMAT_STEP_NULL);
			}
		}else{
			$this->setErreurs("Parametre setValeurPasParametre " . self::FORMAT_FLOAT);
		}
	}
	
	/**
	 * *******getters*****
	 */
	public function getIdParametre() {
		return $this->idParametre;
	}
	public function getNomParametre() {
		return $this->nomParametre;
	}
	public function getDescriptionParametre() {
		return $this->descriptionParametre;
	}
	public function getFonctions() {
		return $this->fonctions;
	}
	
	public function getFonctionFromFonctions($idFonction){
		$fonctionReturn = null;
		if (ctype_digit($idFonction) || is_int($idFonction)) {
			foreach ($this->fonctions as $fonction){
				if ($fonction->getIdFonction() == $idFonction){
					$fonctionReturn = $fonction;
				}
			}
		}
		else {
			$this->setErreurs("Parametre getFonctionFromFonctions " . self::FORMAT_INT);
		}
		return $fonctionReturn;
	}
	
	public function getStatutPublicParametre() {
		return $this->statutPublicParametre;
	}
	
	public function getValeurDefautParametre() {
		return $this->valeurDefautParametre;
	}
	
	public function getTypeAffichageParametre() {
		return $this->typeAffichageParametre;
	}
	
	public function getValeurMinParametre() {
		return $this->valeurMinParametre;
	}
	
	public function getValeurMaxParametre() {
		return $this->valeurMaxParametre;
	}
	
	public function getValeurPasParametre() {
		return $this->valeurPasParametre;
	}
	
	/**
	 * 
	 * 
	 * adders des listes
	 * 
	 */
	
	public function addFonction($fonction){
		if ($fonction instanceof Fonction){
			array_push($this->fonctions, $fonction);
		}
		else{
			$this->setErreurs("Parametre addFonction " . self::FORMAT_FONCTION);
		}
	}
	public function addAllFonctions(Array $fonctions){
		if (is_array($fonctions)){
			foreach ($fonctions as $fonction){
				if ($fonction instanceof Fonction){
					array_push($this->fonctions, $fonction);
				}
				else{
					$this->setErreurs("Parametre addAllFonctions " . self::FORMAT_FONCTION);
				}
			}
		}
		else{
			$this->setErreurs("Parametre addAllFonctions " . self::FORMAT_ARRAY);
		}
	}
}
