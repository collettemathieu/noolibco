<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib |
// +----------------------------------------------------------------------+
// | Classe PHP pour les DonneeUtilisateur. |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe DonneeUtilisateur
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe DonneeUtilisateur
 */
class DonneeUtilisateur extends \Library\Entity {
	protected $idDonneeUtilisateur, $urlDonneeUtilisateur, $nomDonneeUtilisateur, $sampleRateDonneeUtilisateur, $tailleDonneeUtilisateur, $tempsMinimumDonneeUtilisateur, $datePublicationDonneeUtilisateur, $typeDonneeUtilisateur, $isInCache, $utilisateurs = array();

	const NAME_DONNEE_UTILISATEUR = 'The name of your data must contain at least 1 letter and be less than 35 letters in length.';
	const SAMPLE_RATE = 'The sample rate of the data cannot be negative.';
	const WEIGHT_DATA = 'The weight of the data must be positive.';
	const TIME_MINIMUM_DATA = 'The minimum of the data must be positive or null.';

	/**
	 * ******setters******
	 */
	public function setIdDonneeUtilisateur($idDonneeUtilisateur) {
		if (ctype_digit($idDonneeUtilisateur) || is_int($idDonneeUtilisateur)) {
			$this->idDonneeUtilisateur = $idDonneeUtilisateur;
		} else {
			$this->setErreurs("DonneeUtilisateur setIdDonneeUtilisateur " . self::FORMAT_INT);
		}
	}
	public function setUrlDonneeUtilisateur($urlDonneeUtilisateur) {
		if (is_string ( $urlDonneeUtilisateur)) {
			$this->urlDonneeUtilisateur = $urlDonneeUtilisateur;
		} else {
			$this->setErreurs("DonneeUtilisateur setUrlDonneeUtilisateur " . self::FORMAT_STRING);
		}
	}
	public function setUrlMiniatureDonneeUtilisateur($urlMiniatureDonneeUtilisateur) {
		if (is_string ( $urlMiniatureDonneeUtilisateur)) {
			$this->urlMiniatureDonneeUtilisateur = $urlMiniatureDonneeUtilisateur;
		} else {
			$this->setErreurs("DonneeUtilisateur setUrlMiniatureDonneeUtilisateur " . self::FORMAT_STRING);
		}
	}
	public function setNomDonneeUtilisateur($nomDonneeUtilisateur) {
		if (is_string ( $nomDonneeUtilisateur)) {
			if (strlen ( $nomDonneeUtilisateur ) > 1 && strlen ( $nomDonneeUtilisateur ) < 35){
				$this->nomDonneeUtilisateur = $nomDonneeUtilisateur;
			}
			else {
				$this->setErreurs (self::NAME_DONNEE_UTILISATEUR);
			}
			
		} else {
			$this->setErreurs("DonneeUtilisateur setNomDonneeUtilisateur " . self::FORMAT_STRING);
		}
	}
	public function setSampleRateDonneeUtilisateur($sampleRateDonneeUtilisateur) {
		if (is_int ( $sampleRateDonneeUtilisateur)) {
			if ($sampleRateDonneeUtilisateur >= 0){
				$this->sampleRateDonneeUtilisateur = $sampleRateDonneeUtilisateur;
			}
			else {
				$this->setErreurs (self::SAMPLE_RATE);
			}
			
		} else {
			$this->setErreurs("DonneeUtilisateur setSampleRateDonneeUtilisateur :: " . self::FORMAT_INT);
		}
	}
	public function setTailleDonneeUtilisateur($tailleDonneeUtilisateur) {
		if (is_int ( $tailleDonneeUtilisateur)) {
			if ($tailleDonneeUtilisateur >= 0){
				$this->tailleDonneeUtilisateur = $tailleDonneeUtilisateur;
			}
			else {
				$this->setErreurs (self::WEIGHT_DATA);
			}
			
		} else {
			$this->setErreurs("DonneeUtilisateur setTailleDonneeUtilisateur :: " . self::FORMAT_INT);
		}
	}
	public function setTempsMinimumDonneeUtilisateur($tempsMinimumDonneeUtilisateur) {
		if (is_int ( $tempsMinimumDonneeUtilisateur)) {
			if ($tempsMinimumDonneeUtilisateur >= 0){
				$this->tempsMinimumDonneeUtilisateur = $tempsMinimumDonneeUtilisateur;
			}
			else {
				$this->setErreurs (self::TIME_MINIMUM_DATA);
			}
			
		} else {
			$this->setErreurs("DonneeUtilisateur setMinimumDonneeUtilisateur :: " . self::FORMAT_INT);
		}
	}
	public function setDatePublicationDonneeUtilisateur($datePublicationDonneeUtilisateur) {
		if (is_string ( $datePublicationDonneeUtilisateur)) {
			$this->datePublicationDonneeUtilisateur = $datePublicationDonneeUtilisateur;
		} else {
			$this->setErreurs("DonneeUtilisateur setDatePublicationDonneeUtilisateur " . self::FORMAT_STRING);
		}
	}
	public function setTypeDonneeUtilisateur($typeDonneeUtilisateur) {
		if ($typeDonneeUtilisateur instanceof TypeDonneeUtilisateur) {
			$this->typeDonneeUtilisateur = $typeDonneeUtilisateur;
		} else {
			$this->setErreurs("DonneeUtilisateur setTypeDonneeUtilisateur " . self::FORMAT_TYPE_DONNEE_UTILISATEUR);
		}
	}
	public function setIsInCache($bool) {
		if (is_bool ( $bool)) {
			$this->isInCache = $bool;
		} else {
			$this->setErreurs("DonneeUtilisateur setIsInCache " . self::FORMAT_BOOLEAN);
		}
	}

