<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Laboratoires. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
* @name : Classe Laboratoire
* @access : public
* @version : 1
*/
namespace Library\Entities;

/**
 * Classe Laboratoire
 */
class Laboratoire extends \Library\Entity {
	protected $idLaboratoire, $nomLaboratoire, $urlLaboratoire, $etablissement, $equipes = array();

	/**
	 * ******setters******
	 */
	public function setIdLaboratoire($idLaboratoire) {
		if (ctype_digit($idLaboratoire) || is_int($idLaboratoire)) {
			$this->idLaboratoire = $idLaboratoire;
		} else {
			$this->setErreurs("Laboratoire setIdLaboratoire " . self::FORMAT_INT);
		}
	}
	public function setNomLaboratoire($nomLaboratoire) {
		if (is_string ( $nomLaboratoire )) {
			$this->nomLaboratoire = $nomLaboratoire;
		} else {
			$this->setErreurs("Laboratoire setNomLaboratoire " . self::FORMAT_STRING);
		}
	}
	public function setUrlLaboratoire($urlLaboratoire) {
		if (is_string ( $urlLaboratoire )) {
			$this->urlLaboratoire = $urlLaboratoire;
		} else {
			$this->setErreurs("Laboratoire setUrlLaboratoire " . self::FORMAT_STRING);
		}
	}
	public function setEtablissement($etablissement) {
		if ($etablissement instanceof Etablissement) {
			$this->etablissement = $etablissement;
		} else {
			$this->setErreurs("Laboratoire setEtablissement " . self::FORMAT_ETABLISSEMENT);
		}
	}
	public function setEquipes($equipes){
		if (is_array($equipes)) {
			$this->equipes = $equipes;
		} else {
			$this->setErreurs("Laboratoire setEquipes " . self::FORMAT_ARRAY);
		}
	
	}
	/**
	 * *******getters*****
	 */
	public function getIdLaboratoire() {
		return $this->idLaboratoire;
	}
	public function getNomLaboratoire() {
		return $this->nomLaboratoire;
	}
	public function getUrlLaboratoire() {
		return $this->urlLaboratoire;
	}
	public function getEtablissement() {
		return $this->etablissement;
	}
	public function getEquipes(){
		return $this->equipes;
	}
	public function getEquipeFromEquipes(int $idEquipe){
		$equipeReturn = null;
		if (ctype_digit($idEquipe) || is_int($idEquipe)) {
			foreach ($this->equipes as $equipe){
				if ($equipe->getIdEquipe() == $idEquipe){
					$equipeReturn = $equipe;
				}
			}
		}
		else {
			$this->setErreurs("Laboratoire getEquipeFromEquipes " . self::FORMAT_INT);
		}
		return $equipeReturn;
	
	}
	/**
	 *
	 * adders
	 *
	 */
	public function addEquipe($equipe){
		if ($equipe instanceof Equipe){
			array_push($this->equipes, $equipe);
		}
		else{
			$this->setErreurs("Laboratoire addEquipe " . self::FORMAT_EQUIPE);
		}
	}
	public function addAllEquipes(Array $equipes){
		if (is_array($equipes)){
			foreach ($equipes as $equipe){
				if ($equipe instanceof Equipe){
					array_push($this->equipes, $equipe);
				}
				else{
					$this->setErreurs("Laboratoire addAllEquipes " . self::FORMAT_EQUIPE);
				}
			}
		}
		else{
			$this->setErreurs("Laboratoire addAllEquipes " . self::FORMAT_ARRAY);
		}
	}
}
?>