<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour le Statut des applications . |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
* @name : Classe Statut
* @access : public
* @version : 1
*/
namespace Library\Entities;

/**
 * Classe Type Statut
 */
class StatutApplication extends \Library\Entity {
	
	protected $idStatut, $nomStatut, $couleurStatut, $applications= array();
	
	/**
	 * ******setters******
	 */
	public function setIdStatut($idStatut) {
		if (ctype_digit($idStatut) || is_int($idStatut)) {
			$this->idStatut = $idStatut;
		} else {
			$this->setErreurs("StatutApplication setIdStatut " . self::FORMAT_INT);
		}
	}
	public function setNomStatut($nomStatut) {
		if (is_string ( $nomStatut )) {
			$this->nomStatut = $nomStatut;
		} else {
			$this->setErreurs("StatutApplication setNomStatut " . self::FORMAT_STRING);
		}
	}
	public function setCouleurStatut($couleurStatut) {
		if (is_string ( $couleurStatut )) {
			$this->couleurStatut = $couleurStatut;
		} else {
			$this->setErreurs("StatutApplication setCouleurStatut " . self::FORMAT_STRING);
		}
	}
	public function setApplications($applications) {
		if (is_array ( $applications )) {
			$this->applications = $applications;
		} else {
			$this->setErreurs("StatutApplication setApplications " . SELF::FORMAT_ARRAY);
		}
	}
	/**
	 * *******getters*****
	 */
	public function getIdStatut() {
		return $this->idStatut;
	}
	public function getNomStatut() {
		return $this->nomStatut;
	}
	public function getApplications() {
		return $this->applications;
	}
	public function getCouleurStatut() {
		return $this->couleurStatut;
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
			$this->setErreurs("StatutApplication getApplicationFromApplications " . self::FORMAT_INT);
		}
		return $applicationReturn;
	}
	
	/**
	 * 
	 * 
	 * adders des listes
	 * 
	 */
	
	public function addApplication($application){
		if ($application instanceof Application){
			array_push($this->applications, $application);
		}
		else{
			$this->setErreurs("StatutApplication addApplication " . self::FORMAT_APPLICATION);
		}
	}
	public function addAllApplications(Array $applications){
		if (is_array($applications)){
			foreach ($applications as $application){
				if ($application instanceof Application){
					array_push($this->applications, $application);
				}
				else{
					$this->setErreurs("StatutApplication addAllApplications " . self::FORMAT_APPLICATION);
				}
			}
		}
		else{
			$this->setErreurs("StatutApplication addAllApplications " . self::FORMAT_ARRAY);
		}
	}
}
?>