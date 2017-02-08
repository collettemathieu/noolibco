<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les ApplicationPublication. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe ApplicationPublication
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe ApplicationPublication
 */
class ApplicationPublication extends \Library\Entity {
	
	/* Définition des attributs */
	protected $application, $publication;
		
	/**
	 * ******Setter *****
	 */
	public function setApplication($application) {
		if ($application instanceof Application) {
			$this->application = $application;
		} 
		else {
			$this->setErreurs("ApplicationPublication setApplication " . self::FORMAT_APPLICATION);
		}
	}
	public function setPublication($publication) {
		if ($publication instanceof Publication) {
			$this->publication = $publication;
		}
		else {
			$this->setErreurs("ApplicationPublication setPublication " . self::FORMAT_PUBLICATION);
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getApplication() {
		return $this->application;
	}
	public function getPublication() {
		return $this->publication;
	}
}
?>