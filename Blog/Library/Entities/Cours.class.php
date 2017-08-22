<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Cours.	 										  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe Cours
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe Cours
 */
class Cours extends \Library\Entity {
	
	/* Définition des attributs */
	protected 	$idCours, 
				$titreCours, 
				$descriptionCours,
				$texteCours,
				$sommaireCours,
				$referencesCours,
				$dateCreationCours,
				$enLigneCours,
				$noteCours,
				$nbreVoteCours,
				$nbreVueCours,
				$urlImageCours,
				$urlImageMiniatureCours,
				$categorie,
				$auteur,
				$coursGlobal,
				$motCles = array(),
				$commentaires = array();
	
	/* Déclaration des constantes d'erreurs particulières à */
	const TITRE_COURS = 'Le nom du cours doit être compris entre 5 et 50 caractères.';
	const DESCRIPTION_COURS = 'La description du cours doit contenir au moins 10 caractères.';
	const FORMAT_MOTSCLES_EMPTY = 'Vous devez entrer au moins un mot-clé.';
	const TEXTE_COURS = 'Le texte du cours doit contenir au moins 5 caractères.';
	const URL_IMAGE_COURS = 'L\'url de l\'image du cours doit comporter au moins 13 caractères';
	
	
	/**
	 * ******Setter *****
	 */
	public function setIdCours($idCours) {
		// verification que l'id est au format integer
		if (ctype_digit($idCours) || is_int($idCours)) {
			$this->idCours = $idCours;
		} 
		//sinon envoi d'une erreur sur le format integer. 
		else {
			$this->setErreurs("Cours setIdCours " . self::FORMAT_INT);
		}
	}
	public function setTitreCours($titreCours) {

		if (is_string ( $titreCours )) {
			//verification que le titre contient entre 5 et 53 caractères.
			
			if (mb_strlen($titreCours, 'UTF8') > 4 && mb_strlen($titreCours, 'UTF8') < 51){
				$this->titreCours = trim($titreCours);
			}
			else {
				$this->setErreurs ("Cours setTitreCours " .self::TITRE_COURS );
			}
		} else {
			$this->setErreurs ("Cours setTitreCours " . self::FORMAT_STRING );
		}
	}
	public function setDescriptionCours($descriptionCours) {

		if (is_string ( $descriptionCours )) {
			//verification que la description contient au moins 10 caractères.
			
			if (mb_strlen($descriptionCours, 'UTF8') > 10){
				$this->descriptionCours = trim($descriptionCours);
			}
			else {
				$this->setErreurs ("Cours setDescriptionCours " .self::DESCRIPTION_COURS );
			}
		} else {
			$this->setErreurs ("Cours setDescriptionCours " . self::FORMAT_STRING );
		}
	}
	public function setTexteCours($texteCours) {

		if (is_string ( $texteCours )) {
			//verification que le texte contient au moins 5 caractères.
			
			if (mb_strlen($texteCours, 'UTF8') > 5){
				$this->texteCours = trim($texteCours);
			}
			else {
				$this->setErreurs ("Cours setTexteCours " .self::TEXTE_COURS );
			}
		} else {
			$this->setErreurs ("Cours setTexteCours " . self::FORMAT_STRING );
		}
	}
	public function setSommaireCours($sommaireCours) {

		if (is_string ( $sommaireCours )) {
			//verification que le sommaire contient au moins 5 caractères.
			$this->sommaireCours = trim($sommaireCours);
		} else {
			$this->setErreurs ("Cours setSommaireCours " . self::FORMAT_STRING );
		}
	}
	public function setReferencesCours($referencesCours) {

		if (is_string ( $referencesCours )) {
			$this->referencesCours = $referencesCours;
		} else {
			$this->setErreurs ("Cours setReferencesCours " . self::FORMAT_STRING );
		}
	}
	public function setEnLigneCours($bool) {

		if (is_bool ( $bool )) {
			$this->enLigneCours = $bool;
		} else {
			$this->setErreurs ("Cours setEnLigneCours " . self::FORMAT_BOOLEAN );
		}
	}
	public function setDateCreationCours($dateCours) {

		if (is_string ( $dateCours )) {
			$this->dateCreationCours = $dateCours;
		} else {
			$this->setErreurs ("Cours setDateCreationCours " . self::FORMAT_STRING );
		}
	}
	public function setNoteCours($noteCours){
		if (is_float($noteCours)) {
			$this->noteCours = $noteCours;
		}
		else {
			$this->setErreurs("Cours setNoteCours " . self::FORMAT_FLOAT);
		}
	}
	public function setNbreVoteCours($nbreVote){
		if (is_int($nbreVote)) {
			$this->nbreVoteCours = $nbreVote;
		}
		else {
			$this->setErreurs("Cours setNbreVoteCours " . self::FORMAT_INT);
		}
	}
	public function setNbreVueCours($nbreVue){
		if (is_int($nbreVue)) {
			$this->nbreVueCours = $nbreVue;
		}
		else {
			$this->setErreurs("Cours setNbreVueCours " . self::FORMAT_INT);
		}
	}
	public function setUrlImageCours($urlImage) {

		if (is_string ( $urlImage )) {
			//verification que l'url contient au moins 10 caractères.
			
			if (mb_strlen($urlImage, 'UTF8') > 14){
				$this->urlImageCours = $urlImage;
			}
			else {
				$this->setErreurs ("Cours setUrlImageCours " .self::URL_IMAGE_COURS );
			}
		} else {
			$this->setErreurs ("Cours setUrlImageCours " . self::FORMAT_STRING );
		}
	}
	public function setUrlImageMiniatureCours($urlImage) {

		if (is_string ( $urlImage )) {
			//verification que l'url contient au moins 10 caractères.
			
			if (mb_strlen($urlImage, 'UTF8') > 14){
				$this->urlImageMiniatureCours = $urlImage;
			}
			else {
				$this->setErreurs ("Cours setUrlImageMiniatureCours " .self::URL_IMAGE_COURS );
			}
		} else {
			$this->setErreurs ("Cours setUrlImageMiniatureCours " . self::FORMAT_STRING );
		}
	}
	public function setCategorie($categorie) {
		if ($categorie instanceof Categorie) {
			$this->categorie = $categorie;
		}
		else {
			$this->setErreurs("Cours setCategorie " . self::FORMAT_CATEGORIE);
		}
	}
	public function setAuteur($auteur) {
		if ($auteur instanceof Utilisateur) {
			$this->auteur = $auteur;
		} 
		else {
			$this->setErreurs("Cours setAuteur " . self::FORMAT_UTILISATEUR);
		}
	}
	public function setCoursGlobal($coursGlobal) {
		if ($coursGlobal instanceof CoursGlobal) {
			$this->coursGlobal = $coursGlobal;
		} 
		else {
			$this->setErreurs("Cours setCoursGlobal " . self::FORMAT_COURS_GLOBAL);
		}
	}
	public function setMotCles($motCles){
		if (is_array($motCles)){
			if(!empty($motCles)){
				$this->motCles = $motCles;
			}
			else{
				$this->setErreurs("Cours setMotCles " . self::FORMAT_EMPTY);
			}	
		}
		else{
			$this->setErreurs("Cours setMotCles " . self::FORMAT_ARRAY);
		}	
	}
	public function setCommentaires($commentaires){
		if (is_array($commentaires)){
			if(!empty($commentaires)){
				$this->commentaires = $commentaires;
			}
			else{
				$this->setErreurs("Cours setCommentaires " . self::FORMAT_EMPTY);
			}	
		}
		else{
			$this->setErreurs("Cours setCommentaires " . self::FORMAT_ARRAY);
		}	
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getIdCours() {
		return $this->idCours;
	}
	public function getTitreCours() {
		return $this->titreCours;
	}
	public function getUrlTitreCours() {
		return $this->cleanTitleCours();
	}
	public function getTexteCours() {
		return $this->texteCours;
	}
	public function getSommaireCours() {
		return $this->sommaireCours;
	}
	public function getReferencesCours() {
		return $this->referencesCours;
	}
	public function getEnLigneCours() {
		return $this->enLigneCours;
	}
	public function getDescriptionCours() {
		return $this->descriptionCours;
	}
	public function getDateCreationCours() {
		return $this->dateCreationCours;
	}
	public function getAuteur() {
		return $this->auteur;
	}
	public function getCoursGlobal() {
		return $this->coursGlobal;
	}
	public function getNoteCours(){
		return $this->noteCours;
	}
	public function getNbreVoteCours(){
		return $this->nbreVoteCours;
	}
	public function getNbreVueCours(){
		return $this->nbreVueCours;
	}
	public function getUrlImageCours(){
		return $this->urlImageCours;
	}
	public function getUrlImageMiniatureCours(){
		return $this->urlImageMiniatureCours;
	}
	public function getCategorie() {
		return $this->categorie;
	}
	public function getMotCles(){
		return $this->motCles;
	}
	public function getCommentaires(){
		return $this->commentaires;
	}
	
	
	// Permet de récuperer un mot cle d'une liste d'après son ID
	public function getMotCleFromMotCles($idMotCle){
		$motCleReturn = null;
		if (ctype_digit($idMotCle) || is_int($idMotCle)) {
			foreach ($this->motCles as $motCle){
				if ($motCle->getIdMotCle() == $idMotCle){
					$motCleReturn = $motCle;
				}
			}
		}
		else {
			$this->setErreurs("Cours getMotCleFromMotCles " . self::FORMAT_INT);
		}
		return $motCleReturn;
	}
	
	/**
	 * 
	 * adders
	 * 
	 */
	
	// Permet d'ajouter un motcle au cours
	public function addMotCle(MotCle $motCle){
		if ($motCle instanceof MotCle){
			array_push($this->motCles, $motCle);
		}
		else{
			$this->setErreurs("Cours addMotCle " . self::FORMAT_MOT_CLE);
		}
	}

	// Permet d'ajouter un commentaire au cours
	public function addCommentaire(Commentaire $commentaire){
		if ($commentaire instanceof Commentaire){
			array_push($this->commentaires, $commentaire);
		}
		else{
			$this->setErreurs("Cours addCommentaire " . self::FORMAT_COMMENTAIRE);
		}
	}

	/**
	*
	* Fonctions 
	*
	*/

	/**
	* Retourne le titre du cours pour être lisible en url.
	**/
	private function cleanTitleCours(){
			$titreCours = $this->titreCours;

			//  Supprimer les espaces et les accents
		    $titreCours=trim($titreCours);
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
		    $titreCours= strtr($titreCours, $table);
		 
		    //  Supprime et remplace les caracètres spéciaux (autres que lettres et chiffres)
		    $titreCours = preg_replace('#([^a-z0-9]+)#i', '-', $titreCours);
		    
	    	return $titreCours;
	}

}
