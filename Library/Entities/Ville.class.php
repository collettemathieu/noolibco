<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Villes. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
* @name : Classe Ville
* @access : public
* @version : 1
*/
namespace Library\Entities;

use Library\Entities\Pays;
/**
 * Classe Ville
 */
class Ville extends \Library\Entity {
	protected $idVille, $nomVille, $pays, $etablissements = array();

	/**
	 * ******setters******
	 */
	public function setIdVille($idVille) {
		if (ctype_digit($idVille) || is_int($idVille)) {
			$this->idVille = $idVille;
		} else {
			$this->setErreurs("Ville setIdVille " . self::FORMAT_INT);
		}
	}
	public function setNomVille($nomVille) {
		if (is_string ( $nomVille )) {
			$this->nomVille = $nomVille;
		} else {
			$this->setErreurs("Ville setNomVille " . self::FORMAT_STRING);
		}
	}
	public function setPays($pays) {
		if ($pays instanceof Pays ) {
			$this->pays = $pays;
		} else {
			$this->setErreurs("Ville setPays " . self::FORMAT_PAYS);
		}
	}
	public function setEtablissements($etablissements){
		if (is_array($etablissements) ) {
			$this->etablissements = $etablissements;
		} else {
			$this->setErreurs("Ville setEtablissements " . self::FORMAT_ARRAY);
		}
	}
	/**
	 * *******getters*****
	 */
	public function getIdVille() {
		return $this->idVille;
	}
	public function getNomVille() {
		return $this->nomVille;
	}
	public function getPays() {
		return $this->pays;
	}
	public function getEtablissements() {
		return $this->etablissements;
	}
	
	public function getEtablissementFromEtablissements($idEtablissement){
		$etablissementReturn = null;
		if (ctype_digit($idEtablissement) || is_int($idEtablissement)) {
			foreach ($this->etablissements as $etablissement){
				if ($etablissement->getIdEtablissement() == $idEtablissement){
					$etablissementReturn = $etablissement;
				}
			}
		}
		else {
			$this->setErreurs("Ville getEtablissementFromEtablissements" . self::FORMAT_INT);
		}
		return $etablissementReturn;
	}
	
	/**
	 *
	 *
	 * adders des listes
	 *
	 */
	
	public function addEtablissement($etablissement){
		if ($etablissement instanceof Etablissement){
			array_push($this->etablissements, $etablissement);
		}
		else{
			$this->setErreurs("Ville addEtablissement " . self::FORMAT_ETABLISSEMENT);
		}
	}
	public function addAllEtablissements(Array $etablissements){
		if (is_array($etablissements)){
			foreach ($etablissements as $etablissement){
				if ($etablissement instanceof Etablissement){
					array_push($this->etablissements, $etablissement);
				}
				else{
					$this->setErreurs("Ville addAllEtablissements" . self::FORMAT_ETABLISSEMENT);
				}
			}
		}
		else{
			$this->setErreurs("Ville addAllEtablissements" . self::FORMAT_ARRAY);
		}
	}
}
?>