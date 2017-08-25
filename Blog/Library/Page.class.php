<?php
namespace Library;

// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Classe PHP Page pour générer et envoyer la vue au client.	  		  |		  										  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+


/**
 * @name:  Classe Page
 * @access: public
 * @version: 1
 */

class Page extends ApplicationComponent
{
	protected $contentFile,
			  $vars = array();

	/**
	* Permet d'ajouter des variables à la page.
	*/
	public function addVar($var, $value)
	{
		if(isset($var) && !empty($var) && is_string($var))
		{
			$this->vars[$var] = $value;
		}
		else
		{
			// On récupère l'utilisateur système
			$user = $this->app->getUser();
			$user->getMessageClient()->addErreur('PAGE :: The name of the variable must be in String format.');
		}
	}

	/**
	* Permet d'assigner une vue à la page.
	*/
	public function setContentFile($contentFile)
	{
		if(isset($contentFile) && is_string($contentFile) && !empty($contentFile))
		{
			$this->contentFile = $contentFile;
		}
		else
		{
			// On récupère l'utilisateur système
			$user = $this->app->getUser();
			$user->getMessageClient()->addErreur('PAGE :: The name of the view must be in String format.');
		}
	}


	/**
	* Permet de générer la page d'une application classique.
	*/
	public function getGeneratedPage()
	{	
		if(file_exists($this->contentFile))
		{
		
			/*On extrait les variables du tableau $this->_vars. La fonction extract créera les variables automatiquement selon les entrées du tableau et les placera en mémoire*/
			extract($this->vars);
								
			/*On importe la session utilisateur via la classe USER*/
			$user = $this->getApp()->getUser();
			
			/*Chargement des librairies JavaScript*/
			ob_start(); //Démarre la mise en mémoire tampon. Plus rien n'est envoyé à l'affichage
			// On charge le template de l'application s'il existe
			if(file_exists(dirname(__FILE__).'/../Applications/'.$this->getApp()->getNomApplication().'/Libraries/JavaScript/load.php')){
				require dirname(__FILE__).'/../Applications/'.$this->getApp()->getNomApplication().'/Libraries/JavaScript/load.php';
			}
			$librairiesJS = ob_get_clean(); //On libère la mémoire tampon dans la variable $dependenciesJS et on ferme le tampon.

			/*Chargement des librairies CSS*/
			ob_start(); //Démarre la mise en mémoire tampon. Plus rien n'est envoyé à l'affichage
			// On charge le template de l'application s'il existe
			if(file_exists(dirname(__FILE__).'/../Applications/'.$this->getApp()->getNomApplication().'/Libraries/CSS/load.php')){
				require dirname(__FILE__).'/../Applications/'.$this->getApp()->getNomApplication().'/Libraries/CSS/load.php';
			}
			$librairiesCSS = ob_get_clean(); //On libère la mémoire tampon dans la variable $dependenciesJS et on ferme le tampon.

			/* Chargement de la vue*/
			ob_start(); //Démarre la mise en mémoire tampon. Plus rien n'est envoyé à l'affichage
			require $this->contentFile;
			
			// On contrôle si c'est un chargement en Ajax (alors pas de template)
			if(!$user->getAjax()){

				$content = ob_get_clean(); //On libère la mémoire tampon dans la variable $content et on ferme le tampon.
				
				/* Chargement du template*/
				ob_start();//Démarre la mise en mémoire tampon. Plus rien n'est envoyé à l'affichage.
				if($this->getApp()->isStandAlone()){
					// On charge le template de l'application s'il existe
					if(file_exists(dirname(__FILE__).'/../Applications/ApplicationsStandAlone/'.$this->getApp()->getNomApplication().'/Templates/layout.php')){
						require dirname(__FILE__).'/../Applications/ApplicationsStandAlone/'.$this->getApp()->getNomApplication().'/Templates/layout.php';
					}
				}else{
					
					// On charge le template de l'application s'il existe sinon on prend le template de base
					if(file_exists(dirname(__FILE__).'/../Applications/'.$this->getApp()->getNomApplication().'/Templates/layout.php')){
						require dirname(__FILE__).'/../Applications/'.$this->getApp()->getNomApplication().'/Templates/layout.php';
					}else{
						require dirname(__FILE__).'/../ConfigSystem/Template/layout.php';
					}
					
				}
			}
			
			return ob_get_clean(); //On libère le tampon et on affiche grâce au return et à exit dans HttpResponse
		}
		else
		{
			// On récupère l'utilisateur système
			$user = $this->app->getUser();
			$user->getMessageClient()->addErreur('PAGE :: The specific view does not exist.');

		}
	}