	public function setUtilisateurs($utilisateurs){
		if (is_array($utilisateurs)) {
			$this->utilisateurs = $utilisateurs;
		} else {
			$this->setErreurs("DonneeUtilisateur setUtilisateurs " . self::FORMAT_ARRAY);
		}
	}
	

	/**
	 * *******getters*****
	 */
	public function getIdDonneeUtilisateur() {
		return $this->idDonneeUtilisateur;
	}
	public function getUrlDonneeUtilisateur() {
		return $this->urlDonneeUtilisateur;
	}
	public function getUrlMiniatureDonneeUtilisateur() {
		return $this->urlMiniatureDonneeUtilisateur;
	}
	public function getNomDonneeUtilisateur() {
		return $this->nomDonneeUtilisateur;
	}
	public function getSampleRateDonneeUtilisateur() {
		return $this->sampleRateDonneeUtilisateur;
	}
	public function getTailleDonneeUtilisateur() {
		return $this->tailleDonneeUtilisateur;
	}
	public function getTempsMinimumDonneeUtilisateur() {
		return $this->tempsMinimumDonneeUtilisateur;
	}
	public function getDatePublicationDonneeUtilisateur() {
		return $this->datePublicationDonneeUtilisateur;
	}
	public function getTypeDonneeUtilisateur() {
		return $this->typeDonneeUtilisateur;
	}
	public function getIsInCache() {
		return $this->isInCache;
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
			$this->setErreurs("DonneeUtilisateur getUtilisateurFromUtilisateurs " . self::FORMAT_INT);
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
			$this->setErreurs("DonneeUtilisateur addUtilisateur " . self::FORMAT_UTILISATEUR);
		}
	}
	public function addAllUtilisateurs(Array $utilisateurs){
		if (is_array($utilisateurs)){
			foreach ($utilisateurs as $utilisateur){
				if ($utilisateur instanceof Utilisateur){
					array_push($this->utilisateurs, $utilisateur);
				}
				else{
					$this->setErreurs("DonneeUtilisateur addAllUtilisateurs " . self::FORMAT_UTILISATEUR);
				}
			}
		}
		else{
			$this->setErreurs("DonneeUtilisateur addAllUtilisateurs " . self::FORMAT_ARRAY);
		}
	}

}
