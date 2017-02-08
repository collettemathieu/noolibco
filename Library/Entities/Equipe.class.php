<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Equipes. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
* @name : Classe Equipe
* @access : public
* @version : 1
*/
namespace Library\Entities;

/**
 * Classe Equipe
 */
class Equipe extends \Library\Entity {
	protected $idEquipe, $nomEquipe, $laboratoire, $utilisateurs = array();

	/**
	 * ******setters******
	 */
	public function setIdEquipe( $idEquipe) {
		if (ctype_digit($idEquipe) || is_int($idEquipe)) {
			$this->idEquipe = $idEquipe;
		} else {
			$this->setErreurs("Equipe setIdEquipe " . self::FORMAT_INT);
		}
	}
	public function setNomEquipe( $nomEquipe) {
		if (is_string ( $nomEquipe)) {
			$this->nomEquipe = $nomEquipe;
		} else {
			$this->setErreurs("Equipe setNomEquipe " . self::FORMAT_STRING);
		}
	}
	public function setLaboratoire($laboratoire) {
		if ($laboratoire instanceof Laboratoire) {
			$this->laboratoire = $laboratoire;
		} else {
			$this->setErreurs("Equipe setLaboratoire " . self::FORMAT_LABORATOIRE);
		}
	}
	public function setUtilisateurs($utilisateurs){
		if (is_array($utilisateurs)) {
			$this->utilisateurs = $utilisateurs;
		} else {
			$this->setErreurs("Equipe setUtilisateurs " . self::FORMAT_ARRAY);
		}
	}
	/**
	 * *******getters*****
	 */
	public function getIdEquipe() {
		return $this->idEquipe;
	}
	public function getNomEquipe() {
		return $this->nomEquipe;
	}
	public function getLaboratoire() {
		return $this->laboratoire;
	}
	public function getUtilisateurs(){
		return $this->utilisateurs;
	}
	public function getUtilisateurFromUtilisateurs(int $idUtilisateur){
		$utilisateurReturn = null;
		if (ctype_digit($idUtilisateur) || is_int($idUtilisateur)) {
			foreach ($this->utilisateurs as $utilisateur){
				if ($utilisateur->getIdUtilisateur() == $idUtilisateur){
					$utilisateurReturn = $utilisateur;
				}
			}
		}
		else {
			$this->setErreurs("Equipe getUtilisateurFromUtilisateurs " . self::FORMAT_INT);
		}
		return $utilisateurReturn;
	
	}
	/**
	 *
	 * adders
	 *
	 */
	public function addUtilisateur($utilisateur){
		if ($utilisateur instanceof Utilisateur){
			array_push($this->utilisateurs, $utilisateur);
		}
		else{
			$this->setErreurs("Equipe addUtilisateur " . self::FORMAT_UTILISATEUR);
		}
	}
	public function addAllUtilisateurs(Array $utilisateurs){
		if (is_array($utilisateurs)){
			foreach ($utilisateurs as $utilisateur){
				if ($utilisateur instanceof Utilisateur){
					array_push($this->utilisateurs, $utilisateur);
				}
				else{
					$this->setErreurs("Equipe addAllUtilisateurs " . self::FORMAT_UTILISATEUR);
				}
			}
		}
		else{
			$this->setErreurs("Equipe addAllUtilisateurs " . self::FORMAT_ARRAY);
		}
	}
}
?>