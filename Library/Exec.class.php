<?php
namespace Library;

// +----------------------------------------------------------------------+
// | PHP Version 7                                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2017 NooLib                                            |
// +----------------------------------------------------------------------+
// | Classe abstraite PHP EXEC pour l'execution de scripts bash.	      |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@noolib.com>               |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe Exec
 * @access: public
 * @version: 1
 */

class Exec extends ApplicationComponent{

	/* Définition des constantes */
	const INSTANCE_OF_USER = 'The argument passed as $user must be an instance of Utilisateur.';
	const INSTANCE_OF_APPLICATION = 'The argument passed as $application must be an instance of Application.';
	const INSTANCE_OF_FONCTION = 'The argument passed as $fonction must be an instance of Fonction.';
	
	/* Définition des attributs */
	protected	$command = '',
				$erreurs = array();

	/**
	* Permet de créer un utilisateur linux
	**/
	public function createUser($user){

		if($user instanceof \Library\Entities\Utilisateur){
			$instructions = '/home/noolibco/Library/ScriptsBash/Debian/CreationUtilisateur '.$user->getVariableFixeUtilisateur();
			shell_exec($instructions);
		}else{
			$this->setErreurs(self::INSTANCE_OF_USER);
		}
	}

	/**
	* Permet de supprimer un utilisateur linux
	**/
	public function delUser($user){
		if($user instanceof \Library\Entities\Utilisateur){
			$instructions = '/home/noolibco/Library/ScriptsBash/Debian/SuppressionUtilisateur '.$user->getVariableFixeUtilisateur();
			shell_exec($instructions);
		}else{
			$this->setErreurs(self::INSTANCE_OF_USER);
		}
	}

	/**
	* Permet de supprimer une application
	**/
	public function delApplication($application){
		if($application instanceof \Library\Entities\Application){
			$nomUtilisateur = $application->getCreateur()->getVariableFixeUtilisateur();
			$nomApplication = $application->getVariableFixeApplication();
			$instructions = '/home/noolibco/Library/ScriptsBash/Debian/SuppressionApplication '.$nomUtilisateur.' '.$nomApplication;
			shell_exec($instructions);
		}else{
			$this->setErreurs(self::INSTANCE_OF_APPLICATION);
		}
	}


	/**
	* Permet de vider le dossier User dans le SafeWorkSpace (prod)
	**/
	public function delFolderInProd($user){
		if($user instanceof \Library\Entities\Utilisateur){
			$instructions = '/home/noolibco/Library/ScriptsBash/Debian/SuppressionUtilisateurInProd '.$user->getVariableFixeUtilisateur();
			shell_exec($instructions);
		}else{
			$this->setErreurs(self::INSTANCE_OF_USER);
		}
	}

	
	/**
	* Permet d'executer une fonction d'une application
	* Copie des données utilisateur et de l'application en cours d'execution
	* dans un espace de travail protégé (SafeWorkSpace)
	* Execution de la fonction avec toutes les données et paramètres d'entrée
	* Retourne le résultat de la fonction dans $outputData
	**/
	public function execFct($createur, $utilisateur, $application, $numVersion, $fonction, $args){
		if($createur instanceof \Library\Entities\Utilisateur && $utilisateur instanceof \Library\Entities\Utilisateur && $application instanceof \Library\Entities\Application && $fonction instanceof \Library\Entities\Fonction){
			$nomUtilisateur = $utilisateur->getVariableFixeUtilisateur();
			$nomCreateur = $createur->getVariableFixeUtilisateur();
			$nomApplication = $application->getVariableFixeApplication();
			$nameFunction = substr(strrchr($fonction->getUrlFonction(),'/'),1);

			$instructions = '/home/noolibco/Library/ScriptsBash/Debian/LancementApplicationServeurProd '.$nomCreateur.' '.$nomUtilisateur.' '.$nomApplication.' '.$numVersion.' '.$nameFunction.' '.$args;
			
			return shell_exec($instructions.' 2>&1');//2>&1 pour obtenir les erreurs de la fonction s'il y a...
			
		}else{
			$this->setErreurs('Fatal error : <p>'.self::INSTANCE_OF_USER.'</p><p>'.self::INSTANCE_OF_APPLICATION.'</p><p>'.self::INSTANCE_OF_FONCTION.'</p>');
		}
	}


	/* SETTERS */
	protected function setCommand($command){
		if(isset($command) && !empty($command)){
			$this->command = $command;
		}
	}

	protected function setErreurs($erreur){
		if(isset($erreur) && !empty($erreur)){
			array_push($this->erreurs, $erreur);
		}
	}


	/* GETTERS */

	public function getCommand(){
		return $this->command;
	}

	public function getErreurs(){
		return $this->erreurs;
	}

}