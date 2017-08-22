<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart                                      |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour l'autopost                             |
// | d'une nouvelle application sur Facebook.                             |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com>        |
// +----------------------------------------------------------------------+

/**
 * @name: Classe AutoPostController
 * @access: private
 * @version: 1
 */ 

namespace Applications\ApplicationsStandAlone\SocialMedia\Modules\AutoPost;

use Library\FacebookAPI;
use Library\Facebook\Facebook;

class AutoPostController extends \Library\BackController
{	

	/**
	* Permet de s'identifier sur Facebook lors de la validation de l'application.
	**/
	public function executeLogin(){ 
        $fbapi = new FacebookAPI();

        $fb = new Facebook([
          'app_id' => $fbapi->getApp_Id(),
          'app_secret' => $fbapi->getApp_Secret(),
          'default_graph_version' => 'v2.5'
          ]);

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['manage_pages', 'publish_pages'];

        $loginUrl = $helper->getLoginUrl($fbapi->getApp_url().'/ForAdminOnly/Articles/ProcessFacebookApplication', $permissions);

        // On procède à la redirection
        $response = $this->app->getHTTPResponse();
        $response->redirect($loginUrl);
	}
    
  /**
	* Permet de récupérer le jeton d'accès et de rediriger l'utilisateur pour poster le message.
	**/
	public function executeProcess(){
        // On récupère le token via l'api Facebook
        $fbapi = new FacebookAPI();
        $fbapi->getAccessToken($fbapi->getApp_Id(),$fbapi->getApp_Secret());

        // On récupère l'entité
        $user = $this->app->getUser();
        $entite = $user->getFlash();

        
        if($entite instanceof \Library\Entities\Article){
          $message = substr($entite->getTitreArticle().'
https://www.scienceapart.com/Blog/'.$entite->getUrlTitreArticle(), 0, 140);
        }elseif($entite instanceof \Library\Entities\Cours){
          $message = substr($entite->getTitreCours().'
https://www.scienceapart.com/Cours/'.$entite->getUrlTitreCours(), 0, 140);
        }

        $status = array(
            'message' => $message
          );

        $token = $fbapi -> getPageTokenRequest();
        $fbapi -> postStatus($status, $token);

        // On procède à la redirection
        $response = $this->app->getHTTPResponse();
        
        if($entite instanceof \Library\Entities\Article){
          $user->getMessageClient()->addReussite('L\'article a bien été posté sur les réseaux sociaux.');
          $response->redirect('/ForAdminOnly/Articles/id='.$entite->getIdArticle());
        }elseif($entite instanceof \Library\Entities\Cours){
          $user->getMessageClient()->addReussite('Le cours a bien été posté sur les réseaux sociaux.');
          $response->redirect('/ForAdminOnly/Cours/id='.$entite->getIdCours());
        }
    }
} 