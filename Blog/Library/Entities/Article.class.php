<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Articles.	 									  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe Article
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe Article
 */
class Article extends \Library\Entity {
	
	/* Définition des attributs */
	protected 	$idArticle, 
				$titreArticle, 
				$descriptionArticle,
				$texteArticle,
				$referencesArticle,
				$dateCreationArticle,
				$enLigneArticle,
				$noteArticle,
				$nbreVoteArticle,
				$nbreVueArticle,
				$urlImageArticle,
				$urlImageMiniatureArticle,
				$categorie,
				$auteur,
				$motCles = array(),
				$commentaires = array();
	
	/* Déclaration des constantes d'erreurs particulières à */
	const TITRE_ARTICLE = 'Le nom de l\'article doit être compris entre 5 et 50 caractères.';
	const DESCRIPTION_ARTICLE = 'La description de l\'article doit contenir au moins 10 caractères.';
	const FORMAT_MOTSCLES_EMPTY = 'Vous devez entrer au moins un mot-clé.';
	const TEXTE_ARTICLE = 'Le texte de l\'article doit contenir au moins 5 caractères.';
	const URL_IMAGE_ARTICLE = 'L\'url de l\'image de l\'article doit comporter au moins 13 caractères';
	
	
	/**
	 * ******Setter *****
	 */
	public function setIdArticle($idArticle) {
		// verification que l'id est au format integer
		if (ctype_digit($idArticle) || is_int($idArticle)) {
			$this->idArticle = $idArticle;
		} 
		//sinon envoi d'une erreur sur le format integer. 
		else {
			$this->setErreurs("Article setIdArticle " . self::FORMAT_INT);
		}
	}
	public function setTitreArticle($titreArticle) {

		if (is_string ( $titreArticle )) {
			//verification que le titre contient entre 5 et 50 caractères.
			
			if (mb_strlen($titreArticle, 'UTF8') > 4 && mb_strlen($titreArticle, 'UTF8') < 51){
				$this->titreArticle = trim($titreArticle);
			}
			else {
				$this->setErreurs ("Article setTitreArticle " .self::TITRE_ARTICLE );
			}
		} else {
			$this->setErreurs ("Article setTitreArticle " . self::FORMAT_STRING );
		}
	}
	public function setDescriptionArticle($descriptionArticle) {

		if (is_string ( $descriptionArticle )) {
			//verification que la description contient au moins 10 caractères.
			
			if (mb_strlen($descriptionArticle, 'UTF8') > 10){
				$this->descriptionArticle = trim($descriptionArticle);
			}
			else {
				$this->setErreurs ("Article setDescriptionArticle " .self::DESCRIPTION_ARTICLE );
			}
		} else {
			$this->setErreurs ("Article setDescriptionArticle " . self::FORMAT_STRING );
		}
	}
	public function setTexteArticle($texteArticle) {

		if (is_string ( $texteArticle )) {
			//verification que le texte contient au moins 5 caractères.
			
			if (mb_strlen($texteArticle, 'UTF8') > 5){
				$this->texteArticle = trim($texteArticle);
			}
			else {
				$this->setErreurs ("Article setTexteArticle " .self::TEXTE_ARTICLE );
			}
		} else {
			$this->setErreurs ("Article setTexteArticle " . self::FORMAT_STRING );
		}
	}
	public function setReferencesArticle($referencesArticle) {

		if (is_string ( $referencesArticle )) {
			$this->referencesArticle = $referencesArticle;
		} else {
			$this->setErreurs ("Article setReferencesArticle " . self::FORMAT_STRING );
		}
	}
	public function setEnLigneArticle($bool) {

		if (is_bool ( $bool )) {
			$this->enLigneArticle = $bool;
		} else {
			$this->setErreurs ("Article setEnLigneArticle " . self::FORMAT_BOOLEAN );
		}
	}
	public function setDateCreationArticle($dateArticle) {

		if (is_string ( $dateArticle )) {
			$this->dateCreationArticle = $dateArticle;
		} else {
			$this->setErreurs ("Article setDateCreationArticle " . self::FORMAT_STRING );
		}
	}
	public function setNoteArticle($noteArticle){
		if (is_float($noteArticle)) {
			$this->noteArticle = $noteArticle;
		}
		else {
			$this->setErreurs("Article setNoteArticle " . self::FORMAT_FLOAT);
		}
	}
	public function setNbreVoteArticle($nbreVote){
		if (is_int($nbreVote)) {
			$this->nbreVoteArticle = $nbreVote;
		}
		else {
			$this->setErreurs("Article setNbreVoteArticle " . self::FORMAT_INT);
		}
	}
	public function setNbreVueArticle($nbreVue){
		if (is_int($nbreVue)) {
			$this->nbreVueArticle = $nbreVue;
		}
		else {
			$this->setErreurs("Article setNbreVueArticle " . self::FORMAT_INT);
		}
	}
	public function setUrlImageArticle($urlImage) {

		if (is_string ( $urlImage )) {
			//verification que l'url contient au moins 10 caractères.
			
			if (mb_strlen($urlImage, 'UTF8') > 14){
				$this->urlImageArticle = $urlImage;
			}
			else {
				$this->setErreurs ("Article setUrlImageArticle " .self::URL_IMAGE_ARTICLE );
			}
		} else {
			$this->setErreurs ("Article setUrlImageArticle " . self::FORMAT_STRING );
		}
	}
	public function setUrlImageMiniatureArticle($urlImage) {

		if (is_string ( $urlImage )) {
			//verification que l'url contient au moins 10 caractères.
			
			if (mb_strlen($urlImage, 'UTF8') > 14){
				$this->urlImageMiniatureArticle = $urlImage;
			}
			else {
				$this->setErreurs ("Article setUrlImageMiniatureArticle " .self::URL_IMAGE_ARTICLE );
			}
		} else {
			$this->setErreurs ("Article setUrlImageMiniatureArticle " . self::FORMAT_STRING );
		}
	}
	public function setCategorie($categorie) {
		if ($categorie instanceof Categorie) {
			$this->categorie = $categorie;
		}
		else {
			$this->setErreurs("Article setCategorie " . self::FORMAT_CATEGORIE);
		}
	}
	public function setAuteur($auteur) {
		if ($auteur instanceof Utilisateur) {
			$this->auteur = $auteur;
		} 
		else {
			$this->setErreurs("Article setAuteur " . self::FORMAT_UTILISATEUR);
		}
	}
	public function setMotCles($motCles){
		if (is_array($motCles)){
			if(!empty($motCles)){
				$this->motCles = $motCles;
			}
			else{
				$this->setErreurs("Article setMotCles " . self::FORMAT_EMPTY);
			}	
		}
		else{
			$this->setErreurs("Article setMotCles " . self::FORMAT_ARRAY);
		}	
	}
	public function setCommentaires($commentaires){
		if (is_array($commentaires)){
			if(!empty($commentaires)){
				$this->commentaires = $commentaires;
			}
			else{
				$this->setErreurs("Article setCommentaires " . self::FORMAT_EMPTY);
			}	
		}
		else{
			$this->setErreurs("Article setCommentaires " . self::FORMAT_ARRAY);
		}	
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getIdArticle() {
		return $this->idArticle;
	}
	public function getTitreArticle() {
		return $this->titreArticle;
	}
	public function getUrlTitreArticle() {
		return $this->cleanTitleArticle();
	}
	public function getTexteArticle() {
		return $this->texteArticle;
	}
	public function getReferencesArticle() {
		return $this->referencesArticle;
	}
	public function getEnLigneArticle() {
		return $this->enLigneArticle;
	}
	public function getDescriptionArticle() {
		return $this->descriptionArticle;
	}
	public function getDateCreationArticle() {
		return $this->dateCreationArticle;
	}
	public function getAuteur() {
		return $this->auteur;
	}
	public function getNoteArticle(){
		return $this->noteArticle;
	}
	public function getNbreVoteArticle(){
		return $this->nbreVoteArticle;
	}
	public function getNbreVueArticle(){
		return $this->nbreVueArticle;
	}
	public function getUrlImageArticle(){
		return $this->urlImageArticle;
	}
	public function getUrlImageMiniatureArticle(){
		return $this->urlImageMiniatureArticle;
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
			$this->setErreurs("Article getMotCleFromMotCles " . self::FORMAT_INT);
		}
		return $motCleReturn;
	}
	
