<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Logs. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe Log
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe Log
 */
class Log extends \Library\Entity {
	protected $idLog, $texteLog, $dateLog, $typeLog, $utilisateur;
	
	/**
	 * ******setters******
	 */
	public function setIdLog($idLog) {
		if (ctype_digit($idLog) || is_int($idLog)) {
			$this->idLog = $idLog;
		} else {
			$this->setErreurs("Log setIdLog " . self::FORMAT_INT);
		}
	}
	public function setTexteLog($texteLog) {
		if (is_string ( $texteLog )) {
			$this->texteLog = $texteLog;
		} else {
			$this->setErreurs("Log setTexteLog " . self::FORMAT_STRING);
		}
	}
	public function setDateLog($dateLog) {
		$this->dateLog = $dateLog;
	}
	public function setTypeLog($typeLog) {
		if ($typeLog instanceof TypeLog) {
			$this->typeLog = $typeLog;
		} else {
			$this->setErreurs("Log setTypeLog " . self::FORMAT_TYPE_LOG);
		}
	}
	public function setUtilisateur($utilisateur) {
		if ($utilisateur instanceof Utilisateur) {
			$this->utilisateur = $utilisateur;
		} else {
			$this->setErreurs("Log setUtilisateur " . self::FORMAT_UTILISATEUR);
		}
	}
	/**
	 * *******getters*****
	 */
	public function getIdLog() {
		return $this->idLog;
	}
	public function getTexteLog() {
		return $this->texteLog;
	}
	public function getDateLog() {
		return $this->dateLog;
	}
	public function getTypeLog() {
		return $this->typeLog;
	}
	public function getUtilisateur() {
		return $this->utilisateur;
	}
	
}
