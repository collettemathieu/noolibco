<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2014 NooLib 											  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les Utilisateurs. 									  |
// +----------------------------------------------------------------------+
// | Auteurs : Corentin Chevallier <ChevallierCorentin@noolib.com>  	  | 
// |		   Mathieu COLLETTE <collettemathieu@noolib.com>		      |
// |		   Steve Despres <despressteve@noolib.com>		     		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe Utilisateur
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe Utilisateur
 */
class Utilisateur extends \Library\Entity {
	
	/* Définition des attributs */
	protected $idUtilisateur, $idAuteur, $variableFixeUtilisateur, $workSpaceFolderUtilisateur, $nomUtilisateur, $prenomUtilisateur, $mailUtilisateur, $statut, $dateDerniereConnexionUtilisateur, $dateInscriptionUtilisateur, 
	$descriptionUtilisateur, $urlPhotoUtilisateur, $lienPagePersoUtilisateur, $passwordUtilisateur, $passwordAdminUtilisateur, $etatBanniUtilisateur, $urlBackgroundUtilisateur, $utilisateurActive, $applications = array(), $favoris = array(),
	$logs = array(), $publicationAuteurs = array(), $equipes = array(), $donneesUtilisateur = array(), $publicationPublieurs = array();
	
	/* Déclaration des constantes d'erreurs */
	
