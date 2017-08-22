<?php
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2016 ScienceAPart									  |
// +----------------------------------------------------------------------+
// | Ce controleur permet de sélectionner entre un cours et un cours 	  |
// | global.														 	  |
// +----------------------------------------------------------------------+
// | Auteurs : Mathieu COLLETTE <collettemathieu@scienceapart.com> 		  |
// +----------------------------------------------------------------------+

/**
 * @name: controleur des coursSelect
 * @access: public
 * @version: 1
 */	


namespace Applications\Backend\Modules\CoursSelect;

class CoursSelectController extends \Library\BackController{
	
	// Page principal pour choisir entre un cours et un cours global
	public function executeSelect($request){
		//On récupère la requête du client
		$user = $this->app->getUser();
		
		if(!$user->getAttribute('isAdmin') || !$user->getAttribute('isSuperAdmin')){
			$response = $this->app->getHTTPResponse();
			$response->redirect('/ForAdminOnly/');
		}	
	}
}
