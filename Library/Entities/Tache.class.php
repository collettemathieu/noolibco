<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Taches. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe Tache
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe Tache
 */
class Tache extends \Library\Entity {
	protected $idTache, $nomTache, $descriptionTache, $tacheTypeDonneeUtilisateurs = array(), $versions = array(), $fonctions = array();
	
	/* Déclaration des constantes de la classe */
	const FORMAT_NOM_TACHE = 'The name of the task must contain at least 5 letters and be less than 20 letters in length.';
	const FORMAT_DESCRIPTION_TACHE = 'The description of the task must contain at least 10 letters and be less than 100 letters in length.';
	

	/**
	 * ******setters******
	 */
	public function setIdTache($idTache) {
		if (ctype_digit($idTache) || is_int($idTache)) {
			$this->idTache = $idTache;
		} else {
			$this->setErreurs("Tache setIdTache " . self::FORMAT_INT);
		}
	}
	public function setNomTache($nomTache) {
		if (is_string ( $nomTache )) {
			if(strlen($nomTache) > 4 && strlen($nomTache) < 51){
				$this->nomTache = $nomTache;
			}else{
				$this->setErreurs(self::FORMAT_NOM_TACHE);
			}
		} else {
			$this->setErreurs("Tache setNomTache " . self::FORMAT_STRING);
		}
	}
	public function setDescriptionTache($descriptionTache) {
		if (is_string ( $descriptionTache )) {
			if(strlen($descriptionTache) > 9 && strlen($descriptionTache) < 1001){
				$this->descriptionTache = $descriptionTache;
			}else{
				$this->setErreurs(self::FORMAT_DESCRIPTION_TACHE);
			}
		} else {
			$this->setErreurs("Tache setDescriptionTache " . self::FORMAT_STRING);
		}
	} 

	public function setTacheTypeDonneeUtilisateurs($tacheTypeDonneeUtilisateurs) {
		if (is_array($tacheTypeDonneeUtilisateurs)) {
			$this->tacheTypeDonneeUtilisateurs = $tacheTypeDonneeUtilisateurs;
		} else {
			$this->setErreurs("Tache setTacheTypeDonneeUtilisateurs " . self::FORMAT_ARRAY);
		}
	}
	public function setVersions($versions) {
		if (is_array ( $versions )) {
			$this->versions = $versions;
		} else {
			$this->setErreurs("Tache setVersions " . SELF::FORMAT_ARRAY);
		}
	}
	public function setFonctions($fonctions) {
		if (is_array ( $fonctions )) {
			$this->fonctions = $fonctions;
		} else {
			$this->setErreurs("Tache setFonctions " . SELF::FORMAT_ARRAY);
		}
	}
	/**
	 * *******getters*****
	 */
	public function getIdTache() {
		return $this->idTache;
	}
	public function getNomTache() {
		return $this->nomTache;
	}
	public function getDescriptionTache() {
		return $this->descriptionTache;
	}

	public function getTacheTypeDonneeUtilisateurs() {
		return $this->tacheTypeDonneeUtilisateurs;
	}
	public function getFonctions(){
		return $this->fonctions;
	}
	public function getVersions(){
		return $this->versions;
	}
	public function getVersionFromVersions(int $idVersion){
		$versionReturn = null;
		if (ctype_digit($idVersion) || is_int($idVersion)) {
			foreach ($this->versions as $version){
				if ($version->getIdVersion() == $idVersion){
					$versionReturn = $version;
				}
			}
		}
		else {
			$this->setErreurs("Tache getVersionFromVersions " . self::FORMAT_INT);
		}
		return $versionReturn;
	
	}
	public function getFonctionFromFonctions(int $idFonction){
		$fonctionReturn = null;
		if (ctype_digit($idFonction) || is_int($idFonction)) {
			foreach ($this->fonctions as $fonction){
				if ($fonction->getIdFonction() == $idFonction){
					$fonctionReturn = $fonction;
				}
			}
		}
		else {
			$this->setErreurs("Tache getFonctionFromFonctions " . self::FORMAT_INT);
		}
		return $fonctionReturn;
	
	}
	
	/**
	 *
	 * Adders
	 *
	*/
	public function addVersion($version){
		if ($version instanceof Version){
			array_push($this->versions, $version);
		}
		else{
			$this->setErreurs("Tache addVersion " . self::FORMAT_VERSION);
		}
	}
	public function addTacheTypeDonneeUtilisateur($tacheTypeDonneeUtilisateur){
		if ($tacheTypeDonneeUtilisateur instanceof TacheTypeDonneeUtilisateur){
			array_push($this->tacheTypeDonneeUtilisateurs, $tacheTypeDonneeUtilisateur);
		}
		else{
			$this->setErreurs("Tache addTacheTypeDonneeUtilisateur " . self::FORMAT_TACHE_TYPE_PARAMETRE);
		}
	}
	public function addFonction($fonction){
		if ($fonction instanceof Fonction){
			array_push($this->fonctions, $fonction);
		}
		else{
			$this->setErreurs("Tache addFonction " . self::FORMAT_FONCTION);
		}
	}
	public function addAllVersions($versions){
		if (is_array($versions)){
			foreach ($versions as $version){
				if ($version instanceof Version){
					array_push($this->versions, $version);
				}
				else{
					$this->setErreurs("Tache addAllVersions " . self::FORMAT_VERSION);
					break;
				}
			}
		}
		else{
			$this->setErreurs("Tache addAllVersions " . self::FORMAT_ARRAY);
		}
	}
	public function addAllTypeDonneeUtilisateurs($typeDonneeUtilisateurs){
		if (is_array($typeDonneeUtilisateurs)){
			foreach ($typeDonneeUtilisateurs as $typeDonneeUtilisateur){
				if ($typeDonneeUtilisateur instanceof TypeDonneeUtilisateur){
					array_push($this->typeDonneeUtilisateurs, $typeDonneeUtilisateur);
				}
				else{
					$this->setErreurs("Tache addAllTypeDonneeUtilisateurs " . self::FORMAT_TYPE_PARAMETRE);
					break;
				}
			}
		}
		else{
			$this->setErreurs("Tache addAllVersions " . self::FORMAT_ARRAY);
		}
	}
	public function addAllFonctions($fonctions){
		if (is_array($fonctions)){
			foreach ($fonctions as $fonction){
				if ($fonction instanceof Fonction){
					array_push($this->fonctions, $fonction);
				}
				else{
					$this->setErreurs("Tache addAllFonctions " . self::FORMAT_FONCTION);
					break;
				}
			}
		}
		else{
			$this->setErreurs("Tache addAllFonctions " . self::FORMAT_ARRAY);
		}
	}
	
}