	/**
	 * 
	 * adders
	 * 
	 */
	
	// Permet d'ajouter un motcle à l'article
	public function addMotCle(MotCle $motCle){
		if ($motCle instanceof MotCle){
			array_push($this->motCles, $motCle);
		}
		else{
			$this->setErreurs("Article addMotCle " . self::FORMAT_MOT_CLE);
		}
	}

	// Permet d'ajouter un commentaire à l'article
	public function addCommentaire(Commentaire $commentaire){
		if ($commentaire instanceof Commentaire){
			array_push($this->commentaires, $commentaire);
		}
		else{
			$this->setErreurs("Article addCommentaire " . self::FORMAT_COMMENTAIRE);
		}
	}

	/**
	*
	* Fonctions 
	*
	*/

	/**
	* Retourne le titre de l'article pour être lisible en url.
	**/
	private function cleanTitleArticle(){
			$titreArticle = $this->titreArticle;

			//  Supprimer les espaces et les accents
		    $titreArticle=trim($titreArticle);
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
		    $titreArticle= strtr($titreArticle, $table);
		    
		    //  Supprime et remplace les caracètres spéciaux (autres que lettres et chiffres)
		    $titreArticle = preg_replace('#([^a-z0-9]+)#i', '-', $titreArticle);
		    
	    	return $titreArticle;
	}

}
