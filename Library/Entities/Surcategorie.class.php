<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Surcategories. |
// +----------------------------------------------------------------------+
// | Surcategorie : Guénaël Dequeker <DequekerGuenael@noolib.com>	      |
// +----------------------------------------------------------------------+

/**
 *
* @name : Classe Surcategorie
* @access : public
* @version : 1
*/
namespace Library\Entities;

use Library\Entities\Categorie;
/**
 * Classe Surcategorie
 */
class Surcategorie extends \Library\Entity {
	protected $idSurcategorie, $nomSurcategorie, $categories = array();

	/**
	 * ******setters******
	 */
	public function setIdSurcategorie($idSurcategorie) {
		if (ctype_digit($idSurcategorie) || is_int($idSurcategorie)) {
			$this->idSurcategorie = $idSurcategorie;
		} else {
			$this->setErreurs("Surcategorie setIdSurcategorie " . self::FORMAT_INT);
		}
	}
	public function setNomSurcategorie($nomSurcategorie) {
		if (is_string ( $nomSurcategorie )) {
			$this->nomSurcategorie = $nomSurcategorie;
		} else {
			$this->setErreurs("Surcategorie setNomSurcategorie " . self::FORMAT_STRING);
		}
	}
	public function setCategories($categories){
		if (is_array($categories) ) {
			$this->categories = $categories;
		} else {
			$this->setErreurs("Surcategorie setCategories " . self::FORMAT_ARRAY);
		}
	}
	/**
	 * *******getters*****
	 */
	public function getIdSurcategorie() {
		return $this->idSurcategorie;
	}
	public function getNomSurcategorie() {
		return $this->nomSurcategorie;
	}
	public function getCategories() {
		return $this->categories;
	}
	
	/**
	 *
	 *
	 * adders des listes
	 *
	 */
	
	public function addCategorie($categorie){
		if ($categorie instanceof Categorie){
			array_push($this->categories, $categorie);
		}
		else{
			$this->setErreurs("Surcategorie addCategorie " . self::FORMAT_ETABLISSEMENT);
		}
	}
	public function addAllCategories(Array $categories){
		if (is_array($categories)){
			foreach ($categories as $categorie){
				if ($categorie instanceof Categorie){
					array_push($this->categories, $categorie);
				}
				else{
					$this->setErreurs("Surcategorie addAllCategories" . self::FORMAT_ETABLISSEMENT);
				}
			}
		}
		else{
			$this->setErreurs("Surcategorie addAllCategories" . self::FORMAT_ARRAY);
		}
	}
}
?>