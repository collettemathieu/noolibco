<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les PublicationAuteur. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe PublicationAuteur
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe PublicationAuteur
 */
class PublicationAuteur extends \Library\Entity {
	
	/* Définition des attributs */
	protected $publication, $auteur;

	/**
	 * ******Setter *****
	 */
	public function setPublication($publication) {
		if ($publication instanceof Publication) {
			$this->publication = $publication;
		} else {
			$this->setErreurs ("PublicationAuteur setPublication " . self::FORMAT_PUBLICATION );
		}
	}
	public function setAuteur($auteur) {
		if ($auteur instanceof Auteur) {
			$this->auteur = $auteur;
		} else {
			$this->setErreurs ("PublicationAuteur setAuteur " . self::FORMAT_AUTEUR );
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getPublication() {
		return $this->publication;
	}
	public function getAuteur() {
		return $this->auteur;
	}
}
?>