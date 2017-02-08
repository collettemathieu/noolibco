<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les type param�tres. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
* @name : Classe TypeLog
* @access : public
* @version : 1
*/
namespace Library\Entities;

/**
 * Classe TypeLog
 */
class TypeLog extends \Library\Entity {
	protected $idTypeLog, $nomTypeLog, $logs = array();

	/**
	 * ******setters******
	 */
	public function setIdTypeLog($idTypeLog) {
		if (ctype_digit($idTypeLog) || is_int($idTypeLog)) {
			$this->idTypeLog = $idTypeLog;
		} else {
			$this->setErreurs("TypeLog setIdTypeLog " . self::FORMAT_INT);
		}
	}
	public function setNomTypeLog($nomTypeLog) {
		if (is_string ( $nomTypeLog )) {
			$this->nomTypeLog = $nomTypeLog;
		} else {
			$this->setErreurs("TypeLog setNomTypeLog " . self::FORMAT_STRING);
		}
	}
	public function setLogs($logs){
		if(is_array($logs)){
			$this->logs = $logs;
		}
		else{
			$this->setErreurs("TypeLog setLogs " . self::FORMAT_ARRAY);
		}
		
	}
	/**
	 * *******getters*****
	 */
	public function getIdTypeLog() {
		return $this->idTypeLog;
	}
	public function getNomTypeLog() {
		return $this->nomTypeLog;
	}
	public function getLogs() {
		return $this->logs;
	}
	
	public function getLogFromLogs($idLog){
		$logReturn = null;
		if (ctype_digit($idLog) || is_int($idLog)) {
			foreach ($this->logs as $log){
				if ($log->getIdLog() == $idLog){
					$logReturn = $log;
				}
			}
		}
		else {
			$this->setErreurs("TypeLog getLogFromLogs " . self::FORMAT_INT);
		}
		return $logReturn;
	}
	
	/**
	 *
	 *
	 * adders des listes
	 *
	 */
	
	public function addLog($log){
		if ($log instanceof Log){
			array_push($this->logs, $log);
		}
		else{
			$this->setErreurs("TypeLog addLog " . self::FORMAT_LOG);
		}
	}
	public function addAllLogs(Array $logs){
		if (is_array($logs)){
			foreach ($logs as $log){
				if ($log instanceof Log){
					array_push($this->logs, $log);
				}
				else{
					$this->setErreurs("TypeLog addAllLogs " . self::FORMAT_LOG);
				}
			}
		}
		else{
			$this->setErreurs("TypeLog addAllLogs " . self::FORMAT_ARRAY);
		}
	}
}
?>