<?php
// +----------------------------------------------------------------------+
// | PHP Version 7 														  |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib 											  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Versions. 										  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe Version
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

use Library\entities\Application;
/**
 * Classe Version
 */
class Version extends \Library\Entity {
	protected $idVersion, $activeVersion, $numVersion, $datePublicationVersion, $noteMajVersion, $application, $taches = array();
	
	/* Déclaration des constantes d'erreurs particulières à */
	const NOM_VERSION = 'The name of the version must contain 8 letters (e.g. 12.34.56).';
	const DESCRIPTION_VERSION = 'The description of the version must contain at least 1 letter and be less than 100 letters in length.';

	/**
	 * ******setters******
	 */
	public function setIdVersion($idVersion) {
		if (ctype_digit($idVersion) || is_int($idVersion)) {
			$this->idVersion = $idVersion;
		} else {
			$this->setErreurs("Version setIdVersion " . self::FORMAT_INT);
		}
	}
	public function setActiveVersion($bool) {
		if (is_bool($bool)) {
			$this->activeVersion = $bool;
		} else {
			$this->setErreurs("Version setActiveVersion " . self::FORMAT_BOOLEAN);
		}
	}
	public function setNumVersion($numVersion) {
		if (is_string ( $numVersion )) {
			if (mb_strlen($numVersion, 'UTF8') <= 8){
				$this->numVersion = $numVersion;
			}
			else {
				$this->setErreurs (self::NOM_VERSION );
			}
		} else {
			$this->setErreurs("Version setNumVersion " . self::FORMAT_STRING);
		}
	}
	public function setDatePublicationVersion($datePublicationVersion) {
		$this->datePublicationVersion = $datePublicationVersion;
	}
	public function setNoteMajVersion($noteMajVersion) {
		if (is_string ( $noteMajVersion )) {
			if (mb_strlen($noteMajVersion, 'UTF8') < 101){
				$this->noteMajVersion = $noteMajVersion;
			}
			else {
				$this->setErreurs (self::DESCRIPTION_VERSION );
			}
		} else {
			$this->setErreurs("Version setNoteMajVersion " . self::FORMAT_STRING);
		}
	}
	public function setApplication($application) {
		if ($application instanceof Application) {
			$this->application = $application;
		} else {
			$this->setErreurs("Version setApplication " . self::FORMAT_APPLICATION);
		}
	}
	public function setTaches($taches) {
		if (is_array($taches)) {
			$this->taches = $taches;
		} else {
			$this->setErreurs("Version setTaches " . self::FORMAT_ARRAY);
		}
	}
	/**
	 * *******getters*****
	 */
	public function getIdVersion() {
		return (int) $this->idVersion;
	}
	public function getActiveVersion() {
		return $this->activeVersion;
	}
	public function getNumVersion() {
		return $this->numVersion;
	}
	public function getDatePublicationVersion() {
		return $this->datePublicationVersion;
	}
	public function getNoteMajVersion() {
		return $this->noteMajVersion;
	}
	public function getApplication() {
		return $this->application;
	}
	public function getTaches(){
		return $this->taches;
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
			$this->setErreurs("Version getTacheFromTaches " . self::FORMAT_INT);
		}
		return $tacheReturn;
	
	}
	/**
	 *
	 * adders
	 *
	 */
	public function addTache($tache){
		if ($tache instanceof Tache){
			array_push($this->taches, $tache);
		}
		else{
			$this->setErreurs("Version addTache " . self::FORMAT_TACHE);
		}
	}
	public function addAllTaches(Array $taches){
		if (is_array($taches)){
			foreach ($taches as $tache){
				if ($tache instanceof Tache){
					array_push($this->taches, $tache);
				}
				else{
					$this->setErreurs("Version addAllTaches " . self::FORMAT_TACHE);
				}
			}
		}
		else{
			$this->setErreurs("Version addAllTaches " . self::FORMAT_ARRAY);
		}
	}
	
}
