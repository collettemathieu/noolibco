<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les ApplicationMotCles. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe ApplicationMotCle
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe ApplicationMotCle
 */
class ApplicationMotCle extends \Library\Entity {
	
	/* Définition des attributs */
	protected $application, $motCle;


	
	/**
	 * ******Setter *****
	 */
	public function setApplication($application) {
		if ($application instanceof Application) {
			$this->application = $application;
		} 
		else {
			$this->setErreurs("ApplicationMotCle setApplication " . self::FORMAT_APPLICATION);
		}
	}
	public function setMotCle($motCle) {
		if ($motCle instanceof MotCle) {
			$this->motCle = $motCle;
		}
		else {
			$this->setErreurs("ApplicationMotCle setMotCle " . self::FORMAT_MOT_CLE);
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getApplication() {
		return $this->application;
	}
	public function getMotCle() {
		return $this->motCle;
	}
}
?>