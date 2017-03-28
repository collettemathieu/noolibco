<?php
// +----------------------------------------------------------------------+
// | PHP Version 7 														  |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib 											  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Applications. 									  |
// +----------------------------------------------------------------------+
// | Auteur : Corentin Chevallier <ChevallierCorentin@noolib.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe Application
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe Application
 */
class Application extends \Library\Entity {
	
	/* Définition des attributs */
	protected $idApplication, $variableFixeApplication, $createur, $auteurs = array(), $statut, $categorie, $nomApplication, $descriptionApplication, $lienApplication, $dateSoumissionApplication, 
	$dateValidationApplication, $dateMiseHorsServiceApplication, $urlLogoApplication, $utilisateurs = array(), 
	$motCles = array(), $publications = array(), $versions = array();
	
	/* Déclaration des constantes d'erreurs particulières à */
	const NOM_APPLICATION = 'The name of your application must contain at least 3 letters and be less than 20 letters in length.';
	const URL_LOGO_APPLICATION = 'The picture of your application must be in PNG or JPEG format and less than 12 ko in weight.';
	const DESCRIPTION_APPLICATION = 'The description of your application must contain at least 50 letters.';
	const FORMAT_MOTSCLES_EMPTY = 'You must enter at least one keyword.';
	const VARIABLE_FIXE_APPLICATION = 'The fixed variable of the application must contain at least 4 characters in length.';
	//*****
	const LIEN_APPLICATION='link is not valid.';
	//*****
	
	
	/**
	 * ******Setter *****
	 */
	public function setIdApplication($idApplication) {
		// verification que l'id est au format integer
		if (ctype_digit($idApplication) || is_int($idApplication)) {
			$this->idApplication = $idApplication;
		} 
		//sinon envoi d'une erreur sur le format integer. 
		else {
			$this->setErreurs("Application setIdApplication " . self::FORMAT_INT);
		}
	}
	public function setVariableFixeApplication($variableFixeApplication) {
		
		if (is_string ( $variableFixeApplication )) {
			// verification que la variable contient au moins quatre caractères
			if (mb_strlen($variableFixeApplication, 'UTF8') > 3){
				$this->variableFixeApplication = $variableFixeApplication;
			}
			else {
				$this->setErreurs (self::VARIABLE_FIXE_APPLICATION );
			}
		} 
		else {
			$this->setErreurs ("Application setNomApplication " . self::FORMAT_STRING );
		}
	}
	public function setCreateur($createur) {
		if ($createur instanceof Utilisateur) {
			$this->createur = $createur;
		} 
		else {
			$this->setErreurs("Application setCreateur " . self::FORMAT_UTILISATEUR);
		}
	}
	public function setStatut($statut) {
		if ($statut instanceof StatutApplication) {
			$this->statut = $statut;
		} 
		else {
			$this->setErreurs("Application setStatut " . self::FORMAT_STATUT);
		}
	}
	public function setCategorie($categorie) {
		if ($categorie instanceof Categorie) {
			$this->categorie = $categorie;
		}
		else {
			$this->setErreurs("Application setCategorie " . self::FORMAT_CATEGORIE);
		}
	}
	public function setNomApplication($nomApplication) {
		
		if (is_string ( $nomApplication )) {
			// verification que le nom contient au moins trois caractères et moins de 21 sinon envoi d'une erreur
			if (mb_strlen($nomApplication, 'UTF8') > 2 && mb_strlen($nomApplication, 'UTF8') < 21){
				$this->nomApplication = $nomApplication;
			}
			else {
				$this->setErreurs (self::NOM_APPLICATION );
			}
		} 
		else {
			$this->setErreurs ("Application setNomApplication " . self::FORMAT_STRING );
		}
	}
	public function setDescriptionApplication($descriptionApplication) {

		if (is_string ( $descriptionApplication )) {
			//verification que la description contient au moins 50 caractères.
			
			if (mb_strlen($descriptionApplication, 'UTF8') > 50){
				$this->descriptionApplication = $descriptionApplication;
			}
			else {
				$this->setErreurs (self::DESCRIPTION_APPLICATION );
			}
		} else {
			$this->setErreurs ("Application setDescriptionApplication " . self::FORMAT_STRING );
		}
	}
	//**************
	//Added by Naoures
	//setter for Application link
	public function setLienApplication($lienApplication){
		if(empty($lienApplication) || $lienApplication=== undefined){
			$this->lienApplication=null;
		}
		elseif(preg_match("#^((http:\/\/|https:\/\/)?(www.)?(([a-zA-Z0-9-]){2,}\.){1,4}([a-zA-Z]){2,6}(\/([a-zA-Z-_\/\.0-9\#:?=&;,]*)?)?)$#", $lienApplication)){
			$this->lienApplication= $lienApplication;
		}
			else{
				$this->setErreurs(self::LIEN_APPLICATION);
			}
	}


