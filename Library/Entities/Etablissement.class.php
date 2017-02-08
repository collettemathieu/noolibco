<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Etablissements. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
* @name : Classe Etablissement
* @access : public
* @version : 1
*/
namespace Library\Entities;

/**
 * Classe Etablissement
 */
class Etablissement extends \Library\Entity {
	protected $idEtablissement, $nomEtablissement, $adresseEtablissement, $ville, $laboratoires = array();

	/**
	 * ******setters******
	 */
	public function setIdEtablissement($idEtablissement) {
		if (ctype_digit($idEtablissement) || is_int($idEtablissement)) {
			$this->idEtablissement = $idEtablissement;
		} else {
			$this->setErreurs("Etablissement setIdEtablissement " . self::FORMAT_INT);
		}
	}
	public function setNomEtablissement($nomEtablissement) {
		if (is_string ( $nomEtablissement )) {
			$this->nomEtablissement = $nomEtablissement;
		} else {
			$this->setErreurs("Etablissement setNomEtablissement " . self::FORMAT_STRING);
		}
	}
	public function setAdresseEtablissement($adresseEtablissement) {
		if (is_string ( $adresseEtablissement )) {
			$this->adresseEtablissement = $adresseEtablissement;
		} else {
			$this->setErreurs("Etablissement setAdresseEtablissement " . self::FORMAT_STRING);
		}
	}
	public function setVille($ville) {
		if ($ville instanceof Ville) {
			$this->ville = $ville;
		} else {
			$this->setErreurs("Etablissement setVille " . self::FORMAT_VILLE);
		}
	}
	public function setLaboratoires($laboratoires){
		if (is_array($laboratoires)) {
			$this->laboratoires = $laboratoires;
		} else {
			$this->setErreurs("Etablissement setLaboratoires " . self::FORMAT_ARRAY);
		}
	}
	/**
	 * *******getters*****
	 */
	public function getIdEtablissement() {
		return $this->idEtablissement;
	}
	public function getNomEtablissement() {
		return $this->nomEtablissement;
	}
	public function getAdresseEtablissement() {
		return $this->adresseEtablissement;
	}
	public function getVille() {
		return $this->ville;
	}
	public function getLaboratoires(){
		return $this->laboratoires;
	}
	public function getLaboratoireFromLaboratoires(int $idLaboratoire){
		$laboratoireReturn = null;
		if (ctype_digit($idLaboratoire) || is_int($idLaboratoire)) {
			foreach ($this->laboratoires as $laboratoire){
				if ($laboratoire->getIdLaboratoire() == $idLaboratoire){
					$laboratoireReturn = $laboratoire;
				}
			}
		}
		else {
			$this->setErreurs("Etablissement getLaboratoireFromLaboratoires " . self::FORMAT_INT);
		}
		return $laboratoireReturn;
	
	}
	/**
	 *
	 * adders
	 *
	 */
	public function addLaboratoire($laboratoire){
		if ($laboratoire instanceof Laboratoire){
			array_push($this->laboratoires, $laboratoire);
		}
		else{
			$this->setErreurs("Etablissement addLaboratoire " . self::FORMAT_LABORATOIRE);
		}
	}
	public function addAllLaboratoires(Array $laboratoires){
		if (is_array($laboratoires)){
			foreach ($laboratoires as $laboratoire){
				if ($laboratoire instanceof Laboratoire){
					array_push($this->laboratoires, $laboratoire);
				}
				else{
					$this->setErreurs("Etablissement addAllLaboratoires " . self::FORMAT_LABORATOIRE);
				}
			}
		}
		else{
			$this->setErreurs("Etablissement addAllLaboratoires " . self::FORMAT_ARRAY);
		}
	}
}
?>