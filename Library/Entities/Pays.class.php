<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Pays. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
* @name : Classe Pays
* @access : public
* @version : 1
*/
namespace Library\Entities;

/**
 * Classe Pays
 */
class Pays extends \Library\Entity {
	protected $idPays, $nomPays, $villes = array();

	/**
	 * ******setters******
	 */
	public function setIdPays($idPays) {
		if (is_int ( $idPays )) {
			$this->idPays = $idPays;
		} else {
			$this->setErreurs("Pays setIdPays " . self::FORMAT_INT);
		}
	}
	public function setNomPays($nomPays) {
		if (is_string ( $nomPays )) {
			$this->nomPays = $nomPays;
		} else {
			$this->setErreurs("Pays setNomPays " . self::FORMAT_STRING);
		}
	}
	public function setVilles($villes){
		if (is_array($villes)) {
			$this->nomPays = $nomPays;
		} else {
			$this->setErreurs("Pays setVilles " . self::FORMAT_ARRAy);
		}
	}
	/**
	 * *******getters*****
	 */
	public function getIdPays() {
		return $this->idPays;
	}
	public function getNomPays() {
		return $this->nomPays;
	}
	public function getVilles(){
		return $this->villes;
	}
	public function getVilleFromVilles(int $idVille){
		$villeReturn = null;
		if (ctype_digit($idVille) || is_int($idVille)) {
			foreach ($this->villes as $ville){
				if ($ville->getIdVille() == $idVille){
					$villeReturn = $ville;
				}
			}
		}
		else {
			$this->setErreurs("Pays getVilleFromVilles " . self::FORMAT_INT);
		}
		return $villeReturn;
	
	}
	/**
	 *
	 * adders
	 *
	 */
	public function addVille($ville){
		if ($ville instanceof Ville){
			array_push($this->villes, $ville);
		}
		else{
			$this->setErreurs("Pays addVille " . self::FORMAT_VILLE);
		}
	}
	public function addAllVilles(Array $villes){
		if (is_array($villes)){
			foreach ($villes as $ville){
				if ($ville instanceof Ville){
					array_push($this->villes, $ville);
				}
				else{
					$this->setErreurs("Pays addAllVilles " . self::FORMAT_VILLE);
				}
			}
		}
		else{
			$this->setErreurs("Pays addAllVilles " . self::FORMAT_ARRAY);
		}
	}
}
?>