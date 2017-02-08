<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 														  |
// +----------------------------------------------------------------------+
// | Copyright (c) 2015 NooLib 										   	  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les TacheTypeDonneeUtilisateur. 					  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com> 			  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe TacheTypeDonneeUtilisateur
 * @access : public
 * @version : 1
 */
namespace Library\Entities;


/**
 * Classe TacheTypeDonneeUtilisateur
 */
class TacheTypeDonneeUtilisateur extends \Library\Entity {
	
	/* Définition des attributs */
	protected $tache, $typeDonneeUtilisateur, $ordre, $description, $uniteDonneeUtilisateur;

	const FORMAT_DESCRIPTION_TACHE_TYPE_DONNEE_UTILISATEUR = 'The data description must contain at least 5 letters and be less than 40 letters in length.';
	
	/**
	 * ******Setter *****
	 */
	public function setTypeDonneeUtilisateur($typeDonneeUtilisateur) {
		if ($typeDonneeUtilisateur instanceof TypeDonneeUtilisateur) {
			$this->typeDonneeUtilisateur = $typeDonneeUtilisateur;
		} else {
			$this->setErreurs ("TacheTypeDonneeUtilisateur setTypeDonneeUtilisateur " . self::FORMAT_TYPE_DONNEE_UTILISATEUR);
		}
	}
	public function setTache($tache) {
		if ($tache instanceof Tache) {
			$this->tache = $tache;
		} else {
			$this->setErreurs ("TacheTypeDonneeUtilisateur setTache " .  self::FORMAT_TACHE );
		}
	}
	public function setOrdre($ordre) {
		if (ctype_digit($ordre) || is_int($ordre))  {
			$this->ordre = $ordre;
		} else {
			$this->setErreurs ("TacheTypeDonneeUtilisateur setOrdre " .  self::FORMAT_INTEGER );
		}
	}
	public function setDescription($description) {
		if(is_string($description)){
			if(strlen($description) > 1 && strlen($description) < 41){
				$this->description = $description;
			}else{
				$this->setErreurs ("TacheTypeDonneeUtilisateur setDescription " .  self::FORMAT_DESCRIPTION_TACHE_TYPE_DONNEE_UTILISATEUR );
			}
		}else {
			$this->setErreurs ("TacheTypeDonneeUtilisateur setDescription " .  self::FORMAT_STRING );
		}
	}
	public function setUniteDonneeUtilisateur($uniteDonneeUtilisateur) {
		if ($uniteDonneeUtilisateur instanceof UniteDonneeUtilisateur) {
			$this->uniteDonneeUtilisateur = $uniteDonneeUtilisateur;
		} else {
			$this->setErreurs("TypeDonneeUtilisateur setUniteDonneeUtilisateur " . self::FORMAT_UNITE_PARAMETRE);
		}
	}
	/**
	 * ********** getter ****************
	 */
	public function getTache() {
		return $this->tache;
	}
	public function getTypeDonneeUtilisateur() {
		return $this->typeDonneeUtilisateur;
	}
	public function getOrdre() {
		return $this->ordre;
	}
	public function getDescription() {
		return $this->description;
	}
	public function getUniteDonneeUtilisateur() {
		return $this->uniteDonneeUtilisateur;
	}
}
?>