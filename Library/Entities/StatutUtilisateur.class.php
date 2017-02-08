<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 														  |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib 											  |
// +----------------------------------------------------------------------+
// | Classe PHP pour le Statut des utilisateurs. 						  |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <ColletteMathieu@noolib.com> 			  |
// +----------------------------------------------------------------------+

/**
 *
* @name : Classe Statut des utilisateurs
* @access : public
* @version : 1
*/
namespace Library\Entities;

/**
 * Classe Type Statut des utilisateurs
 */
class StatutUtilisateur extends \Library\Entity {
	
	protected $idStatut, $nomStatut, $utilisateurs= array();
	
	/**
	 * ******setters******
	 */
	public function setIdStatut($idStatut) {
		if (ctype_digit($idStatut) || is_int($idStatut)) {
			$this->idStatut = $idStatut;
		} else {
			$this->setErreurs("StatutUtilisateur setIdStatut " . self::FORMAT_INT);
		}
	}
	public function setNomStatut($nomStatut) {
		if (is_string ( $nomStatut )) {
			$this->nomStatut = $nomStatut;
		} else {
			$this->setErreurs("StatutUtilisateur setNomStatut " . self::FORMAT_STRING);
		}
	}
	public function setUtilisateurs($utilisateurs) {
		if (is_array ( $utilisateurs )) {
			$this->utilisateurs = $utilisateurs;
		} else {
			$this->setErreurs("StatutUtilisateur setUtilisateurs " . SELF::FORMAT_ARRAY);
		}
	}
	/**
	 * *******getters*****
	 */
	public function getIdStatut() {
		return $this->idStatut;
	}
	public function getNomStatut() {
		return $this->nomStatut;
	}
	public function getUtilisateurs() {
		return $this->utilisateurs;
	}
	public function getUtilisateurFromUtilisateurs($idUtilisateur){
		$utilisateurReturn = null;
		if (ctype_digit($idUtilisateur) || is_int($idUtilisateur)) {
			foreach ($this->utilisateurs as $utilisateur){
				if ($utilisateur->getIdApplication() == $idUtilisateur){
					$utilisateurReturn = $utilisateur;
				}
			}
		}
		else {
			$this->setErreurs("StatutUtilisateur getApplicationFromApplications " . self::FORMAT_INT);
		}
		return $utilisateurReturn;
	}
	
	/**
	 * 
	 * 
	 * Adders des listes
	 * 
	 */
	
	public function addUtilisateur($utilisateur){
		if ($utilisateur instanceof Utilisateur){
			array_push($this->utilisateur, $utilisateur);
		}
		else{
			$this->setErreurs("StatutUtilisateur addUtilisateur " . self::FORMAT_UTILISATEUR);
		}
	}
	public function addAllUtilisateurs(Array $utilisateurs){
		if (is_array($utilisateurs)){
			foreach ($utilisateurs as $utilisateur){
				if ($utilisateur instanceof Application){
					array_push($this->utilisateurs, $utilisateur);
				}
				else{
					$this->setErreurs("StatutUtilisateur addAllUtilisateurs " . self::FORMAT_UTILISATEUR);
				}
			}
		}
		else{
			$this->setErreurs("StatutUtilisateur addAllUtilisateurs " . self::FORMAT_ARRAY);
		}
	}
}
?>