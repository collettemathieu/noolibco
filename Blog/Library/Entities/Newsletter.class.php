<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP pour les newsletter. 									  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 *
 * @name : Classe Newsletter
 * @access : public
 * @version : 1
 */
namespace Library\Entities;

/**
 * Classe Newsletter
 */
class Newsletter extends \Library\Entity {
	protected 	$idNewsletter,
				$titreNewsletter, 
				$texteNewsletter,
				$dateNewsletter,
				$utilisateurs = array();
	
	/* Déclaration des constantes de la classe */
	const FORMAT_TITRE_NEWSLETTER = 'Le titre de la newsletter doit être compris entre 5 et 50 caractères.';
	const FORMAT_TEXTE_NEWSLETTER = 'Le texte de la newsletter doit comporter au moins 20 caractères.';
	

	/**
	 * ******setters******
	 */
	public function setIdNewsletter($idNewsletter) {
		if (ctype_digit($idNewsletter) || is_int($idNewsletter)) {
			$this->idNewsletter = $idNewsletter;
		} else {
			$this->setErreurs("Newsletter setIdNewsletter " . self::FORMAT_INT);
		}
	}
	public function setTitreNewsletter($titreNewsletter) {
		if (is_string ( $titreNewsletter )) {
			if(strlen($titreNewsletter) > 4 && strlen($titreNewsletter) < 51){
				$this->titreNewsletter = $titreNewsletter;
			}else{
				$this->setErreurs(self::FORMAT_TITRE_NEWSLETTER);
			}
		} else {
			$this->setErreurs("Newsletter setTitreNewsletter " . self::FORMAT_STRING);
		}
	}
	public function setTexteNewsletter($texteNewsletter) {
		if (is_string ( $texteNewsletter )) {
			if(strlen($texteNewsletter) > 19){
				$this->texteNewsletter = $texteNewsletter;
			}else{
				$this->setErreurs(self::FORMAT_TEXTE_NEWSLETTER);
			}
		} else {
			$this->setErreurs("Newsletter setTexteNewsletter " . self::FORMAT_STRING);
		}
	}
	public function setDateNewsletter($dateNewsletter) {

		if (is_string ( $dateNewsletter )) {
			$this->dateNewsletter = $dateNewsletter;
		} else {
			$this->setErreurs ("Newsletter setDateNewsletter " . self::FORMAT_STRING );
		}
	}
	public function setUtilisateurs($utilisateurs) {
		if (is_array($utilisateurs)) {
			$this->utilisateurs = $utilisateurs;
		} else {
			$this->setErreurs("Newsletter setUtilisateurs " . self::FORMAT_ARRAY);
		}
	}

	/**
	 * *******getters*****
	 */
	public function getIdNewsletter() {
		return $this->idNewsletter;
	}
	public function getTitreNewsletter() {
		return $this->titreNewsletter;
	}
	public function getTexteNewsletter() {
		return $this->texteNewsletter;
	}
	public function getDateNewsletter() {
		return $this->dateNewsletter;
	}
	public function getUtilisateurs() {
		return $this->utilisateurs;
	}
	
	/**
	 *
	 * Adders
	 *
	*/
	// Permet d'ajouter un utilisateur à la liste des utilisateurs souhaitant recevoir la newsletter
	public function addUtilisateur($utilisateur){
		if ($utilisateur instanceof Utilisateur){
			array_push($this->utilisateurs, $utilisateur);
		}
		else{
			$this->setErreurs(self::FORMAT_UTILISATEUR);
		}
	}

	// Permet de modifier un utilisateur à la liste des utilisateurs souhaitant recevoir la newsletter
	public function updateUtilisateur($utilisateur){
		if ($utilisateur instanceof Utilisateur){
			foreach($this->utilisateurs as $index => $utilisateurNewsletter)
			{
				if($utilisateurNewsletter->getIdUtilisateur() == $utilisateur->getIdUtilisateur())
				{
					$this->utilisateurs[$index] = $utilisateur;
					break;
				}
			}
		}
		else{
			$this->setErreurs(self::FORMAT_UTILISATEUR);
		}
	}

	// Permet de supprimer un utilisateur à la liste des utilisateurs souhaitant recevoir la newsletter
	public function removeUtilisateur($utilisateur){
		if ($utilisateur instanceof Utilisateur){
			foreach($this->utilisateurs as $index => $utilisateurNewsletter)
			{
				if($utilisateurNewsletter->getIdUtilisateur() == $utilisateur->getIdUtilisateur())
				{
					array_splice($this->utilisateurs, $index, 1);
					break;
				}
			}
		}
		else{
			$this->setErreurs(self::FORMAT_UTILISATEUR);
		}
	}
	
}