	//*****
	
	public function setDateSoumissionApplication($dateSoumissionApplication) {
		//Pas de vérification sur les dates car elles sont automatiquement détèctées lors de l'écriture ou de la lecture dans la BDD.
		$this->dateSoumissionApplication = $dateSoumissionApplication;
	}
	public function setDateValidationApplication($dateValidationApplication) {
		$this->dateValidationApplication = $dateValidationApplication;
	}
	public function setDateMiseHorsServiceApplication($dateMiseHorsServiceApplication) {
		$this->dateMiseHorsServiceApplication = $dateMiseHorsServiceApplication;
	}
	public function setUrlLogoApplication($urlLogoApplication) {
		if(isset($urlLogoApplication)){
		
			if (is_string( $urlLogoApplication)){

				$this->urlLogoApplication = $urlLogoApplication;
			} 
			else {
				$this->setErreurs ("Application setUrlLogoApplication " . self::FORMAT_STRING);
			}
		}
		else{
			$this->setErreurs (self::URL_LOGO_APPLICATION);
		}
	}
	public function setUtilisateurs($utilisateurs){
		if (is_array($utilisateurs)){
			$this->utilisateurs = $utilisateurs;
		}
		else{
			$this->setErreurs("Application setUtilisateurs " . self::FORMAT_ARRAY);
		}	
	}
	public function setMotCles($motCles){
		if (is_array($motCles)){
			if(!empty($motCles)){
				$this->motCles = $motCles;
			}
			else{
				$this->setErreurs("Application setMotCles " . self::FORMAT_MOTSCLES_EMPTY);
			}	
		}
		else{
			$this->setErreurs("Application setMotCles " . self::FORMAT_ARRAY);
		}	
	}
	public function setPublications($publications){
		if (is_array($publications)){
			$this->publications = $publications;
		}
		else{
			$this->setErreurs("Application setPublications " . self::FORMAT_ARRAY);
		}	
	}
	
