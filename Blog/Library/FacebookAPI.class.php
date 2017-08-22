<?php 
namespace Library;
//require_once __DIR__ .'/Facebook/autoload.php';
use \Library\Facebook\Facebook;
use \Library\Facebook\FacebookRequest;
use \Library\Facebook\FacebookResponse;
    
// API Class that will interact with the facebook php sdk.    
class FacebookAPI {
    private $App_Id;
    private $App_Secret;
    private $Page_Id;
    private $App_Url;
    private $fb; 
    
    public function __construct () {
    	$config = parse_ini_file("../ConfigSystem/Facebook/config.ini");
    	$this->setApp_Id($config);
    	$this->setApp_Secret($config);
    	$this->setPage_Id($config);
        $this->setApp_Url();
        $this->setFB(); 
    }

    public function getApp_Id(){
    	return $this->App_Id;
    }
    public function getApp_Secret(){
    	return $this->App_Secret;
    }
    public function getPage_Id(){
    	return $this->Page_Id;
    }
    public function getApp_Url(){
    	return $this->App_Url;
    }
    public function setApp_Id($config){
    	$this->App_Id=$config['app_id'];
    }    
    public function setApp_Secret($config){
    	$this->App_Secret=$config['app_secret'];
    }
    public function setPage_Id($config){
        $this->Page_Id=$config['page_id'];
    }
    public function setApp_Url(){
    	$this->App_Url= 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
    }
    public function setFB(){
        $this->fb= new Facebook([
            'app_id' => $this->App_Id,
            'app_secret' => $this->App_Secret,
            'default_graph_version' => 'v2.5'
            ]);	
    }

	public function getPageTokenRequest(){
		try {
            // GET page token
            $responsePage = $this->fb->get('/'.$this->Page_Id.'?fields=access_token', $_SESSION['user_access_token']);
            $result = $responsePage->getGraphObject()->asArray();
            $pageToken = $result['access_token'];
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
		    // When Graph returns an error
		    echo 'Graph with token returned an error: ' . $e->getMessage();
		    exit;
		  } catch(Facebook\Exceptions\FacebookSDKException $e) {
		    // When validation fails or other local issues
		    echo 'Facebook SDK with token returned an error: ' . $e->getMessage();
		    exit;
		}
		return $pageToken;
	}

	public function getAccessToken(){ //get the user access token and store it in SESSION
		$helper = $this->fb->getRedirectLoginHelper();
		  try {
		    $accessToken = $helper->getAccessToken();
		  } catch(Facebook\Exceptions\FacebookResponseException $e) {
		    // When Graph returns an error
		    echo 'Graph without token returned an error: ' . $e->getMessage();
		    exit;
		  } catch(Facebook\Exceptions\FacebookSDKException $e) {
		    // When validation fails or other local issues
		    echo 'Facebook SDK without token returned an error: ' . $e->getMessage();
		    exit;
		}

		if (isset($accessToken)) {
		  // Logged in!
		  $_SESSION['user_access_token'] = (string) $accessToken;
		}
	}
    
    public function postStatus($status, $pageToken){
        // post to Facebook
        try {
            $responsePost = $this->fb->post('/'.$this->Page_Id.'/feed', $status, $pageToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }         
        $graphNode = $responsePost->getGraphNode(); 
        //echo 'Posted with id: ' . $graphNode['id'];
    }
}	
	

