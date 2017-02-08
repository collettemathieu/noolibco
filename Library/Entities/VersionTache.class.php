<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les VersionTache. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe VersionTache
 * @access : public
 * @version : 1
 */
namespace Library\Entities;


/**
 * Classe VersionTache
 */
class VersionTache extends \Library\Entity {
	
	/* Définition des attributs */
	protected $version, $tache;
	
	/**
	 * ******Setter *****
	 */
	public function setVersion($version) {
		if ($version instanceof Version) {
			$this->version = $version;
		} else {
			$this->setErreurs ("VersionTache setVersion " . self::FORMAT_VERSION );
		}
	}
	public function setTache($tache) {
		if ($tache instanceof Tache) {
			$this->tache = $tache;
		} else {
			$this->setErreurs ("VersionTache setTache " .  self::FORMAT_TACHE );
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getTache() {
		return $this->tache;
	}
	public function getVersion() {
		return $this->version;
	}
}
?>