	const NOM_INVALIDE = 'Your last name must contain at least 2 letters in length.';
	const PRENOM_INVALIDE = 'Your first name must contain at least 2 letters in length.';
	const VARIABLE_FIXE_INVALIDE = 'The fixed variable of the user must contain at least 6 characters in length.';
	const WORKSPACE_FOLDER_INVALIDE = 'The name of the workspace of the user must contain at least 6 characters in length.';
	const MAIL_INVALIDE = 'Your e-mail address entered is incorrect.';
	const PASSWORD_INVALIDE = 'Your password must contain at least 8 letters in length with at least one number, uppercase and lowercase.';
	const DESCRIPTION_INVALIDE = 'Your profile description entered is incorrect.';
	const URL_PHOTO_INVALIDE = 'Your profile picture entered is not valid.';
	const LIEN_PAGE_PERSO_INVALIDE = 'The link for your personal page is incorrect.';
	const STATUT_INVALIDE = 'You must select a status.';
	const CONFIRMATION_PASSWORD_INVALIDE = 'Your second password does not match with the first.';
	const MAIL_JETABLE = 'Free and disposable e-mail addresses are not accepted.';
	
	
	/**
	 * ******Setter *****
	*/
	public function setIdUtilisateur($idUtilisateur) {
		// verification que l'id est au format integer
		if (ctype_digit($idUtilisateur) || is_int($idUtilisateur)) {
			$this->idUtilisateur = $idUtilisateur;
		} 
		//sinon envoi d'une erreur sur le format integer. 
		else {
			$this->setErreurs(self::FORMAT_INT);
		}
	}
	public function setIdAuteur($idAuteur) {
		// verification que l'id est au format integer
		if (ctype_digit($idAuteur) || is_int($idAuteur)) {
			$this->idAuteur = $idAuteur;
		} 
		//sinon envoi d'une erreur sur le format integer. 
		else {
			$this->setErreurs(self::FORMAT_INT);
		}
	}
	public function setVariableFixeUtilisateur($variableFixeUtilisateur) {
		if (is_string($variableFixeUtilisateur)) {
			if (strlen($variableFixeUtilisateur) > 6){
				$this->variableFixeUtilisateur = $variableFixeUtilisateur;
			}
			else{
				$this->setErreurs(self::VARIABLE_FIXE_INVALIDE);
			}
		} 
		else {
			$this->setErreurs(self::FORMAT_STRING);
		}
	}
	public function setWorkSpaceFolderUtilisateur($workSpaceFolderUtilisateur) {
		if (is_string($workSpaceFolderUtilisateur)) {
			if (strlen($workSpaceFolderUtilisateur) > 6){
				$this->workSpaceFolderUtilisateur = $workSpaceFolderUtilisateur;
			}
			else{
				$this->setErreurs(self::WORKSPACE_FOLDER_INVALIDE);
			}
		} 
		else {
			$this->setErreurs(self::FORMAT_STRING);
		}
	}
	public function setNomUtilisateur($nomUtilisateur) {
		if (is_string($nomUtilisateur)) {
			if (strlen($nomUtilisateur) > 1){
				$this->nomUtilisateur = $nomUtilisateur;
			}
			else{
				$this->setErreurs(self::NOM_INVALIDE);
			}
		} 
		else {
			$this->setErreurs(self::FORMAT_STRING);
		}
	}
	public function setPrenomUtilisateur($prenomUtilisateur) {
		if (is_string($prenomUtilisateur)) {
			if (strlen($prenomUtilisateur) > 1){
				$this->prenomUtilisateur = $prenomUtilisateur;
			}
			else{
				$this->setErreurs(self::PRENOM_INVALIDE);
			}
		} 
		else {
			$this->setErreurs(self::FORMAT_STRING);
		}
	}
	public function setMailUtilisateur($mailUtilisateur) {
		if (is_string($mailUtilisateur)) {
			if( !empty($mailUtilisateur) && preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $mailUtilisateur)){
				$this->mailUtilisateur = $mailUtilisateur;
			}
			else{
				$this->setErreurs(self::MAIL_INVALIDE);
			}
		}elseif($mailUtilisateur === false) {
			$this->setErreurs(self::MAIL_JETABLE);
		}else{
			$this->setErreurs(self::FORMAT_STRING);	
		}
	}
	public function setStatut($statut) {
		if(false) {
			$this->statut = $statut;
		} 
		else {
			$this->setErreurs(self::STATUT_INVALIDE);
		}
	}
	public function setDateDerniereConnexionUtilisateur($dateDerniereConnexionUtilisateur) {
		// Pas de vérification sur les dates car elles sont automatiquement traitées lors de l'écriture ou de la lecture dans la BDD.
		$this->dateDerniereConnexionUtilisateur = $dateDerniereConnexionUtilisateur;
	}
	public function setDateInscriptionUtilisateur($dateInscriptionUtilisateur) {
		$this->dateInscriptionUtilisateur = $dateInscriptionUtilisateur;
	}
	public function setDescriptionUtilisateur($descriptionUtilisateur) {
		if (is_string( $descriptionUtilisateur ))		{
			$this->descriptionUtilisateur = $descriptionUtilisateur;
		}
		else{
			$this->setErreurs (self::FORMAT_STRING );
		}
	}
	public function setUrlPhotoUtilisateur($urlPhotoUtilisateur) {
		if (is_string( $urlPhotoUtilisateur ))		{
			$this->urlPhotoUtilisateur = $urlPhotoUtilisateur;
		} 
		else{
			$this->setErreurs (self::FORMAT_STRING );
		}
	}
	public function setUrlBackgroundUtilisateur($urlBackgroundUtilisateur) {
		if (is_string( $urlBackgroundUtilisateur ))		{
			$this->urlBackgroundUtilisateur = $urlBackgroundUtilisateur;
		} 
		else{
			$this->setErreurs (self::FORMAT_STRING );
		}
	}
	
	public function setLienPagePersoUtilisateur($lienPagePersoUtilisateur) {
		if (is_string($lienPagePersoUtilisateur) && !empty( $lienPagePersoUtilisateur)){
			if(preg_match("#^((http:\/\/|https:\/\/)?(www.)?(([a-zA-Z0-9-]){2,}\.){1,4}([a-zA-Z]){2,6}(\/([a-zA-Z-_\/\.0-9\#:?=&;,]*)?)?)$#", $lienPagePersoUtilisateur)){
				$this->lienPagePersoUtilisateur = $lienPagePersoUtilisateur;
			}
			else{
				$this->setErreurs(self::LIEN_PAGE_PERSO_INVALIDE);
			}
		}
		else{
			$this->lienPagePersoUtilisateur = '';
		}
	}
	
	
	public function setPasswordUtilisateur($passwordUtilisateur) {
	
		if(is_string($passwordUtilisateur)){
			if(strlen($passwordUtilisateur) > 59 ){
				$this->passwordUtilisateur = $passwordUtilisateur;
			}else{
				$this->setErreurs(self::PASSWORD_INVALIDE);	
			}
		}elseif($passwordUtilisateur === null){
			
			$this->setErreurs(self::PASSWORD_INVALIDE);
				
		}else{
			$this->setErreurs(self::PASSWORD_INVALIDE);
		}
	
	}
	
	public function setPasswordAdminUtilisateur($passwordAdminUtilisateur) {	
		if (is_string( $passwordAdminUtilisateur )){
			$this->passwordAdminUtilisateur = $passwordAdminUtilisateur;
		}else{
			$this->setErreurs(self::PASSWORD_INVALIDE);
		}			
	}
	
	public function setEtatBanniUtilisateur($etatBanniUtilisateur){
		if (is_bool( $etatBanniUtilisateur )){
			$this->etatBanniUtilisateur = $etatBanniUtilisateur;
		}
		else{
			$this->setErreurs (self::FORMAT_BOOLEAN );
		}
	}
	public function setApplications($applications){
		if (is_array($applications)){
			$this->applications = $applications;
		}
		else{
			$this->setErreurs(self::FORMAT_ARRAY);
		}	
	}
	public function setFavoris($applications){
		if (is_array($applications)){
			$this->favoris = $applications;
		}
		else{
			$this->setErreurs(self::FORMAT_ARRAY);
		}
	}
	public function setLogs($logs){
		if (is_array($logs)){
			$this->logs = $logs;
		}
		else{
			$this->setErreurs(self::FORMAT_ARRAY);
		}	
	}
	public function setPublicationAuteurs($publicationAuteurs){
		if (is_array($publicationAuteurs)){
			$this->publicationAuteurs = $publicationAuteurs;
		}
		else{
			$this->setErreurs(self::FORMAT_ARRAY);
		}		
	}
	public function setEquipes($equipes){
		if (is_array($equipes)){
			$this->equipes = $equipes;
		}
		else{
			$this->setErreurs(self::FORMAT_ARRAY);
		}
	}
	public function setDonneesUtilisateur($donneesUtilisateur){
		if (is_array($donneesUtilisateur)){
			$this->donneesUtilisateur = $donneesUtilisateur;
		}
		else{
			$this->setErreurs(self::FORMAT_ARRAY);
		}
	}
	public function setPublicationPublieurs($publicationPublieurs){
		if (is_array($publicationPublieurs)){
			$this->publicationPublieurs = $publicationPublieurs;
		}
		else{
			$this->setErreurs(self::FORMAT_ARRAY);
		}
	}
	public function setUtilisateurActive ($utilisateurActive) {
		if (is_bool( $utilisateurActive )){
			$this->utilisateurActive = $utilisateurActive;
		}else{
			$this->setErreurs (self::FORMAT_BOOLEAN );
		}
	}
	
	/**
	 * ********** getter ****************
	 */
	public function getIdUtilisateur() {
		return $this->idUtilisateur;
	}
	public function getIdAuteur() {
		return $this->idAuteur;
	}
	public function getVariableFixeUtilisateur() {
		return $this->variableFixeUtilisateur;
	}
	public function getWorkSpaceFolderUtilisateur() {
		return $this->workSpaceFolderUtilisateur;
	}
	public function getNomUtilisateur() {
		return $this->nomUtilisateur;
	}
	public function getPrenomUtilisateur() {
		return $this->prenomUtilisateur;
	}
	public function getMailUtilisateur() {
		return $this->mailUtilisateur;
	}
	public function getStatut() {
		return $this->statut;
	}
	public function getDateDerniereConnexionUtilisateur() {
		return $this->dateDerniereConnexionUtilisateur;
	}
	public function getDateInscriptionUtilisateur() {
		return $this->dateInscriptionUtilisateur;
	}
	public function getDescriptionUtilisateur() {
		return $this->descriptionUtilisateur;
	}
	public function getUrlPhotoUtilisateur() {
		return $this->urlPhotoUtilisateur;
	}
	public function getUrlBackgroundUtilisateur() {
		return $this->urlBackgroundUtilisateur;
	}
	public function getLienPagePersoUtilisateur(){
		return $this->lienPagePersoUtilisateur;
	}
	public function getPasswordUtilisateur() {
		return $this->passwordUtilisateur;
	}
	public function getPasswordAdminUtilisateur() {
		return $this->passwordAdminUtilisateur;
	}
	public function getEtatBanniUtilisateur() {
		return $this->etatBanniUtilisateur;
	}
	public function getApplications(){
		return $this->applications;
	}
	public function getFavoris(){
		return $this->favoris;
	}
	public function getLogs(){
		return $this->logs;
	}
	public function getPublicationAuteurs(){
		return $this->publicationAuteurs;
	}
	public function getEquipes(){
		return $this->equipes;
	}
	public function getDonneesUtilisateur(){
		return $this->donneesUtilisateur;
	}
	public function getPublicationPublieurs(){
		return $this->publicationPublieurs;
	}
	public function getUtilisateurActive(){
		return $this->utilisateurActive;
	}
	
	
	//permet de récuperer un application d'une liste d'apr�s son ID
	public function getApplicationFromApplications($idApplication){
		$applicationReturn = null;
		if (ctype_digit($idApplication) || is_int($idApplication)) {
			foreach ($this->applications as $application){
				if ($application->getIdApplication() == $idApplication){
					$applicationReturn = $application;
				}
			}
		} 
		else {
			$this->setErreurs(self::FORMAT_INT);
		}
		return $applicationReturn;
	}
	public function getApplicationFromFavoris($idApplication){
		$applicationReturn = null;
		if (ctype_digit($idApplication) || is_int($idApplication)) {
			foreach ($this->favoris as $application){
				if ($application->getIdApplication() == $idApplication){
					$applicationReturn = $application;
				}
			}
		}
		else {
			$this->setErreurs(self::FORMAT_INT);
		}
		return $applicationReturn;
	}
	//permet de récuperer un mot cle d'une liste d'apr�s son ID
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
			$this->setErreurs(self::FORMAT_INT);
		}
		return $log;
	}
	//permet de récuperer un publication d'une liste d'apr�s son ID
	public function getPublicationFromPublicationAuteurs($idPublication){
		$publicationReturn = null;
		if (ctype_digit($idPublication) || is_int($idPublication)) {
			foreach ($this->publicationAuteurs as $publication){
				if ($publication->getIdPublication() == $idPublication){
					$publicationReturn = $publication;
				}
			}
		}
		else {
			$this->setErreurs(self::FORMAT_INT);
		}
		return $publicationReturn;
	}
	public function getEquipeFromEquipes($idEquipe){
		$equipeReturn = null;
		if (ctype_digit($idEquipe) || is_int($idEquipe)) {
			foreach ($this->equipes as $equipe){
				if ($equipe->getIdEquipe() == $idEquipe){
					$equipeReturn = $equipe;
				}
			}
		}
		else {
			$this->setErreurs(self::FORMAT_INT);
		}
		return $publicationReturn;
	}
	public function getDonneesUtilisateurFromDonneeUtilisateur($idDonneeUtilisateur){
		$donneeUtilisateurReturn = null;
		if (ctype_digit($idDonneeUtilisateur) || is_int($idDonneeUtilisateur)) {
			foreach ($this->donneesUtilisateur as $donneeUtilisateur){
				if ($donneeUtilisateur->getIdDonneeUtilisateur() == $idDonneeUtilisateur){
					$donneeUtilisateurReturn = $donneeUtilisateur;
				}
			}
		}
		else {
			$this->setErreurs(self::FORMAT_INT);
		}
		return $donneeUtilisateurReturn;
	}
	public function getPublicationFromPublicationPublieurs($idPublication){
		$publicationReturn = null;
		if (ctype_digit($idPublication) || is_int($idPublication)) {
			foreach ($this->publicationPublieurs as $publication){
				if ($publication->getIdPublication() == $idPublication){
					$publicationReturn = $publication;
				}
			}
		}
		else {
			$this->setErreurs(self::FORMAT_INT);
		}
		return $publicationReturn;
	}
	/**
	 * 
	 * adders
	 * 
	 */

	// Permet d'ajouter une application à la liste des applications de l'utilisateur
	public function addApplication($application){
		if ($application instanceof Application){
			array_push($this->applications, $application);
		}
		else{
			$this->setErreurs(self::FORMAT_APPLICATION);
		}
	}

	// Permet de mettre une application à jour dans la liste des applications de l'utilisateur
	public function updateApplication($application){
		if ($application instanceof Application){
			foreach($this->applications as $index => $applicationUser)
			{
				if($applicationUser->getIdApplication() == $application->getIdApplication())
				{
					$this->applications[$index] = $application;
					break;
				}
			}
		}
		else{
			$this->setErreurs(self::FORMAT_APPLICATION);
		}
	}

	// Permet de supprimer une application de la liste des applications de l'utilisateur
	public function removeApplication($application){
		if ($application instanceof Application){
			foreach($this->applications as $index => $applicationUser)
			{
				if($applicationUser->getIdApplication() == $application->getIdApplication())
				{
					array_splice($this->applications, $index, 1);
					break;
				}
			}
		}
		else{
			$this->setErreurs(self::FORMAT_APPLICATION);
		}
	}
	
	public function addFavori($application){
		if ($application instanceof Application){
			array_push($this->favoris, $application);
		}
		else{
			$this->setErreurs(self::FORMAT_APPLICATION);
		}
	}
	public function removeFavori($application, $index){
		if ($application instanceof Application){
			if(is_int($index)){
				array_splice($this->favoris, $index, 1);
			}else{
				$this->setErreurs(self::FORMAT_INT);
			}
		}else{
			$this->setErreurs(self::FORMAT_APPLICATION);
		}
	}
	public function updateFavori($application, $index){
		if ($application instanceof Application){
			if(is_int($index)){
				$this->favoris[$index] = $application;
			}else{
				$this->setErreurs(self::FORMAT_INT);
			}
		}else{
			$this->setErreurs(self::FORMAT_APPLICATION);
		}
	}
	
	// Permet d'ajouter un mot cle à la liste des applications ayant pour favori l'utilisateur
	public function addLog($log){
		if ($log instanceof Log){
			array_push($this->logs, $log);
		}
		else{
			$this->setErreurs(self::FORMAT_MOT_CLE);
		}
	}
	// Permet d'ajouter une publication à la liste des applications ayant pour favori l'utilisateur
	
	public function addPublicationAuteur($publication){
		if ($publication instanceof Publication){
			array_push($this->publicationAuteurs, $publication);
		}
		else{
			$this->setErreurs(self::FORMAT_PUBLICATION);
		}
	}
	public function addDonneeUtilisateur($donneeUtilisateur){
		if ($donneeUtilisateur instanceof DonneeUtilisateur){
			array_push($this->donneesUtilisateur, $donneeUtilisateur);
		}
		else{
			$this->setErreurs(self::FORMAT_DONNEE_UTILISATEUR);
		}
	}
	// Permet de mettre une donnée Utilisateur à jour dans la liste des données de l'utilisateur
	public function updateDonneeUtilisateur($donneeUtilisateur){
		if ($donneeUtilisateur instanceof DonneeUtilisateur){
			foreach($this->donneesUtilisateur as $index => $donneeUtilisateurUser)
			{
				if($donneeUtilisateurUser->getIdDonneeUtilisateur() == $donneeUtilisateur->getIdDonneeUtilisateur())
				{
					$this->donneesUtilisateur[$index] = $donneeUtilisateur;
					break;
				}
			}
		}
		else{
			$this->setErreurs(self::FORMAT_DONNEE_UTILISATEUR);
		}
	}
	public function removeDonneeUtilisateur($donneeUtilisateur){
		if ($donneeUtilisateur instanceof DonneeUtilisateur){
			foreach($this->donneesUtilisateur as $i => $donnee)
			{
				if($donnee->getIdDonneeUtilisateur() == $donneeUtilisateur->getIdDonneeUtilisateur())
				{
					unset($this->donneesUtilisateur[$i]);
				}
			}
		}
		else{
			$this->setErreurs(self::FORMAT_DONNEE_UTILISATEUR);
		}
	}
	public function addEquipe($equipe){
		if ($equipe instanceof Equipe) {
			$dejaDansUtilisateur = false;
			foreach($this->getEquipes() as $equipeInUtilisateur)
			{
				$dejaDansUtilisateur = $dejaDansUtilisateur || ($equipe->getIdEquipe() == $equipeInUtilisateur->getIdEquipe());
			}
			if(!$dejaDansUtilisateur)
			{
				array_push($this->equipes, $equipe);
			}
		}
		else{
			$this->setErreurs(self::FORMAT_EQUIPE);
		}
	}
	public function removeEquipe($equipe){
		if ($equipe instanceof Equipe){
			foreach($this->equipes as $i => $equ)
			{
				if($equ->getIdEquipe() == $equipe->getIdEquipe())
				{
					unset($this->equipes[$i]);
				}
			}
		}
		else{
			$this->setErreurs(self::FORMAT_EQUIPE);
		}
	}
	public function addPublicationPublieur($publication){
		if ($publication instanceof Publication){
			array_push($this->publicationPublieurs, $publication);
		}
		else{
			$this->setErreurs(self::FORMAT_PUBLICATION);
		}
	}
	
	//permet d'ajouter une liste d'applications � la liste des applications ayant pour favori l'utilisateur	
	public function addAllApplications($applications){
		if (is_Array($applications)){
			foreach ($applications as $application){
				if ($application instanceof Application){
					array_push($this->applications, $application);
				}
				else{
					$this->setErreurs(self::FORMAT_APPLICATION);
				}
			}
		}
		else{
			$this->setErreurs(self::FORMAT_ARRAY);
		}
	}
	public function addAllFavoris($applications){
		if (is_Array($applications)){
			foreach ($applications as $application){
				if ($application instanceof Application){
					array_push($this->favoris, $application);
				}
				else{
					$this->setErreurs(self::FORMAT_APPLICATION);
				}
			}
		}
		else{
			$this->setErreurs(self::FORMAT_ARRAY);
		}
	}
	
	//permet d'ajouter une liste de mot cl�s � la liste des applications ayant pour favori l'utilisateur
	public function addAllLogs($logs){
		if (is_Array($logs)){
			foreach ($logs as $log){
				if ($log instanceof Log){
					array_push($this->logs, $log);
				}
				else{
					$this->setErreurs(self::FORMAT_MOT_CLE);
				}
			}
		}
		else{
			$this->setErreurs(self::FORMAT_ARRAY);
		}
	}
	//permet d'ajouter une liste de publications à la liste des applications ayant pour favori l'utilisateur
	public function addAllPublicationAuteurs($publications){
		if (is_Array($publications)){
			foreach ($publications as $publication){
				if ($publication instanceof Publication){
					array_push($this->publicationAuteurs, $publication);
				}
				else{
					$this->setErreurs(self::FORMAT_PUBLICATION);
					$this->setErreurs(self::FORMAT_PUBLICATION);
				}
			}
		}
		else{
			$this->setErreurs(self::FORMAT_ARRAY);
		}
	}
	public function addAllDonneesUtilisateur($donneesUtilisateur){
		if (is_Array($donneesUtilisateur)){
			foreach ($donneesUtilisateur as $donneeUtilisateur){
				if ($donneeUtilisateur instanceof DonneeUtilisateur){
					array_push($this->donneesUtilisateur, $donneeUtilisateur);
				}
				else{
					$this->setErreurs(self::FORMAT_DONNE_DE_SORTIE);
				}
			}
		}
		else{
			$this->setErreurs(self::FORMAT_ARRAY);
		}
	}
	public function addAllPublicationPublieurs($publications){
		if (is_Array($publications)){
			foreach ($publications as $publication){
				if ($publication instanceof Publication){
					array_push($this->publicationPublieurs, $publication);
				}
				else{
					$this->setErreurs(self::FORMAT_PUBLICATION);
				}
			}
		}
		else{
			$this->setErreurs(self::FORMAT_ARRAY);
		}
	}
	public function addAllEquipes($equipes){
		if (is_Array($equipes)){
			foreach ($equipes as $equipe){
				if ($equipe instanceof Equipe){
					array_push($this->equipes, $equipe);
				}
				else{
					$this->setErreurs(self::FORMAT_EQUIPE);
				}
			}
		}
		else{
			$this->setErreurs(self::FORMAT_ARRAY);
		}
	}
	
}
