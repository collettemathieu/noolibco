<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart                                      |
// +----------------------------------------------------------------------+
// | Classe PHP de l'application newsletter.                              |
// +----------------------------------------------------------------------+
// | Auteur : Mathieu COLLETTE <collettemathieu@scienceapart.com>         |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe NewsLetterApplication
 * @access: public
 * @version: 1
 */ 

namespace Applications\NewsLetter;

class NewsLetterApplication extends \Library\Application
{
  /* Appel du constructeur parent */
  public function __construct()
  {
    parent::__construct();
    $this->nomApplication = 'NewsLetter';
  }
  
  /* Permet de démarrer l'application NewsLetter */
  public function run()
  {
    // On crée le routeur. Si celui est déjà en cache, on l'instancie via le cache.
    $routeur = $this->getCacheRouteur('../Applications/'.$this->nomApplication.'/Cache/routeur.txt', '\Library\Routeur');
    
    // On récupére le contrôleur en fonction de la requête du client.
    $controleur = $this->getControleur($routeur);
    
    // On exécute la requête demandée
    $controleur->execute();

    // On crée la page associée à la requête client.
    $this->HTTPResponse->setPage($controleur->getPage());
    // On envoie la page associée à la requête client.
    $this->HTTPResponse->send();
  }
}