<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Mots Cl�s. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe MotCle
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe MotCle
 */
class MotCle extends \Library\Entity {
	protected $idMotCle, $nomMotCle, $applications = array();
	
	/**
	 * ******setters******
	 */
	public function setIdMotCle($idMotCle) {
		if (ctype_digit($idMotCle) || is_int($idMotCle)) {
			$this->idMotCle = $idMotCle;
		} else {
			$this->setErreurs("MotCle setIdMotCle " . self::FORMAT_INT);
		}
	}
	public function setNomMotCle($nomMotCle) {
		if (is_string ( $nomMotCle )) {
			$this->nomMotCle = $nomMotCle;
		} else {
			$this->setErreurs("MotCle setNomMotCle " . self::FORMAT_STR);
		} 
	}
	public function setApplications($applications) {
		if (is_array($applications)) {
			$this->applications = $applications;
		} else {
			$this->setErreurs("MotCle setApplications " . self::FORMAT_ARRAY);
		}
	}
	/**
	 * *******getters*****
	 */
	public function getIdMotCle() {
		return $this->idMotCle;
	}
	public function getNomMotCle() {
		return $this->nomMotCle;
	}
	public function getApplications() {
		return $this->applications;
	}
	
	public function getApplicationFromApplications($idApplication){
		$applicationReturn = null;
		if (ctype_digit($idApplication) || is_int($idApplication)) {
			foreach ($this->applications as $app){
				if ($app->getIdApplication() == $idApplication){
					$applicationReturn = $app;
				}
			}
		}
		else {
			$this->setErreurs("MotCle getApplicationFromApplications " . self::FORMAT_INT);
		}
		return $applicationReturn;
	}
	
	/**
	 * 
	 * 
	 * adders des listes
	 * 
	 */
	
	public function addApplication($applications){
		if ($applications instanceof Application){
			array_push($this->applications, $applications);
		}
		else{
			$this->setErreurs("MotCle addApplication " . self::FORMAT_APPLICATION);
		}
	}
	public function addAllApplications(Array $applications){
		if (is_array($applications)){
			foreach ($applications as $app){
				if ($app instanceof Application){
					array_push($this->applications, $app);
				}
				else{
					$this->setErreurs("MotCle addAllApplications " . self::FORMAT_APPLICATION);
				}
			}
		}
		else{
			$this->setErreurs("MotCle addAllApplications " . self::FORMAT_ARRAY);
		}
	}
}
?>