	/**
	* Permet de générer la réponse par l'envoi d'un email.
	*/
	public function getGeneratedPageByMail(){
		if(file_exists($this->contentFile)){
			
			//On extrait les variables du tableau $this->_vars. La fonction extract créera les variables automatiquement selon les entrées du tableau
			extract($this->vars);
			

			//On importe la session utilisateur via la classe USER
			$user = $this->getApp()->getUser();

			if(!empty($destinataires) && !empty($expediteur) && !empty($titreMail)){

				ob_start(); //Démarre la mise en mémoire tampon. Plus rien n'est envoyé à l'affichage
				require $this->contentFile;
				$content = ob_get_clean(); //On libère la mémoire tampon dans la variable $content et on ferme le tampon.
				
				ob_start();//Démarre la mise en mémoire tampon. Plus rien n'est envoyé à l'affichage.
				if($this->getApp()->isStandAlone()){
				if(file_exists(dirname(__FILE__).'/../Applications/ApplicationsStandAlone/'.$this->getApp()->getNomApplication().'/Templates/layout.php')){
						require dirname(__FILE__).'/../Applications/ApplicationsStandAlone/'.$this->getApp()->getNomApplication().'/Templates/layout.php';
					}
				}else{
					if(file_exists(dirname(__FILE__).'/../Applications/'.$this->getApp()->getNomApplication().'/Templates/layout.php')){
						require dirname(__FILE__).'/../Applications/'.$this->getApp()->getNomApplication().'/Templates/layout.php';
					}else{
						require dirname(__FILE__).'/../ConfigSystem/Template/layout.php';
					}
				}
				
				$texteCompletMail = ob_get_clean(); //On libère le tampon et on affiche grâce au return et à exit dans HttpResponse
				
				// On créé la structure du mail
				// On créé un string de structure de mail à partir d'un tableau de mails clients
				if(is_array($destinataires)){
					$destinataires = implode(', ', $destinataires);
				}

				// On créé un délimiteur
				$delimiteur = md5(uniqid(mt_rand()));		
		
				//On définit le header du mail avec un contenu type HTML sans pièce jointe ni image
				$headers = "Reply-to:".$expediteur."\r\nFrom:".$expediteur."\r\nBcc:".$destinataires."\n";
				$headers.= "MIME-Version: 1.0 \n"; 
				$headers.= "Content-type:multipart/mixed; boundary=\"".$delimiteur."\"\n";
				$headers.="\n";

				//On définit le contenu du mail
				$message = "Ce message est au format MIME \n";
				$message.="\n";
				
				$message.="--$delimiteur\n";
				
				$message.="Content-type: text/html; charset=utf-8 \n";
				$message.="Content-Transfert-Encoding:8bit\n";
				$message.="\n";

				//On inclut le template du mail
				$message.= $texteCompletMail;

				$message.="\n";
				
				$message.= "--$delimiteur\n";

				//On ferme le délimiteur
				$message.="--$delimiteur--\n";

				//On envoit le tout à l'aide de la fonction mail()
				if(mail($destinataires, $titreMail, $message, $headers)){
					$user->setFlash('Un email a été envoyé au(x) destinataire(s) : '.$destinataires);
				}else{
					$user->setFlash('MAIL :: Aucun email envoyé au(x) destinataire(s) : '.$destinataires);
				}

			}else{
				$user->setFlash('MAIL :: Aucun expéditeur, destinataire ou titre n\'a été spécifié.');
			}

		}

	}

}
