<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Type de Publications. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
* @name : Classe TypePublication
* @access : public
* @version : 1
*/
namespace Library\Entities;

/**
 * Classe Type TypePublication
 */
class TypePublication extends \Library\Entity {
	
	protected $idTypePublication, $nomTypePublication, $publications= array();

	/**
	 * ******setters******
	 */
	public function setIdTypePublication($idTypePublication) {
		if (ctype_digit($idTypePublication) || is_int($idTypePublication)) {
			$this->idTypePublication = $idTypePublication;
		} else {
			$this->setErreurs("TypePublication setIdTypePublication " . self::FORMAT_INT);
		}
	}
	public function setNomTypePublication($nomTypePublication) {
		if (is_string ( $nomTypePublication )) {
			$this->nomTypePublication = $nomTypePublication;
		} else {
			$this->setErreurs("TypePublication setNomTypePublication " . self::FORMAT_STRING);
		}
	}
	public function setPublications($publications) {
		if (is_array ( $publications )) {
			$this->publications = $publications;
		} else {
			$this->setErreurs("TypePublication setPublications " . self::FORMAT_ARRAY);
		}
	}
	/**
	 * *******getters*****
	 */
	public function getIdTypePublication() {
		return $this->idTypePublication;
	}
	public function getNomTypePublication() {
		return $this->nomTypePublication;
	}
	public function getPublications() {
		return $this->publications;
	}
	
	public function getPublicationFromPublications($idPublication){
		$publicationReturn = null;
		if (ctype_digit($idPublication) || is_int($idPublication)) {
			foreach ($this->publications as $publication){
				if ($publication->getIdPublication() == $idPublication){
					$publicationReturn = $publication;
				}
			}
		}
		else {
			$this->setErreurs("TypePublication getPublicationFromPublications " . self::FORMAT_INT);
		}
		return $publicationReturn;
	}
	
	/**
	 * 
	 * 
	 * adders des listes
	 * 
	 */
	
	public function addPublication($publication){
		if ($publication instanceof Publication){
			array_push($this->publications, $publication);
		}
		else{
			$this->setErreurs("TypePublication addPublication " . self::FORMAT_PUBLICATION);
		}
	}
	public function addAllPublications(Array $publications){
		if (is_array($publications)){
			foreach ($publications as $publication){
				if ($publication instanceof Publication){
					array_push($this->publications, $publication);
				}
				else{
					$this->setErreurs("TypePublication addAllPublications " . self::FORMAT_PUBLICATION);
				}
			}
		}
		else{
			$this->setErreurs("TypePublication addAllPublications " . self::FORMAT_ARRAY);
		}
	}
}
?>