<?php
// +----------------------------------------------------------------------+
// | PHP Version 7														  |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib 											  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Auteur. 										  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe Auteur
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

use Library\Entities\Publication;
use Library\Entities;
/**
 * Classe Auteur
 */
class Auteur extends \Library\Entity {
	protected $idAuteur, $nomAuteur, $prenomAuteur, $mailAuteur, $publications = Array();

	/* Déclaration des constantes d'erreurs */
	const MAIL_INVALIDE = 'The last email address entered is incorrect.';
	const MAIL_JETABLE = 'Free and disposable email addresses are not accepted.';
	const NOM_INVALIDE = 'The last name of the author must contain at least 2 letters in length.';
	const PRENOM_INVALIDE = 'The first name of the author must contain at least 2 letters in length.';

	/**
	 * ******setters******
	 */
	public function setIdAuteur($idAuteur) {
		if (ctype_digit($idAuteur) || is_int($idAuteur)) {
			$this->idAuteur = $idAuteur;
		} else {
			$this->setErreurs("Auteur setIdAuteur " . self::FORMAT_INT);
		}
	}
	public function setNomAuteur($nomAuteur) {
		if (is_string ( $nomAuteur )) {
			if (strlen($nomAuteur) > 1){
				$this->nomAuteur = $nomAuteur;
			}else{
				$this->setErreurs(self::NOM_INVALIDE);
			}
		} else {
			$this->setErreurs("Auteur setNomAuteur " . self::FORMAT_STRING);
		}
	}
	public function setPrenomAuteur($prenomAuteur) {
		if (is_string ( $prenomAuteur )) {
			if (strlen($prenomAuteur) > 1){
				$this->prenomAuteur = $prenomAuteur;
			}else{
				$this->setErreurs(self::PRENOM_INVALIDE);
			}
		} else {
			$this->setErreurs("Auteur setPrenomAuteur " . self::FORMAT_STRING);
		}
	}
	public function setMailAuteur($mailAuteur) {
		if (is_string($mailAuteur)) {
			if( !empty($mailAuteur) && preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $mailAuteur)){
				$this->mailAuteur = $mailAuteur;
			}
			else{
				$this->setErreurs(self::MAIL_INVALIDE);
			}
		}elseif($mailAuteur === false) {
			$this->setErreurs(self::MAIL_JETABLE);
		}else{
			$this->setErreurs(self::FORMAT_STRING);	
		}
	}
	public function setPublications($publications) {
		if (is_array ( $publications )) {
			$this->publications = $publications;
		} else {
			$this->setErreurs("Auteur setPublications " . self::FORMAT_ARRAY);
		}
	}
	/**
	 * *******getters*****
	 */
	public function getIdAuteur() {
		return $this->idAuteur;
	}
	public function getNomAuteur() {
		return $this->nomAuteur;
	}
	public function getPrenomAuteur() {
		return $this->prenomAuteur;
	}
	public function getMailAuteur() {
		return $this->mailAuteur;
	}
	public function getPublications() {
		return $this->publications;
	}
	
	public function getPublicationFromPublications($idPublication){
		$publicationReturn = null;
		if (ctype_digit($idPublication) || is_int($idPublication)) {
			foreach ($this->publications as $publication){
				if ($publication->getIdPublication() == $idPublication){
					$publicationReturn = $publication;
				}
			}
		}
		else {
			$this->setErreurs("Auteur getPublicationFromPublications " . self::FORMAT_INT);
		}
		return $publicationReturn;
	}
	
	/**
	 * 
	 * *******Adder des tableaux
	 * 
	 */
	
	public function addPublication($publication){

		if ($publication instanceof Publication){
			array_push($this->publications, $publication);
		}
		else{
			$this->setErreurs("Auteur addPublication " . self::FORMAT_PUBLICATION);
		}
	}
	public function addAllPublications(Array $publications){

	if (is_array($publications)){
			foreach ($publications as $publication){
				if ($publication instanceof Publication){
					array_push($this->publications, $publication);
				}
				else{
					$this->setErreurs("Auteur addAllPublications " . self::FORMAT_PUBLICATION);
				}
			}
		}
		else{
			$this->setErreurs("Auteur addAllPublications " . self::FORMAT_ARRAY);
		}
	}
}
