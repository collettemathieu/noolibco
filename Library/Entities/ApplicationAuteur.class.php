<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 														  |
// +----------------------------------------------------------------------+
// | Copyright (c) 2015 NooLib 											  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les entités ApplicationAuteur. 					  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com> 			  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe ApplicationAuteur
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe ApplicationAuteur
 */
class ApplicationAuteur extends \Library\Entity {
	
	/* Définition des attributs */
	protected $application, $auteur;

	/**
	 * ******Setter *****
	 */
	public function setApplication($application) {
		if ($application instanceof Application) {
			$this->application = $application;
		} else {
			$this->setErreurs ("ApplicationAuteur setApplication " . self::FORMAT_APPLICATION );
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
	public function getApplication() {
		return $this->application;
	}
	public function getAuteur() {
		return $this->auteur;
	}
}
?>