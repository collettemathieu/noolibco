<?php
// +----------------------------------------------------------------------+
// | PHP Version 5 								                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 NooLib			         				                      |
// +----------------------------------------------------------------------+
// | Classe PHP du contrôleur pour l'autopost                             |
// | d'une nouvelle application sur Facebook.	  						              |
// +----------------------------------------------------------------------+
// | Auteur : Antoine FAUCHARD   		                                   	  |
// +----------------------------------------------------------------------+

/**
 * @name:  Classe AutoPostController
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
        $loginUrl = $helper->getLoginUrl($fbapi->getApp_url().'/PourAdminSeulement/Applications/ProcessFacebookApplication', $permissions);

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

        // On récupère l'application
        $user = $this->app->getUser();
        $application = $user->getFlash();

        /*
        $status = array(
          'message' => "A new application has been released on NooLib Web Application.",
          'link' => "https://www.noolib.com/".$application->getNomApplication(), //app url (app id ?)
          'picture' => "https://www.noolib.com/Images/BackgroundUtilisateurs/background_defaut.jpg", //Application logo
          'name' => $application->getNomApplication(),//.$nomApplication,
          'caption' => $application->getCreateur()->getNomUtilisateur(), 
          'description' => $application->getDescriptionApplication(), // Application description
          'admin_creator' => [
            'id' => 100008548565758,
            'name' => 'Adresse Bidon'
          ]
        );
        */

        $status = array(
          'message' => '---A new application has been released---'.strtoupper($application->getNomApplication()).': '.$application->getDescriptionApplication().' https://www.noolib.com/'.urlencode($application->getNomApplication())
        );

        $token = $fbapi -> getPageTokenRequest();
        $fbapi -> postStatus($status, $token);

        $user->getMessageClient()->addReussite('L\'application a bien été postée sur les réseaux sociaux.');

        // On procède à la redirection
        $response = $this->app->getHTTPResponse();
        $response->redirect('/ManagerOfApplications/app='.$application->getIdApplication());
        
    }
} 