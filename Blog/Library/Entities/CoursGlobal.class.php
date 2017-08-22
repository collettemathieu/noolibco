<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les CoursGlobal.	 								  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe CoursGlobal
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe CoursGlobal
 */
class CoursGlobal extends \Library\Entity {
	
	/* Définition des attributs */
	protected 	$idCoursGlobal, 
				$titreCoursGlobal, 
				$descriptionCoursGlobal,
				$dateCreationCoursGlobal,
				$enLigneCoursGlobal,
				$noteCoursGlobal,
				$nbreVoteCoursGlobal,
				$nbreVueCoursGlobal,
				$urlImageCoursGlobal,
				$urlImageMiniatureCoursGlobal,
				$categorie,
				$auteur,
				$cours = array();
	
	/* Déclaration des constantes d'erreurs particulières à */
	const TITRE_COURS_GLOBAL = 'Le nom du cours doit être compris entre 5 et 50 caractères.';
	const DESCRIPTION_COURS_GLOBAL = 'La description du cours doit contenir au moins 10 caractères.';
	const FORMAT_MOTSCLES_EMPTY = 'Vous devez entrer au moins un mot-clé.';
	const URL_IMAGE_COURS_GLOBAL = 'L\'url de l\'image du cours doit comporter au moins 13 caractères';
	
	
	/**
	 * ******Setter *****
	 */
	public function setIdCoursGlobal($idCoursGlobal) {
		// verification que l'id est au format integer
		if (ctype_digit($idCoursGlobal) || is_int($idCoursGlobal)) {
			$this->idCoursGlobal = $idCoursGlobal;
		} 
		//sinon envoi d'une erreur sur le format integer. 
		else {
			$this->setErreurs("CoursGlobal setIdCoursGlobal " . self::FORMAT_INT);
		}
	}
	public function setTitreCoursGlobal($titreCoursGlobal) {

		if (is_string ( $titreCoursGlobal )) {
			//verification que le titre contient entre 5 et 50 caractères.
			
			if (mb_strlen($titreCoursGlobal, 'UTF8') > 4 && mb_strlen($titreCoursGlobal, 'UTF8') < 51){
				$this->titreCoursGlobal = $titreCoursGlobal;
			}
			else {
				$this->setErreurs ("CoursGlobal setTitreCoursGlobal " .self::TITRE_COURS_GLOBAL );
			}
		} else {
			$this->setErreurs ("CoursGlobal setTitreCoursGlobal " . self::FORMAT_STRING );
		}
	}
	public function setDescriptionCoursGlobal($descriptionCoursGlobal) {

		if (is_string ( $descriptionCoursGlobal )) {
			//verification que la description contient au moins 10 caractères.
			
			if (mb_strlen($descriptionCoursGlobal, 'UTF8') > 10){
				$this->descriptionCoursGlobal = $descriptionCoursGlobal;
			}
			else {
				$this->setErreurs ("CoursGlobal setDescriptionCoursGlobal " .self::DESCRIPTION_COURS_GLOBAL );
			}
		} else {
			$this->setErreurs ("CoursGlobal setDescriptionCoursGlobal " . self::FORMAT_STRING );
		}
	}
	public function setEnLigneCoursGlobal($bool) {

		if (is_bool ( $bool )) {
			$this->enLigneCoursGlobal = $bool;
		} else {
			$this->setErreurs ("CoursGlobal setEnLigneCoursGlobal " . self::FORMAT_BOOLEAN );
		}
	}
	public function setDateCreationCoursGlobal($dateCoursGlobal) {

		if (is_string ( $dateCoursGlobal )) {
			$this->dateCreationCoursGlobal = $dateCoursGlobal;
		} else {
			$this->setErreurs ("CoursGlobal setDateCreationCoursGlobal " . self::FORMAT_STRING );
		}
	}
	public function setNoteCoursGlobal($noteCoursGlobal){
		if (is_float($noteCoursGlobal)) {
			$this->noteCoursGlobal = $noteCoursGlobal;
		}
		else {
			$this->setErreurs("CoursGlobal setNoteCoursGlobal " . self::FORMAT_FLOAT);
		}
	}
	public function setNbreVoteCoursGlobal($nbreVote){
		if (is_int($nbreVote)) {
			$this->nbreVoteCoursGlobal = $nbreVote;
		}
		else {
			$this->setErreurs("CoursGlobal setNbreVoteCoursGlobal " . self::FORMAT_INT);
		}
	}
	public function setNbreVueCoursGlobal($nbreVue){
		if (is_int($nbreVue)) {
			$this->nbreVueCoursGlobal = $nbreVue;
		}
		else {
			$this->setErreurs("CoursGlobal setNbreVueCoursGlobal " . self::FORMAT_INT);
		}
	}
	public function setUrlImageCoursGlobal($urlImage) {

		if (is_string ( $urlImage )) {
			//verification que l'url contient au moins 10 caractères.
			
			if (mb_strlen($urlImage, 'UTF8') > 14){
				$this->urlImageCoursGlobal = $urlImage;
			}
			else {
				$this->setErreurs ("CoursGlobal setUrlImageCoursGlobal " .self::URL_IMAGE_COURS_GLOBAL );
			}
		} else {
			$this->setErreurs ("CoursGlobal setUrlImageCoursGlobal " . self::FORMAT_STRING );
		}
	}
	public function setUrlImageMiniatureCoursGlobal($urlImage) {

		if (is_string ( $urlImage )) {
			//verification que l'url contient au moins 10 caractères.
			
			if (mb_strlen($urlImage, 'UTF8') > 14){
				$this->urlImageMiniatureCoursGlobal = $urlImage;
			}
			else {
				$this->setErreurs ("CoursGlobal setUrlImageMiniatureCoursGlobal " .self::URL_IMAGE_COURS_GLOBAL );
			}
		} else {
			$this->setErreurs ("CoursGlobal setUrlImageMiniatureCoursGlobal " . self::FORMAT_STRING );
		}
	}
	public function setCategorie($categorie) {
		if ($categorie instanceof Categorie) {
			$this->categorie = $categorie;
		}
		else {
			$this->setErreurs("CoursGlobal setCategorie " . self::FORMAT_CATEGORIE);
		}
	}
	public function setAuteur($auteur) {
		if ($auteur instanceof Utilisateur) {
			$this->auteur = $auteur;
		} 
		else {
			$this->setErreurs("CoursGlobal setAuteur " . self::FORMAT_UTILISATEUR);
		}
	}
	public function setCours($cours) {
		if (is_array($cours)) {
			$this->cours = $cours;
		} 
		else {
			$this->setErreurs("CoursGlobal setCours " . self::FORMAT_ARRAY);
		}
	}

	
	/**
	 * ********** getter ****************
	 */
	public function getIdCoursGlobal() {
		return $this->idCoursGlobal;
	}
	public function getTitreCoursGlobal() {
		return $this->titreCoursGlobal;
	}
	public function getUrlTitreCoursGlobal() {
		return $this->cleanTitleCoursGlobal();
	}
	public function getEnLigneCoursGlobal() {
		return $this->enLigneCoursGlobal;
	}
	public function getDescriptionCoursGlobal() {
		return $this->descriptionCoursGlobal;
	}
	public function getDateCreationCoursGlobal() {
		return $this->dateCreationCoursGlobal;
	}
	public function getAuteur() {
		return $this->auteur;
	}
	public function getNoteCoursGlobal(){
		return $this->noteCoursGlobal;
	}
	public function getNbreVoteCoursGlobal(){
		return $this->nbreVoteCoursGlobal;
	}
	public function getNbreVueCoursGlobal(){
		return $this->nbreVueCoursGlobal;
	}
	public function getUrlImageCoursGlobal(){
		return $this->urlImageCoursGlobal;
	}
	public function getUrlImageMiniatureCoursGlobal(){
		return $this->urlImageMiniatureCoursGlobal;
	}
	public function getCategorie() {
		return $this->categorie;
	}
	public function getCours() {
		return $this->cours;
	}
	
	
	/**
	*
	* Fonctions 
	*
	*/

	// Permet d'ajouter un cours au cours global
	public function addCours(Cours $cours){
		if ($cours instanceof Cours){
			array_push($this->cours, $cours);
		}
		else{
			$this->setErreurs("Article addCours " . self::FORMAT_COURS);
		}
	}

	/**
	* Retourne le titre du cours pour être lisible en url.
	**/
	private function cleanTitleCoursGlobal(){
			$titreCoursGlobal = $this->titreCoursGlobal;

			//  Supprimer les espaces et les accents
		    $titreCoursGlobal=trim($titreCoursGlobal);
		    $table = array(
		        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
		        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
		        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
		        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
		        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
		        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
		        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
		        'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
		    );
		    $titreCoursGlobal= strtr($titreCoursGlobal, $table);
		 
		    //  Supprime et remplace les caracètres spéciaux (autres que lettres et chiffres)
		    $titreCoursGlobal = preg_replace('#([^.a-z0-9]+)#i', '-', $titreCoursGlobal);
		    
	    	return $titreCoursGlobal;
	}

}