	public function setVersions($versions){
		if (is_array($versions)){
			$this->versions = $versions;
		}
		else{
			$this->setErreurs("Application setVersions " . self::FORMAT_ARRAY);
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getIdApplication() {
		return $this->idApplication;
	}
	public function getVariableFixeApplication() {
		return $this->variableFixeApplication;
	}
	public function getCreateur() {
		return $this->createur;
	}
	public function getAuteurs() {
		return $this->auteurs;
	}
	public function getStatut() {
		return $this->statut;
	}
	public function getCategorie() {
		return $this->categorie;
	}
	public function getNomApplication() {
		return $this->nomApplication;
	}
	public function getDescriptionApplication() {
		return $this->descriptionApplication;
	}
	//*****
	public function getLienApplication(){
		return $this->lienApplication;
	}
	//*****
	public function getDateSoumissionApplication() {
		return $this->dateSoumissionApplication;
	}
	public function getDateValidationApplication() {
		return $this->dateValidationApplication;
	}
	public function getDateMiseHorsServiceApplication() {
		return $this->dateMiseHorsServiceApplication;
	}
	public function getUrlLogoApplication() {
		return $this->urlLogoApplication;
	}
	public function getUtilisateurs(){
		return $this->utilisateurs;
	}
	public function getMotCles(){
		return $this->motCles;
	}
	public function getPublications(){
		return $this->publications;
	}
	public function getVersions(){
		return $this->versions;
	}
	
	
	//permet de récuperer un utilisateur d'une liste d'après son ID
	public function getUtilisateurFromUtilisateurs($idUtilisateur){
		$utilisateurReturn = null;
		if (ctype_digit($idUtilisateur) || is_int($idUtilisateur)) {
			foreach ($this->utilisateurs as $utilisateur){
				if ($utilisateur->getIdUtilisateur() == $idUtilisateur){
					$utilisateurReturn = $utilisateur;
				}
			}
		} 
		else {
			$this->setErreurs("Application getUtilisateurFromUtilisateurs " . self::FORMAT_INT);
		}
		return $utilisateurReturn;
	}
	//permet de récuperer un mot cle d'une liste d'après son ID
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
			$this->setErreurs("Application getMotCleFromMotCles " . self::FORMAT_INT);
		}
		return $motCle;
	}
	//permet de récuperer un publication d'une liste d'après son ID
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
			$this->setErreurs("Application getPublicationFromPublications " . self::FORMAT_INT);
		}
		return $publicationReturn;
	}
	public function getVersionFromVersions($idVersion){
		$versionReturn = null;
		if (ctype_digit($idVersion) || is_int($idVersion)) {
			foreach ($this->versions as $version){
				if ($version->getIdVersion() == $idVersion){
					$versionReturn = $version;
				}
			}
		}
		else {
			$this->setErreurs("Application getVersionFromVersions " . self::FORMAT_INT);
		}
		return $versionReturn;
	}
	
	/**
	 * 
	 * adders
	 * 
	 */
	// Permet d'ajouter un auteur à l'application
	public function addAuteur($auteur){
		if ($auteur instanceof Auteur){
			array_push($this->auteurs, $auteur);
		}
		else{
			$this->setErreurs("Application addAuteur " . self::FORMAT_AUTEUR);
		}
	}
	//permet d'ajouter un utilisateur à la liste des utilisateurs ayant pour favori l'application
	public function addUtilisateur(Utilisateur $utilisateur){
		if ($utilisateur instanceof Utilisateur){
			array_push($this->utilisateurs, $utilisateur);
		}
		else{
			$this->setErreurs("Application addUtilisateur " . self::FORMAT_UTILISATEUR);
		}
	}
	//permet d'ajouter un motcle à la liste des utilisateurs ayant pour favori l'application
	
	public function addMotCle(MotCle $motCle){
		if ($motCle instanceof MotCle){
			array_push($this->motCles, $motCle->getNomMotCle());
		}
		else{
			$this->setErreurs("Application addMotCle " . self::FORMAT_MOT_CLE);
		}
	}
	// Permet d'ajouter une publication à la liste des utilisateurs ayant pour favori l'application
	
	public function addPublication(Publication $publication){
		if ($publication instanceof Publication){
			array_push($this->publications, $publication);
		}
		else{
			$this->setErreurs("Application AddPublication " . self::FORMAT_PUBLICATION);
		}
	}
	
	public function addVersion(Version $version){
		if ($version instanceof Version){
			array_push($this->versions, $version);
		}
		else{
			$this->setErreurs("Application AddVersion " . self::FORMAT_VERSION);
		}
	}
	//permet d'ajouter une liste d'utilisateurs à la liste des utilisateurs ayant pour favori l'application
	
	public function addAllUtilisateurs($utilisateurs){
		if (isArray($utilisateurs)){
			foreach ($utilisateurs as $utilisateur){
				if ($utilisateur instanceof Utilisateur){
					array_push($this->utilisateurs, $utilisateur);
				}
				else{
					$this->setErreurs("Application addAllUtilisateurs " . self::FORMAT_UTILISATEUR);
				}
			}
		}
		else{
			$this->setErreurs("Application addAllUtilisateurs " . self::FORMAT_ARRAY);
		}
	}
	//permet d'ajouter une liste de mot clés à la liste des utilisateurs ayant pour favori l'application
	public function addAllMotCles($motCles){
		if (isArray($motCles)){
			foreach ($motCles as $motCle){
				if ($motCle instanceof MotCle){
					array_push($this->motCles, $motCle);
				}
				else{
					$this->setErreurs("Application addAllMotCles " . self::FORMAT_MOT_CLE);
				}
			}
		}
		else{
			$this->setErreurs("Application addAllMotCles " . self::FORMAT_ARRAY);
		}
	}
	//permet d'ajouter une liste de publications à la liste des utilisateurs ayant pour favori l'application
	public function addAllPublications($publications){
		if (isArray($publications)){
			foreach ($publications as $publication){
				if ($publication instanceof Publication){
					array_push($this->publications, $publication);
				}
				else{
					$this->setErreurs("Application addAllPublications " . self::FORMAT_PUBLICATION);
				}
			}
		}
		else{
			$this->setErreurs("Application addAllPublications " . self::FORMAT_ARRAY);
		}
	}
	public function addAllVersions($versions){
		if (isArray($versions)){
			foreach ($versions as $version){
				if ($version instanceof Version){
					array_push($this->versions, $version);
				}
				else{
					$this->setErreurs("Application addAllVersions " . self::FORMAT_VERSION);
				}
			}
		}
		else{
			$this->setErreurs("Application addAllVersions " . self::FORMAT_ARRAY);
		}
	}
}
