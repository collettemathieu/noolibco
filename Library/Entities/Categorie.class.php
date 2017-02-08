<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Categorie. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe Categorie
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

use Library\Entities\Application;
use Library\Entities;
/**
 * Classe Categorie
 */
class Categorie extends \Library\Entity {
	protected $idCategorie, $nomCategorie, $descriptionCategorie, $applications = Array(), $surcategorie;
	
	/**
	 * ******setters******
	 */
	public function setIdCategorie($idCategorie) {
		if (ctype_digit($idCategorie) || is_int($idCategorie)) {
			$this->idCategorie = $idCategorie;
		} else {
			$this->setErreurs("Categorie setIdCategorie " . self::FORMAT_INT);
		}
	}
	public function setNomCategorie($nomCategorie) {
		if (is_string ( $nomCategorie )) {
			$this->nomCategorie = $nomCategorie;
		} else {
			$this->setErreurs("Categorie setNomCategorie " . self::FORMAT_STRING);
		}
	}
	public function setDescriptionCategorie($descriptionCategorie) {
		if (is_string ( $descriptionCategorie )) {
			$this->descriptionCategorie = $descriptionCategorie;
		} else {
			$this->setErreurs("Categorie setDescriptionCategorie " . self::FORMAT_STRING);
		}
	}
	public function setApplications($applications) {
		if (is_array ( $applications )) {
			$this->applications = $applications;
		} else {
			$this->setErreurs("Categorie setApplications " . self::FORMAT_ARRAY);
		}
	}
	public function setSurcategorie($surcategorie) {
		if ($surcategorie instanceof Surcategorie) {
			$this->surcategorie = $surcategorie;
		} else {
			$this->setErreurs("Categorie setSurcategorie " . self::FORMAT_STRING);
		}
	}
	/**
	 * *******getters*****
	 */
	public function getIdCategorie() {
		return $this->idCategorie;
	}
	public function getNomCategorie() {
		return $this->nomCategorie;
	}
	public function getDescriptionCategorie() {
		return $this->descriptionCategorie;
	}
	public function getApplications() {
		return $this->applications;
	}
	public function getSurcategorie() {
		return $this->surcategorie;
	}
	
	public function getApplicationFromApplications($idApplication){
		$applicationReturn = null;
		if (ctype_digit($idApplication) || is_int($idApplication)) {
			foreach ($this->applications as $application){
				if ($application->getIdApplication() == $idApplication){
					$applicationReturn = $application;
				}
			}
		}
		else {
			$this->setErreurs("Categorie getApplicationFromApplications " . self::FORMAT_INT);
		}
		return $applicationReturn;
	}
	
	/**
	 * 
	 * *******Adder des tableaux
	 * 
	 */
	
	public function addApplication($application){
		if ($application instanceof Application){
			array_push($this->applications, $application);
		}
		else{
			$this->setErreurs("Categorie addApplication " . self::FORMAT_APPLICATION);
		}
	}
	public function addAllApplications(Array $applications){
	if (is_array($applications)){
			foreach ($applications as $application){
				if ($application instanceof Application){
					array_push($this->applications, $application);
				}
				else{
					$this->setErreurs("Categorie addAllApplications " . self::FORMAT_APPLICATION);
				}
			}
		}
		else{
			$this->setErreurs("Categorie addAllApplications " . self::FORMAT_ARRAY);
		}
	}
}
